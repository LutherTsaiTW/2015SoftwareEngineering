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
            $tname=urlencode($row['name']);
            $tdes=urlencode($row['t_des']);
            $tdata=urlencode($row['data']);
            $tpid=urlencode($row['pid']);
            $towner_id=urlencode($row['owner_id']);
            $tresult=urlencode($row['result']);
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

         //取得與testcase有關的的所有 req
        $getReq = "SELECT * FROM req as r WHERE r.rid IN (SELECT rid FROM test_relation WHERE tid=".$tid.")";
        $result = $sqli->query($getReq);
        if (!$result )
        {
            echo "Error: there is an error when getReq";
            exit();
        }

        $i=0;
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $req['req'][$i]['rid']=urlencode($row['rid']);
            $req['req'][$i]['name']=urlencode($row['rname']);
            $i++;
            $req['count'] = $i;
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
                <a>Detail</a>
                </div>
                <div class="tag tagGap">
                <a>Input/Ouput</a>
                </div>
                <div class="tag tagGap">
                <a>Relationship</a>
                </div>
            </div>
            <div class="bottomBox"> 
                
            </div>
        </div>
    </body>
</html>