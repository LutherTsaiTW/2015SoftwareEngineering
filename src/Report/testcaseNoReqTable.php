<?php
	// [BC]
	// 這個function是用來產生那些沒有 requirement 關聯，或是 testcase 尚未 confirm 的表格API
	// 因為是用require_once呼叫
	// 所以必須事先準備好以下變數
	// $pid -> 專案的id
	// 然後會直接 echo 出表格的形式

	echo "<style>
			.testcaseNoReq {
			    border-collapse: collapse;
			    text-align: center;
			    font-size: 12pt;
			}
			.cell {
				width: 270px;
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
		// [BC] 取得testcase
		$selectTestCase = "SELECT tid, name FROM testcase WHERE pid=$pid ORDER BY tid";
		$result = $sqli->query($selectTestCase);
		if(!$result){
			echo "there is an error when SELECT testcases in testcaseNoReqTable.php";
			$error = true;
		}
	}
	$tpos = 0;
	$testcases = array();
	
	if(!$error){
		while($data = $result->fetch_array()){
			$testcases[$tpos++] = $data;
		}
	}

	// [BC] 開始輸出表格
	$outputTable = array();
	$size = 0;

	if(!$error){
		foreach ($testcases as $testcase) {
			$select = "SELECT count(relation_id) AS r, sum(confirmed) AS c FROM test_relation WHERE tid = " . $testcase['tid'];
			$result = $sqli->query($select);
			if(!$result){
				echo "there is an error whent get relation informaion in testcaseNoReqTable.php";
				$error = true;
				break;
			}
			$data = $result->fetch_array();
			if($data['r'] == 0){
				$outputTable[$size++] = array('name' => $testcase['name'], 'req' => 1, 'confirmed' => 0);
				//echo "<tr><td>" . $testcase['name'] . "</td><td>O</td><td></td></tr>";
			}else if($data['r'] > $data['c']){
				$outputTable[$size++] = array('name' => $testcase['name'], 'req' => 0, 'confirmed' => 1);
				//echo "<tr><td>" . $testcase['name'] . "</td><td></td><td>O</td></tr>";
			}
		}
	}

	if($size != 0 && !$error){
		echo "<table class='testcaseNoReq' border=1px><tr><th class='cell'>Name</th><th class='cell'>No Relation</th><th class='cell'>non-Confirm</th></tr>";
		for($i = 0; $i < $size;$i++){
			echo "<tr><td>" . $outputTable[$i]['name'] . "</td>";
			if($outputTable[$i]['req'] == 1){
				echo "<td>O</td><td></td>";
			}else {
				echo "<td></td><td>O</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
?>