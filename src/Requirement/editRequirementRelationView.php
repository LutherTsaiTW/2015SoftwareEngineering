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
		$selectReq = "SELECT rid, rname, rtype, rdes, rowner, rstatus, version FROM req WHERE rproject=$pid AND (rstatus=1 OR rstatus=2 OR rstatus=3)";
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
			<a href="requirementListView.php?pid=<?=$pid?>" class="backLink">back</a>
			<h1 class="greyBox">Edit Requirement Relationship</h1>
		</div>
		<!--主要畫面-->
		<div class="mainBox">
			<div class="blackBox leftBox">
				<div class="insideBox">
					<p style="font-size:22px;font-weight:bold;margin:0px">Requirement:</p>
					<select  name="requirements" id="requirements" multiple="yes" onchange="getData(value);">
					<?php
						foreach ($reqs as $req) {
							echo "<option value=" . $req['rid'] . ">" . $req['rname'] . "</option>";
						}
					?>
					</select>
				</div>
			</div>
			<div id="rightBlock" class="blackBox rightBOx" style="margin-left:15px">
				<div class="insideBox" style="float:left;">
					<input type="hidden" name="pid" id="pid" value="<?=$pid; ?>">
					<p style="font-size:22px;font-weight:bold;margin:0px;visibility:hidden">Blank Line</p>
					<select  multiple="yes" name="notInList" id="notInList">
						
					</select>
					<div style="float:left;width:110px;height:100%;text-align: center;">
                            <div style="float:left;height:150px;width:100%;">
                            </div>
                            <div style="float:left;height:40px;width:100%;">
                                <font style="font-size:20px" >Add</font>
                            </div>
                             <div style="float:left;height:30px;width:100%;">
                                 <button type="reset" onclick="doAdd()" style="margin-left:30px;float:left;width:50px;height:30px;text-align: center;font-size:20px;background-color:rgb(100,100,100)"><b>></b></button>
                            </div>
                            <div style="float:left;height:40px;width:100%;">
                                             <font style="font-size:20px">Remove</font>
                            </div>
                     
                            <div style="float:left;height:30px;width:100%;"> 
                                <button type="reset" onclick="doRemove()" style="margin-left:30px;float:left;width:50px;height:30px;text-align:center;font-size:20px;background-color:rgb(100,100,100)"><b></b></button>
                            </div>

                        </div> 

                    <select multiple="yes" name="inList" id="inList">
                    
                    </select>
                    
          
            </div>
            <div style="float:right;margin-right:30px;margin-top:5px">
                 <form action="editTestCaseRelation.php" method="POST" id="confirmForm" target="_iframe">
                    <input hidden="hidden" name="rid" id="rid" value="">
                    <select hidden="hidden" name="changed_rids[]" id="changed_rids"  multiple="yes"   >
                
                    </select>
                    <input  type="reset" onclick="confirm();" value="confirm" style="text-align:center;background-color:rgb(0,149,135)">
                 </form>
                  <iframe id="_iframe" name="_iframe" style="display:none;"></iframe> 
            </div>
                    
        </div>
    </div>
	</body>
</html>