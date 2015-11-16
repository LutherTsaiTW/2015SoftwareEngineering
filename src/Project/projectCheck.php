<?php

	header("Content-Type:text/html; charset=utf-8");

	$pCompany = $_POST["Company"];
	$pName = $_POST["Project_Name"];

	/* Database Setting */
	$dburl = "";
	$dbuser = "";
	$dbpass = "";
	$db = "";
	
	// Create connection
	$conn = mysqli_connect($dburl, $dbuser, $dbpass, $db);
	// Check connection
	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}

	//Show Chinese Chracters Correctly
	mysqli_query($conn, "SET NAMES 'UTF8'");

	//Select SQL Statement FOR check ACCOUNT
	$select = "SELECT * FROM project WHERE `p_name` = '$pName' AND `p_company` = '$pCompany'";
	$searchResult = mysqli_query($conn, $select);
	$searchResultCount = mysqli_num_rows($searchResult);

	
	if ($searchResultCount == 0) {
		//SUCCESS with no repeatance
		$feedback = array();
		$feedback['SUCCESS'] = '1';
		echo(json_encode($feedback));
	} else {
		//Error with conflicts
		$feedback = array();
		$feedback['SUCCESS'] = '0';
		echo(json_encode($feedback));
	}

?>