<html style="height: 100%">
    <head>
        <title>Edit TestCase Relation</title>
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

   <?php session_start();    

        @$tid = $_GET['tid']; 
        // [KL] 取得DB連線
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
        
        // [KL] 取得testcase資訊
        $selectTestcase = "SELECT * FROM testcase WHERE tid=".$tid;
        $result = $sqli->query($selectTestcase);
        if (!$result )
        {
            echo "Error: there is an error when select testcase ";
            exit();
        }
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $tname = whitespaceHandler(urlencode($row['name']));
            $tdes = whitespaceHandler(urlencode($row['t_des']));
            $tdata = whitespaceHandler(urlencode($row['data']));
            $tresult = whitespaceHandler(urlencode($row['result']));
            $tpid=urlencode($row['pid']);
            $towner_id=urlencode($row['owner_id']);
        }

        //取得testcase owner資訊
        $getTestCaseOwner = "SELECT name FROM user_info WHERE uid=".$towner_id;
        $result = $sqli->query($getTestCaseOwner);
        if (!$result )
        {
            echo "Error: there is an error when getTestCaseOwner";
            exit();
        }
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $towner=whitespaceHandler(urlencode($row['name']));
        }

        //取得使用者資訊
        $selectUser = "SELECT COUNT(uid) as count, name, previlege, uid FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "';";
        $result = $sqli->query($selectUser) or die('there is an error when SELECT USER in editRequirementRelationView.php');
        $user = $result->fetch_array();
        if($user['count'] != 1){
            exit('there is an error after SELECT USER ');
        }

        //取得testcase 的所有 relation
        $getTestCaseRelation = "SELECT * FROM test_relation WHERE tid=".$tid;
        $result = $sqli->query($getTestCaseRelation);
        if (!$result )
        {
            echo "Error: there is an error when getTestCaseRelation";
            exit();
        }

        $i=0;
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $relation['relation'][$i]['rid']=urlencode($row['rid']);
            $relation['relation'][$i]['confirmed']=urlencode($row['confirmed']);
            $i++;
        }

        $notConfirmed=0;
        foreach ( $relation['relation'] as $ifConfirm) {
            if($ifConfirm['confirmed'] == 0)
                $notConfirmed++;
        }

         //取得與testcase有關的not confirmed req
        $getReqNotConfirmed = "SELECT * FROM req as r WHERE r.rid IN (SELECT rid FROM test_relation WHERE (tid=".$tid." AND confirmed = 0))";
        $result = $sqli->query($getReqNotConfirmed);
        if (!$result )
        {
            echo "Error: there is an error when getReqNotConfirmed";
            exit();
        }

        $i=0;
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $notConfirmedReq['req'][$i]['rid']=urlencode($row['rid']);
            $notConfirmedReq['req'][$i]['name']=whitespaceHandler(urlencode($row['rname']));
            $i++;
            $notConfirmedReq['count'] = $i;
        }   

         //取得與testcase有關的 confirmed req
        $getReqConfirmed = "SELECT * FROM req as r WHERE r.rid IN (SELECT rid FROM test_relation WHERE (tid=".$tid." AND confirmed = 1))";
        $result = $sqli->query($getReqConfirmed);
        if (!$result )
        {
            echo "Error: there is an error when getReqConfirmed";
            exit();
        }

        $i=0;
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $confirmedReq['req'][$i]['rid']=urlencode($row['rid']);
            $confirmedReq['req'][$i]['name']=whitespaceHandler(urlencode($row['rname']));
            $i++;
            $confirmedReq['count'] = $i;
        }  

        function whitespaceHandler($vstring)
        {   
            $REG = '/[%0D%0A]+/';
            $space = '/[+]/';
            $string = preg_replace($REG, '<br />', $vstring);
            $string = preg_replace($space, '&nbsp;', $string);
            return $string;
        }
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
            <a href="testCaseListView.php?pid=<?=$pid?>" class="backLink" style="float:left">back</a>
        </div>

        <!--主要畫面-->
        <br>
        <div class="mainBox">
            <div class="upperBox">
                <div class="tag">
                <a onclick="switchBox(1)">Detail</a>
                </div>
                <div class="tag tagGap">
                <a onclick="switchBox(2)">Input/Ouput</a>
                </div>
                <div class="tag tagGap">
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
                <div class="bottomBox hidden" id="relationBox"> 
                        <div id="notConfirmBox"  class="relationshipBox">
                            <div>
                             <table class="font-22" >
                                <tr>
                                    <td ><font class="bold-22">Not Confirm: </font></td>
                                </tr>
                                    <?php 
                                        if($notConfirmedReq['count']>0){
                                            foreach ($notConfirmedReq['req'] as $t ) {
                                                echo "<tr><td>". $t['name'] ."</td> <td><button class='btn' onclick'doConfirm(".$t['rid'].")'>Confirm</button></td> <td><button class='btn' onclick'doRemove(".$t['rid'].")'>Remove</button></td></tr>";
                                            }
                                        } 
                                    ?>                               
                             </table>
                             </div>                            
                        </div>
                         <div id="confirmedBox"  class="relationshipBox">
                                   <table class="font-22">
                                <tr>
                                    <td ><font class="bold-22">Confirmed: </font></td>
                                </tr>
                                    <?php 
                                        if($confirmedReq['count']>0){

                                            foreach ($confirmedReq['req'] as $t ) {
                                                echo "<tr><td>". $t['name'] ."</td><td> </td><td> </td></tr>";
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