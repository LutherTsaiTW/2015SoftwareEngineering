<html style="height: 100%;">

<head>
    <title>ProjectDetail</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="../css/html5tooltips.css" />
	<link rel="stylesheet" type="text/css" href="../css/html5tooltips.animation.css" />
    <script type="text/javascript" src="../js/projectDetailWindowController.js"></script>
    <script type="text/javascript" src="../js/moment-with-locales.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/w3.css">
	<script type="text/javascript" src="../js/html5tooltips.js"></script>
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
    width:950px;
    margin: 0px auto;
    border-radius: 15px;
}

.listButton {
    background-color: grey;
    height: 65px;
    border-radius: 15px;
    float: top;
    margin-top: 5px;
    padding-left: 6px;
    text-align: center;
    font-size: 28px;
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
    font-size: 20px;
    color: white;
}

.detail td {

    font-size: 20px;
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

.html5tooltip-box
{
	color: black;
	font-size: 20px;
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
    // [BC] 取得資料庫連線的的php
    require_once '../assist/DBConfig.php';
    $sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
    $errno = mysqli_connect_errno();
    if($errno)
    {
        $user = array('success' => 0, 'message' => 'there is an error when getting DB connection in projectDetailView.php');
        echo(json_encode($user));
        exit();
    }
    $sqli->query("SET NAMES 'UTF8'");

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
    
    // [BC] Get User Info 中的uid和name
    $selectUser = "SELECT name, uid, company FROM user_info WHERE user_session='" . $session . "'";
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
                <select  multiple="yes"name="removeusers[]" id="removeZone"  class="addZone" style="background-color:white">
                  <?php for($i=0;$i<$countMemberNotInProject;$i++) { echo '<option value= "'.$membersNotInProject[$i][2].'">'.$membersNotInProject[$i][0].'-'.$membersNotInProject[$i][1].'</option>'; }?>
                </select>
                <select hidden="hidden"  name="iniremoveusers[]" id="iniRemoveZone" multiple="yes">
                  <?php for($i=0;$i<$countMemberNotInProject;$i++) { echo '<option value= "'.$membersNotInProject[$i][2].'">'.$membersNotInProject[$i][0].'-'.$membersNotInProject[$i][1].'</option>'; }?>
                 </select>
                    <div style="float:left;width:40px;height:300px;margin-left:15px;">
                        <font style="margin-left:5px;float:left;font-size:16px; margin-top: 90px;color:white">Add</font>
                        <button type="reset" onclick="addMember()" style="float:left;width:40px;height:20px; margin-top: 5px;text-align: center;font-size:10px;background-color: #282828 !important;color:white"><b>></b></button>
                        <font style="margin-left:-10px;font-size:16px;color:white">Remove</font>
                        <button type="reset" onclick="removeMember()" style="float:left;width:40px;height:20px; margin-top: 5px ;text-align: center;font-size:10px;background-color: #282828 !important;color:white"><b><</b></button>
                    </div> 

                <select  multiple="yes" name="addusers[]" id="addZone" class="addZone" style="margin-left:20px;background-color:white">
                    <?php for($i=0;$i<$countMemberInProject;$i++) { echo '<option value= "'.$membersInProject[$i][2].'">'.$membersInProject[$i][0].'-'.$membersInProject[$i][1].'</option>'; }?>
                </select>
                 <select hidden="hidden" name="iniaddusers[]" id="iniAddZone"  multiple="yes"   >
                    <?php for($i=0;$i<$countMemberInProject;$i++) { echo '<option value= "'.$membersInProject[$i][2].'">'.$membersInProject[$i][0].'-'.$membersInProject[$i][1].'</option>'; }?>
                </select>

                    <input type="button" onclick ="showSuccessWindow()"  value="Ok" class="addButton" style="color:white;background-color:#009688!important;width:auto"> 
                    <button type="reset" onclick="back();" class="addButton" style="color:white;background-color:#009688!important;width:auto">Cancel</button>
            </form>
            <iframe id="_iframe" name="_iframe" style="display:none;"></iframe> 
        </div>
    </div>

    <div id="successWindow" class="successWindow">
        <p style="color:white;  font-weight: 600;font-size: 25">Edit Member Success!</p>
         <button onclick="window.location.assign(window.location.href);;back()" style="background-color: #009688 !important;color:white" >OK</button>
    </div>
    <br/>
    <div style="z-index:1">
        <div class="w3-row ">
            <div style="float:left">
               <img  src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" />
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

        <br/>
        <div class="w3-row " style="vertical-align:center;Height:50px;color:white;text-align:center;background-color:grey;border-radius:5px">
                     <font id="projectName" style="font-size:36px">                   
                    <?php 
                        echo $projectdetail['p_name'] ;   
                        ?> 
                        <?php   
                        
                    ?> 
                    </font>
                    <a href="projectList.html" style="font-size:20px;float:left;margin-left:5px;margin-top:10px">Back</a>
                    <?php
                        if($projectdetail['p_owner']==$user['name'])
                        {
                            echo "<a id=\"edit\" style=\"font-size:20px;float:right;padding-right:10px;margin-top:10px\" href=\"editProjectView.php?pid=",$p_id,"\" >Edit </a>";
                        }
                    ?>
        </div>

        <!--右側detail-->
        <br/>
        <div class="detailBox ">
            <div >
                <div id="description" class="detail" style="height:550px;Width:600px;float: left; margin-right: 10px;">
                <?php
                    echo "<font style=\"font-size:24\"><b>Description:</b></font><br>",$projectdetail['p_des'];   
                ?>
                </div>
                <div style="float:left;Width:300px;">
                    <div id="detail" class="detail" style="height:270px">
                        <table>
                            <tr >
                                <td>
                                <?php
                                    $stime = explode(" ", $projectdetail['p_start_time'])[0]; 
									$stime = str_replace("-", "/", $stime);
                                    echo "<font class=\"detailBoxFont\"> <b>Start Time: </b></font></td><td><font id=\"startTime\"  class=\"detailBoxFont\"style=\"color:white;float:left;margin-right:15px\">",$stime,"</font></td></tr>";
								
									$etime = explode(" ", $projectdetail['p_end_time'])[0];
									$etime = str_replace("-", "/", $etime);
                                    echo "<tr><td><font class=\"detailBoxFont\"> <b>End Time: </b></font></td><td><font id=\"endTime\" class=\"detailBoxFont\" style=\"color:white;float:left;margin-right:15px\">",$etime,"</font></td></tr>";
                                    
                                    echo "<tr><td></td><td><font id=\"days\" class=\"detailBoxFont\" style=\"float:left;color:grey;font-size:16;margin-right:15px\"></font></td></tr>";  

                                    $Ow=mb_strimwidth  ( $projectdetail['p_owner']  ,0 ,15, "...", "UTF-8" );
                                    echo "<tr><td><font class=\"detailBoxFont\"> <b>Owner: </b></font></td><td><font  class=\"detailBoxFont\" style=\"float:left;margin-right:15px\">",$Ow,"</font></td></tr>";                             
                                    echo "<tr><td><font class=\"detailBoxFont\"> <b>Company: </b></font></td><td><font  class=\"detailBoxFont\" style=\"float:left;margin-right:15px\">",$projectdetail['p_company'],"</font></td></tr>"; 
                                ?>
                                <?php
                                    echo "<tr><td style=\"word-wrap:break-word;width:290px;vertical-align: baseline\" class=\"detailBoxFont\" ><font>";
                                    echo "<b>Members: </b></font></td>";
                                    $mem = "";
                                    for( $i = 0;  $i < $projectdetail['memberCount'];$i++)
                                    {
                                        if($i==0) $mem=$mem.$projectdetail[$i];
                                        else $mem=$mem.", ".$projectdetail[$i];     

                                    }
                                    $longmem = $mem;  
                                    $mem = mb_strimwidth  ( $mem  ,0 ,45, "...", "UTF-8" );
                                    echo "<td style=\"word-wrap:break-word;width:290px\" class=\"detailBoxFont\" ><font data-tooltip=\"". $longmem ."\" data-tooltip-stickto=\"left\" data-tooltip-color=\"stone\" data-tooltip-animate-function=\"scalein\">".$mem."</font></td></tr>";
                                ?>
                                <?php
                                    echo "<tr><td><font class=\"detailBoxFont\"> <b>Status: </b></font></td><td><font  class=\"detailBoxFont\" style=\"float:left;margin-right:15px\">";     
                                    if($projectdetail['status']==0) echo "Close </font></td></tr>";
                                    if($projectdetail['status']==1) echo "Open </font></td></tr>";
                                    if($projectdetail['status']==2) echo "Terminated </font></td></tr>";
                                ?>
                            </table>
                                </div>
                                <div class="listButton">
                                    <a href="../Requirement/requirementListView.php?pid=<?php echo $pid;?>" >Requirement</a>
                                </div>
                                <div class="listButton">

                                    <a href="../TestCase/testCaseListView.php?pid=<?php echo $pid;?>">Test Case</a>

                                </div>

                                <div class="listButton">
                                    <a href="../RePoret/reportListView.php">Report</a>
                                </div>
                                 <?php   
                                    if($projectdetail['p_owner']==$user['name'])
                                    {
                                        echo "<div class=\"listButton\"> <a  onclick=\"showAddMemberWindow()\">Edit Members</a> </div>";
                                    }
                                ?>

                </div>
            </div>
        </div>

    </div>
</body>

<!--//計算預期天數-->
<script type="text/javascript">

        var startTime = moment("<?php echo $projectdetail['p_start_time']; ?>", "YYYY-MM-DD HH:mm:ss");
        var endTime = moment("<?php echo $projectdetail['p_end_time']; ?>", "YYYY-MM-DD HH:mm:ss");
        var diffDays = endTime.diff(startTime, 'days') + 1;
        document.getElementById("days").innerHTML ="Expect: "+  diffDays.toString() + " Day(s)";

        
</script>

<script type="text/javascript">
    getMemberData();
</script>


</html>
