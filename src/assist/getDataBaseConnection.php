<?php
	// [BC]
	// 這個檔案提供了DataBase的相關設定參數

	function getDBConnection(){
		// [BC] DataBase的設定
		$dburl = "";
		$dbuser = "";
		$dbpass = "";
		$db = "";

		// [BC] Get Connection
		$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
		$errno = mysqli_connect_errno();
		if($errno)
		{
			$user = array('success' => 0, 'message' => 'db_error');
			echo(json_encode($user));
			return null;
		}
		//Show Chinese Chracters Correctly
		$sqli->query("SET NAMES 'UTF8'");

		return $sqli;
	}
?>