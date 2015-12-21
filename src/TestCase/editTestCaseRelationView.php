<html style="height: 100%">
    <head>
        <title>Edit TestCase Relation</title>
        <meta charset="utf-8" />
        
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/dateRangePicker.css">
        <link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
        <link rel="stylesheet" type="text/css" href="../css/relationPageElement.css">

        <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/moment-with-locales.js"></script>
        <script type="text/javascript" src="../js/sessionCheck.js"></script>
        <script type="text/javascript" src="../js/editTestCaseRelationController.js"></script>
    </head>

    <style>
    a {
    color: lightgrey;
    cursor: pointer;
    }

    a:link {

        background-color: transparent;
        text-decoration: none;
    }

    a:visited {
        color: lightgrey;
        background-color: transparent;
        text-decoration: none;
    }

    a:hover {
        color: white;
        background-color: transparent;
        text-decoration: underline;
    }

    a:active {
        color: white;
        background-color: transparent;
        text-decoration: underline;
    }
    select {
        float: left;
        height: 470px;
        width: 240px;
    } 
    button{
        float: left;   
    }
    select {
        background-color: white;
    }
    </style>

    <?php
        @$pid = $_GET['pid'];  

    ?>
    <script type="text/javascript">
    //ajax取得testcase
    var xmlhttp = new XMLHttpRequest();
    var url = "getTestCase.php?pid="+<?php echo $pid;?>;       
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200 && !bool) {

            var arr = $.parseJSON(xmlhttp.responseText);
            var testcase = arr.testcases;
            testcase = Object.keys(testcase).map(function(k) {
                return testcase[k];
            });

                var i;
                 for (i = 0; i < testcase.length; i++) {
                    
                    var option=new Option(testcase[i].name,testcase[i].tid);
                    option.style.color='black';
                    document.getElementById("testcase").options.add(option);
            }
            bool=true;
         }
        }
    </script>
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
        <div class="w3-row" style="text-align: center">
            <a href="testCaseListView.php?pid=<?=$pid?>" class="backLink">Back</a>
            <h1 class="greyBox title">Edit Test Case Relationship</h1>
        </div>

        <!--主要畫面-->
        <div class="mainBox">
        <div class="leftBox blackBox">
        <font style="font-size:22px;margin-left:30px;margin-top:10px;float:left"><b>Test Case:</b></font>
            <select  name="testcase" id="testcase" size="2" style="margin-left:30px;margin-top:5px" onchange="getData(value);">
           
             </select>
        </div>
        <div id="rightBlock" class="rightBox blackBox" style="margin-left:15px">           
            <div style="float:left;height:470px;width:100%;margin-left:30px;margin-top:30px">

                    <input hidden="hidden" name="pid" id="pid" value="<?=$pid; ?>">
                    <select  multiple="yes" name="notInList" id="notInList"  >
                    
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
                                <button type="reset" onclick="doRemove()" style="margin-left:30px;float:left;width:50px;height:30px;text-align:center;font-size:20px;background-color:rgb(100,100,100)"><b><</b></button>
                            </div>

                        </div> 

                    <select  multiple="yes" name="inList" id="inList">
                        
                    </select>
                    
          
            </div>
            <div style="float:right;margin-right:30px;margin-top:5px">
                <form action="editTestCaseRelation.php" method="POST" id="confirmForm" target="_iframe">
                    <input hidden="hidden" name="tid" id="tid" value="">
                    <select hidden="hidden" name="changed_rids[]" id="changed_rids"  multiple="yes"   >
                
                    </select>
                    <input  type="button" onclick="confirm();" value="Confirm" style="text-align:center;background-color:rgb(0,149,135)">
                </form>
                <iframe id="_iframe" name="_iframe" style="display:none;"></iframe>
            </div>
                    
        </div>
    </div>
    </body>
</html>
