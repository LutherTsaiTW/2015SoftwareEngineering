<html style="height: 100%">
	<head>
    	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    	<link rel="stylesheet" href="../css/w3.css">
    	<link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
    	<title>Add Requirement</title>
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    	<script type="text/javascript" src="../js/sessionCheck.js"></script>
	</head>
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
		$user = $result->fetch_array();
		if($user['count'] != 1){
			exit('there is an error after SELECT USER by count is not one in addRequirementView.php');
		}
	?>
	<script type="text/javascript">
	function addRequirement(){
		// [BC] 取得form的資料
		var form = {
			'pid'				: $('input[id=pid]').val(),
			'uid'				: $('input[id=uid]').val(),
			'requirementName'	: $('input[id=requirementName]').val(),
			'typeName'			: $('SELECT[id=typeName]').val(),
			'priority'			: $('SELECT[id=priority]').val(),
			'requirementDes'	: $('textarea[id=requirementDes]').val()
		};

		// [BC] 做POST
		var posting = $.post("addRequirement.php", form);
		// [BC] 完成POST之後，檢查response的內容
		posting.done(
			function(response){
				try {
		            var r = $.parseJSON(response);
		        } catch (err) {
		            alert("Parsing JSON Fail!: " + err.message + "\nJSON: " + response);
		            return;
		        }
		        if(r.SUCCESS == 1){
		        	document.location.href = "requirementListView.php?pid=" + form.pid;
		        } else {
		        	alert('adding requirement failed\nthe error message = ' + r.MESSAGE);
		        }
			}
		);
	}
	</script>
	<body class="w3-container" style="height: 100%; background-color: rgb(61, 61, 61); color: white">
		<div><br></div>
		<div class="w3-row">
			<div style="float:left">
				<a href="<?='../Requirement/requirementListView.php?pid=' . $pid;?>">
					<img src="../imgs/ptsIcon.png" alt="here is an icon" title="back to Requirement List Page" width="100", height="30">
				</a>
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
				<div class="w3-third formBox" algin="left">
					<form id="requirementForm" action="javascript:addRequirement()" >
						<input type="hidden" id="pid" name="pid" value="<?=$pid;?>" />
						<input type="hidden" id="uid" name="uid" value="<?=$user['uid'];?>" />
						<div class="formElement">
							<div id="name">Name:</div>
							<input id="requirementName" type="text" name="requirementName" class="textBoxStyle" placeholder="Enter Requirement's Name" />
						</div>
						<div class="formElement">
							<div>Type:</div>
							<SELECT name="typeName" id="typeName" class="selectBoxStyle">
								<option value="0">non-Functional</option>
								<option value="1">Functional</option>
							</SELECT>
						</div>
						<div class="formElement">
							<div>Priority:</div>
							<SELECT name="priority" id="priority" class="selectBoxStyle">
								<option value="0">Low</option>
								<option value="1">Medium</option>
								<option value="2">High</option>
							</SELECT>
						</div>
						<div class="formElement">
							<div>Description:</div>
							<textarea type="text" id="requirementDes" name="requirementDes" class="textBoxStyle" rows="5" required></textarea>
						</div>
						<div class="formElement" style="font-size:16px"> <!-- Keep Space For Exit Button -->
							<button class="w3-teal formButton" style="float: left" type="submit" id="addButton">Add</button>
							<a href=<?="requirementListView.php?pid=$pid"?>>
								<button type="button" class="w3-teal formButton" style="float: left">Exit</button>
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>