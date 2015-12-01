<?php
	// [BC]
	// 這個API是用來做 review 決定的 update
	// 把決定的結果和 comment 的內容 update 到 req_review table 中
	// update 玩了之後，檢查其review 情形，決定 req 的狀態
	// 此API並不做 review 和 reviewer 的對應檢查
	// 輸入如下
	$reqReviewID	= $_POST['reqReviewID'];
	$comment		= $_POST['comment'];
	$decision		= $_POST['decision'];
	$requirement	= $_POST['requirement'];

	// [BC] 去除 comment 的前後空白
	$comment = trim($comment);

	// [BC] 取得DB連線
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('SUCCESS' => 0, 'MESSAGE' => 'db_error in doDecision.php');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");


	// [BC] update 新的資訊到 review table 中
	$updateReview = "UPDATE req_review SET reviewComment='$comment', decision=$decision WHERE reqreviewID=$reqReviewID";
	$result = $sqli->query($updateReview);
	if(!$result)
	{
		$response = array('SUCCESS'=>0, 'MESSAGE'=>'there is an error when UPDATE review in doDecision.php');
		echo json_encode($response);
		exit();
	}
	$response = array('SUCCESS'=>1, 'MESSAGE'=>'successfully update the review information, ');

	// [BC]
	// 取得 review 的數量
	// all代表全部，app代表同意，dis代表不同意
	$selectAll = "SELECT count(reqreviewID) AS c FROM req_review WHERE req_ID=$requirement";
	$selectApp = "SELECT count(reqreviewID) AS c FROM req_review WHERE req_ID=$requirement AND decision=1";
	$selectDis = "SELECT count(reqreviewID) AS c FROM req_review WHERE req_ID=$requirement AND decision=2";
	$resultAll = $sqli->query($selectAll);
	$resultApp = $sqli->query($selectApp);
	$resultDis = $sqli->query($selectDis);
	
	if(!$resultAll || !$resultApp || !$resultDis)
	{
		$response = array('SUCCESS'=>0, 'MESSAGE'=>'there is an error when Find review conclusion in doDecision.php');
		echo json_encode($response);
		exit();
	}
	$response['MESSAGE'] = $response['MESSAGE'] . 'successfully find the result of review conclusion, ';
	$all = $resultAll->fetch_array(MYSQL_ASSOC);
	$app = $resultApp->fetch_array(MYSQL_ASSOC);
	$dis = $resultDis->fetch_array(MYSQL_ASSOC);
	//echo "all = " . $all['c'] . " app = " . $app['c'] . " dis = " . $dis['c'] . " sum = " . ($app['c'] + $dis['c']) . "<br>";

	// [BC]
	// 判斷是不是大家都已經完成reivew
	// 如果是，表示大家都已經完成 review，這樣就修改 requirement 的 status
	// 反之，不做任何事
	if($all['c'] == ($app['c'] + $dis['c'])){
		// [BC] 取得 requirement 的 approved 條件，目前先以100%代替
		$term['term'] = 100;
		
		/*
		$selectTerm = "SELECT term FROM req WHERE rid=$requirement",
		$result->query($selectTerm);
		if(!$result){
			$response = array('SUCCESS'=>0, 'MESSAGE'=>'there is an error when SELECT req term in doDecision.php');
			echo json_encode($response);
			exit();
		}
		$term = $result->fetch_array(MYSQL_ASSOC);
		*/

		// [BC] 檢查條件是否通過，通過的話，改 req status 為 approved，反之，改為 disapproved
		if((100*$app['c']/$all['c']) >= $term['term']){
			// [BC] approved status
			$newStatus = 3;
		}else {
			// [BC] disapproved status
			$newStatus = -1;
		}
		
		// [BC] update req status
		$updateReq = "UPDATE req SET rstatus=$newStatus WHERE rid=$requirement";
		$result = $sqli->query($updateReq);
		if(!$result){
			$response = array('SUCCESS'=>0, 'MESSAGE'=>'there is an error when UPDATE req status in doDecision.php');
			echo json_encode($response);
			exit();
		}
		$response['MESSAGE'] = $response['MESSAGE'] . 'successfully update the requirement\'s status ' . $newStatus;
		echo json_encode($response);
	}else {
		$response['MESSAGE'] = $response['MESSAGE'] . 'it is not necessary to change the req status';
		echo json_encode($response);
	}
?>