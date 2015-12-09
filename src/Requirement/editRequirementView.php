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
	<script type="text/javascript">
		// [BC] 這個function是做處理，做edit，然後把頁面轉回到正確的頁面
		function doEditReq(){
			var form = {
				'id'			: $('input[id=id]').val(),
				'name'			: $('input[id=name]').val(),
				'description'	: $('textarea[id=description]').val(),
				'type'			: $('select[id=type]').val(),
				'priority'		: $('select[id=priority]').val()
			}
			// [BC] 做POST
			var posting = $.post("editRequirement.php", form);
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
			        	alert('edit requirement failed\nthe error message = ' + r.message);
			        }
				}
			);
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
		        <div class="w3-third formBox" algin="left">
		            <form action="javascript:doEditReq()" method="POST" id="editProject">
		            	<div>
		            		<input id="id" type="hidden" name="id" value="<?=$requirement['rid']?>">
		            	</div>
		                <div class="formElement">
							<div id="name">Name:</div>
							<input id="name" type="text" name="name" class="textBoxStyle" placeholder="Enter Requirement's Name" value="<?=$requirement['rname']?>"/>
						</div>
						<div class="formElement">
							<div>Type:</div>
							<SELECT name="type" id="type" class="selectBoxStyle">
								<option value="0" <?php if($requirement['rtype'] == 0)echo"selected";?>>non-Functional</option>
								<option value="1" <?php if($requirement['rtype'] == 1)echo"selected";?>>Functional</option>
							</SELECT>
						</div>
						<div class="formElement">
							<div>Priority:</div>
							<SELECT name="priority" id="priority" class="selectBoxStyle">
								<option value="0" <?php if($requirement['rpriority'] == 0)echo"selected";?>>Low</option>
								<option value="1" <?php if($requirement['rpriority'] == 1)echo"selected";?>>Medium</option>
								<option value="2" <?php if($requirement['rpriority'] == 2)echo"selected";?>>High</option>
							</SELECT>
						</div>
						<div class="formElement">
							<div>Description:</div>
							<textarea type="text" id="description" name="description" class="textBoxStyle" rows="5" required><?=$requirement['rdes'] ?></textarea>
						</div>
						<div class="formElement" style="font-size:16px"> <!-- Keep Space For Exit Button -->
							<button class="w3-teal formButton" style="float: left" type="submit" id="editButton">Edit</button>
							<button type="button" class="w3-teal formButton" style="float: left" onclick="location.href = document.referrer;">Exit</button>
						</div>
		            </form>
		        </div>
			</div>
	    </div>
	</body>
</html>