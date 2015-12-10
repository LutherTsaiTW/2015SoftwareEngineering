<html style="height: 100%">
	<?php
		if(array_key_exists('tid', $_GET)){
			$input_tid = $_GET['tid'];
		} else{
			$input_tid = -1;
		}
		
		if(array_key_exists('pid', $_GET)){
			$pid = $_GET['pid'];
		} else{
			$pid = -1;
		}
		if($input_tid == -1){
			$title = "Add Test Case";
			$button = "Add";
		}else {
			$title = "Edit Test Case";
			$button = "Edit";
		}
	?>
	<head>
    	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    	<link rel="stylesheet" href="../css/w3.css">
    	<link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
    	<title><?=$title;?></title>
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    	<script type="text/javascript" src="../js/sessionCheck.js"></script>
	</head>
	</style>
	
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
		if($input_tid > -1){
			$selectTestCase = "SELECT *, COUNT(tid) AS c FROM testcase WHERE tid=$input_tid";
			$result = $sqli->query($selectTestCase);
			$testcase = $result->fetch_array();
			if($testcase['c'] != 1){
				$response = array('success'=>0, 'message'=>'there is an error after SELECT TestCase by count isn\'t one in editTestCaseView.php');
				echo json_encode($response);
				exit();
			}
		}else {
			$testcase = array('c'=>'', 'name'=>'', 't_des'=>'', 'data'=>'', 'result'=>'');
		}
	?>
	<script type="text/javascript">
		// [BC] 這個function是做處理，把頁面轉回到正確的頁面
		function doEdit(){
			var form = {
				<?php if($input_tid != -1){?>
					'tid'			: $('input[id=tid]').val(),
				<?php } else{?>
					'pid'			: $('input[id=pid]').val(),
				<?php }?>
				'name'			: $('input[id=name]').val().trim(),
				'des'			: $('textarea[id=des]').val().trim(),
				'data'			: $('textarea[id=data]').val().trim(),
				'result'		: $('textarea[id=result]').val().trim()
			};
			
			// [BC] 檢查每個資料是不是空的，皆不可為空
			if(form.name == ""){
				alert("test case's name can not be empty!");
				return ;
			}
			if(form.des == ""){
				alert("test case's des can not be empty!");
				return ;
			}
			if(form.data == ""){
				alert("test case's data can not be empty!");
				return ;
			}
			if(form.result == ""){
				alert("test case's result can not be empty!");
				return ;
			}

			// [BC] POST
			if(<?=$input_tid?> != -1){
				var posting = $.post("editTestCase.php", form);
			}else {
				var posting = $.post("addTestCase.php", form);
			}
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
			<h1 class="greyBox"><?=$title?></h1>
		</div>
		<div class="w3-row" align="center">
			<div class="w3-col blackBox" style="width: 900;height: 500">
				<div class="w3-third formBox" algin="left">
					<input type="hidden" id="tid" name="tid" value="<?=$testcase['tid'];?>" />
					<input type="hidden" id="pid" name="pid" value="<?=$pid;?>" />
					<div class="formElement">
						<div>Name:</div>
						<input id="name" type="text" name="name" class="textBoxStyle" placeholder="Enter Requirement's Name" value="<?=$testcase['name']?>" />
					</div>
					<div class="formElement">
						<div>Description:</div>
						<textarea type="text" id="des" name="des" class="textBoxStyle" style="height:300px" required ><?=$testcase['t_des']?></textarea>
					</div>
					<div class="formElement" style="font-size:16px;margin-top: 5px;"> <!-- Keep Space For Exit Button -->
						<button class="w3-teal formButton" style="float: left" type="submit" id="addButton" onclick="javascript:doEdit()"><?=$button?></button>
						<button type="button" class="w3-teal formButton" style="float: left" onclick="location.href = document.referrer;">Exit</button>
					</div>
				</div>
				<div class="w3-third formBox" algin="left">
					<div class="formElement">
						<div>Input Data:</div>
						<textarea id="data" type="text" name="data" class="textBoxStyle" style="height:180px" /><?=$testcase['data']?></textarea>
					</div>
					<div class="formElement">
						<div>Except Result:</div>
						<textarea type="text" id="result" name="result" class="textBoxStyle" style="height:180px" required><?=$testcase['result']?></textarea>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>