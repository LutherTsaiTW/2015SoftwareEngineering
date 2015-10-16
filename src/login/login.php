<?php
	$user = $_POST["account_id"];
	$password = $_POST["password"];
	$now = getdata();
	
	/* Database Setting */
	$dburl = "";
	$dbuser = "";
	$dbpass = "";
	$db = "2015softwareengineering";
	
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$feedback = array('success' => 0, 'message' => 'db_error');
		echo json_encode($feedback);
		exit();
	}
	$stmt = $sqli->prepare("SELECT uid, name FROM user_info WHERE account_id = ? AND password = ?;");
	$stmt->bind_param("ss", $user, md5($password));
	$stmt->execute();
	
	$result = $stmt->get_result();
	if($result->num_rows == 1)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		
		session_start();
		$_SESSION['sessionid'] = md5("{$row['uid']}{$now['year']}{$now['mon']}{$now['mday']}{$now['hours']}{$now['minutes']}{$now['seconds']}");
		session_write_close();
		
		$feedback = array('success' => 1, 'message' => 'ok', 'uid' => $row['uid'], 'name' => $row['name'], 'sid' = "{$row['uid']}{$now['year']}{$now['mon']}{$now['mday']}{$now['hours']}{$now['minutes']}{$now['seconds']}");
		echo json_encode($feedback);
	}
	else
	{
		$feedback = array('success' => 0, 'message' => 'result_error');
		echo json_encode($feedback);
	}
	
	$sqli->close();
?>
