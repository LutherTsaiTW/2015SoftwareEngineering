<?php
	$uid = $_POST['uid'];
	$rid = $_POST['rid'];
	
	require_once '../assist/DBConfig.php';
	
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$feedback = array('success' => 0 ,'message' => 'db_error');
		echo(json_encode($feedback));
		exit();
	}
	
	$sqli->query("SET NAMES 'UTF8'"); // [CLY] Let Chinese charcters show correctly
	if($sqli->query("INSERT INTO req_review(reviewerID, req_ID, decision) VALUES(" . $uid . ", " . $rid . ", 0);"))
	{
		$feedback = array('success' => 1);
		echo(json_encode($feedback));
	}
	else
	{
		$feedback = array('success' => 0 ,'message' => $sqli->error);
		echo(json_encode($feedback));
	}
?>
