<?php
	// [BC]
	// 此FUNCTION是用來取得PROJECT資訊的
	// 參數如下
	// $Name -> 專案名稱
	// $Description -> 專案描述
	// $Company -> 需要該專案的CLIENT公司
	// $Connect -> 目前連接的資料庫
	function getProject($Name, $Description, $Company, $Connect){
		$select = "SELECT p_id FROM project WHERE project.p_name = \"" . $Name . "\" AND project.p_des = \"" . $Description . "\" AND project.p_company = \"" . $Company . "\"";
		echo "select -> " . $select . "<br>";
		$result = $Connect->query($select);
		if(!$result)
		{
			$user = array('success' => 0, 'message' => 'there is an error when SELECT p_id');
			echo(json_encode($user));
			exit();
		}
		$data = $result->fetch_array(MYSQLI_ASSOC);
		return $data['p_id'];
	}

	// [BC]
	// 此API是用來做新增專案功能的
	// 使用post這個method，以下關於form中的那些元件名稱該如何命名
	// Name -> 專案名稱
	// Description -> 專案描述
	// Company -> 需要該專案的Client公司
	// Status -> 專案的狀態
	// StartTime -> 專案開始時間
	// EndTime -> 專案結束時間
	// 新增完成之後，會把owner加到該專案中
	// 並回傳一個JSON Code，包含SUCCESS和MESSAGE
	// SUCCESS表示是否成功，成功做完新增專案後會導到projectlist頁面
	// 反之，SUCCESS等於 0
	// MESSAGE則是代表任何錯誤訊息

	require_once("../assist/getUserInfo.php");

	header("Content-Type:text/html; charset=utf-8");

	// [BC] 網頁應該要用POST傳進來的資訊
	$pName = $_POST["Name"];
	$pDescription = $_POST["Description"];
	$pCompany = $_POST["Company"];
	// [BC] 時間必須是Y-m-d H:i:s的格式，像是2015-11-05 12:45:19這樣
	$pStartTime = $_POST["StartTime"];
	$pEndTime = $_POST["EndTime"];
	$pStatus = $_POST["Status"];

	// [AC]Database Setting 
	$dburl = "";
	$dbuser = "";
	$dbpass = "";
	$db = "";
	
	// [AC]Create connection
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'db_error');
		echo(json_encode($user));
		exit();
	}
	// [AC]Show Chinese Chracters Correctly
	$sqli->query("SET NAMES 'UTF8'");
	// [BC]get user info
	echo "in Add Project<br>" . json_encode($user) . "<br>";

	// [BC] mysql query
	$insertProject = "INSERT INTO project (p_id, p_name, p_des, p_company, p_owner, p_start_time, p_end_time, status) VALUES (" . "NULL, \"" . trim($pName) . "\", \"" . trim($pDescription) . "\", \"" . trim($pCompany) . "\", " . $user['uid'] . ", \"" . $pStartTime . "\", \"" . $pEndTime . "\", " . $pStatus . ")";
	echo "insert project -> " . $insertProject . "<br>";
	// [BC] 執行新增專案的Query
	$result = $sqli->query($insertProject);
	if (!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'there is an error when executing INSERT PROJECT');
		exit(json_encode($response));
	}
	echo "string 2<br>";
	// [BC] 取得新增的專案資料
	$pid = getProject(trim($pName), trim($pDescription), trim($pCompany), $sqli);
	echo "test pid = " . $pid . " uid = " . $user['uid'] . "<br>";
	// [BC] 將新增專案的人，也就是owner加到該PROJECT中的team中
	$insertTeamMember = "INSERT INTO project_team (project_id, user_id) VALUES (" . $pid . ", " . $user['uid'] . ")";
	echo $insertTeamMember . "<br>";

	// [BC] 執行將擁有專案的擁有人加到project_team這個表格中的QUERY
	$result = $sqli->query($insertTeamMember);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'there is an error when executing INSERT PROJECT_TEAM');
		exit(json_encode($response));
	}

	// [BC] 重新導到專案列表頁面，如果沒有錯誤的話
	header("Location: projectList.html");
?>