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
        
        // [KL] 取得testcase資訊
        $selectReq = "SELECT * FROM testcase ";
        $result = $sqli->query($selectReq);
        if (!$result )
        {
            echo "Error: there is an error when select testcase not relate";
            exit();
        }


        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $tid = $row['tid'];
            $feedback['testcases'][$tid]['tid']=$tid;
            $feedback['testcases'][$tid]['name']=urlencode($row['name']);

        }
            $feedback['success'] = '1';
            $feedback['message'] = 'ok';

        echo(urldecode(json_encode($feedback))); 
        $feedback=json_encode($feedback);
    ?>
