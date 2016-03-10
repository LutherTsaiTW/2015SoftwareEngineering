<?php
	// [BC]
	// 這個API是用來取得requirement資訊的API
	// input 是 rid
	// output 是 requirement 資訊
	
	$rid = $_GET['rid'];

	// [BC] db connection
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	if($sqli->connect_errno)
	{
		$feedback = array('success' => 0, 'message' => 'DB error = ' . $sqli->connect_error . ' in the getRequriement.php');
		echo(json_encode($feedback));
		exit;
	}

	$selectReq = "SELECT * FROM req WHERE rid=$rid";
	$result = $sqli->query($selectReq);
	$data = $result->fetch_array();
	echo json_encode($data);
?>