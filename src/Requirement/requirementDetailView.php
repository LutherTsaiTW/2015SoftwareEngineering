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
			$rid = $_GET['rid'];
			
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
			
			//Get User Info
			$result = $sqli->query("SELECT uid, name, previlege FROM user_info WHERE user_session='" . $session . "'") or die($sqli->error);
			if (!($userinfo = $result->fetch_array(MYSQLI_ASSOC)))
			{
				$feedback = array('success' => 0, 'message' => 'userinfo_fetch_error');
				echo(json_encode($feedback));
				exit;
			}
			
			
			$result = $sqli->query("SELECT * FROM req WHERE rid = '" . $rid . "'") or die($sqli->error);
			if(!($req_info = $result->fetch_array(MYSQLI_ASSOC)))
			{
				$feedback = array('success' => 0, 'message' => 'project_fetch_error');
				echo(json_encode($feedback));
				exit;
			}
		?>
		<br>
		<div style="z-index:1;">
			<div class="w3-row">
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
			</div>

			<div class="w3-row " style="Height:30%;color:white;text-align:center">
				<a id="back" style="float:left;padding-left:10px;padding-top:10px;font-size:20px" href="../Project/requirementListView.php?pid=<?= $req_info['rproject']; ?>">Back</a>
				<a id="edit" style="float:right;padding-right:10px;padding-top:10px;font-size:20px" href="../Project/editRequirementView.php?rid=<?=$rid; ?>">Edit</a>
				<h1 style="background-color:grey;border-radius:5px"><?= $req_info['rname']; ?></h1>
			</div>

			<div style="float:left;width:60%">
				<div style="float:left;width:100%;margin-right:5px">
					<div id="detail" class="detail">
						<table>
							<font style="float:left;width:200px;margin-right:5px;font-size:24px;">
								<b>Description:</b>
							</font>
							<br><br>
							<font class="detailBoxFont" style="float:left;width:200px;margin-right:5px">
								<b><?= $req_info['rdes']; ?></b>
							</font>
						</table>
					</div>
				</div>
				<br>
				<br>
				<div style="float:left;width:100%;margin-top:10px;margin-right:5px">
					<div id="detail" class="detail">
						<table>
							<font style="float:left;width:200px;margin-right:5px;font-size:24px;">
								<b>MEMO</b>
							</font>
						</table>
					</div>
				</div>
			</div>

			<div style="float:left;width:40%">
				<div style="float:right;width:330px;">
					<div id="detail" class="detail">
						<table>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left;width:50px;margin-right:5px">
										<b>Type:</b>
									</font>
								</td>
								<td>
									<font class="detailBoxFont" style="float:right;width:200px;margin-right:5px">
										<b>
										<?php
											if($req_info['rtype']==0) echo "non-functional";
											if($req_info['rtype']==1) echo "functional";
										?>
										</b>
									</font>
								</td>
							</tr>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left;width:50px;margin-right:5px">
										<b>Status:</b>
									</font>
								</td>
								<td>
									<font class="detailBoxFont" style="float:right;width:200px;margin-right:5px">
										<b>
										<?php
											if($req_info['rstatus']==0) echo "Terminated";
											if($req_info['rstatus']==1) echo "Open";
											if($req_info['rstatus']==2) echo "In Review";
											if($req_info['rstatus']==3) echo "Approved";
											if($req_info['rstatus']==4) echo "Old";
										?>
										</b>
									</font>
								</td>
							</tr>
							<tr>
								<td>
									<font class="detailBoxFont" style="float:left;width:50px;margin-right:5px">
										<b>Status:</b>
									</font>
								</td>
								<td>
									<font class="detailBoxFont" style="float:right;width:200px;margin-right:5px">
										<b>
										<?php
											if($req_info['rpriority']==0) echo "Low";
											if($req_info['rpriority']==1) echo "Medium";
											if($req_info['rpriority']==2) echo "High";
										?>
										</b>
									</font>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
