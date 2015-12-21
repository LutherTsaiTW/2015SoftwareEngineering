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

         //取得與testcase有關的 confirmed req
        $getReq = "SELECT * FROM req as r WHERE r.rid =".$rid;
        $result = $sqli->query($getReq);
        if (!$result )
        {
            echo "Error: there is an error when getReq";
            exit();
        }
        if ($result )
        $req = $result->fetch_array(MYSQLI_ASSOC);
        $req['rdes']=whitespaceHandler($req['rdes']);
 


        function whitespaceHandler($vstring)
        {   
            $REG = '/[%0D%0A]+/';
            $space = '/[+]/';
            $string = preg_replace($REG, '<br />', $vstring);
            $string = preg_replace($space, '&nbsp;', $string);
            return $string;
        }
    ?>