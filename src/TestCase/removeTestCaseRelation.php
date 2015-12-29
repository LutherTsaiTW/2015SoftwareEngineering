<?php
	$rid  = $_POST['rid'];
	$tid = $_POST['tid'];

	/* [CLY] Database Setting */
	require_once '../assist/DBConfig.php';

	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$feedback = array('success' => 0, 'message' => 'db_error');
		echo(json_encode($feedback));
		exit();
	}
	
	$sqli->query("SET NAMES 'UTF8'"); // [CLY] Let Chinese charcters show correctly

	$update = "DELETE FROM test_relation WHERE rid = " . $rid ." AND tid = " . $tid . ";";
	$result = $sqli->query($update);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'ERROR IN UPDATE - changeRequirement.php');
		exit(json_encode($response));
	}
	
	$feedback['success'] = 1;
	echo json_encode($feedback);
?>
