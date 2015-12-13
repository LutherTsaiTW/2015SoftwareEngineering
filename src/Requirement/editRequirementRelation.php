<?php
	$rid = $_POST['rid'];
	$changes = $_POST['changed_rids'];
	// $rid = 11;
	// $changes = array(10, 12, 14);
			
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
	
	$relationToBeChangedinA = array();
	$relationToBeChangedinB = array();

	//Retreive all the relations
	$sql = "SELECT * FROM `req_relation` WHERE rid_a = " . $rid . " OR rid_b = " . $rid . ";";
	$result = $sqli->query($sql) or trigger_error($sqli->error."[$sql]");

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if ($row['rid_a'] == $rid)
			array_push($relationToBeChangedinB, $row['rid_b']);
		elseif ($row['rid_b'] == $rid)
			array_push($relationToBeChangedinA, $row['rid_a']);
	}

	// echo json_encode($relationToBeChangedinA);
	// echo json_encode($relationToBeChangedinB);
	
	foreach ($changes as $value) {
		if (in_array($value, $relationToBeChangedinA) || in_array($value, $relationToBeChangedinB))
		{
			if (in_array($value, $relationToBeChangedinA)) {
				$sqli->query("DELETE FROM req_relation WHERE rid_b = " . $rid . " AND rid_a = " . $value . ";") or die($sqli->error);
			}
			else {
				$sqli->query("DELETE FROM req_relation WHERE rid_a = " . $rid . " AND rid_b = " . $value . ";") or die($sqli->error);
			}
		}
		else
		{
			$sqli->query("INSERT INTO req_relation(rid_a, rid_b) VALUES(" . $rid . ", " . $value . ");") or die($sqli->error);
		}
	}
	
	$feedback = array('success' => 1);
	echo json_encode($feedback);	
?>
