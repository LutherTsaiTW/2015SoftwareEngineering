<?php
	// [BC] register dhutdown function <= MKZ found it
	include "../assist/CatchFatalError.php";
	register_shutdown_function("catch_fatal_error");
	// [BC]
	// 這個API用於修改test case
	// 輸入包含tid, name, des, data, result

	// [BC] 取得輸入
	$name = $_POST['name'];
	$des = trim($_POST['des']);
	$data = $_POST['data'];
	$expectResult = $_POST['result'];
	$tid = $_POST['tid'];

	// [BC] Create connection
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'there is an error when connection DB in editTestCase.php');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");

	// [BC] 修改Test Case
	$update = "UPDATE  testcase SET name='$name', t_des='$des', data='$data', result='$expectResult' WHERE tid=$tid";
	$result = $sqli->query($update);
	if(!$result){
		$resopnse = array('success' => 0, 'message' => 'there is an error when UPDATE testcase in editTestCase.php');
		echo json_encode($resopnse);
		exit();
	}
	$resopnse = array('success' => 1, 'message' => 'SUCCESS');
	echo json_encode($resopnse);
	exit();
?>