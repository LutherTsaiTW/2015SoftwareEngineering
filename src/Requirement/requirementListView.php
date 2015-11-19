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
		<?php $pid = $_GET['pid']; ?>
	</body>
			<?php
			$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
			if($sqli->connect_errno)
			{
				$feedback = array('success' => 0, 'message' => 'db_error');
				echo(json_encode($feedback));
				exit;
			}
			//Show Chinese Chracters Correctly
			$sqli->query("SET NAMES 'UTF8'");
			
			$result = $sqli->query("SELECT * FROM project WHERE p_id=" . $pid . ";");
			if($row = $result->fetch_array())
			{
				
			}
			else
			{
				$feedback = array('success' => 0, 'message' => 'project_fetch_error');
				echo(json_encode($feedback));
				exit;
			}
			
			$result = $sqli->query("SELECT u.uid, u.name FROM project_team AS p RIGHT JOIN user_info AS u ON u.uid = p.user_id AND p.project_id=" . $pid . ";");
			if($row = $result->fetch_array())
			{
				
			}
			else
			{
				$feedback = array('success' => 0, 'message' => 'project_team_fetch_error');
				echo(json_encode($feedback));
				exit;
			}
			
			$result = $sqli->query("SELECT * FROM req WHERE rproject=" . $pid . " AND rstatus != 3 ORDER BY rpriority;");
			if($row = $result->fetch_array())
			{
				
			}
			else
			{
				$feedback = array('success' => 0, 'message' => 'requirement_error');
				echo(json_encode($feedback));
				exit;
			}
		?>
</html>
