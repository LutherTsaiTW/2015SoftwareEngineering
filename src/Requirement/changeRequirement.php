<?php
	$oldRID = $_POST['old_rid'];
	$versionType = $_POST['version_type'];
	$description = $_POST['des'];

	// $oldRID = 12;
	// $versionType = 0;
	// $description = "testing Change 2";

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

	$select = "SELECT * FROM req WHERE rid = " . $oldRID .";";
	$result = $sqli->query($select);
	if(!($req_info = $result->fetch_array(MYSQLI_ASSOC)))
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'ERROR IN SELECT - changeRequirement.php');
		exit(json_encode($response));
	}

	$pid = $req_info['rproject'];
	$uid = $req_info['rowner'];
	$rName = $req_info['rname'];
	$rNumber = $req_info['rnumber'];
	$rType = $req_info['rtype'];
	$rPriority = $req_info['rpriority'];
	$oldVerionID = $req_info['rid'];
	list($currentVersionPrefix, $currentVersionSuffix) = explode(".", $req_info['version']);

	if ($versionType) {
		$currentVersionPrefixVal = intval($currentVersionPrefix) + 1;
		$currentVersionSuffixVal = 0;
	}
	else {
		$currentVersionPrefixVal = intval($currentVersionPrefix);
		$currentVersionSuffixVal = intval($currentVersionSuffix) + 1;
	}

	$newVersion = $currentVersionPrefixVal . "." . $currentVersionSuffixVal;

	$insert = "INSERT INTO req(rid, rnumber, rname, rtype, rdes, rowner, rpriority, rproject, rstatus, version, oldVersion) VALUES (NULL, '$rNumber', '$rName', $rType, '$description', $uid, $rPriority, $pid, 1, '$newVersion', $oldVerionID);";
	$result = $sqli->query($insert);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'ERROR IN INSERT - changeRequirement.php');
		exit(json_encode($response));
	}
	else
	{
		 $newREQID = $sqli->insert_id;
	}

	$update = "UPDATE req SET rstatus = 5 WHERE rid = " . $oldRID .";";
	$result = $sqli->query($update);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'ERROR IN UPDATE - changeRequirement.php');
		exit(json_encode($response));
	}

	$update = "UPDATE req_relation SET rid_a = " . $newREQID ." WHERE rid_a = " . $oldRID .";";
	$result = $sqli->query($update);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'ERROR IN UPDATE - changeRequirement.php');
		exit(json_encode($response));
	}

	$update = "UPDATE req_relation SET rid_b = " . $newREQID ." WHERE rid_b = " . $oldRID .";";
	$result = $sqli->query($update);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'ERROR IN UPDATE - changeRequirement.php');
		exit(json_encode($response));
	}

	$update = "UPDATE test_relation SET rid = " . $newREQID .", confirmed = 0 WHERE rid = " . $oldRID .";";
	$result = $sqli->query($update);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'ERROR IN UPDATE - changeRequirement.php');
		exit(json_encode($response));
	}

	$response = array('SUCCESS' => 1);
	echo json_encode($response);
	
?>
