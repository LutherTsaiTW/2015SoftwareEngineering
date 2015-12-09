<?php session_start();
	// [BC]
	// 這個API用於新增test case
	// 輸入包含name, des, data, result
	// user_id則是另外取得的
	
	$name = $_POST['name'];
	$des = trim($_POST['des']);
	$data = $_POST['data'];
	$expectResult = $_POST['result'];

	// [BC] Create connection
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'there is an error when connection DB');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");

	// [BC] 取得user_info
	$findUser = "SELECT * FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "'";
	$result = $sqli->query($findUser);
	if (!$result) {
		$resopnse = array('success' => 0, 'message' => 'there is an error when SELECT user_info in addTestCase.php');
		echo json_encode($resopnse);
		exit();
	}
	$user = $result->fetch_array(MYSQLI_ASSOC);
	$uid = $user['uid'];

	// [BC] 新增Test Case
	$query = "INSERT INTO testcase (tid, name, t_des, data, owner_id, result) VALUES (NULL, '$name', '$des', '$data', $uid, '$expectResult')";
	$result = $sqli->query($query);
	if(!$result){
		$resopnse = array('success' => 0, 'message' => 'there is an error when INSERT testcase in addTestCase.php');
		echo json_encode($resopnse);
		exit();
	}
	$resopnse = array('success' => 1, 'message' => 'SUCCESS');
	echo json_encode($resopnse);
	exit();
?>