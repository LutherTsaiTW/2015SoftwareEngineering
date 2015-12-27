<?php
	// [BC]
	// 這個function是用來產生 requirement 和 test case 關係表格的API
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
			}
			td {
				width:45px;
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
		$user = array('success' => 0, 'message' => 'db_error in reqTestcaseTable.php');
		echo(json_encode($user));
		exit();
	}
	$sqli->query("SET NAMES 'UTF8'");

	// [BC] 取得requirements
	$selectReq = "SELECT rid, rnumber FROM req WHERE rproject=$pid AND (rstatus != 0 OR rstatus != 5) ORDER BY rnumber";
	$result = $sqli->query($selectReq);
	if(!$result){
		echo "there is an error when SELECT requirements in reqTestcaseTable.php";
		exit();
	}
	$rpos = 0;
	while($data = $result->fetch_array()){
		$reqs[$rpos++] = $data;
	}

	// [BC] 取得Test Cases
	$selectTestCase = "SELECT tid, name FROM testcase WHERE pid=$pid ORDER BY tid";
	$result = $sqli->query($selectTestCase);
	if(!$result){
		echo "there is an error when SELECT testcases in reqTestcaseTable.php";
		exit();
	}
	$tpos = 0;
	while ($data = $result->fetch_array()) {
		$testcases[$tpos++] = $data;
	}

	// [BC] 開始輸出表格
	$pos = 0;

	while(true){
		echo "<table><tr><th></th>";
		for ($i = $pos; $i < $pos+14 && $i < $rpos ; $i++) { 
			echo "<th>R" . $reqs[$i]['rnumber'] . "</th>";
		}
		
		echo "</tr>";
		foreach ($testcases as $key) {
			echo "<tr><td style='width:180px'>" . $key['name'] . "</td>";
			for($i = $pos;$i < $pos+14 && $i < $rpos; $i++){
				$getRelation = "SELECT count(relation_id) AS c FROM test_relation WHERE tid=" . $key['tid'] . " AND rid=" . $reqs[$i]['rid'];
				$result = $sqli->query($getRelation);
				if(!$result){
					echo "there is an error when $getRelation in reqTestcaseTable.php";
					exit();
				}
				$temp = $result->fetch_array();
				if($temp['c'] == 1){
					echo "<td>O</td>";
				} else {
					echo "<td> </td>";
				}
			}
			echo "</tr>";
		}
		echo "</tr></table>";
		$pos += 14;
		if($pos < $rpos){
			echo "<br><br>";
		}else {
			break;
		}
	}

	echo "<body>";
?>