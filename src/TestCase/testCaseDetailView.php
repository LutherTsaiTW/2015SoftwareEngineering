<html style="height: 100%">
    <head>
        <title>TestCase Detail</title>
        <meta charset="utf-8" />
        
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/dateRangePicker.css">
        <link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
        <link rel="stylesheet" type="text/css" href="../css/testCaseDetailElement.css">

        <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/moment-with-locales.js"></script>
        <script type="text/javascript" src="../js/sessionCheck.js"></script>
        <script type="text/javascript" src="../js/testCaseDetail.js"></script>
    </head>

    <?php 
        @$tid = $_GET['tid'];
        require_once 'getTestCaseDetailData.php';
    ?>


    
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
            <font class="title"><img src="../imgs/alert_36.png" style="visibility:<?php if($notConfirmed == 0) echo 'hidden' ?>"> <?php echo $tname?></font>
            <a href="testCaseListView.php?pid=<?=$tpid?>" class="backLink" style="float:left">Back</a>
        </div>

        <!--主要畫面-->
        <br>
        <div class="mainBox">
            <div class="upperBox">
                <div id="tag1" class="tag bgColor-grey">
                <a onclick="switchBox(1)">Detail</a>
                </div>
                <div id="tag2" class="tag tagGap">
                <a onclick="switchBox(2)">Input/Ouput</a>
                </div>
                <div id="tag3" class="tag tagGap">
                <a onclick="switchBox(3)">Relationship</a>
                </div>
            </div>
            <div class="bottom">
                <div class="bottomBox" id="detailBox"> 
                        <div id="detail"  class="insideBox-detail">
                            <font class="bold-22">Owner: </font><font class="font-22"><?php echo $towner;?></font><br>
                            <font class="bold-22">Description: </font><font class="font-22"><?php echo $tdes;?></font>
                        </div>
                </div>
                <div class="bottomBox hidden" id="dataBox"> 
                        <div id="inputBox"  class="insideBox-data">
                             <font class="bold-22">Input Data: </font><br><font class="font-22"><?php echo $tdata;?></font><br>  
                        </div>
                         <div id="outputBox"  class="insideBox-data">
                             <font class="bold-22">Output Data: </font><br><font class="font-22"><?php echo $tresult;?></font>
                        </div>
                </div>
                <div class="bottomBox hidden" id="relationBox" > 
			            <div id="notConfirmBox"  class="relationshipBox" <?php if($notConfirmedReq['count'] == 0) echo 'style="visibility:hidden;height:0px;padding:0;margin:0px"' ?>>
			                <div>
			                 <table class="font-22" id="notConfirmTable">
			                    <tr>
			                        <td ><font class="bold-22">Not Confirm: </font></td>
			                    </tr>
			                        <?php 
			                            if($notConfirmedReq['count']>0){
			                                if($user['previlege']==777 || $user['previlege']==999){
			                                    foreach ($notConfirmedReq['req'] as $t ) {
			                                    echo "<tr><td><a target='_block' href='../Requirement/requirementDetailView.php?rid=".$t['rid']."'>". $t['name'] ."</a></td> <td><button class='btn font-22' onclick='doConfirm(".$tid.",".$t['rid'].")'>Confirm</button></td> <td><button class='btn font-22' onclick='doRemove(".$tid.",".$t['rid'].")'>Remove</button></td></tr>";
			                                    }
			                                }
			                                else
			                                {
			                                    foreach ($notConfirmedReq['req'] as $t ) {
			                                    echo "<tr><td>". $t['name'] ."</td></tr>";
			                                    }
			                                }
			                            } 
			                        ?>                               
			                 </table>
			                 </div>                            
			            </div>
			             <div id="confirmedBox"  class="relationshipBox" <?php if($confirmedReq['count'] == 0) echo 'style="visibility:hidden;height:0px;padding:0;margin:0px"' ?>>
			                <table class="font-22" id="confirmedTable">
			                    <tr>
			                        <td ><font class="bold-22">Confirmed: </font></td>
			                    </tr>
			                        <?php 
			                            if($confirmedReq['count']>0){

			                                foreach ($confirmedReq['req'] as $t ) {
			                                    echo "<tr><td><a target='_block' href='../Requirement/requirementDetailView.php?rid=".$t['rid']."'>". $t['name'] ."</a></td><td> </td><td> </td></tr>";
			                                }
			                            } 
			                        ?>                               
			                 </table> 
			            </div>
			        </div>
                </div>
        </div>


    </body>
</html>