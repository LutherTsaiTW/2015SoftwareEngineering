<?php
	// [BC]
	// 這個function是用來產生 requirement 和 requirement 關係表格的API
	// 因為是用require_once呼叫
	// 所以必須事先準備好以下變數
	// $pid -> 專案的id
	// 然後會直接 echo 出表格的形式

	echo "<style>
			.reqRelation {
				border-collapse: collapse;
				text-align: center;
				font-size: 12pt;
			}
			.empty{
				background:grey;
			}
			.cellA {
				width: 36px;
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
		$user = array('success' => 0, 'message' => 'db_error in reqRelationTable.php');
		echo(json_encode($user));
		$error = true;
	}
	$sqli->query("SET NAMES 'UTF8'");

	if(!$error){
		// [BC] 先檢查有沒有 requirements
		$findReq = "SELECT count(rid) AS c FROM req WHERE rproject=$pid AND rstatus != 0 AND rstatus != 5";
		$result = $sqli->query($findReq);
		if(!$result){
			echo "there si an error when Count rid in reqRelationTable.php<br>";
			echo "error message = " . $sqli->error;
			$error = true;
		}
		$reqNum = $result->fetch_array();
		if($reqNum['c'] == 0){
			return;
		}
	}

	if(!$error){
		// [BC] 取得requirements
		$selectReq = "SELECT rid, rnumber FROM req WHERE rproject=$pid AND (rstatus != 0 AND rstatus != 5) ORDER BY rnumber";
		$result = $sqli->query($selectReq);
		if(!$result){
			echo "there is an error when SELECT requirements in reqRelationTable.php";
			$error = true;
		}
	}

	$rpos = 0;
	$reqs = array();
	if(!$error){
		while($data = $result->fetch_array()){
			$reqs[$rpos++] = $data;
		}
	}

	// [BC] 開始輸出表格
	$pos = 0;
	while(!$error){
		echo "<table class='reqRelation' border=1px><tr><th class='empty cellA'></th>";
		for ($i = $pos; $i < $pos+17 && $i < $rpos ; $i++) { 
			echo "<td class='cellA'>R" . $reqs[$i]['rnumber'] . "</td>";
		}

		echo "</tr>";
		foreach ($reqs as $key) {
			echo "<tr><td class='cellA'>R" . $key['rnumber'] . "</td>";
			for($i = $pos;$i < $pos+17 && $i < $rpos; $i++){
				// [檢查是不是一樣的 requirement]
				if($key['rnumber'] == $reqs[$i]['rnumber']){
					echo "<td class='empty'> </td>";
					continue;
				}

				// [BC] 檢查 requirement 和 testcase 是否有關係
				$getRelation = "SELECT count(relation_id) AS c FROM req_relation WHERE (rid_a=" . $key['rid'] . " AND rid_b=" . $reqs[$i]['rid'] . ") OR (rid_a=" . $reqs[$i]['rid'] . " AND rid_b=" . $key['rid'] . ")";
				$result = $sqli->query($getRelation);
				if(!$result){
					echo "there is an error when getRelation in reqRelationTable.php";
					$error = true;
					break;
				}
				$temp = $result->fetch_array();
				if($temp['c'] == 1){
					echo "<td class='cellA'>O</td>";
				} else if($temp['c'] == 0){
					echo "<td class='cellA'> </td>";
				} else {
					echo "there is an error that req_relation table has two rows containing " . $key['rnumber'] . "and " . $reqs[$i]['rnumber'] . " in reqRelationTable.php";
				}
			}
			echo "</tr>";
		}
		echo "</tr></table>";
		$pos += 17;
		if($pos < $rpos){
			echo "<br><br>";
		}else {
			break;
		}
	}
?>