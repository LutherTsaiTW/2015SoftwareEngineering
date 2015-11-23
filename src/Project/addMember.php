<?php
	@$addusers = $_POST['addusers'];
	@$removeusers = $_POST['removeusers'];
	@$iniaddusers = $_POST['iniaddusers'];
	@$iniremoveusers = $_POST['iniremoveusers'];
	@$pid = $_POST['pid'];
	foreach($iniaddusers as $iniadduser)
		{
			echo $iniadduser."<br>";
		}

	/* [CLY] Database Setting */
	require_once '../assist/DBConfig.php';

	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$feedback = array('success' => 0, 'message' => 'db_error');
		echo(json_encode($feedback));
		exit();
	}
	
	$sqli->query("SET NAMES 'UTF8'"); // [CLY] Let Chinese charcters show correctly
	foreach ($addusers as $adduser)
	{	
		//[KL]判斷是否存在原本的名單內，是則不做事，否則新增
		$exist=0;
		foreach($iniaddusers as $iniadduser)
		{
			if($adduser==$iniadduser)
				$exist=1;
		}
		if($exist==0)
		{
			$sqli->query("INSERT INTO project_team  VALUES (" . $pid . ", " . $adduser . ");") or die('Insert Query error');
			echo "Insert".$adduser."<br>";
		}
		
	}
	
	foreach ($removeusers as $removeuser)
	{
		//[KL]判斷是否存在原本的名單內，是則不做事，否則新增
		$exist=0;
		foreach($iniremoveusers as $iniremoveuser)
		{
			if($removeuser==$iniremoveuser)
				$exist=1;
		}
		if($exist==0)
		$sqli->query("DELETE FROM project_team  WHERE user_id=".$removeuser) or die('Query error');
	}
	
	$feedback['success'] = 1;
	echo json_encode($feedback);
?>
