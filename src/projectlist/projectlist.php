<?php
	/* [AC] Database Setting */
	$dburl = "";
	$dbuser = "";
	$dbpass = "";
	$db = "2015softwareengineering";

	$user_session = null;
	session_start();
	$user_session = $_SESSION['sessionid']; // [CLY] Get Session ID
	session_write_close();
	
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$feedback = array('success' => 0, 'message' => 'db_error');
		echo(json_encode($feedback));
		exit();
	}
	
	$sqli->query("SET NAMES 'UTF8'");
	$result = $sqli->query("SELECT COUNT(uid) AS c, uid, name, previlege FROM user_info WHERE user_session='" . $user_session . "';") or die('Session query error'); // [CLY] Get user info
	
	$feedback = array();
	$uid = null;
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
        if($row['c'] == 1)
		{
			$uid = $row['uid'];
			
			$feedback['name'] = urlencode($row['name']); // [CLY] urlencode : Let Chinese charcters show correctly
			$feedback['previlege'] = $row['previlege'];
		}
		else
		{
			$feedback = array('success' => 0, 'message' => 'session_error');
			echo(json_encode($feedback));
			$sqli->close();
			exit();
		}
    }
    
    /* [AC] Get project list */
    $result = $sqli->query("SELECT p.p_id, p.p_name, p.p_des, p.p_company, p.p_owner, p.p_start_time, p.p_end_time FROM project AS p LEFT JOIN project_team AS t ON t.user_id = " . $uid . " AND p.p_id = t.project_id;") or die('Project query error');
    
	$feedback['projects'] = array();
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$pid = $row['p_id'];
		$feedback['projects'][$pid]['name'] = urlencode($row['p_name']);
		$feedback['projects'][$pid]['des'] = urlencode($row['p_des']);
		$feedback['projects'][$pid]['company'] = urlencode($row['p_company']);
		$feedback['projects'][$pid]['owner'] = urlencode($row['p_owner']);
		$feedback['projects'][$pid]['start_time'] = urlencode($row['p_start_time']);
		$feedback['projects'][$pid]['end_time'] = urlencode($row['p_end_time']);
		$feedback['success'] = '1';
		$feedback['message'] = 'ok';
    }
	echo(urldecode(json_encode($feedback))); // [CLY] urldecode : Decode html code to let it show correctly
?>
