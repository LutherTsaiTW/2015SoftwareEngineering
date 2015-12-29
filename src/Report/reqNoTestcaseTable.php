<?php
	// [BC]
	// 這個function是用來產生那些沒有 testcase 關聯 的 requirement 的表格API
	// 因為是用require_once呼叫
	// 所以必須事先準備好以下變數
	// $pid -> 專案的id
	// 然後會直接 echo 出表格的形式

	echo "<style>
			table, th, td {
		    	border: 5px solid white;
			    border-collapse: collapse;
			    color: white;
			    text-align: center;
			    font-size: 12pt;
			    padding: 0 0 0 0;
			    margin: auto;
			}
		</style>";
	echo "<body style='background:black;color:white'>";
	$pid = $_GET['pid'];

	// [BC] 取得DB連線
	require_once '../assist/DBConfig.php';
	$sqli = @new mysqli($dburl, $dbuser, $dbpass, $db);
	$errno = mysqli_connect_errno();
	if($errno)
	{
		$user = array('success' => 0, 'message' => 'db_error in testcaseNoReqTable.php');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");

	// [BC] 取得requriement
	$selectReq = "SELECT rid, rnumber, rname, rstatus, version, rpriority, rtype FROM req WHERE rproject=$pid AND rstatus != 0 AND rstatus != 5 ORDER BY rnumber";
	$result = $sqli->query($selectReq);
	if(!$result){
		echo "there is an error when SELECT reqs in reqNoTestcaseTable.php";
		exit();
	}
	$rpos = 0;
	while($data = $result->fetch_array()){
		$reqs[$rpos++] = $data;
	}

	// [BC] 開始輸出表格
	$outputTable = array();
	$size = 0;

	foreach ($reqs as $req) {
		$select = "SELECT count(relation_id) AS r FROM test_relation WHERE rid = " . $req['rid'];
		$result = $sqli->query($select);
		if(!$result){
			echo "there is an error whent get relation informaion in testcaseNoReqTable.php<br>";
			echo "error = " . $sqli->error . "<br>";
			exit();
		}
		$data = $result->fetch_array();
		if($data['r'] == 0){
			$outputTable[$size++] = $req;
		}
	}

	if($size != 0){
		echo "<table><tr><th style='width:100px'>Number</th><th style='width:300px'>Name</th><th style='width:00px'>Status</th><th style='width:125px'>Type</th><th style='width:100px'>Version</th><th style='width:125px'>Priority</th></tr>";
		//foreach ($outputTable as $key) {
		for ($i = 0; $i < $size;$i++){
			echo "<tr><td>" . $outputTable[$i]['rnumber'] . "</td><td>" . $outputTable[$i]['rname'] . "</td>";

			switch ($outputTable[$i]['rstatus']) {
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
					echo "there is an error that the requirement's status is " . $outputTable[$i]['rstatus'] . " in reqNoTestcaseTable.php";
					break;
			}

			switch ($outputTable[$i]['rtype']) {
				case 0:
					echo "<td>non-Functional</td>";
					break;
				case 1:
					echo "<td>Functional</td>";
					break;
				default:
					echo "there is an error that the requirement's type is " . $outputTable[$i]['rtype'] . " in reqNoTestcaseTable.php";
					break;
			}
			echo "<td>" . $outputTable[$i]['version'] . "</td>";
			
			switch ($outputTable[$i]['rpriority']) {
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
				echo "there is an error that the requriment's priority is " . $outputTable[$i]['rpriority'] . " in reqNoTestcaseTable.php";
					break;
			}
			echo "</tr>";
		}
		echo "</table>";
	}

	echo "</body>";
?>