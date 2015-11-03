<?php
	require_once('assist/GetUserInfo.php');
    
    /* [CLY] Get project list */
    $result = $sqli->query("SELECT p.p_id, p.p_name, p.p_des, p.p_company, p.p_owner, p.p_start_time, p.p_end_time FROM project AS p LEFT JOIN project_team AS t ON p.p_id = t.project_id WHERE t.user_id = " . $user['uid'] . ";") or die('Project query error');
    
	$feedback = $user;
	$feedback['name'] = urlencode($user['name']);
	$feedback['company'] = urlencode($user['company']);
	unset($feedback['password']);
	unset($feedback['user_session']);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$result_owner = $sqli->query("SELECT name FROM user_info WHERE uid = " . $row['p_owner'] . ";");
		$row_owner = $result_owner->fetch_array(MYSQLI_ASSOC);
		$pid = $row['p_id'];
		$feedback['projects'][$pid]['name'] = urlencode($row['p_name']);
		$feedback['projects'][$pid]['des'] = urlencode($row['p_des']);
		$feedback['projects'][$pid]['company'] = urlencode($row['p_company']);
		$feedback['projects'][$pid]['owner'] = urlencode($row_owner['name']);
		$feedback['projects'][$pid]['start_time'] = urlencode($row['p_start_time']);
		$feedback['projects'][$pid]['end_time'] = urlencode($row['p_end_time']);
		$feedback['success'] = '1';
		$feedback['message'] = 'ok';
    }
	echo(urldecode(json_encode($feedback))); // [CLY] urldecode : Decode html code to let it show correctly
?>
