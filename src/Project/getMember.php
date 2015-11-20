<?php
	// [BC]
	// 這個FUNCTION是用來抓出相同公司使用者的FUNCTON，其中，$NotInProject這個變數，用來決定要取出來的人是已經在專案中呢？還是待加入
	// 參數如下
	// $connection -> 用於連線的var
	// $company -> 相同公司的公司名稱
	// $pid -> 該專案的pid，用來判斷是否已經在該專案中
	// $NotInProject -> 判斷要取的人是在該專案中，或是沒有在專案中的人，這是一個boolean
	// $array -> 要把資料放進去的陣列，採用reference輸入
	// $count -> 資料的總量，採用reference輸入
	// 此FUNCTION沒有回傳值，因為$array和$count是pass by reference
	function getUserWithTheSameCompany($connection, $company, $pid, $NotInProject, &$array, &$count)
	{
		// [BC] 如果要取的人是當前沒有在專案中的人
		if($NotInProject)
		{
			$selectUser = "SELECT u.uid, u.name, u.company FROM user_info as u WHERE u.company=\"" . $company . "\" AND u.uid NOT IN (SELECT uid FROM project_team as t WHERE t.project_id=" . $pid . " AND t.user_id=u.uid)";
			//echo "SELECT = " . $selectUser . "<br>";
		}
		else   // [BC] 要不然要取的人就是已經在專案中了
		{
			$selectUser = "SELECT u.uid, u.name, u.company FROM user_info as u WHERE u.company=\"" . $company . "\" AND u.uid IN (SELECT uid FROM project_team as t WHERE t.project_id=" . $pid . " AND t.user_id=u.uid)";
			//echo "SELECT = " . $selectUser . "<br>";
		}
		// [BC] 在MySQL中做測試的指令
		// [BC] SELECT DISTINCT u.uid, u.name, u.company FROM user_info as u WHERE u.company="PTS" AND u.uid NOT IN (SELECT uid FROM project_team as t WHERE t.project_id=64 AND t.user_id=u.uid)
		$result = $connection->query($selectUser);
		if(!$result)
		{
			$error = array('SUCCESS'=>0, 'message'=>'there is an error when SELECT user company which is equal to ' . $company);
			echo json_encode($error);
			exit();
		}
		// [BC] 把取得的資料加到ARRAY中，並另外設定一個變數儲存總共有多少MEMBER被抓到
		while ($data = $result->fetch_array()) 
		{
			//echo "name = " . $data['name'] . " company = " . $data['company'] . " uid = " . $data['uid'] . "<br>";
			array_push($array, array($data['name'], $data['company'], $data['uid']));
			$count++;
		}
	}

	// [BC]
	// 這個API是用來取得所有可以被加入某專案中的成員
	// 此API只有專案擁有者可以使用
	// 成員包含，專案所屬公司的員工，以及開發該專案公司的工程師
	// 若成功取得成員，會回傳一個陣列，儲存所有可被加入的成員名稱、公司和ID
	// 反之，SUCCESS為0，並回傳錯誤訊息
	// 這個API會和projectDetail.php放一起，所以資料庫連線、seeionid這兩項變數不需要取得，就可以直接使用
	// 另外，因為projectDetail.php會事先取得pid，所以也不需要用POST或是GET拿到pid
	
	// [BC] 作為單獨測試此API時，所需要的必要資訊，請別輕易刪除，為了日後測試時，可以比較方便
	// session_start();
	// $session = $_SESSION['sessionid'];
	// $pid=$_GET['pid'];
	// require_once '../assist/DBConfig.php';
	// $sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	// $sqli->query("SET NAMES 'UTF8'");

	$selectUser = "SELECT * FROM user_info WHERE user_session='" . $session . "'";
    $result = $sqli->query($selectUser);
    if(!$result)
    {
        $error = array('success' => 0, 'message' => 'there is an error when SELECT user_info in getMember.php');
        echo(json_encode($error));
        exit();
    }
    $user = $result->fetch_array(MYSQLI_ASSOC);

	// [BC] 取得專案的對應公司，也就是pCompany
	$selectProjectCompany = "SELECT p_company FROM project WHERE p_id=" . $pid;
	$result = $sqli->query($selectProjectCompany);
	if(!$result)
	{
		$error = array('SUCCESS'=>0, 'message'=>'there is an error when SELECT project company');
		echo json_encode($error);
		exit();
	}
	$data = $result->fetch_array(MYSQLI_ASSOC);
	$pCompany = $data['p_company'];

	// [BC] 取得專案擁有者公司
	$oCompany = $user['company'];
	
	// [BC] 初始化一些參數，包含分別儲存成員的陣列、與陣列大小
	$countMemberNotInProject = 0;
	$membersNotInProject = array();
	$countMemberInProject = 0;
	$membersInProject = array();

	// [BC] 取得與專案擁有者相同公司的使用者，且目前不是在專案中的人
	getUserWithTheSameCompany($sqli, $oCompany, $pid, TRUE, $membersNotInProject, $countMemberNotInProject);
	
	// [BC] 取得和專案相同公司的使用者，且目前不是在專案中的人
	getUserWithTheSameCompany($sqli, $pCompany, $pid, TRUE, $membersNotInProject, $countMemberNotInProject);


	// [BC] 取得與專案擁有者相同公司的使用者，且目前在專案中的人
	getUserWithTheSameCompany($sqli, $oCompany, $pid, FALSE, $membersInProject, $countMemberInProject);
	
	// [BC] 取得和專案相同公司的使用者，且目前在專案中的人
	getUserWithTheSameCompany($sqli, $pCompany, $pid, FALSE, $membersInProject, $countMemberInProject);

	// echo "Not In Project COUNT = " . $countMemberNotInProject . " ". json_encode($membersNotInProject) . "<br>";
	// echo "In Project COUNT = " . $countMemberInProject . " ". json_encode($membersInProject) . "<br>";
?>