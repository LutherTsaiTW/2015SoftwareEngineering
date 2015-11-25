<!DOCTYPE HTML>
<html>
	<head>
		<title>Edit Project</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="../css/w3.css">
		<link rel="stylesheet" type="text/css" href="../css/html5tooltips.css" />
		<link rel="stylesheet" type="text/css" href="../css/html5tooltips.animation.css" />
		<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/moment-with-locales.js"></script>
		<script type="text/javascript" src="../js/html5tooltips.js"></script>
	</head>
	<style>
	#block {
		position: fixed;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		background-color: rgba(0, 0, 0, 0.6);
		margin: =0;
		padding: =0;
		z-index: 998;
		visibility: hidden;
	}

	a {
		cursor: pointer;
	}

	a:link {
		color: lightgrey;
		background-color: transparent;
		text-decoration: none;
	}

	a:visited {
		color: lightgrey;
		background-color: transparent;
		text-decoration: none;
	}

	a:hover {
		color: white;
		background-color: transparent;
		text-decoration: underline;
	}

	a:active {
		color: white;
		background-color: transparent;
		text-decoration: underline;
	}

	.fastAccount {
		background-color: grey;
		border-radius: 5px;
		float: right;
	}

	.fastAccountBlock {
		width: 10;
		float: right;
	}

	#footer {
		position: fixed;
		width: 100%;
		bottom: 0;
		z-index: 1;
		text-align: center;
	}

	.detailBox {
		height: 900px;
		width: 900px;
		margin: 0px auto;
		border-radius: 15px;
	}

	.listButton {
		background-color: grey;
		height: 75px;
		border-radius: 15px;
		float: top;
		margin-top: 5px;
		padding-left: 6px;
		text-align: center;
		font-size: 35;
		color: white;
		font-weight: 600;
		line-height: 75px;
	}

	.detail {
		background-color: rgb(40, 40, 40);
		border-radius: 15px;
		float: top;
		padding-left: 15px;
		padding-top: 10px;
		padding-bottom: 10px;
		font-size: 25;
		color: white;
	}

	.addMemberWindow {
		position: fixed;
		top: 30%;
		left: 40%;
		margin: 0px auto;
		height: 400px;
		width: 300px;
		background-color: rgb(80, 80, 80);
		z-index: 999;
		visibility: hidden;
		border-radius: 15px;
	}

	.backButton {
		float: right;
		margin-top: 10px;
		margin-right: 10px;
		height: 30px;
		width: 20px;
		cursor: pointer;
	}

	.addButton {
		float: right;
		margin-right: 5px;
		margin-top: 300px;
		height: 30px;
		width: 60px;
		background-color:green;
	}

	.memberMutiSelect {
		float: left;
		margin-top: 10px;
		margin-left: 10px;
		height: 380px;
		width: 200px;
	}

	.successWindow {
		text-align: center;
		position: fixed;
		top: 30%;
		left: 40%;
		margin: 0px auto;
		height: 150px;
		width: 400px;
		background-color: rgb(80, 80, 80);
		z-index: 999;
		visibility: hidden;
		border-radius: 15px;

	}
	
	.detailBoxFont
	{
		font-size: 20px;
	}
	
	.listTable {
		text-decoration: none;
		table-layout: fixed;
		word-wrap: break-word;
		border-collapse: separate;
		overflow: hidden font-size: 18;
		border-spacing: 0 10px;
		margin-top: -10px;
	}
	
	.listTable .items {
		font-size: 20px;
	}
	
	.listTable #header,.listTable #link
	{
		font-size: 22px;
	}

	.listTable td {
		color: white;
		padding: 10px;
		background-color: rgb(40, 40, 40);
		valign: center;
	}
	
	.listTable .nonfunctional td {
		background-color: rgb(127, 106, 0);
	}
	
	.listTable .functional td {
		background-color: rgb(82, 127, 63);
	}

	.listTable td:first-child {
		border-top-left-radius: 10px;
		border-bottom-left-radius: 10px;
		width: 264px;
	}

	.listTable td:last-child {
		text-align: right;
		border-bottom-right-radius: 10px;
		border-top-right-radius: 10px;
	}
	
	.html5tooltip-box
	{
		color: black;
		font-size: 20px;
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
			
			$result = $sqli->query("SELECT u.name FROM project_team AS p RIGHT JOIN user_info AS u ON u.uid = p.user_id WHERE p.project_id=" . $pid . ";") or die($sqli->error);
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$members[]['name'] = $row['name'];
			}
			
			$result = $sqli->query("SELECT r.rid, r.rname, r.rtype, r.rdes, r.rstatus, r.rpriority, u.name AS owner FROM req AS r LEFT JOIN user_info AS u ON r.rowner = u.uid WHERE rproject=" . $pid . " AND rstatus != 0 ORDER BY rpriority DESC;") or die($sqli->error);
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
		<br>
		<div style="z-index:1;">
			<div class="w3-row ">
				<div style="float:left">
					<img src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" />
				</div>
				<div class="w3-container fastAccount">
					<a href="../logout.php">Logout</a>
				</div>
				<div class="fastAccountBlock">
					<p></p>
				</div>
				<div class="w3-container" id="userName" style="float:right;color:white;font-size:18px">
				   Welcome! <?= $userinfo['name']; ?></div>
				</div>
			<div class="w3-row " style="Height:30%;color:white;text-align:center">
				<a id="edit" style="float:left;padding-left:10px;padding-top:10px;font-size:20px" href="../Project/projectDetail.php?pid=<?=$pid; ?>">Back</a>
				<h1 style="background-color:grey;border-radius:5px"><?= $project_info['p_name']; ?> Requirements</h1>
			</div>
			<div class="w3-row " style="Height:40%">
				<div style="position:absolute;float:left;width:330px">
					<div id="detail" class="detail">
						<table>
							<tr>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left;width:108px;margin-right:5px">
										<b>Start Time:</b>
									</font>
								</td>
								<td>
									<font id="startTime"  class="detailBoxFont" style="float:left;color:white">
										<?=$project_info["p_start_time"]; ?>
									</font>
								</td>
							</tr>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left;margin-right:15px">
										<b>End Time:</b>
									</font>
								</td>
								<td>
									<font id="endTime" class="detailBoxFont" style="float:left;color:white">
										<?=$project_info["p_end_time"]; ?>
									</font></td>
							</tr>
							<tr>
								<td></td>
								<td><font id="days" class="detailBoxFont" style="font-size:16px;color:gray;float:right;padding-right:22px"></font></td>
							</tr>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left">
										<b style="float:left;margin-right:40px">Owner:</b>
									</font>
								</td>
								<td>
									<font class="detailBoxFont" style="float:left">
										<?=$project_info["owner"]; ?>
									</font>
								</td>
							</tr>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left">
										<b style="float:left;margin-right:12px">Company:</b>
									</font>
								</td>
								<td>
									<font class="detailBoxFont" style="float:left">
										<?=$project_info["p_company"]; ?>
									</font>
								</td>
							</tr>
							<tr>
								<?php
									$str = "";
									foreach($members as $member)
									{
										$str = $str . $member['name'] . ', ';
									}
									$str = trim($str);
									$str = rtrim($str, ",");
								?>
								<td style="vertical-align: baseline">
									<font class="detailBoxFont" style="float:left">
										<b style="float:left;margin-right:12px" id="members">Members:</b>
									</font>
								</td>
								<td>
									<font class="detailBoxFont" style="float:left" id="members-box" data-tooltip="<?= $str; ?>" data-tooltip-stickto="right" data-tooltip-color="stone" data-tooltip-animate-function="scalein">
										<?php
											$shortstr = $str;
											if(mb_strlen($shortstr) > 22)
											{
												$shortstr = mb_substr($shortstr, 0, 22, "UTF-8");
												$shortstr = $shortstr . '...';
											}
											echo($shortstr);
										?>
									</font>
								</td>
							</tr>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left">
										<b style="float:left;margin-right:44px">Status:</b>
									</font>
								</td>
								<td>
									<font class="detailBoxFont" style="float:left">
										<?php
											if($project_info['status']==0) echo "Close";
											if($project_info['status']==1) echo "Open";
											if($project_info['status']==2) echo "Terminated";
										?>
									</font>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div style="float:right;margin-left:350px" id="listTable">
					<table class="listTable">
						<tr id="header">
							<td><b>Name</b></td>
							<td><b>Status</b></td>
							<td><b>Priority</b></td>
							<td><b>Owner</b></td>
							<td><b>Task Amount</b></td>
							<td></td>
							<td></td>
						</tr>
						<?php
							foreach($reqs as $req)
							{
						?>
						<tr class="items <?php if($req['type'] == 0) echo("nonfunctional"); else echo("functional"); ?>">
							<td><a href="requirementDetailView.php?rid=<?=$req['id']; ?>"><?= $req['name']; ?></a></td>
							<td>
								<?php
									if($req['status']==0) echo "Terminated";
									if($req['status']==1) echo "Open";
									if($req['status']==2) echo "In Review";
									if($req['status']==3) echo "Approved";
									if($req['status']==4) echo "Old";
								?>
							</td>
							<td>
								<?php
									if($req['priority']==0) echo "Low";
									if($req['priority']==1) echo "Medium";
									if($req['priority']==2) echo "High";
								?>
							</td>
							<td><?= $req['owner']; ?></td>
							<td>0</td>
							<?php
								if($req['status']==1)
								{
									echo("<td><a href='editRequirementView.php?rid=" . $req['id'] . "'>Edit</a></td>");
									echo("<td><a href='deleteRequirementView.php?rid=" . $req['id'] . "'>Delete</a></td>");
								}
								elseif($req['status']==3)
								{
									echo("<td><a href='changeRequirementView.php?rid=" . $req['id'] . "'>Change</a></td>");
									echo("<td></td>");
								}
								else
								{
									echo("<td></td>");
									echo("<td></td>");
								}
							?>
						</tr>
						<?php
							}
						?>
						<tr id="link">
							<td><a href="addRequirementView.php?pid=<?=$pid; ?>"><b>Add New Requirement</b></a></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				var startTime = moment('<?= $project_info["p_start_time"]; ?>', "YYYY-MM-DD HH:mm:ss");
				var endTime = moment('<?= $project_info["p_end_time"]; ?>', "YYYY-MM-DD HH:mm:ss");
				var diffDays = endTime.diff(startTime, 'days') + 1;
				$("#days").html("Expect: "+  diffDays.toString() + " Day(s)");
			});
		</script>
	</body>
</html>
