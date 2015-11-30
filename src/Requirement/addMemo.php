<?php
	@$rm_id  = $_POST['rm_id'];
	@$rid  = $_POST['rid'];
	@$uid = $_POST['uid'];
	@$content = $_POST['content'];
	@$datetime = $_POST['datetime'];

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

	$sqli->query("INSERT INTO `req_memo`(`rm_id`, `rid`, `uid`, `content`, `datetime`, `status`)  VALUES (" . $rm_id . ", " . $rid .  ", " . $uid . ", " . trim($content) . ", " . $datetime. ", " . $status. ");") or die('Insert Query error');
	
	$feedback['success'] = 1;
	echo json_encode($feedback);
?>
