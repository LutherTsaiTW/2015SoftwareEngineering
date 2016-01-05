<html style="height: 100%">
    <head>
        <title>Change Requirement</title>
        <meta charset="utf-8" />
        
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/dateRangePicker.css">
        <link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
        <link rel="stylesheet" type="text/css" href="../css/changeRequirementElement.css">

        <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/moment-with-locales.js"></script>
        <script type="text/javascript" src="../js/sessionCheck.js"></script>
    </head>

    <?php 
        @$rid = $_GET['rid'];
        require_once 'getRequirementDetailData.php';
    ?>

    <script type="text/javascript">

        function doChange () {
            //document.getElementById("mainForm").submit();
            //doExit();
            function addRequirement(){
		// [BC] 取得form的資料
		var form = document.getElementById("mainForm");

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
		        	doExit();
		        } else {
		        	alert('adding requirement failed\nthe error message = ' + r.MESSAGE);
		        }
			}
		);
        }

        function doExit(){
            document.getElementById("backLink").click();
        }

    </script>
    
    <body class="w3-container" style="height: 100%; background-color: rgb(61, 61, 61); color: white">

        <!--頁面上半部-->
       <br/>
      <div class="w3-row ">
            <div style="float:left">
               <img  src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" />
            </div>
            <div class="w3-container greyBox logoutLink">
                <a href="../logout.php">Logout</a>
            </div>
            <div class="w3-container WelcomeMessage" style="float:right">
                <?php echo "Welcome, ",$user['name'] . "!"; ?>
            </div>
        </div>

        <br/>
        <div class="w3-row greyBox " style="text-align: center">
            <font class="title">Requirement Change</font>
            <a id="backLink" href="requirementListView.php?pid=<?=$req['rproject'];?>" class="backLink" style="float:left">Back</a>
        </div>

        <!--主要畫面-->
        <br>
        <div class="mainBox">
        	<div style="height:500px">
            <!--左半部修改畫面-->
			<div class="leftBox">
                <iframe id="_iframe" name="_iframe" style="display:none;"></iframe>
				<form id="mainForm" action="changeRequirement.php" method="POST" target="_iframe">
					<input hidden="hidden" name="old_rid" value="<?php echo $rid?>">
					<table>
						<tr>
							<td class="align-left bold-20">Name: </td>
							<td class="align-left font-20"><?php echo $req['rname'];?></td>
						</tr>
						<tr>
							<td class="align-left bold-20">Type: </td>
							<td class="align-left font-20">
							<?php 
								switch ($req['rtype']) {
								case '0':
									echo "non-functional";
									break;
								case '1':
									echo "functional";
								break;
								}
							?>
							</td>
						</tr>
						<tr>
							<td class="align-left bold-20">Priority: </td>
							<td class="align-left font-20">
							<?php 
								switch ($req['rpriority']) {
								case '0':
									echo "Low";
									break;
								case '1':
									echo "Medium";
									break;
								case '2':
									echo "High";
								break;
								};
							?>
							</td>
						</tr>
						<tr>
							<td class="align-left bold-20">Change: </td>
							<td class="align-left font-20" >
								<input class="radioBtn" type="radio"  name="version_type" value="1" checked> Significant</input>
							</td>
						</tr>
						<tr>
							<td></td>
							<td class="align-left font-20">
								<input class="radioBtn" type="radio"  name="version_type" value="0" > Minor</input>
							</td>
						</tr>
					</table>
					<div class="align-left bold-20">Description: </div>
					<textarea id="des" class="desBox blackFont" name="des" rows="10"><?php echo $req['rdes'];?></textarea>
				</form>
			</div>
            <!-- 右半部 requirement 連結 -->
            <div class="rightBox">
                <div class="listBox">
                    <div class="affectedFont bold-20" style="color: rgb(200, 10, 10)">Affected Requirements:</div>
                    <div class="list font-20" style="background:inherit">
                    <?php
                        if(count($relReq)>0)
                        foreach ( $relReq['req'] as $va ) {
                            echo "<a target='_blank' class='font-20' href='requirementDetailView.php?rid=".$va['rid']."'>".$va['rname']."</a><br>";
                        }
                        else
                            echo "<font class='font-20'>None</font>";
                    ?>
                    </div>
                </div>
                <div class="listBox">
                    <div class="affectedFont bold-20" style="color: rgb(200, 10, 10)">Affected Test Cases:</div>
                    <div class="list font-20" style="background:inherit">
                    <?php
                        if(count($relTestCase)>0)
                        foreach ( $relTestCase['testcase'] as $va ) {
                            echo "<a target='_blank' class='font-20' href='../TestCase/testCaseDetailView.php?tid=".$va['tid']."'>".$va['name']."</a><br>";
                        }
                        else
                            echo "<font class='font-20'>None</font>";
                    ?>
                    </div>   
                </div>             
            </div>
            </div>
            <div style="margin-left:30px;width:800px;height:30px">
				<button class="Btn" onclick="doChange();" >Change</button>
				<button class="Btn" onclick="doExit();" >Exit</button>
			</div>
        </div>


    </body>
</html>
