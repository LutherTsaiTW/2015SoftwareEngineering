<?php session_start();
        $tid = $_GET['tid'];
        $pid = $_GET['pid'];     
        
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


        // [KL] 取得與testcase無關的的req
        $selectReq = "SELECT * FROM req as r WHERE r.rid NOT IN (SELECT rid FROM test_relation WHERE tid =" . $tid . ")  AND r.rproject = ".$pid;
        $result = $sqli->query($selectReq);
        if (!$result)
        {
            echo "Error: there is an error when select req not relate to testcase";
            exit();
        }


        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $rid = $row['rid'];
            $feedback['notInLists'][$rid]['rid']=$rid;
            $feedback['notInLists'][$rid]['name']=urlencode($row['rname']);

        }
            $feedback['success'] = '1';
            $feedback['message'] = 'ok';

        echo(urldecode(json_encode($feedback))); 
        $feedback=json_encode($feedback);
    ?>
