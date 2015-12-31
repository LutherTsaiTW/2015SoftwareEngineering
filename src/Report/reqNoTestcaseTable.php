<?php
	// [BC]
	// 這個function是用來產生那些沒有 testcase 關聯 的 requirement 的表格API
	// 因為是用require_once呼叫
	// 所以必須事先準備好以下變數
	// $pid -> 專案的id
	// 然後會直接 echo 出表格的形式

	echo "<style>
			.reqNoTestcaseTable {
		    	border-collapse: collapse;
				text-align: center;
				font-size: 12pt;
			}
		</style>";
	$pid = $_GET['pid'];

	// [BC] error用來檢查是否有錯誤產生，如果有為true，作為exit的替代
	$error = false;

	// [BC] 取得DB連線
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'db_error in testcaseNoReqTable.php');
		echo(json_encode($user));
		$error = true;
	}
	$sqli->query("SET NAMES 'UTF8'");

	if(!$error){
		// [BC] 取得requriement
		$selectReq = "SELECT rid, rnumber, rname, rstatus, version, rpriority, rtype FROM req WHERE rproject=$pid AND rstatus != 0 AND rstatus != 5 ORDER BY rnumber";
		$result = $sqli->query($selectReq);
		if(!$result){
			echo "there is an error when SELECT reqs in reqNoTestcaseTable.php";
			$error = true;
		}
		$rpos = 0;
		$reqs = array();
		while($data = $result->fetch_array()){
			$reqs[$rpos++] = $data;
		}
	}

	// [BC] 開始輸出表格
	$outputTable = array();
	$size = 0;

	if(!$error){
		foreach ($reqs as $req) {
			$select = "SELECT count(relation_id) AS r FROM test_relation WHERE rid = " . $req['rid'];
			$result = $sqli->query($select);
			if(!$result){
				echo "there is an error whent get relation informaion in testcaseNoReqTable.php<br>";
				echo "error = " . $sqli->error . "<br>";
				$error = true;
				break;
			}
			$data = $result->fetch_array();
			if($data['r'] == 0){
				$outputTable[$size++] = $req;
			}
		}
	}

	if($size != 0 && !$error){
		echo "<table class='reqNoTestcaseTable' border=1px><tr><th style='width:100px'>Number</th><th style='width:300px'>Name</th><th style='width:00px'>Status</th><th style='width:125px'>Type</th><th style='width:100px'>Version</th><th style='width:125px'>Priority</th></tr>";
		foreach ($outputTable as $key) {
		//for ($i = 0; $i < $size;$i++){outputTable[$i]
			echo "<tr><td>" . $key['rnumber'] . "</td><td>" . $key['rname'] . "</td>";

			switch ($key['rstatus']) {
				case 1:
					echo "<td>Open</td>";
					break;
				case 2:
					echo "<td>In Review</td>";
					break;
				case 3:
					echo "<td>Approved</td>";
					break;
				case 4:
					echo "<td>Disapproved</td>";
					break;
				default:
					echo "there is an error that the requirement's status is " . $key['rstatus'] . " in reqNoTestcaseTable.php";
					break;
			}

			switch ($key['rtype']) {
				case 0:
					echo "<td>non-Functional</td>";
					break;
				case 1:
					echo "<td>Functional</td>";
					break;
				default:
					echo "there is an error that the requirement's type is " . $key['rtype'] . " in reqNoTestcaseTable.php";
					break;
			}
			echo "<td>" . $key['version'] . "</td>";
			
			switch ($key['rpriority']) {
				case 0:
					echo "<td>Low</td>";
					break;
				case 1:
					echo "<td>Medium</td>";
					break;
				case 2:
					echo "<td>High</td>";
					break;
				default:
				echo "there is an error that the requriment's priority is " . $key['rpriority'] . " in reqNoTestcaseTable.php";
					break;
			}
			echo "</tr>";
		}
		echo "</table>";
	}
?>