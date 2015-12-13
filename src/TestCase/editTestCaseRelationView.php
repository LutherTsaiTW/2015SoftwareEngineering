<html style="height: 100%">
    <head>
        <title>Edit TestCase Relation</title>
        <meta charset="utf-8" />
        
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/dateRangePicker.css">
        <link rel="stylesheet" type="text/css" href="../css/basicPageElement.css">

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
    .mainDiv{
        margin: 0px auto;
        width: 970px;
        height: 550px;        
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
    select {
        float: left;
        height: 470px;
        width: 240px;
    } 
    button{
        float: left;   
    }  
    .leftBlock {
        float: left;
        overflow:hidden;
        height: 550px;
        width: 300px;
        background-color: rgb(40, 40, 40);
        border-radius: 15px;
    }

    .rightBlock {
        visibility: hidden;
        margin-left: 20px;
        float: left;
        overflow:hidden;
        height: 550px;
        width: 650px;
        background-color: rgb(40, 40, 40);
        border-radius: 15px;
    }
    </style>

    <?php
        @$pid = $_POST['pid'];  
    ?>
    <script type="text/javascript">
        // [KL] 這個function是做處理，把edit頁面轉回到正確的頁面
        function doEditReq(){
            var form = {
                'tid'            : $('input[id=tid]').val(),
                'changed_rids'   : $('input[id=changed_rids]').val(),
            }
            // [KL] 做POST
            var posting = $.post("editTestCaseRelation.php", form);
            // [KL] 完成POST之後，檢查response的內容
            posting.done(
                function(response){
                    try {
                        var r = $.parseJSON(response);
                    } catch (err) {
                        alert("Parsing JSON Fail!: " + err.message + "\nJSON: " + response);
                        return;
                    }
                    if(r.success == 1){
                        document.location.href = document.referrer;
                    } else {
                        alert('edit testcase relation failed\nthe error message = ' + r.message);
                    }
                }
            );
        }

         var Submit=function(){
            var URLs="getTestCaseRelation.php" ;
          
            $.ajax({
                url: URLs,
                data: $('#sentToBack').serialize(),
                type:"POST",
                dataType:'JSON',

            });
           
        }
    </script>
    

    <body class="w3-container" style="height: 100%; background-color: rgb(61, 61, 61); color: white">

        <!--頁面上半部-->
       <br/>
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
               <!--<?php echo "Welcome! ",$user['name']; ?>-->
            </div>
        </div>

        <br/>
        <div class="w3-row " style="vertical-align:center;Height:50px;color:white;text-align:center;background-color:grey;border-radius:5px">
                     <font id="projectName" style="font-size:36px">                   
                       Edit Test Case Relationship
                    </font>
                    <a href="    testCaseListView.php" style="font-size:20px;float:left;margin-left:5px;margin-top:10px">back</a>                   
        </div>

        <!--主要畫面-->
        <br>
        <div class="mainDiv">
        <div class="leftBlock">
        <font style="font-size:22px;margin-left:30px;margin-top:10px;float:left"><b>Test Case:</b></font>
            <select  name="testcase" id="testcase" multiple="yes" style="margin-left:30px;margin-top:5px" onchange="getData(value);">
           
             </select>
        </div>
        <div id="rightBlock" class="rightBlock">           
            <div style="float:left;height:470px;width:100%;margin-left:30px;margin-top:30px">

                    <input hidden="hidden" name="pid" id="pid" value=<?echo $pid;?>>
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
                    <input  type="reset" onclick="confirm();" value="confirm" style="text-align:center;background-color:rgb(0,149,135)">
                 </form>
                  <iframe id="_iframe" name="_iframe" style="display:none;"></iframe> 
            </div>
                    
        </div>
    </div>
    </body>
</html>