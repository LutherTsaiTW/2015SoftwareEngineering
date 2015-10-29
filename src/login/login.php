<?php
	header("Content-Type:text/html; charset=utf-8");
	
	$user = $_POST["account_id"];
	$password = $_POST["password"];
	$now = getdate();

	/* [CLY] Database Setting */
	$dburl = " ";
	$dbuser = " ";
	$dbpass = " ";
	$db = "2015softwareengineering";

	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$feedback = array('success' => 0, 'message' => 'db_error');
		echo(json_encode($feedback));
		exit();
	}

	$sqli->query("SET NAMES 'UTF8'"); // [CLY] Let Chinese charcters show correctly
	$result = $sqli->query("SELECT COUNT(uid) AS c, uid, name, previlege FROM user_info WHERE account_id='" . $user . "' AND password='" . md5($password) . "';") or die('Query error');
	
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
        if($row['c'] == 1)
		{
			session_start();
			$sid = md5("{$row['uid']}{$now['year']}{$now['mon']}{$now['mday']}{$now['hours']}{$now['minutes']}{$now['seconds']}"); // [CLY] Generate Session ID
			$_SESSION['sessionid'] = $sid; // [CLY] Save Session ID
			$sqli->query("UPDATE user_info SET user_session='" . $sid . "' WHERE uid=" . $row['uid'] . " AND account_id='" . $user . "';") or die('Query error'); // [CLY] Upload Session ID
			session_write_close();
			
			$feedback = array();
			$feedback['success'] = '1';
			$feedback['message'] = 'ok';
			$feedback['uid'] = $row['uid'];
			$feedback['name'] = urlencode($row['name']); // [CLY] urlencode : Let Chinese charcters show correctly
			$feedback['previlege'] = $row['previlege'];
			
			// [CLY] Redirect to projectlist.php
			header("Location: ./projectlist.php");
			exit();
		}
		else
		{
			$feedback = array('success' => 0, 'message' => 'result_error');
			echo(json_encode($feedback));
			$sqli->close();
			exit();
		}
    }
