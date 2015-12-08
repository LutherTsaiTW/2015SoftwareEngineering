<?php
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
	if($sqli->query("UPDATE req SET rstatus=0 WHERE rid=" . $rid . ";"))
	{
		$feedback = array('success' => 1);
	}
	else
	{
		$feedback = array('success' => 0 ,'message' => $sqli->error);
	}
	
	if($sqli->query("DELETE FROM req_relation WHERE rid_a=" . $rid . " OR rid_b = " . $rid . ";"))
	{
		$feedback = array('success' => 1);
	}
	else
	{
		$feedback = array('success' => 0 ,'message' => $sqli->error);
	}
	
	if($sqli->query("DELETE FROM test_relation WHERE rid=" . $rid . ";"))
	{
		$feedback = array('success' => 1);
	}
	else
	{
		$feedback = array('success' => 0 ,'message' => $sqli->error);
	}
	echo(json_encode($feedback));
?>
