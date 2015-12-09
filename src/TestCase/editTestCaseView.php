<html style="height: 100%">
	<head>
    	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    	<link rel="stylesheet" href="../css/w3.css">
    	<link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
    	<title>Edit Test Case</title>
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    	<script type="text/javascript" src="../js/addRequirement.js"></script>
    	<script type="text/javascript" src="../js/sessionCheck.js"></script>
	</head>
	<style type="text/css">
	</style>
	<?php
		$tid=$_GET['tid'];
	?>
	<?php
		// [BC] 取得當前使用者資料
		session_start();

		// [BC] 取得資料庫連線
		require_once '../assist/DBConfig.php';
		$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
		$errno = mysqli_connect_errno();
		if($errno)
		{
			$error = array('SUCCESS'=>0, 'Message'=>'there is an error when connecting to DB in addRequirementView.php');
			echo json_encode($error);
			exit();
		}
		$sqli->query("SET NAMES UTF8");

		// [BC] 對使用者資訊進行query
		$selectUser = "SELECT COUNT(uid) as count, name, previlege, uid FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "';";
		$result = $sqli->query($selectUser) or die('there is an error when SELECT USER in addRequirementView.php');
		$user = $result->fetch_array();
		if($user['count'] != 1){
			$response = array('success'=>0, 'message'=>'there is an error after SELECT USER by count isn\'t one in editTestCaseView.php');
			echo json_encode($response);
			exit();
		}

		// [BC] 取得Test Case資料
		$selectTestCase = "SELECT *, COUNT(tid) AS c FROM testcase WHERE tid=$tid";
		$result = $sqli->query($selectTestCase);
		$testcase = $result->fetch_array();
		if($testcase['c'] != 1){
			$response = array('success'=>0, 'message'=>'there is an error after SELECT TestCase by count isn\'t one in editTestCaseView.php');
			echo json_encode($response);
			exit();
		}
	?>
	<body class="w3-container" style="height: 100%; background-color: rgb(61, 61, 61); color: white">
		<div><br></div>
		<div class="w3-row">
			<div style="float:left">
				<a href="<?='../Requirement/requirementListView.php?pid=' . $pid;?>">
					<img src="../imgs/ptsIcon.png" alt="here is an icon" title="back to Requirement List Page" width="100", height="30">
				</a>
			</div>
			<div class="w3-container greyBox logoutLink">
                <a href="../logout.php" >Logout</a>
            </div>
			<div class="w3-container WelcomeMessage">
				<?php echo "Welcome, ",$user['name'] . "!"; ?>
			</div>
		</div>
		<div class="w3-row" style="text-align: center">
			<h1 class="greyBox">Edit Test Case</h1>
		</div>
		<div class="w3-row" align="center">
			<div class="w3-col blackBox" style="width: 900;height: 500">
				<div class="w3-third formBox" algin="left">
					<input type="hidden" id="tid" name="tid" value="<?=$pid;?>" />
					<div class="formElement">
						<div>Name:</div>
						<input id="name" type="text" name="name" class="textBoxStyle" placeholder="Enter Requirement's Name" onkeyup="javascript:checkRequirementName()" />
					</div>
					<div class="formElement">
						<div>Description:</div>
						<textarea type="text" id="des" name="des" class="textBoxStyle" style="height:300px" required></textarea>
					</div>
					<div class="formElement"> <!-- Keep Space For Exit Button -->
						<button class="w3-teal formButton" style="float: left" type="submit" id="addButton">Add</button>
						<a href=<?="Location: document.referrer;"?>>
							<button type="button" class="w3-teal formButton" style="float: left">Exit</button>
						</a>
					</div>
				</div>
				<div class="w3-third formBox" algin="left">
					<input type="hidden" id="tid" name="tid" value="<?=$pid;?>" />
					<div class="formElement">
						<div>Input Data:</div>
						<input id="name" type="text" name="name" class="textBoxStyle" onkeyup="javascript:checkRequirementName()" />
					</div>
					<div class="formElement">
						<div>Except Result:</div>
						<textarea type="text" id="des" name="des" class="textBoxStyle" style="height:300px" required></textarea>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>