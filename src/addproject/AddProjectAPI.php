<?php
	require_once("assist/GetUserInfo.php");

	header("Content-Type:text/html; charset=utf-8");
	$pName = $_POST["Name"];
	$pDescription = $_POST["Description"];
	$pCompany = $_POST["Company"];
	// [BC] 結束時間必須是Y-m-d H:i:s的格式，像是2015-11-05 12:45:19這樣
	$pEndTime = $_POST["EndTime"];

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
	$user = GetUser();
	// [BC] mysql query
	$insert = "INSERT INTO project (p_id, p_name, p_des, p_company, p_owner, p_start_time, p_end_time) VALUES (" . "NULL, \"" . $pName . "\", \"" . $pDescription . "\", \"" . $pCompany . "\", " . 55 . ", \"" . date('Y-m-d H:i:s') . "\", \"" . $pEndTime . "\")";

	// [BC]execute the query
	$result = $sqli->query($insert) or die("query error");
	if ($result)
	{
		$answer['success'] = '1';
	}else
	{
		$answer = array('success' => 0, 'message' => 'db_error');
	}
	echo json_encode($answer);
?>