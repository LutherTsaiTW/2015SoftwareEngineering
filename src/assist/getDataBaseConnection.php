<?php
	// [BC]
	// 這個檔案提供了DataBase的相關設定參數

	function getDBConnection(){
		// [BC] 抓DataBase的設定進來
		require_once 'DBConfig.php';

		// [BC] Get Connection
		$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
		$errno = mysqli_connect_errno();
		if($errno)
		{
			$user = array('success' => 0, 'message' => 'there is an error when connection with DB in getDataBaseConnection.php');
			echo(json_encode($user));
			return null;
		}
		//Show Chinese Chracters Correctly
		$sqli->query("SET NAMES 'UTF8'");
		
		return $sqli;
	}
?>