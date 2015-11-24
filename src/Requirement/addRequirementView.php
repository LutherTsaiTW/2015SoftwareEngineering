<html style="height: 100%">
	<head>
    	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    	<link rel="stylesheet" href="../css/w3.css">
    	<link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
    	<title>Add Requirement</title>
	</head>
	<style type="text/css">
	</style>
	<?php
		$pid=$_GET['pid'];
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
		$data = $result->fetch_array();
		if($data['count'] != 1){
			exit('there is an error after SELECT USER by count is not one in addRequirementView.php');
		}

		// [BC] 設定$user資料
		$user['uid'] = $data['uid'];
		$user['name'] = $data['name'];
		$user['previlege'] = $data['previlege'];
	?>
	<script type="text/javascript">
		// [BC] 檢查requirement的名稱是不是空字串
		function validation(){
			var str = document.getElementById("requirementName").value;
			str = str.trim();
			document.getElementById("requirementName").value = str;
			if(str == null || str == ""){
				alert('需求名稱不可為空');
				return false;
			}
			return true;
		}
	</script>
	<body class="w3-container" style="height: 100%; background-color: rgb(61, 61, 61); color: white">
		<div><br></div>
		<div class="w3-row">
			<div style="float:left">
				<a href="../Project/projectList.html"><img src="../imgs/ptsIcon.png" alt="here is an icon" title="back to Requirement List Page" width="100", height="30"></a>
			</div>
			<div class="w3-container greyBox logoutLink">
                <a href="../logout.php">Logout</a>
            </div>
			<div class="w3-container" style="float:right;font-size:18">
				<?php echo "Welcome, ",$user['name'] . "!"; ?>
			</div>
		</div>
		<div class="w3-row" style="text-align: center">
			<h1 class="greyBox">Add Requirement</h1>
		</div>
		<div class="w3-row" align="center">
			<div class="w3-col blackBox" style="width: 450;height: 500">
				<br>
				<div class="w3-third formBox" algin="left">
					<form action="addRequirement.php" onsubmit="return validation()" method="POST">
						<input type="hidden" id="pid" name="pid" value="<?=$pid;?>" />
						<input type="hidden" id="uid" name="uid" value="<?=$user['uid'];?>" />
						<div class="formElement">
							<p >Name:</p>
							<input id="requirementName" type="text" name="requirementName" class="textBoxStyle" placeholder="Enter Requirement's Name" />
						</div>
						<div class="formElement">
							<p>Type:</p>
							<SELECT name="typeName" id="typeName" class="selectBoxStyle">
								<option value="0">non-Functional</option>
								<option value="1">Functional</option>
							</SELECT>
						</div>
						<div class="formElement">
							<p>Priority:</p>
							<SELECT name="priority" id="priority" class="selectBoxStyle">
								<option value="0">Low</option>
								<option value="1">Medium</option>
								<option value="1">High</option>
							</SELECT>
						</div>
						<div class="formElement">
							<p>Description:</p>
							<textarea type="text" id="requirementDes" name="requirementDes" class="textBoxStyle" rows="9"></textarea>
						</div>
						<div class="formElement"> <!-- Keep Space For Exit Button -->
							<button class="w3-teal formButton" style="float: left" type="submit">Add</button>
							<a href=<?="requirementListView.php?pid=$pid"?>>
								<button type="button" class="w3-teal formButton" style="float: left" onclick="Back()">exit</button>
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
