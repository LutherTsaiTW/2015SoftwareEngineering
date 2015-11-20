<?php
	// [BC]
	// 這個php是用來刪除一個專案
	// 需要的參數如下
	// pid -> 要被刪除的專案ID
	// 使用GET的方式取得上述參數
	// 若成刪除，沒有任何錯誤的話，會直接回到projectList.html的頁面
	// 反之會顯示錯誤資訊
	
	// [BC] include一個可以呼叫函式，取得資料庫連線的的php
	include_once '../assist/getDataBaseConnection.php';

	// [BC] 取得參數
	$pid = $_GET["pid"];

	// [BC] 取得連線
	$sqli = getDBConnection();
	if(is_null($sqli)){
		echo "<br>data base connection is fail in deleteReoject.php<br>";
		exit();
	}

	// [BC] 設定專案的狀態改成3，也就是delete的狀態
	$delete = "UPDATE project SET status=3 WHERE p_id=" . $pid;
	$result = $sqli->query($delete);
	if(!$result){
		$user = array('success' => 0, 'message' => 'there is an error when UPDATE project status to 3 in deleteProject.php');
		echo(json_encode($user));
		exit();
	}

	// [BC] 重新導到專案列表頁面，如果沒有錯誤的話
	header("Location: projectList.html");
?>