<?php
	// [KL]
	//此API用以刪除testcase以及關聯
	//input: tid
	//output: 1/0

	require_once '../assist/DBConfig.php';

	// [KL] 取得參數
	@$tid = $_POST["tid"];

	// [KL] 取得連線
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'there is an error when getting DB connection in deleteProject.php');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");

	// [KL] 刪除testcase
	$result1 =$sqli->query("DELETE FROM testcase WHERE tid=" . $tid) or die('Delete testcase Query error');
	// [KL] 刪除testcase的關聯
	$result2 =$sqli->query("DELETE FROM test_relation WHERE tid=" . $tid) or die('Delete test_relation Query error');

if ($result1&&$result2)
	{
		$answer['success'] = '1';
	}else
	{
		$answer = array('success' => 0, 'message' => 'there is an error when deletting testcase');
	}
		echo json_encode($answer);

?>
