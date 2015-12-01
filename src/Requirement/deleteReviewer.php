<?php
    try
    {
        require_once '../assist/DBConfig.php';
        $database = new mysqli($dburl, $dbuser, $dbpass, $db);
        $database->query("SET NAMES 'UTF8'");
        $reviewID = $_GET['rvid'];
        $result = array();
        
        $sql = "DELETE FROM `req_review` WHERE `reqreviewID` = ?";
        $stmt = $database->prepare($sql); //[MKZ] http://blog.roga.tw/2010/06/%E6%B7%BA%E8%AB%87-php-mysql-php-mysqli-pdo-%E7%9A%84%E5%B7%AE%E7%95%B0/
        $stmt->bind_param('i', $reviewID); //[MKZ] http://php.net/manual/en/mysqli-stmt.bind-param.php
        $stmt->execute();

        $result['success'] = 1;
        $result['reviewID'] = $reviewID;
        $result['deleted_rows'] = $database->affected_rows;
        echo(json_encode($result));

        $stmt->close();
        $database->close();
        exit();
    }
    catch(Exception $e)
    {
        $result['success'] = 0;
        $result['message'] = $e->getMessage();
        echo(json_encode($result));
        exit();
    }
?>