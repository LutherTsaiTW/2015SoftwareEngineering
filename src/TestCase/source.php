<?php session_start();
        $pid = $_GET['pid'];
        
        // [KL] 取得DB連線
        require_once '../assist/DKLonfig.php';
        $sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
        $errno = mysqli_connect_errno();
        if($errno)
        {
            $user = array('success' => 0, 'message' => 'db_error');
            echo(json_encode($user));
            exit();
        }
        $sqli->query("SET NAMES 'UTF8'");
        
        // [KL] 取得project的testcase資訊
        $selectReq = "SELECT * FROM testcase WHERE pid=" . $pid . ";";
        $result = $sqli->query($selectReq);
        if (!$result)
        {
            echo "Error: there is an error when select testcase ";
            exit();
        }
        $requirement = $result->fetch_array(MYSQLI_ASSOC); 
    ?>
    