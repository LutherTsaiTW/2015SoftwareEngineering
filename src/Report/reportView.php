<!doctype html>
<html class="no-js" lang="">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Report</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/contenttoggle.css">
        <link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">
        <link rel="stylesheet" type="text/css" href="../css/reportViewElement.css">

            <script type="text/javascript" src="../js/sessionCheck.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
  </head>


    <?php session_start();
        @$pid = $_GET['pid'];
        // [KL] 取得DB連線
        require_once '../assist/DBConfig.php';
        $sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
        $errno = mysqli_connect_errno();
        if($errno)
        {
            $user = array('success' => 0, 'message' => 'db_error');
            echo($user);
            exit();
        }
        $sqli->query("SET NAMES 'UTF8'");
         //取得使用者資訊
        $selectUser = "SELECT COUNT(uid) as count, name, previlege, uid FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "';";
        $result = $sqli->query($selectUser) or die('there is an error when SELECT USER in reportView.php');
        $user = $result->fetch_array();
        if($user['count'] != 1){
            exit('there is an error after SELECT USER ');
        }

            // [KL] 取得專案資料
        $selectProject = "SELECT p_name FROM project WHERE p_id=" . $pid;
        $result = $sqli->query($selectProject);
        if(!$result)
        {
            $error = array('success' => 0, 'message' => 'there is an error when SELECT project by pid');
            echo(json_encode($error));
            exit();
        }
        $project = $result->fetch_array(MYSQLI_ASSOC);
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
            <font class="title"><?php echo $project['p_name']?></font>
            <a href="../Project/projectDetailView.php?pid=<?=$pid?>" class="backLink" style="float:left">Back</a>
             <a href="printReport.php?print=0&pid=<?=$pid?>" target="_blank"><img src="../imgs/print.png"  class="printAll"></a>

        </div>

        <!--主要畫面-->
        <br>
    <div class="main">
    <ul class="accordion">
      <li class="accordion__item js-contentToggle">
        <button class="accordion__trigger js-contentToggle__trigger greyBackground white30" type="button">Requirements</button>
        <div class="accordion__content is-hidden js-contentToggle__content">
        <?php require_once 'reqListTable.php';?>
            <div class="printBlock">
                <a href="printReport.php?print=1&pid=<?=$pid?> " target="_blank"><img src="../imgs/print.png"  class="print"></a>
            </div>
        </div>
      </li>
      <li class="accordion__item js-contentToggle">
        <button class="accordion__trigger js-contentToggle__trigger blackBackground white30" type="button">Requirement Relations</button>
        <div class="accordion__content is-hidden js-contentToggle__content">
        <?php require_once 'reqRelationTable.php';?>
            <div class="printBlock">
                <a href="printReport.php?print=2&pid=<?=$pid?> " target="_blank"><img src="../imgs/print.png"  class="print"></a>
            </div>
          </div>
      </li>
      <li class="accordion__item js-contentToggle">
        <button class="accordion__trigger js-contentToggle__trigger greyBackground white30" type="button">Test Case Requirement Relation</button>
        <div class="accordion__content is-hidden js-contentToggle__content">
        <?php require_once 'reqTestcaseTable.php';?>
            <div class="printBlock">
                <a href="printReport.php?print=3&pid=<?=$pid?> " target="_blank"><img src="../imgs/print.png"  class="print"></a>
            </div>
          </div>
      </li>
      <li class="accordion__item js-contentToggle">
        <button class="accordion__trigger js-contentToggle__trigger blackBackground white30" type="button">Requirements without Test Case</button>
        <div class="accordion__content is-hidden js-contentToggle__content">
        <?php require_once 'reqNoTestcaseTable.php';?>
            <div class="printBlock">
                <a href="printReport.php?print=4&pid=<?=$pid?> " target="_blank"><img src="../imgs/print.png"  class="print"></a>
            </div>
          </div>
      </li>
      <li class="accordion__item js-contentToggle">
        <button class="accordion__trigger js-contentToggle__trigger greyBackground white30" type="button">Test Cases in special condition</button>
        <div class="accordion__content is-hidden js-contentToggle__content">
        <?php require_once 'testcaseNoReqTable.php';?>
            <div class="printBlock">
                <a href="printReport.php?print=5&pid=<?=$pid?> " target="_blank"><img src="../imgs/print.png"  class="print"></a>
            </div>
         </div>
      </li>
    </ul>

<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="../js/jquery.contenttoggle.js"></script>
    <script src="../js/reportView.js"></script>
    </div>


  </body>
</html>
