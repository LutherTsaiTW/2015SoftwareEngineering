<html style="height: 100%;">

<head>
    <title>ProjectDetail</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script type="text/javascript" src="../js/projectDetailWindowController.js"></script>
        <script type="text/javascript" src="../js/moment-with-locales.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/w3.css">
    <script type="text/javascript" src="../js/sessionCheck.js"></script>
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
  color: white;
    background-color: transparent;
    text-decoration: none;
}

a:visited {
    color: white;
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
    height: 300px;
    width: 400px;
    background-color: rgb(80, 80, 80);
    z-index: 999;
    visibility: hidden;
    border-radius: 15px;
}

.backButton {
    float: right;
    margin-top: 5px;
    margin-right: 5px;
    height: 30px;
    width: 20px;
    cursor: pointer;
    color: white;

}

.addButton {
    text-align: center;
    float: right;
    margin-right: 10px;
    margin-top: 5px;
    height: 30px;
    width: 60px;
    background-color:green;
}

.addZone {
    float: left;
    margin-top: 10px;
    margin-left: 10px;
    height: 250px;
    width: 150px;
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

.textBox
{
    display: inline;
    overflow:hidden;
}

.detailBoxFont
{
    font-size: 20;

}
</style>
<?php
    session_start();
    $session=$_SESSION['sessionid'];
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
    $p_id=$pid;
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
    
    // [BC] Get User Info
    $selectUser = "SELECT * FROM user_info WHERE user_session='" . $session . "'";
    $result = $sqli->query($selectUser);
    if(!$result)
    {
        $error = array('success' => 0, 'message' => 'there is an error when SELECT user_info in project detail');
        echo(json_encode($error));
        exit();
    }
    $user = $result->fetch_array(MYSQLI_ASSOC);

    // [BC]
    // 此API取得在PROJECT中的人員以及不在PROJECT中的人員
    // 總共有四個變數，別如下
    // $countMemberNotInProject -> 沒有在當前專案的人員數量
    // $memberNotInProject -> 沒有在當前專案的人員資料，[$i][0]是名稱，[$i][1]是公司名稱，[$i][2]是ID
    // $countMemberInProject -> 有在當前專案的人員數量
    // $memberInProject -> 有在當前專案的人員資料，[$i][0]是名稱，[$i][1]是公司名稱，[$i][2]是ID
    require_once 'getMember.php';

    // [BC] 分別給memberToAdd和memberToRemove

?>



<!--addmember畫面-->
<?php
    $memberToAdd=array();
    $memberToRemove=array();
?>


<body class="w3-container" style="height: 100%;background-color:rgb(61, 61, 61)">
    <div id="block"></div>
    <!--AddMemberWindow-->
    <div>
        <div id="addMemberWindow" class="addMemberWindow">
            <form id="addMemberForm"   action="addMember.php" method="POST" target="_iframe">
                <input hidden="hidden" name="pid" value="<?php echo $pid ?>">
                <select  multiple="yes"name="removeusers[]" id="removeZone"  class="addZone" >
                  <?php for($i=0;$i<$countMemberNotInProject;$i++) { echo '<option value= "'.$membersNotInProject[$i][2].'">'.$membersNotInProject[$i][0].'-'.$membersNotInProject[$i][1].'</option>'; }?>
                </select>
                <select hidden="hidden"  name="iniremoveusers[]" id="iniRemoveZone" multiple="yes"  >
                  <?php for($i=0;$i<$countMemberNotInProject;$i++) { echo '<option value= "'.$membersNotInProject[$i][2].'">'.$membersNotInProject[$i][0].'-'.$membersNotInProject[$i][1].'</option>'; }?>
                 </select>
                    <div style="float:left;width:40px;height:300px;margin-left:15px;">
                        <font style="margin-left:5px;float:left;font-size:16px; margin-top: 90px">Add</font>
                        <button type="reset" onclick="addMember()" style="float:left;width:40px;height:20px; margin-top: 5px;text-align: center;font-size:10px;background-color:rgb(100,100,100)"><b>></b></button>
                         <font style="margin-left:-10px;font-size:16px">Remove</font>
                        <button type="reset" onclick="removeMember()" style="float:left;width:40px;height:20px; margin-top: 5px ;text-align: center;font-size:10px;background-color:rgb(100,100,100)"><b><</b></button>
                    </div> 

                <select  multiple="yes" name="addusers[]" id="addZone" class="addZone" style="margin-left:20px">
                    <?php for($i=0;$i<$countMemberInProject;$i++) { echo '<option value= "'.$membersInProject[$i][2].'">'.$membersInProject[$i][0].'-'.$membersInProject[$i][1].'</option>'; }?>
                </select>
                 <select hidden="hidden" name="iniaddusers[]" id="iniAddZone"  multiple="yes"   >
                    <?php for($i=0;$i<$countMemberInProject;$i++) { echo '<option value= "'.$membersInProject[$i][2].'">'.$membersInProject[$i][0].'-'.$membersInProject[$i][1].'</option>'; }?>
                </select>

                    <input  type="reset" onclick ="showSuccessWindow()"  value="Ok" class="addButton" > 
                    <button type="reset" onclick="back();" class="addButton">Cancel</button>
            </form>
            <iframe id="_iframe" name="_iframe" style="display:none;"></iframe> 
        </div>
    </div>

    <div id="successWindow" class="successWindow">
        <p style="color:white;  font-weight: 600;font-size: 25">Add Member Success!</p>
         <button onclick="window.location.assign(window.location.href);;back()" style=";background-color:green" >OK</button>
    </div>
    <br/>
    <div style="z-index:1;">
        <div class="w3-row ">
            <div style="float:left">
               <a href="projectList.html"><img  src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" /></a>
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
        <div class="w3-row " style="Height:70px;color:white;text-align:center">
            <h1 id="projectName" style="background-color:grey;border-radius:5px">
               <?php 
                echo $projectdetail['p_name'] ;   
                ?> 
                <?php   
                if($projectdetail['p_owner']==$user['name'])
                {
                   echo "<a id=\"edit\" style=\"float:right;padding-right:10px\" href=\"editProjectView.php?pid=",$p_id,"\" >Edit </a>";
                }
             ?> 
            </h1>

        </div>
        <div class="w3-row " style="Height:rest">
            <div class="detailBox ">
                <div id="description" class="detail" style="height:800px;Width:450px;float: left; margin-right: 10px;">
                <?php
                    echo "<font style=\"font-size:24\"><b>Description:</b></font><br>",$projectdetail['p_des'];   
                ?>
                </div>
                <div style="float: left;Width:330px;">
                    <div id="detail" class="detail">
                        <?php
                          
                            echo "<font class=\"detailBoxFont\"> <b>End Time: </b></font> <font id=\"endTime\" class=\"detailBoxFont\" style=\"color:lightgreen\">",$projectdetail['p_end_time'],"</font><br/>";
                            echo "<font class=\"detailBoxFont\"> <b>Start Time: </b></font><font id=\"startTime\"  class=\"detailBoxFont\"style=\"color:lightgreen\">",$projectdetail['p_start_time'],"</font><br/>";
                            echo "<font id=\"days\" class=\"detailBoxFont\" style=\"float:right;color:grey;font-size:16\"></font><br/>";  
                            echo "<font class=\"detailBoxFont\"> <b>Company: </b>",$projectdetail['p_company'],"</font><br/>"; 
                            echo "<font class=\"detailBoxFont\"> <b>Owner: </b>",$projectdetail['p_owner'],"<br/>"; 
                            
                            echo "<font class=\"detailBoxFont\"> <b>Members: </b><br/><font class=\"textBox\" >  ";
                            $countBr=0;//整行寬度與換行次數的計算變數

                            for( $i = 0;  $i < $projectdetail['memberCount'];$i++)
                            {
                                
                                 if($i==$projectdetail['memberCount']-1||$countBr>=3)
                                 {
                                    if($projectdetail['memberCount']>4)
                                    echo $projectdetail[$i]," ...</font><br/>";
                                    else
                                    echo $projectdetail[$i]," </font><br/>";
                                    break; 
                                 }
                                else
                                 echo $projectdetail[$i], " ,";   

                                 $countBr++;
                                                                      
                            }            
                            echo "<font class=\"detailBoxFont\"> <b>Status: </b>";     
                            if($projectdetail['status']==0) echo "Close </font><br/>";
                            if($projectdetail['status']==1) echo "Open </font><br/>";
                            if($projectdetail['status']==2) echo "Terminated </font><br/>";
                        ?>
                        <script type="text/javascript">
                        </script>
                    </div>
                    <div class="listButton">
                        <a href="../Requirement/requirementListView.php?pid=<?php echo $pid;?>" >Requirement</a>
                    </div>
                    <div class="listButton">
                        <a "../Test/testListView.php">Test Case</a>
                    </div>

                    <div class="listButton">
                        <a "../RePoret/reportListView.php">Report</a>
                    </div>
                     <?php   
                        if($projectdetail['p_owner']==$user['name'])
                        {
                            echo "<div class=\"listButton\"> <a onclick=\"showAddMemberWindow()\">Edit Members</a> </div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="w3-row " style="Height:10%">
            <p></p>
        </div>
    </div>
</body>

<!--//計算預期天數-->
<script type="text/javascript">

        var startTime = moment("<?php echo $projectdetail['p_start_time']; ?>", "YYYY-MM-DD HH:mm:ss");
        var endTime = moment("<?php echo $projectdetail['p_end_time']; ?>", "YYYY-MM-DD HH:mm:ss");
        var diffDays = endTime.diff(startTime, 'days') + 1;
        document.getElementById("days").innerHTML ="Expect: "+  diffDays.toString() + " Days";

        
</script>

<script type="text/javascript">
    getMemberData();
</script>


</html>
