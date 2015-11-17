<?php session_start();
	// 這個API會讀取session然後抓出對應的user
	// 會產生一個新的變數，叫做$user
	// 該變數中會儲存所有user_info對應的資料
	// $user中會有一個新的欄位，叫做success
	// 如果順利抓到資料，success = 1，反之是 0
	
	header("Content-Type:text/html; charset=utf-8");
	$session = $_SESSION['sessionid'];

	// [BC] 取得連線
	include_once 'getDataBaseConnection.php';
	$sqli = getDBConnection();
	if(is_null($sqli)){
		exit("connection_error");
	}
	

	//Insert Course Criticize to SQL
	$query = "SELECT * FROM user_info WHERE user_session='" . $session . "'";
	$result = $sqli->query($query) or die('Query Error');
	
	if ($user = $result->fetch_array(MYSQLI_ASSOC)) {
		$user['success'] = '1';
	}else {
		$user = array('success' => 0, 'message' => 'db_error');
	}
?>