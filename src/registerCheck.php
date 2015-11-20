<?php

header("Content-Type:text/html; charset=utf-8");

$aAccount = $_POST["AccountID"];
$aEmail = $_POST["Email"];

/* Database Setting */
require_once 'assist/DBConfig.php';
	
// Create connection
$conn = mysqli_connect($dburl, $dbuser, $dbpass, $db);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Show Chinese Chracters Correctly
mysqli_query($conn, "SET NAMES 'UTF8'");

//Select SQL Statement FOR check ACCOUNT
$select = "SELECT account_id FROM user_info WHERE `account_id` = '$aAccount'";
$searchResult = mysqli_query($conn, $select);
$searchResultCount = mysqli_num_rows($searchResult);

$errFlag = 0;
$accountError = 0;
$emailError = 0;

if ($searchResultCount > 0) {
	$errFlag = 1;
	$accountError = 1;
}

//Select SQL Statement FOR check ACCOUNT
$select = "SELECT account_id FROM user_info WHERE `email` = '$aEmail'";
$searchResult = mysqli_query($conn, $select);
$searchResultCount = mysqli_num_rows($searchResult);

if ($searchResultCount > 0) {
	$errFlag = 1;
	$emailError = 1;
}

if ($errFlag == 0) {
	$feedback = array();
	$feedback['success'] = '1';
	echo(json_encode($feedback));
}
else
{
	$feedback = array();
	$feedback['success'] = '0';
	$feedback['account'] = $accountError;
	$feedback['email'] = $emailError;
	echo(json_encode($feedback));
}
?>