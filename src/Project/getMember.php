<?php
	// [BC]
	// 這個FUNCTION是用來抓出相同公司使用者的FUNCTON
	// 參數如下
	// $connection -> 連線用的
	// $company -> 相同公司的公司名稱
	// $array -> 要把資料放進去的陣列
	// $count -> 資料的總量
	// 此FUNCTION沒有回傳值
	function getUserWithTheSameCompany($connection, $company, &$array, &$count)
	{
		// [BC] 取得與參數相同公司的使用者
		$selectUser = "SELECT * FROM user_info WHERE company=\"" . $company . "\"";
		echo "SELECT = " . $selectUser . "<br>";
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
			echo "name = " . $data['name'] . " company = " . $data['company'] . "<br>";
			array_push($array, array($data['name'], $data['company']));
			$count++;
		}
	}

	// [BC]
	// 這個API是用來取得所有可以被加入某專案中的成員
	// 此API只有專案擁有者可以使用
	// 成員包含，專案所屬公司的員工，以及開發該專案公司的工程師
	// 用POST為輸入，參數如下
	// pid -> 要被加入成員的專案
	// 若成功取得成員，會回傳一個陣列，儲存所有可被加入的成員名稱與公司
	// 反之，SUCCESS為0，並回傳錯誤訊息
	
	// [BC] 取得連線資訊
	include_once '../assist/getDataBaseConnection.php';
	$sqli = getDBConnection();

	//[BC] 取得當前使用者資訊，也就是該專案擁有者的資訊
	require_once '../assist/getUserInfo.php';

	// [BC] 取得專案的對應公司，也就是pCompany
	$pid = 9;//$_POST["pid"];
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
	echo $pCompany . "<br>" . $oCompany . "<br>";

	// [BC] 初始化一些參數，包含儲存所有成員的變數、所有成員的總量
	$countMember = 0;
	$members = array();

	// [BC] 取得與專案擁有者相同公司的使用者
	getUserWithTheSameCompany($sqli, $oCompany, $members, $countMember);
	echo "COUNT = " . $countMember . " ". json_encode($members);
	echo "<br>";

	// [BC] 取得和專案相同公司的使用者
	getUserWithTheSameCompany($sqli, $pCompany, $members, $countMember);

	echo "COUNT = " . $countMember . " ". json_encode($members);
?>