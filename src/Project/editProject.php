<?php 
	header("Content-Type:text/html; charset=utf-8");
	
	// [BC]Get the Data
	$pID = $_POST["pid"];
	$pName = $_POST["name"];
	$pCompany = $_POST["company"];
	$pStartTime = $_POST["startTime"];
	$pEndTime = $_POST["endTime"];
	$pDes = $_POST["des"];
	$pStatus = $_POST["status"];
	
	// [BC] Set the DataBase 
	require_once '../assist/DBConfig.php';
	
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'db_error');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");
	
	// [BC] Update的內容
	$update = "UPDATE project SET p_name=\"" . $pName . "\", p_des=\"" . $pDes . "\", p_company=\"" . $pCompany . "\", p_start_time=\"" . $pStartTime . "\", p_end_time=\"" . $pEndTime . "\", status=". $pStatus. " WHERE p_id=" . $pID;

	// [BC] 對資料庫update
	$result = $sqli->query($update) or die("query error");
	if ($result)
	{
		$answer['success'] = '1';
	}else
	{
		$answer = array('success' => 0, 'message' => 'db_error');
	}
	echo json_encode($answer);
?>
