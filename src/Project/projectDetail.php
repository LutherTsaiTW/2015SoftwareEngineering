<html style="height: 100%;">

<head>
    <title>ProjectDetail</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script type="text/javascript" src="../js/projectDetailWindowController.js"></script>
    <link rel="stylesheet" href="../css/w3.css">
</head>
<style>
#block {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    margin: =0;
    padding: =0;
    z-index: 998;
    visibility: hidden;
}

a {
    cursor: pointer;
}

a:link {
    color: lightgrey;
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

.fastAccount {
    background-color: grey;
    border-radius: 5px;
    float: right;
}

.fastAccountBlock {
    width: 10;
    float: right;
}

#footer {
    position: fixed;
    width: 100%;
    bottom: 0;
    z-index: 1;
    text-align: center;
}

.detailBox {
    height: 900px;
    width: 900px;
    margin: 0px auto;
    border-radius: 15px;
}

.listButton {
    background-color: grey;
    height: 75px;
    border-radius: 15px;
    float: top;
    margin-top: 5px;
    padding-left: 6px;
    text-align: center;
    font-size: 35;
    color: white;
    font-weight: 600;
    line-height: 75px;
}

.detail {
    background-color: rgb(40, 40, 40);
    height: 400px;
    border-radius: 15px;
    float: top;
    padding-left: 15px;
    padding-top: 10px;
    font-size: 25;
    color: white;
}

.addMemberWindow {
    position: fixed;
    top: 30%;
    left: 40%;
    margin: 0px auto;
    height: 400px;
    width: 300px;
    background-color: rgb(80, 80, 80);
    z-index: 999;
    visibility: hidden;
    border-radius: 15px;
}

.backButton {
    float: right;
    margin-top: 10px;
    margin-right: 10px;
    height: 30px;
    width: 20px;
    cursor: pointer;
}

.addButton {
    float: right;
    margin-right: 5px;
    margin-top: 300px;
    height: 30px;
    width: 60px;
    background-color:green;
}

.memberMutiSelect {
    float: left;
    margin-top: 10px;
    margin-left: 10px;
    height: 380px;
    width: 200px;
}

.successWindow {
    text-align: center;
    position: fixed;
    top: 30%;
    left: 40%;
    margin: 0px auto;
    height: 150px;
    width: 400px;
    background-color: rgb(80, 80, 80);
    z-index: 999;
    visibility: hidden;
    border-radius: 15px;
}
</style>
<?php
    // [BC]
    // [BC]
    // 此函式是藉由使用者ID取得使用者名稱的
    // 參數如下
    // $uid -> 使用者ID
    // $Connect -> 目前連接的資料庫
    function GetUser($uid, $connection){
        $select = "SELECT name FROM user_info WHERE uid=" . $uid;
        $result = $connection->query($select);
        if(!$result)
        {
            $error = array('success' => 0, 'message' => 'there is an error when SELECT user name');
            echo(json_encode($error));
            exit();
        }
        $data = $result->fetch_array(MYSQLI_ASSOC);
        return $data['name'];
    }

    // [BC]
    // 此API是用來取得對應專案的專案資料
    // 取得所有的專案資料，包含專案名稱、專案描述、專案公司、專案擁有者、
    // 專案開始時間、專案結束時間、專案狀態、以及專案成員
    // 該傳入的GET參數如下
    // pid -> 專案id
    // 使用GET取得參數，另外若成功，SUCCESS=1，並會回傳所有的資料到一個變數中
    // 反之，SUCCESS=0，然後顯示錯誤訊息

    // [BC] 取得GET的參數，也就是pid
    $pid = $_GET['pid'];

    // [BC] include一個可以呼叫函式，取得資料庫連線的的php
    include_once '../assist/getDataBaseConnection.php';
    $sqli = getDBConnection();

    // [BC] 取得專案資料
    $selectProject = "SELECT * FROM project WHERE p_id=" . $pid;
    $result = $sqli->query($selectProject);
    if(!$result)
    {
        $error = array('success' => 0, 'message' => 'there is an error when SELECT project by pid');
        echo(json_encode($error));
        exit();
    }
    $project = $result->fetch_array(MYSQLI_ASSOC);

    // [BC] 取得參與專案人員
    $selectMember = "SELECT * FROM project_team WHERE project_id=" . $pid;
    $result = $sqli->query($selectMember);
    if(!$result)
    {
        $error = array('success' => 0, 'message' => 'there is an error when SELECT project_team');
        echo(json_encode($error));
        exit();
    }

    $member = array();
    $memberCount = 0;
    while ($data = $result->fetch_assoc()) 
    {
        $memberCount++;
        array_push($member, getUser($data['user_id'], $sqli));
    }
    $member['memberCount'] = $memberCount;

    // [BC] Merge兩個ARRAY
    $projectdetail = array_merge($project, $member);

    // [BC] 把Owner從ID轉成STRING
    $projectdetail['p_owner'] = GetUser($projectdetail['p_owner'], $sqli);
    require_once('../assist/getUserInfo.php');
?>

<body class="w3-container" style="height: 100%;background-color:rgb(61, 61, 61)">
    <div id="block"></div>
    <div>
        <div id="addMemberWindow" class="addMemberWindow">
            <p onclick="back()" class="backButton"> x </p>
            <form action="addMember.php" method="POST"  target="id_iframe">
            <select multiple id="memberMutiSelect" class="memberMutiSelect">
            <?php
                require_once('getMember.php');
                for($j =0;$j<$countMember;$j++)
                {
                    echo" <option value=\"" ,$members[$j][2], "\">",$members[$j][0] ,"</option>";
                }
            ?>
            </select>
            <input type="submit" onclick="showSuccessWindow()" name="submit" value="Add" " class="addButton"> 
            </form>
            <iframe id="id_iframe" name="id_iframe" style="display:none;"></iframe> 
        </div>
    </div>
    <div id="successWindow" class="successWindow">
        <p style="color:white;  font-weight: 600;font-size: 25">Add Member Success!</p>
         <button onclick="back()" style=";background-color:green" >OK</button>
    </div>
    <br/>
    <div style="z-index:1;">
        <div class="w3-row ">
            <div style="float:left">
                <img src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" />
            </div>
            <div class="w3-container fastAccount">
                <a href="../logout.php">Logout</a>
            </div>
            <div class="fastAccountBlock">
                <p/>
            </div>
            <div class="w3-container" id="userName" style="float:right;color:white;font-size:18">
               <?php echo "Welcome! ",$user['name']; ?>
            </div>
        </div>
        <div class="w3-row " style="Height:15%;color:white;text-align:center">
            <h1 id="projectName" style="background-color:grey;border-radius:5px">
                    ProjectName <a id="edit" style="float:right;padding-right:10px" <?php echo "href=\"editProjectView.php?pid=",$pid,"\""?> >Edit</a>
            </h1>
        </div>
        <div class="w3-row " style="Height:rest">
            <div class="detailBox ">
                <div id="description" class="detail" style="height:800px;Width:450px;float: left; margin-right: 10px;">
                <?php
                    echo $projectdetail['p_des'];   
                ?>
                </div>
                <div style="float: left;Width:330px;">
                    <div id="detail" class="detail">
                        <?php
                            echo "Company:",$projectdetail['p_company'],"<br/>";  
                            echo "Owner:",$projectdetail['p_owner'],"<br/>"; 
                            echo "Start Time:<br/><font style=\"color:green\">",$projectdetail['p_start_time'],"</font><br/>";
                            echo "End Time:</br><font style=\"color:green\">",$projectdetail['p_end_time'],"</font><br/>"; 
                            echo "Status:";
                            if($projectdetail['status']==0) echo "Close <br/>";
                            if($projectdetail['status']==1) echo "Open <br/>";
                            if($projectdetail['status']==2) echo "Terminated <br/>";
                            if($projectdetail['status']==3) echo "Delete <br/>";
                            echo "Member:<br/>";
                            for( $i = 0;  $i < $projectdetail['memberCount'];$i++)
                            {
                                if($i==$projectdetail['memberCount']-1)
                                    echo $projectdetail[$i];
                                else
                                 echo $projectdetail[$i],",";   
                            }                 
                        ?>
                    </div>
                    <div class="listButton">
                        <a>Request</a>
                    </div>
                    <div class="listButton">
                        <a>Test Case</a>
                    </div>
                    <div class="listButton">
                        <a>Review</a>
                    </div>
                    <div class="listButton">
                        <a>Report</a>
                    </div>
                    <div class="listButton">
                        <a onclick="showAddMemberWindow()">Add Member</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w3-row " style="Height:10%">
            <p></p>
        </div>
    </div>
</body>

</html>
