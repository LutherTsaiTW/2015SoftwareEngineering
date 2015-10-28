<?php
	/* Database Setting */
	$dburl = " ";
	$dbuser = " ";
	$dbpass = " ";
	$db = "2015softwareengineering";

	$user = null;
	session_start();
	$user_session = $_SESSION['sessionid'];
	
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$feedback = array('success' => 0, 'message' => 'db_error');
		echo(json_encode($feedback));
		exit();
	}
	
	$sqli->query("SET NAMES 'UTF8'");
	$result = $sqli->query("SELECT COUNT(uid) AS c, uid, name, previlege FROM user_info WHERE user_session='" . $user_session . "';") or die('Query error');
	
	$feedback = array();
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
        if($row['c'] == 1)
		{
			$uid = $row['uid'];
			$previlege = $row['previlege'];
			
			$feedback['name'] = urlencode($row['name']);
		}
		else
		{
			$feedback = array('success' => 0, 'message' => 'session_error');
			echo(json_encode($feedback));
			$sqli->close();
			exit();
		}
    }
    
    $result = $sqli->query("SELECT P.p_id, P.p_name, P.p_des, P.p_company, P.p_owner, P.p_start_time, P.p_end_time FROM project AS P LEFT JOIN project_team AS T WHERE P.p_id = T.project_id AND T.user_id = " . $uid . ";") or die('Query error');
    
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
    }
    
	$feedback['success'] = '1';
	$feedback['message'] = 'ok';
	echo(urldecode(json_encode($feedback)));
?>
