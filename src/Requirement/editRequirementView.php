<html style="height: 100%">
	<head>
	    <title>Edit Requirement</title>
	    <meta charset="utf-8" />
	    
	    <link rel="stylesheet" href="../css/w3.css">
	    <link rel="stylesheet" href="../css/dateRangePicker.css">
    	<link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">

	    <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
	    <script type="text/javascript" src="../js/moment-with-locales.js"></script>
	    <script type="text/javascript" src="../js/sessionCheck.js"></script>
	</head>

	<style>
	/*.fastAccount {
	    background-color: grey;
	    border-radius: 5px;
	    float: right;
	}

	.fastAccountBlock {
	    width: 10;
	    float: right;
	}*/
	</style>

	<script type="text/javascript">
		// [BC] 這個function是做處理，把edit頁面轉回到正確的頁面
		function doEdit(){
			var form = {
				'pid'			: $('input[id=pid]').val(),
				'name'			: $('input[id=name]').val(),
				'company'		: $('input[id=company]').val(),
				'startTime'		: $('input[id=startTime]').val(),
				'endTime'		: $('input[id=endTime]').val(),
				'des'			: $('textarea[id=des]').val(),
				'status'		: $('select[id=status]').val()
			}
			// [BC] 做POST
			var posting = $.post("editProject.php", form);
			// [BC] 完成POST之後，檢查response的內容
			posting.done(
				function(response){
					try {
			            var r = $.parseJSON(response);
			        } catch (err) {
			            alert("Parsing JSON Fail!: " + err.message + "\nJSON: " + response);
			            return;
			        }
			        if(r.success == 1){
			        	document.location.href = document.referrer;
			        } else {
			        	alert('edit project failed\nthe error message = ' + r.message);
			        }
				}
			);
		}

		// [BC] 顯示錯誤訊息，並回到上一頁
		function showError(var message){
			alert(message);
			document.location.href = document.referrer;
		}
	</script>
	<?php session_start();
		$rid = $_GET['rid'];
		
		// [BC] 取得DB連線
		require_once '../assist/DBConfig.php';
		$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
		$errno = mysqli_connect_errno();
		if($errno)
		{
			$user = array('success' => 0, 'message' => 'db_error');
			echo(json_encode($user));
			exit();
		}
		$sqli->query("SET NAMES 'UTF8'");
		
		// [BC] 取得requirement資訊
		$selectReq = "SELECT * FROM req WHERE rid=" . $rid . ";";
		$result = $sqli->query($selectReq);
		if (!$result)
		{
			echo "Error: there is an error when select requirement in editRequirementView.php";
			exit();
		}
		$requirement = $result->fetch_array(MYSQLI_ASSOC);

		// [BC] 取得user資訊
		$selectUser = "SELECT name, uid FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "'";
		$result = $sqli->query($selectUser);
		if (!$result)
		{	
			echo "Error: there is an error when select user in editRequirementView.php";
			exit();
		}
		$user = $result->fetch_array(MYSQLI_ASSOC);
	?>

	<body class="w3-container" style="height: 100%; background-color: rgb(61, 61, 61); color: white">
	    <div><br></div>
	    <div class="w3-row">
	        <div style="float:left">
	            <img src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" />
	        </div>
	        <div class="w3-container greyBox logoutLink">
                <a href="../logout.php">Logout</a>
            </div>
	        <div class="w3-container" style="float:right;font-size:18">
				<?php echo "Welcome, ",$user['name'] . "!"; ?>
			</div>
			<!--<script type="text/javascript" src="../js/getUser.js"></script>-->
	    </div>
	    <div class="w3-row" style="text-align: center">
			<h1 class="greyBox">Edit Requirement</h1>
		</div>
	    <div class="w3-row" align="center">
			<div class="w3-col blackBox" style="width: 450;height: 500">
		        <br>
		        <div class="w3-third formBox" algin="left">
		            <form action="javascript:doEdit()" method="POST" id="editProject">
		                <div class="formElement">
							<div id="name">Name:</div>
							<input id="requirementName" type="text" name="requirementName" class="textBoxStyle" placeholder="Enter Requirement's Name" onkeyup="javascript:checkRequirementName()" value="<?=$requirement['rname']?>"/>
						</div>
						<div class="formElement">
							<p>Type:</p>
							<SELECT name="typeName" id="typeName" class="selectBoxStyle">
								<option value="0" <?php if($requirement['rtype'] == 0)echo"selected";?>>non-Functional</option>
								<option value="1" <?php if($requirement['rtype'] == 1)echo"selected";?>>Functional</option>
							</SELECT>
						</div>
						<div class="formElement">
							<p>Priority:</p>
							<SELECT name="priority" id="priority" class="selectBoxStyle">
								<option value="0" <?php if($requirement['rpriority'] == 0)echo"selected";?>>Low</option>
								<option value="1" <?php if($requirement['rpriority'] == 1)echo"selected";?>>Medium</option>
								<option value="2" <?php if($requirement['rpriority'] == 2)echo"selected";?>>High</option>
							</SELECT>
						</div>
						<div class="formElement">
							<p>Description:</p>
							<textarea type="text" id="requirementDes" name="requirementDes" class="textBoxStyle" rows="9" required><?=$requirement['rdes'] ?>
							</textarea>
						</div>
						<div class="formElement"> <!-- Keep Space For Exit Button -->
							<button class="w3-teal formButton" style="float: left" type="submit" id="addButton">Add</button>
							<button type="button" class="w3-teal formButton" style="float: left" onclick="location.href = document.referrer;">exit</button>
						</div>
		            </form>
		        </div>
			</div>
	    </div>
	</body>
	<footer style="Height:rest;text-align:center">
	    <span style="text-decoration:underline;color:white">About Us</span>
	</footer>

</html>