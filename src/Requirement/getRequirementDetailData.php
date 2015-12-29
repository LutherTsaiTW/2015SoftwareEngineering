   <?php session_start();   

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
        

        //取得使用者資訊
        $selectUser = "SELECT COUNT(uid) as count, name, previlege, uid FROM user_info WHERE user_session='" . $_SESSION['sessionid'] . "';";
        $result = $sqli->query($selectUser) or die('there is an error when SELECT USER in editRequirementRelationView.php');
        $user = $result->fetch_array();
        if($user['count'] != 1){
            exit('there is an error after SELECT USER ');
        }

         //取得req
        $getReq = "SELECT * FROM req as r WHERE r.rid =".$rid;
        $result = $sqli->query($getReq);
        if (!$result )
        {
            echo "Error: there is an error when getReq";
            exit();
        }
        if ($result )
        $req = $result->fetch_array(MYSQLI_ASSOC);
        $req['rdes']=$req['rdes'];

        //取得與req有關的requirement
        $relIndex=0;
        $relReq=null;
        $getRelationReqA = "SELECT * FROM req_relation as r WHERE r.rid_a =".$rid;
        $resultA = $sqli->query($getRelationReqA);

        if (!$resultA )
        {
            echo "Error: there is an error when getRelationReqA";
            exit();
        }
        while($row1 = $resultA->fetch_array(MYSQLI_ASSOC))
        {

            $relReq['req'][$relIndex]['rid']=$row1['rid_b'];
            $relIndex++;
        }
        $getRelationReqB = "SELECT * FROM req_relation as r WHERE r.rid_b =".$rid;
        $resultB = $sqli->query($getRelationReqB);
        if (!$resultB )
        {
            echo "Error: there is an error when getRelationReqB";
            exit();
        }
        while($row2 = $resultB->fetch_array(MYSQLI_ASSOC))
        {
            $relReq['req'][$relIndex]['rid']=$row2['rid_a'];
            $relIndex++;
        }

        //取得requirement的name
        if(count($relReq)>0)
        {
            $relIndex=0;
            foreach ( $relReq['req']as $v ) {
                 
                $getRelationReq = "SELECT * FROM req as r WHERE r.rid =".$v['rid'];

                $result = $sqli->query($getRelationReq);
                if (!$result )
                {
                    echo "Error: there is an error when getRelationReq";
                    exit();
                }   
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $relReq['req'][$relIndex]['rname']=$row['rname'];
                    $relIndex++;
            }
        }

        //取得有關的testcase
        $relTIndex=0;
        $getRelationTestCase = "SELECT * FROM testcase WHERE tid IN (SELECT tid FROM test_relation  WHERE rid =".$rid.")";
        $result = $sqli->query($getRelationTestCase);
        if (!$result )
        {
            echo "Error: there is an error when getRelationTestCase";
            exit();
        }
        $relTestCase=null;
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {

            $relTestCase['testcase'][$relIndex]['tid']=$row['tid'];
            $relTestCase['testcase'][$relIndex]['name']=$row['name'];
            $relIndex++;
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