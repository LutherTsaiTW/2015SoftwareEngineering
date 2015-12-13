<?php
	$tid = $_POST['tid'];
	$changes = $_POST['changed_tids'];
			
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
	
	$sqli->query("SELECT rid FROM test_relation WHERE tid=" . $tid . ";");
	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$rrids[$row['rid']] = $row['rid'];
	}
	
	foreach ($changes as $change)
	{
		if(in_array($rrids))
		{
			$sqli->query("DELETE FROM test_relation WHERE tid=" . $tid . " AND rid=" . $change . ";") or die($sqli->error);
		}
		else
		{
			$sqli->query("INSERT INTO test_relation(tid, rid) VALUES(" . $tid . ", " . $change . ");") or die($sqli->error);
		}
	}
	
	$feedback = array('success' => 1);
	echo json_encode($feedback);	
?>
