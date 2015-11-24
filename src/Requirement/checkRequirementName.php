<?php
	// [BC]
	// 這個API用在檢查是否有重複的requirement name
	// 回傳值是SUCCESS 和MESSAGE
	// SUCCESS -> 0 -> 失敗
	// SUCCESS -> 1 -> 成功
	// 參數列表如下
	$pid = $_POST['pid'];
	$requirementName = $_POST['requirementName'];

	// [BC] 取得DB連線
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

	// [BC] SQL Query
	$selectQuery = "SELECT count(rproject) AS count, rproject, rname FROM req WHERE rproject=$pid AND rname='$requirementName'";
	$result = $sqli->query($selectQuery);
	$data = $result->fetch_array();
	if($data['count'] != 0){
		$response = array('SUCCESS'=>0, 'MESSAGE'=>'this requirement\'name has used in this project');
		echo json_encode($response);
		return;
	}
	$response = array('SUCCESS'=>1, 'MESSAGE'=>'this requirement\' name is not used in this project');
	echo json_encode($response);
	return;
?>