   <?php session_start();    

        @$tid = $_GET['tid']; 
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
            $tname = $row['name'];
            $tdes = $row['t_des'];
            $tdata = $row['data'];
            $tresult = $row['result'];
            $tpid=$row['pid'];
            $towner_id=$row['owner_id'];
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
            $towner=$row['name'];
        }

        //取得使用者資訊
        $selectUser = "SELECT COUNT(uid) as count, name, previlege, uid FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "';";
        $result = $sqli->query($selectUser) or die('there is an error when SELECT USER in editRequirementRelationView.php');
        $user = $result->fetch_array();
        if($user['count'] != 1){
            exit('there is an error after SELECT USER ');
        }

        //取得testcase 的所有 relation
        $relation=null;
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
            $relation['relation'][$i]['rid']=$row['rid'];
            $relation['relation'][$i]['confirmed']=$row['confirmed'];
            $i++;
        }

        $notConfirmed=0;
        if( $relation!=null)
        foreach ( $relation['relation'] as $ifConfirm) {
            if($ifConfirm['confirmed'] == 0)
                $notConfirmed++;
        }
        echo $row;

         //取得與testcase有關的not confirmed req
        $getReqNotConfirmed = "SELECT * FROM req as r WHERE r.rid IN (SELECT rid FROM test_relation WHERE (tid=".$tid." AND confirmed = 0))";
        $result = $sqli->query($getReqNotConfirmed);
        if (!$result )
        {
            echo "Error: there is an error when getReqNotConfirmed";
            exit();
        }

        $i=0;
        $notConfirmedReq['count'] = $i;
        if ($result )
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $notConfirmedReq['req'][$i]['rid']=$row['rid'];
            $notConfirmedReq['req'][$i]['name']=$row['rname'];
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
        $confirmedReq['count'] = $i;
        if ($result )
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $confirmedReq['req'][$i]['rid']=$row['rid'];
            $confirmedReq['req'][$i]['name']=$row['rname'];
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