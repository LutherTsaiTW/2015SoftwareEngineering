<!DOCTYPE HTML>
<html>
	<head>
		<title>Requirement Detail</title>
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
	
	.html5tooltip-box
	{
		color: black;
		font-size: 20px;
	}
	</style>

	<body class="w3-container" style="background-color:rgb(61, 61, 61)">
		<?php
			$rid = $_GET['rid'];

			//Require DBConfig.php
			require_once '../assist/DBConfig.php';
			//Connect to Database
			$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
			if($sqli->connect_errno)
			{
				$feedback = array('success' => 0, 'message' => $sqli->connect_error);
				echo(json_encode($feedback));
				exit;
			}
			//Show Chinese Chracters Correctly
			$sqli->query("SET NAMES 'UTF8'");
			
			//Start session
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
			
			//Get REQ Info
			$result = $sqli->query("SELECT * FROM req WHERE rid = '" . $rid . "'") or die($sqli->error);
			if(!($req_info = $result->fetch_array(MYSQLI_ASSOC)))
			{
				$feedback = array('success' => 0, 'message' => 'project_fetch_error');
				echo(json_encode($feedback));
				exit;
			}

			if($req_info['oldVersion'] != NULL)
			{
				//Get REQ Info
				$result = $sqli->query("SELECT * FROM req WHERE rid = '" . $req_info['oldVersion'] . "'") or die($sqli->error);
				if(!($req_old_info = $result->fetch_array(MYSQLI_ASSOC)))
				{
					$feedback = array('success' => 0, 'message' => 'project_fetch_error');
					echo(json_encode($feedback));
					exit;
				}
			}

			//Get Project Info
			$result = $sqli->query("SELECT p_owner FROM project WHERE p_id = '" . $req_info['rproject'] . "'") or die($sqli->error);
			if(!($project_info = $result->fetch_array(MYSQLI_ASSOC)))
			{
				$feedback = array('success' => 0, 'message' => 'project_fetch_error');
				echo(json_encode($feedback));
				exit;
			}

			//Get Memo Array
			$memoArray = array();
			$memo = array();
			$result = $sqli->query( "SELECT * FROM `req_memo` WHERE rid = $rid AND status != 0") or die($sqli->error);
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				//Get User Info
				$userResult = $sqli->query("SELECT name FROM user_info WHERE uid='" . $row['uid'] . "'") or die($sqli->error);
				if (!($memoUser = $userResult->fetch_array(MYSQLI_ASSOC)))
				{
					$feedback = array('success' => 0, 'message' => 'userinfo_fetch_error in memo');
					echo(json_encode($feedback));
					exit;
				}

				$memo['id'] = $row['rm_id'];
				$memo['name'] = $memoUser['name'];
				$memo['content'] = $row['content'];
				$memo['datetime'] = $row['datetime'];
				array_push($memoArray, $memo);
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
				<a id="back" style="float:left;padding-left:10px;padding-top:10px;font-size:20px" href="../Requirement/requirementListView.php?pid=<?= $req_info['rproject']; ?>">Back</a>
				<?php
					if($req_info['rstatus']==1)
					{
						echo "<a id=\"edit\" style=\"float:right;padding-right:10px;padding-top:10px;font-size:20px\" href=\"../Requirement/editRequirementView.php?rid=" . $rid ."\">Edit</a>";
					}
					else if($req_info['rstatus'] == 3 || $req_info['rstatus'] == 4 )
					{
						echo "<a id=\"change\" style=\"float:right;padding-right:10px;padding-top:10px;font-size:20px\" href=\"\">Change</a>";
					}
				?>
				<h1 style="background-color:grey;border-radius:5px"><?= $req_info['rname'] . " v" . $req_info['version']; ?></h1>
			</div>
			<div style="width:935px ;margin: 0 auto;">
				<div style="float:left;width:600px">
					<div style="float:left;width:100%;margin-right:5px">
						<div id="des_area" class="detail">
							<table>
								<font style="float:left;width:200px;margin-right:5px;font-size:24px;">
									<b>Description:</b>
								</font>
								<br>
								<font id="des_text" style="float:left;width:540px;margin-right:5px;font-size:20px;">
								<?php
									if($req_info['oldVersion'] != NULL)
									{
										echo "<pre>v" . $req_info['version'] . ":<br>" . $req_info['rdes'] . "</pre>";
										echo "<pre style=\"color:rgb(0,255,0);\">v" . $req_old_info['version'] . ":<br>" . $req_old_info['rdes'] . "</pre>";
									}
									else
									{
										echo "<pre>" . $req_info['rdes'] . "</pre>";
									}
								?>
								</font>
							</table>
						</div>
					</div>
					<br>
					<br>
					<div style="float:left;width:100%;margin-top:10px;margin-right:5px">
						<script type="text/javascript">
							function DoAddMemo() {
								var req_id = "<?php echo $rid ?>";
								var uid = "<?php echo $userinfo['uid']; ?>";
								var currentdate = new Date(); 
								var datetime = currentdate.getFullYear() + "-"
                								+ currentdate.getMonth()  + "-" 
                								+ currentdate.getDate() + " "  
               								 	+ currentdate.getHours() + ":"  
                								+ currentdate.getMinutes() + ":" 
                								+ currentdate.getSeconds();
								console.log(uid);
								console.log(datetime);
								var posting = $.post("addMemo.php", {
            						rid: req_id,
            						uid: uid,
            						content:$("#req_memo").val(),
            						datetime: datetime
        						});

        						posting.done(function(data) {
        							console.log(data);
            						var check_result = $.parseJSON(data);
            						if (check_result.success == "1") {
                						location.reload();
            						} else {
                						alert("Error Occur");
            						}
        						});
							}
						</script>
						<div id="memo_area" class="detail">
							<table>
								<tr>
									<font style="float:left;width:200px;margin-right:5px;font-size:24px;">
										<b>MEMO</b>
									</font>
								</tr>
								<br><br>
								<?php
									foreach ($memoArray as $value) {
										echo "<tr>";
										echo "<div style=\"width:560px;margin-top:5px;margin-left:5px;margin-right:15px;\" class=\"w3-container fastAccount\">";
										echo "<font style=\"float:left;font-size:20px;\"><b>" . $value['name'] . "</b></font>";
										echo "<font style=\"float:right;font-size:16px;color:rgb(64, 64, 64);margin-top:5px;\"><b>at " . $value['datetime'] . "</b></font>";	
										echo "<textarea style=\"width:520px;border:0px;resize:none;color:white;border-radius:10px;font-size:20px;background-color:rgb(64, 64, 64);\" readonly>" . $value['content'] . "</textarea>";
										echo "</div>";
										echo "</tr>";
										echo "<br><br>";
									}
								?>
								<tr>
									<div style="width:560px;height:205px;margin-top:5px;margin-left:5px;margin-right:15px;" class="w3-container fastAccount">
									<form action="javascript:DoAddMemo();">
										<br>
											<textarea style="width:520px;height:140px;border:0px;border-radius:10px;resize:none;color:black;font-size:20px;" id="req_memo" name="req_memo" placeholder="Leave your message here..." required></textarea>
										<br>
											<input style="float:right;margin-right:10px;height:30px;width:70px" id="submitBtn" type="submit" name="submit" value="Save" class="w3-teal">
										<br>
									</form>
									</div>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<div style="float:left;width:330px;margin-left:5px">
					<div style="float:right;width:100%;">
						<div id="req_detail" class="detail" style="float:left;width:100%;">
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
												if($req_info['rtype']==0) echo "non-Functional";
												if($req_info['rtype']==1) echo "Functional";
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
												if($req_info['rstatus']==4) echo "Disapproved";
												if($req_info['rstatus']==5) echo "Old";
											?>
											</b>
										</font>
									</td>
								</tr>
								<tr>
									<td>
										<font class="detailBoxFont" style="float:left;width:50px;margin-right:5px">
											<b>Priority:</b>
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
						<script type="text/javascript">
							function ConfirmRequirement() {
								var req_id = "<?php echo $rid ?>";
								console.log(req_id);
								var posting = $.post("confirmReq.php", {
            						rid: req_id
        						});

        						posting.done(function(data) {
        							console.log(data);
            						var check_result = $.parseJSON(data);
            						if (check_result.success == "1") {
                						location.reload();
            						} else {
                						alert("Error Occur");
            						}
        						});
							}

							function changeMouseOverText(theTag) {
								theTag.innerHTML = "DEL";
							}

							function changeMouseLeaveText(theTag) {
								theTag.innerHTML = "?";
							}
						</script>
						<?php
							$existingReviewers = array();

							if($req_info['rstatus']==1)
							{
								echo "<div style=\"float:left;width:100%;height:70px;text-align:center;\" class=\"listButton\">";
								echo "<a href=\"javascript:ConfirmRequirement();\" style=\"font-size:36px;\" >Confirm</a>";
								echo "</div>";
							}
							else if($req_info['rstatus']==2)
							{
								echo "<div class=\"detail\" style=\"float:left;width:100%;margin-top:10px;\">";
								echo "<font style=\"float:left;width:200px;font-size:22px\">";
								echo "<b>Review</b>";
								echo "</font>";
								$reviewRowResult = $sqli->query("SELECT * FROM req_review WHERE req_ID = " . $rid . "") or die($sqli->error);
								while($reviewRow = $reviewRowResult->fetch_array(MYSQLI_ASSOC))
								{
									array_push($existingReviewers, $reviewRow['reviewerID']);
									echo "<div style=\"width:300px;margin-top:5px;margin-left:5px;margin-right:15px;\" class=\"w3-container fastAccount\">";
									echo "<font style=\"float:left;width:100px;font-size:20px;\">";
									//Get User Info
									$userResult = $sqli->query("SELECT name FROM user_info WHERE uid=" . $reviewRow['reviewerID']) or die($sqli->error);
									if (!($reviewers = $userResult->fetch_array(MYSQLI_ASSOC)))
									{
										$feedback = array('success' => 0, 'message' => 'userinfo_fetch_error');
										//echo(json_encode($feedback));
									}
									else
									{
										echo "<b>" . $reviewers['name'] . "</b>";
									}
									echo "</font>";

									if ($reviewRow['reviewerID'] == $userinfo['uid']) 
									{
										if ($reviewRow['decision'] == 1 || $reviewRow['decision'] == 2) {
											if ($reviewRow['decision'] == 1)
											{
												echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(38,127,0);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
												echo "<b>V</b>";
												echo "</div>";
											}
											elseif ($reviewRow['decision'] == 2) {
												echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(127,0,0);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
												echo "<b>X</b>";
												echo "</div>";
											}
											echo "<div style=\"font-size:20px;\">";
											echo "<font style=\"width:100%;\"><pre style=\"width:100%;\">";
											echo $reviewRow['reviewComment'];
											echo "</pre></font>";
											echo "</div>";
										}
										else {
											if ($project_info['p_owner'] == $userinfo['uid'])
											{
												echo "<div style=\"float:right;width:40px;height:30px;background-color:rgb(64,64,64);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
												echo "<a href=\"javascript:deleteReviewer(" . $reviewRow['reqreviewID'] . ");\">D</a>";
												echo "</div>";
												echo "<div style=\"float:right;width:40px;height:30px;background-color:rgb(127,0,0);margin-top:5px;margin-right:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											}
											else
											{
												echo "<div style=\"float:right;width:40px;height:30px;background-color:rgb(127,0,0);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											}
											echo "<a href=\"javascript:DoDecision(2," . $reviewRow['reqreviewID'] . ");\">X</a>";
											echo "</div>";
											echo "<div style=\"float:right;width:40px;height:30px;background-color:rgb(38,127,0);margin-top:5px;margin-right:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											echo "<a href=\"javascript:DoDecision(1," . $reviewRow['reqreviewID'] . ");\">V</a>";
											echo "</div>";
											echo "<textarea style=\"margin-top:5px;width:270px;height:50px;resize:none;color:black;font-size:14px;\" id=\"review_comment\" name=\"review_comment\" required></textarea>";
										}
									}
									else
									{
										if ($reviewRow['decision'] == 1)
										{
											echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(38,127,0);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											echo "<b>V</b>";
											echo "</div>";
										}
										elseif ($reviewRow['decision'] == 2) {
											echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(127,0,0);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											echo "<b>X</b>";
											echo "</div>";
										}
										elseif ($reviewRow['decision'] == 0) {
											echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(64,64,64);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											if ($project_info['p_owner'] == $userinfo['uid'])
												echo "<a href=\"javascript:deleteReviewer(" . $reviewRow['reqreviewID'] . ");\"><span onmouseover=\"changeMouseOverText(this)\" onmouseleave=\"changeMouseLeaveText(this)\">?</span></a>";
											else
												echo "<b>?</b>";
											echo "</div>";
										}
										echo "<div style=\"font-size:20px;\">";
										echo "<font style=\"width:100%;\"><pre style=\"width:100%;\">";
										echo $reviewRow['reviewComment'];
										echo "</pre></font>";
										echo "</div>";
									}
									echo "</div>";
								}
								if ($project_info['p_owner'] == $userinfo['uid'])
								{
								echo "<div style=\"width:300px;height:70px;margin-top:5px;margin-left:5px;margin-right:15px;\" class=\"w3-container fastAccount\">";
								echo "<font style=\"float:left;width:200px;font-size:20px;\">";
								echo "<b>Add Reviewer</b>";
								echo "</font><br>";
								echo "<form action=\"javascript:AddReviewers();\">";
								echo "<select id=\"reviewer\" name=\"reviewer\" style=\"float:left;color:black;width:200px;height:30px\" required>";
								$result = $sqli->query("SELECT user_id FROM project_team WHERE project_id = '" . $req_info['rproject'] . "'") or die($sqli->error);
								while($row = $result->fetch_array(MYSQLI_ASSOC))
								{
									$isInReview = 1;
									foreach ($existingReviewers as $value) {
										if($value == $row['user_id'])
										{
											$isInReview = 0;
											break;
										}
									}
									if ($isInReview)
									{
										//Get User Info
										$userResult = $sqli->query("SELECT name FROM user_info WHERE uid=" . $row['user_id']) or die($sqli->error);
										if (!($reviewers = $userResult->fetch_array(MYSQLI_ASSOC)))
										{
											$feedback = array('success' => 0, 'message' => 'userinfo_fetch_error');
											//echo(json_encode($feedback));
										}
										else
										{
											echo "<option value=\"" . $row['user_id'] . "\">" . $reviewers['name'] . "</option>";
										}
									}
								}
								echo "</select>";
								echo "<input style=\"float:right;height:30px;width:60px\" type=\"submit\" name=\"submit\" value=\"Add\" class=\"w3-teal\">";
								echo "</form>";
								echo "</div>";
								}
								echo "</div>";
							}
							else if($req_info['rstatus']==3 || $req_info['rstatus']==4)
							{
								echo "<div class=\"detail\" style=\"float:left;width:100%;margin-top:10px;\">";
								echo "<font style=\"float:left;width:200px;font-size:22px\">";
								echo "<b>Review</b>";
								echo "</font>";
								$reviewRowResult = $sqli->query("SELECT * FROM req_review WHERE req_ID = " . $rid . "") or die($sqli->error);
								while($reviewRow = $reviewRowResult->fetch_array(MYSQLI_ASSOC))
								{
									array_push($existingReviewers, $reviewRow['reviewerID']);
									echo "<div style=\"width:300px;margin-top:5px;margin-left:5px;margin-right:15px;\" class=\"w3-container fastAccount\">";
									echo "<font style=\"float:left;width:100px;font-size:20px;\">";
									//Get User Info
									$userResult = $sqli->query("SELECT name FROM user_info WHERE uid=" . $reviewRow['reviewerID']) or die($sqli->error);
									if (!($reviewers = $userResult->fetch_array(MYSQLI_ASSOC)))
									{
										$feedback = array('success' => 0, 'message' => 'userinfo_fetch_error');
										//echo(json_encode($feedback));
									}
									else
									{
										echo "<b>" . $reviewers['name'] . "</b>";
									}
									echo "</font>";

									if ($reviewRow['decision'] == 1)
										{
											echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(38,127,0);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											echo "<a>V</a>";
											echo "</div>";
										}
										elseif ($reviewRow['decision'] == 2) {
											echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(127,0,0);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											echo "<a align=\"center\">X</a>";
											echo "</div>";
										}
										elseif ($reviewRow['decision'] == 0) {
											echo "<div style=\"float:right;width:80px;height:30px;background-color:rgb(64,64,64);margin-top:5px;text-align:center;\" class=\"w3-container fastAccount\">";
											echo "<a align=\"center\">?</a>";
											echo "</div>";
										}
										echo "<div style=\"font-size:20px;\">";
										echo "<font style=\"width:100%;\"><pre style=\"width:100%;\">";
										echo $reviewRow['reviewComment'];
										echo "</pre></font>";
										echo "</div>";
									echo "</div>";
								}
								echo "</div>";
							}
						?>
						<script type="text/javascript">
							function AddReviewers() {
								var req_id = "<?php echo $rid ?>";
								var req_id = "<?php echo $rid ?>";
								console.log(req_id);
								console.log($("#reviewer").val());
								var posting = $.post("addReviewer.php", {
									uid: $("#reviewer").val(),
									rid: req_id
        						});

        						posting.done(function(data) {
        							console.log(data);
            						var check_result = $.parseJSON(data);
            						if (check_result.success == "1") {
                						location.reload();
            						} else {
                						alert("Add Reviewer Error Occur");
            						}
        						});
							}

							function DoDecision(decisionResult, reviewID) {
								var req_id = "<?php echo $rid ?>";
								if( !$("#review_comment").val() ) {
									alert("Please input your review opinion!!!");
								} else {
									var posting = $.post("doDecision.php", {
										reqReviewID: reviewID,
										comment: $("#review_comment").val(),
										decision: decisionResult,
            							requirement: req_id
        							});

        							posting.done(function(data) {
        								console.log(data);
            							var check_result = $.parseJSON(data);
            							if (check_result.SUCCESS == "1") {
                							location.reload();
            							} else {
                							alert("Decision Error Occur");
            							}
        							});
								}
							}

							function deleteReviewer(reviewID) {
								var getting = $.get( "deleteReviewer.php", { rvid: reviewID } );
								getting.done(function(data) {
									var check_result = $.parseJSON(data);
            						if (check_result.success == "1") {
                						location.reload();
            						} else {
                						alert("Delete Error Occur");
            						}
  								})
							}
						</script>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
