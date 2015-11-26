<?php
	// [BC]
	// 這個API用在新增requirement
	// 回傳值是SUCCESS 和MESSAGE
	// SUCCESS -> 0 -> 失敗
	// SUCCESS -> 1 -> 成功
	// 參數列表如下
	$pid = $_POST['pid'];
	$uid = $_POST['uid'];
	$rName = $_POST['requirementName'];
	$rType = $_POST['typeName'];
	$rPriority = $_POST['priority'];
	$rDescription = $_POST['requirementDes'];

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

	$insert = "INSERT INTO req(rid, rname, rtype, rdes, rowner, rpriority, rproject, rstatus) VALUES (NULL, '$rName', $rType, '$rDescription', $uid, $rPriority, $pid, 1)";
	$result = $sqli->query($insert);
	if(!$result)
	{
		$response = array('SUCCESS' => 0, 'MESSAGE' => 'there is an error when executing INSERT req in addRequirement.php');
		exit(json_encode($response));
	}
	$response = array('SUCCESS' => 1, 'MESSAGE' => 'successfully add a requirement');
	echo json_encode($response);
	//header("Location: requirementListView.php?pid=" . $pid);
?>
