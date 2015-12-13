<?php
	// $rid = $_POST['rid'];
	$rid = 11;
	// $changes = $_POST['changed_rids'];
			
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	if($sqli->connect_errno)
	{
		$feedback = array('success' => 0, 'message' => $sqli->connect_error);
		echo(json_encode($feedback));
		exit;
	}
			
	//Show Chinese Chracters Correctly
	$sqli->query("SET NAMES 'UTF8'");
	
	$relationToBeChanged = array();

	//Retreive all the relations
	$sql = "SELECT * FROM `req_relation` WHERE rid_a = 11 OR rid_b = 11;";
	$result = $sqli->query($sql) or trigger_error($sqli->error."[$sql]");

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		array_push($relationToBeChanged, $row);
	}

	echo json_encode($relationToBeChanged);
	
	// foreach ($changes as $value)
	// {
	// 	if(in_array($rrids))
	// 	{
	// 		$sqli->query("DELETE FROM test_relation WHERE tid=" . $tid . " AND rid=" . $change . ";") or die($sqli->error);
	// 	}
	// 	else
	// 	{
	// 		$sqli->query("INSERT INTO test_relation(tid, rid) VALUES(" . $tid . ", " . $change . ");") or die($sqli->error);
	// 	}
	// }
	
	// $feedback = array('success' => 1);
	// echo json_encode($feedback);	
?>
