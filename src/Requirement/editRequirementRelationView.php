<html style="height: 100%">
	<head>
    	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    	<link rel="stylesheet" href="../css/w3.css">
    	<link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
    	<link rel="stylesheet" type="text/css" href="../css/relationPageElement.css">
    	<title>Edit Req Relation</title>
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    	<script type="text/javascript" src="../js/sessionCheck.js"></script>
    	<script type="text/javascript" src="../js/editRequirementRelationController.js"></script>
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
			$error = array('SUCCESS'=>0, 'Message'=>'there is an error when connecting to DB in editRequirementRelationView.php');
			echo json_encode($error);
			exit();
		}
		$sqli->query("SET NAMES UTF8");

		// [BC] 對使用者資訊進行query
		$selectUser = "SELECT COUNT(uid) as count, name, previlege, uid FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "';";
		$result = $sqli->query($selectUser) or die('there is an error when SELECT USER in editRequirementRelationView.php');
		$user = $result->fetch_array();
		if($user['count'] != 1){
			exit('there is an error after SELECT USER by count is not one in editRequirementRelationView.php');
		}

		// [BC] 取得該project的所有requirements
		$selectReq = "SELECT rid, rname, rtype, rdes, rowner, rstatus, version FROM req WHERE rproject=$pid AND (rstatus=1 OR rstatus=2 OR rstatus=3 OR rstatus=4)";
		$result = $sqli->query($selectReq) or die('there is an error when SELECT req in editRequirementRelationView.php');
		$reqs = array();
		while($data = $result->fetch_array(MYSQLI_ASSOC)){
			$reqs[$data['rid']]['rid'] = $data['rid'];
			$reqs[$data['rid']]['rname'] = $data['rname'];
			$reqs[$data['rid']]['rtype'] = $data['rtype'];
			$reqs[$data['rid']]['rdes'] = $data['rdes'];
			$reqs[$data['rid']]['rowner'] = $data['rowner'];
			$reqs[$data['rid']]['rstatus'] = $data['rstatus'];
			$reqs[$data['rid']]['version'] = $data['version'];
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

	<style type="text/css">
		select{
			background-color: white;
		}
	</style>

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
			<div class="w3-container WelcomeMessage" style="float:right">
				<?php echo "Welcome, ",$user['name'] . "!"; ?>
			</div>
		</div>
		<div class="w3-row" style="text-align: center">
			<a href="requirementListView.php?pid=<?=$pid?>" class="backLink">Back</a>
			<h1 class="greyBox">Edit Requirement Relationship</h1>
		</div>
		<!--主要畫面-->
		<div class="mainBox">
			<div class="blackBox leftBox">
				<div class="insideBox">
					<p style="font-size:22px;font-weight:bold;margin:0px">Requirement:</p>
					<select  name="requirements" id="requirements" size="2" onchange="getData(value);">
					<?php
						foreach ($reqs as $req) {
							echo "<option value=" . $req['rid'] . ">" . $req['rname'] . "</option>";
						}
					?>
					</select>
				</div>
			</div>
			<div id="rightBlock" class="blackBox rightBOx" style="margin-left:15px">
				<div class="insideBox" style="float:left;height:500px">
					<input type="hidden" name="pid" id="pid" value="<?=$pid; ?>">
					<p style="font-size:18px;margin:0px;" id="editReq" name="editReq">Requirement : </p>
					<select  multiple="yes" name="notInList" id="notInList">
						
					</select>
					<div style="float:left;width:110px;height:100%;text-align: center;">
						<div style="float: left;width: inherit;margin: 130px 0px 20px 0px;">
							<p style="font-size:20px;margin:0px;" >Add</p>
							<button type="reset" onclick="doAdd()" class="arrow">></button>
						</div>
						<div style="float: left;width: inherit;">
							<p style="font-size:20px;margin:0px;">Remove</p>
							<button type="reset" onclick="doRemove()" class="arrow"><</button>
						</div>
					</div>
					<select multiple="yes" name="inList" id="inList">
						
					</select>
				</div>
				<div style="float:right;margin-right:30px;">
					<form action="editRequirementRelation.php" method="POST" id="confirmForm" target="_iframe">
						<input hidden="hidden" name="rid" id="rid" value="">
						<select hidden="hidden" name="changed_rids[]" id="changed_rids"  multiple="yes" >

						</select>
						<input  type="button" onclick="confirm();" class="w3-teal" value="Confirm" style="font-size:20px;" id="confirmButton" name="confirmButton" disabled>
					</form>
					<iframe id="_iframe" name="_iframe" style="display:none;"></iframe>
				</div>
			</div>
		</div>
	</body>
</html>