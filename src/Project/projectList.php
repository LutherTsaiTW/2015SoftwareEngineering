<?php session_start();
	//require_once('../assist/getUserInfo.php');
    // [BC] DB connection
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$response = array('success' => 0, 'message' => 'there is an error when getting DB connection in editProject.php');
		echo(json_encode($response));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");
	$session = $_SESSION['sessionid'];

	// [BC] 取的使用者資料
	$selectUser = "SELECT name, uid, previlege FROM user_info WHERE user_session='" . $session . "'";
	$result = $sqli->query($selectUser) or die('there is an error when SELECT user info in projectList.php');
	$user = $result->fetch_array(MYSQLI_ASSOC);

    /* [CLY] Get project list */
    $result = $sqli->query("SELECT p.p_id, p.p_name, p.p_company, p.p_owner, p.p_start_time, p.p_end_time, p.status FROM project AS p LEFT JOIN project_team AS t ON p.p_id = t.project_id WHERE t.user_id = " . $user['uid'] . ";") or die('Project query error');
    
	$feedback['name'] = urlencode($user['name']);
	$feedback['previlege'] = urlencode($user['previlege']);
	$pos = 0;
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$result_owner = $sqli->query("SELECT name FROM user_info WHERE uid = " . $row['p_owner'] . ";");
		$row_owner = $result_owner->fetch_array(MYSQLI_ASSOC);
		$feedback['projects'][$pos]['pid'] = $row['p_id'];
		$feedback['projects'][$pos]['name'] = urlencode($row['p_name']);
		$feedback['projects'][$pos]['company'] = urlencode($row['p_company']);
		$feedback['projects'][$pos]['owner'] = urlencode($row_owner['name']);
		
		$stime = explode(" ", $row["p_start_time"])[0]; 
		$stime = str_replace("-", "/", $stime);
		$feedback['projects'][$pos]['start_time'] = urlencode($stime);
		
		$etime = explode(" ", $row["p_end_time"])[0];
		$etime = str_replace("-", "/", $etime);
		$feedback['projects'][$pos]['end_time'] = urlencode($etime);
		
		$feedback['projects'][$pos]['status'] = urlencode($row['status']);
		$feedback['success'] = '1';
		$feedback['message'] = 'ok';
		$pos++;
    }
	echo(urldecode(json_encode($feedback))); // [CLY] urldecode : Decode html code to let it show correctly
	$feedback=json_encode($feedback);
?>
