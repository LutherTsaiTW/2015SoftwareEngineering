<html style="height: 100%">

    <head>
        <script type="text/javascript" src="../js/testCaseDetail.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/testCaseDetailElement.css">

        <script language="javascript">
            function reSize(){
            　　//parent.document.all.frameid.height=document.body.scrollHeight; 
            　　parent.document.getElementById("relationIframe").height=document.body.scrollHeight;
            } 
            window.onload=reSize;
        </script>
    </head>


        <?php 

        @$tid = $_GET['tid']; 
        require_once 'getTestCaseDetailData.php';
        ?>


    <body>
        <div class="bottomBox" id="relationBox"> 
            <div id="notConfirmBox"  class="relationshipBox" <?php if($notConfirmedReq['count'] == 0) echo 'style="visibility:hidden;height:0px;padding:0;margin:0px"' ?>>
                <div>
                 <table class="font-22" id="notConfirmTable">
                    <tr>
                        <td ><font class="bold-22">Not Confirm: </font></td>
                    </tr>
                        <?php 
                            if($notConfirmedReq['count']>0){
                                if($user['previlege']==777){
                                    foreach ($notConfirmedReq['req'] as $t ) {
                                    echo "<tr><td>". $t['name'] ."</td> <td><button class='btn font-22' onclick='doConfirm(".$tid.",".$t['rid'].")'>Confirm</button></td> <td><button class='btn font-22' onclick='doRemove(".$tid.",".$t['rid'].")'>Remove</button></td></tr>";
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
                                    echo "<tr><td>". $t['name'] ."</td><td> </td><td> </td></tr>";
                                }
                            } 
                        ?>                               
                 </table> 
            </div>
        </div>
    </body>
</html>