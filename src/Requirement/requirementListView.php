<!DOCTYPE HTML>
<html>
	<head>
		<title>Edit Project</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../css/w3.css">
		<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
	</head>
	<style>
	.fastAccount {
		background-color: grey;
		border-radius: 5px;
		float: right;
	}

	.fastAccountBlock {
		width: 10;
		float: right;
	}
	</style>
	<body class="w3-container" style="background-color:rgb(61, 61, 61)">
		<?php
			$pid = $_GET['pid'];
			
			require_once '../assist/DBConfig.php';
			$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
			if($sqli->connect_errno)
			{
				$feedback = array('success' => 0, 'message' => $sqli->connect_error);
				echo(json_encode($feedback));
				exit;
			}
			
			//Show Chinese Chracters Correctly
			$sqli->query("SET NAMES 'UTF8'");
			
			session_start();
			$session = $_SESSION['sessionid'];
			session_write_close();
			
			$result = $sqli->query("SELECT uid, name, previlege FROM user_info WHERE user_session='" . $session . "'") or die($sqli->error);
			if (!($userinfo = $result->fetch_array(MYSQLI_ASSOC)))
			{
				$feedback = array('success' => 0, 'message' => 'userinfo_fetch_error');
				echo(json_encode($feedback));
				exit;
			}
			
			$result = $sqli->query("SELECT p.p_id, p.p_name, p.p_des, p.p_company, u.name AS owner, p.p_start_time, p.p_end_time, p.status FROM project AS p LEFT JOIN user_info AS u ON p.p_owner = u.uid WHERE p_id=" . $pid . ";") or die($sqli->error);
			if(!($project_info = $result->fetch_array(MYSQLI_ASSOC)))
			{
				$feedback = array('success' => 0, 'message' => 'project_fetch_error');
				echo(json_encode($feedback));
				exit;
			}
			
			$result = $sqli->query("SELECT u.name FROM project_team AS p RIGHT JOIN user_info AS u ON u.uid = p.user_id AND p.project_id=" . $pid . ";") or die($sqli->error);
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$members[]['name'] = $row['name'];
			}
			
			$result = $sqli->query("SELECT r.rid, r.rname, r.rtype, r.rdes, r.rstatus, r.rpriority, u.name AS owner FROM req AS r LEFT JOIN user_info AS u ON r.rowner = u.uid WHERE rproject=" . $pid . " AND rstatus != 3 ORDER BY rpriority;") or die($sqli->error);
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$reqs[$row['rid']]['id'] = $row['rid'];
				$reqs[$row['rid']]['name'] = $row['rname'];
				$reqs[$row['rid']]['type'] = $row['rtype'];
				$reqs[$row['rid']]['des'] = $row['rdes'];
				$reqs[$row['rid']]['status'] = $row['rstatus'];
				$reqs[$row['rid']]['priority'] = $row['rpriority'];
				$reqs[$row['rid']]['owner'] = $row['owner'];
			}
		?>
	</body>
</html>
