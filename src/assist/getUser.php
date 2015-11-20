<?php
	// [BC]
	// 這個FUNCTION是以使用者的ID來取的使用者資料，會回傳使用者的所有資訊
	// 取得使用者資料
	function getUser($uid)
	{
		include_once '../assist/getDataBaseConnection.php';
		$sqli = getDBConnection();

		$selectUser = "SELECT * FROM user_info WHERE uid=" . $uid;
		$result = $sqli->query($selectUser);
		if(!$result)
		{
			$error = array('SUCCESS'=>0, 'message'=>'there is an error when SELECT user_info by uid in getUser.php');
			echo json_encode($error);
			exit();
		}

		return $result->fetch_array();
	}
?>