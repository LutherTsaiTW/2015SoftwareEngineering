<!DOCTYPE HTML>
<html>
	<head>
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
			$userinfo = $result->fetch_array(MYSQLI_ASSOC);
			
			$result = $sqli->query("SELECT p_name FROM project WHERE p_id=" . $pid . ";") or die($sqli->error);
			if ($project = $result->fetch_array(MYSQLI_ASSOC))
			{
				$project_name = $project['p_name'];
			}
			
			$result = $sqli->query("SELECT t.tid, t.name, t.owner_id, u.name AS owner FROM testcase AS t LEFT JOIN user_info AS u ON t.owner_id = u.uid WHERE t.pid = " . $pid . ";") or die($sqli->error);
			while ($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$testcases[$row['tid']]['tid'] = $row['tid'];
				$testcases[$row['tid']]['name'] = $row['name'];
				$testcases[$row['tid']]['ownerid'] = $row['owner_id'];
				$testcases[$row['tid']]['owner'] = $row['owner'];
			}
		?>
		<title><?= $project_name; ?> - Test Cases</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="../css/w3.css">
		<link rel="stylesheet" type="text/css" href="../css/html5tooltips.css" />
		<link rel="stylesheet" type="text/css" href="../css/html5tooltips.animation.css" />
		<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/moment-with-locales.js"></script>
		<script type="text/javascript" src="../js/html5tooltips.js"></script>
		<script type="text/javascript" src="../js/sessionCheck.js"></script>
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

	.listTable #lastchild {
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
		<br>
		<div style="z-index:1;">
			<div class="w3-row ">
				<div style="float:left">
					<img src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" />
				</div>
				<div class="w3-container fastAccount">
					<a href="../logout.php" style="font-size:20px">Logout</a>
				</div>
				<div class="fastAccountBlock">
					<p></p>
				</div>
				<div class="w3-container" id="userName" style="float:right;color:white;font-size:20px">
				   Welcome! <?= $userinfo['name']; ?></div>
				</div>
			<div class="w3-row " style="Height:30%;color:white;text-align:center">
				<a id="edit" style="float:left;padding-left:10px;padding-top:10px;font-size:20px" href="../Project/projectDetailView.php?pid=<?=$pid; ?>">Back</a>
				<h1 style="background-color:grey;border-radius:5px;font-weight:bold"><?= $project_name; ?> - Test Cases</h1>
			</div>
			<div class="w3-row " style="Height:40%">
				<div id="listTable">
					<center>
						<table class="listTable" style="width:900px">
							<tr id="header">
								<td><b>Name</b></td>
								<td><b>Owner</b></td>
								<td></td>
							</tr>
							<?php
								foreach($testcases as $testcase)
								{
							?>
							<tr class="items">
								<td style="font-size:22px"><a href="testCaseDetailView?tid=<?= $testcase['tid']; ?>"><?= $testcase['name']; ?></a></td>
								<td style="font-size:22px"><?= $testcase['owner']; ?></td>
									<?php
										if($userinfo['previlege'] == 999 || $userinfo['previlege'] == 777)
										{
											echo("<td id=\"lastchild\" style=\"font-size:22px\"><a id='deletelink' href='#' onclick='deleteTestCase" . $testcase['tid'] . "()'>Delete</a><span>&nbsp;&nbsp;</span><a href='editTestCaseView.php?tid=" . $testcase['tid'] . "'>Edit</a></td>");
									?>
									<script>function deleteTestCase<?= $testcase['tid']; ?>()
											{
												$.post('deleteTestCase.php',
													{tid : <?= $testcase['tid']; ?>},
													function(data)
													{
														if(data.success == '1')
														{
															location.reload();
														}
													},
													'json');
											}
									</script>
									<?php
										}
										else
										{
											echo("<td></td>");
										}
									?>
							</tr>
							<?php	
								}
							?>
							<tr id="link">
								<td colspan=3 style="text-align:left"><?php if($userinfo['previlege']==777 || $userinfo['previlege']==999) echo('<a href="addTestCaseView.php?pid=' . $pid . '" style="white-space:nowrap;font-size:22px"><b>Add New Test Case</b></a>'); ?></td>
							</tr>
						</table>
					</center>
                    <div class="listButton">
						<a href="editTestCaseRelationView.php?pid=<?php echo $pid;?>">Relation</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
