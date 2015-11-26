<?php
    header("Content-Type:text/html; charset=utf-8");

    session_start();
    if(isset($_SESSION['sessionid']) && !empty($_SESSION['sessionid'])) {
        $result = "EXSIT";
    } else {
        $result = "NULL";
    }
    session_write_close();

    echo($result);
?>