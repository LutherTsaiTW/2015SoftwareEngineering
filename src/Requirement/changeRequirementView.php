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
            document.getElementById("mainForm").submit();
            doExit();
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
			<div class="leftBox">
				<form id="mainForm" action="changeRequirement.php" method="POST">
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
					<textarea class="desBox blackFont" name="des" rows="10"><?php echo $req['rdes'];?></textarea>
				</form>
			</div>
            <div class="rightBox">
                <div class="listBox">
                    <div class="affectedFont bold-20">Affected Requirements:</div>
                    <div class="list font-20">
                    <?php
                        if(count($relReq)>0)
                        foreach ( $relReq['req'] as $va ) {
                            echo "<a class='blackFont' href='requirementDetailView.php?rid=".$va['rid']."'>".$va['rname']."</a><br>";
                        }
                        else
                            echo "None";
                    ?>
                    </div>
                </div>
                <div class="listBox">
                    <div class="affectedFont bold-20">Affected Test Cases:</div>
                    <div class="list font-20">
                    <?php
                        if(count($relTestCase)>0)
                        foreach ( $relTestCase['testcase'] as $va ) {
                            echo "<a class='blackFont' href='../TestCase/testCaseDetailView.php?tid=".$va['tid']."'>".$va['name']."</a><br>";
                        }
                        else
                            echo "None";
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
