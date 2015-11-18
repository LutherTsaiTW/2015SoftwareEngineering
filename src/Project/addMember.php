<?php
	$addusers = $_POST['addusers'];
	$pid = $_POST['pid'];

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
	foreach ($addusers as $adduser)
	{
		$sqli->query("INSERT INTO project_team (project_id, user_id) VALUES (" . $pid . ", " . $adduser . ");") or die('Query error');
	}
	
	$feedback['success'] = 1;
	echo json_encode($feedback);
?>
