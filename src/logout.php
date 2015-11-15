<?php
	// [BC]
	// 這個API是用來登出，也就是把session id清除
	// 這個API沒有參數
	// 成功就會直接導到login.html
	// 至於失敗...我想不到有失敗的可能...
	
	// [BC] 開啟seesion
	session_start();
	// [BC]清除session
	unset($_SESSION['sessionid']);
	// [BC] 重新導至login.html
	header("location: login.html");
?>