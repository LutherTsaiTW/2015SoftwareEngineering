<?php
	// [BC]
	// 這個API是用來做 review 決定的 update
	// 把決定的結果和 comment 的內容 update 到 req_review table 中
	// 此API並不做 review 和 reviewer 的對應檢查
	// 輸入如下
	$reqReviewID	= $_POST['reqReviewID'];
	$comment		= $_POST['comment'];
	$decision		= $_POST['decision'];

	// [BC] 取得DB連線
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


	// [BC] update 新的資訊到 review table 中
	$update = "UPDATE req_review SET reviewComment=$comment, decision=$decision WHERE reqreviewID=$reqReviewID";
	
?>