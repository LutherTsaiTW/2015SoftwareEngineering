<?php
	header("Content-Type:text/html; charset=utf-8");
	@$Rid  = $_POST['rid'];

	/* [CLY] Database Setting */
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
	$sqli->query("UPDATE `req` SET `rstatus` = '2' WHERE `req`.`rid` =  ".$Rid.";") or die('Insert Query error');
	
	$feedback['success'] = 1;
	echo json_encode($feedback);
?>
