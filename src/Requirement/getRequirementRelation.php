<?php
	// [BC]
	// 這個API是用來取得所有有相關聯的requirement的API
	// 需要input 是，pid、rid和是不是有關連的bool參數
	// 如果輸入是false，表示要找沒有關聯的requirement，反之，就是要找有關連的
	// 會回傳兩個東西，分別是inRelation，和outRelation
	// 二者都是陣列

	$pid = $_GET['pid'];
	$rid = $_GET['rid'];
	$relation = $_GET['relation'];

	// [BC] Set the DataBase connection
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'there is an error when getting DB connection in editProject.php');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");

	// [BC] 取得所有的requirement
	$selectRequirement = "SELECT rid, rname FROM req WHERE rproject=$pid AND rid!=$rid";
	$result = $sqli->query($selectRequirement);

	// [BC] 判斷requirement是否符合我們的條件
	$responese = array();
	$pos = 0;
	while($data = $result->fetch_array()){
		$temp = $data['rid'];
		$find = "SELECT count(relation_id) as c FROM req_relation WHERE (rid_a=$temp AND rid_b=$rid) OR (rid_a=$rid AND rid_b=$temp)";
		$exist = $sqli->query($find);
		$row = $exist->fetch_array();
		if(!($relation xor ($row['c']!=0))){
			$responese[$pos]['rid'] = $data['rid'];
			$responese[$pos]['rname'] = $data['rname'];
			$pos = $pos + 1;
		}
	}
	echo urldecode(json_encode($responese));
?>