<?php
	// [BC]
	// 這個function是用來產生 requirement 和 test case 關係表格的API
	// 因為是用require_once呼叫
	// 所以必須事先準備好以下變數
	// $pid -> 專案的id
	// 然後會直接 echo 出表格的形式
	echo "<style>
			table, th, td {
		    	border: 3px solid white;
			    border-collapse: collapse;
			    color: white;
			    width:auto;
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
	echo json_encode($reqs) . "<br>";

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
	echo json_encode($testcases) . "<br>";

	// [BC] 開始輸出表格
	echo "<table><tr><th></th>";
	foreach ($reqs as $req) {
		echo "<th>R" . $req['rnumber'] . "</th>";
	}
	echo "</tr><tr>";
	foreach ($testcases as $key) {
		echo "<td></td>";
	}
	echo "</tr></table>";

	echo "<body>";
?>