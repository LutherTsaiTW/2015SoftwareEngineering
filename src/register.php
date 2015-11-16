<?php

header("Content-Type:text/html; charset=utf-8");

$aName = $_POST["Name"];
$aAccount = $_POST["AccountID"];
$psdReceive = $_POST["PasswordInput"];
$aPassword = md5($psdReceive);
$aEmail = $_POST["Email"];
$aCompany = $_POST["Company"];
$aPrivilege = intval($_POST["Privilege"]);

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

//Insert Course Criticize to SQL
$insert = "INSERT INTO user_info (`uid`, `name`, `account_id`, `password`, `email`, `company`, `previlege`, `user_session`) VALUES (NULL, '$aName', '$aAccount', '$aPassword', '$aEmail', '$aCompany', '$aPrivilege', NULL)";

if (mysqli_query($conn, $insert)) {
	$feedback = array();
	$feedback['success'] = '1';
	echo(json_encode($feedback));
} else {
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

?>