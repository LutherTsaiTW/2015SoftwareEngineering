<?php
	require_once '../assist/DBConfig.php';
	$mysqli = new mysqli($dburl, $dbuser, $dbpass, $db);
	$mysqli->query("SET NAMES 'UTF8'");
	$project = $_GET['pid'];
	$reqs = array();
	$tables = array('Open' => 1, 'In Review' => 2, 'Approved' => 3, 'Disapproved' => 4);
	$types = ['non-Functional', 'Functional'];
	$priorities  = ['Low', 'Medium', 'High'];
	$query = "SELECT rnumber, rname, version, rtype, rpriority, rstatus "
	. "FROM req WHERE rproject = ". $project. " AND rstatus BETWEEN 1 AND 4 "
	. "ORDER BY rnumber";
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_row()) {
			$type = $types[$row[3]];
			$prioritiy = $priorities[$row[4]];
			$reqs[$row[5]][] = [$row[0], $row[1], $row[2], $type, $prioritiy];
		}
		$result->close();
	}
?>

<?php
	$heads = '<tr>
	<th width = 100px>Number</th>
	<th width = 300px>Name</th>
	<th width = 100px>Version</th>
	<th width = 200px>Type</th>
	<th width = 150px>Priority</th>
	</tr>
	';
?>

<?php
	$table_start = '<table border = 1px style = "font-size:12pt; border-collapse:collapse; text-align:center;">
	';
?>

<?php
	foreach (array_keys($tables) as $table) {
		$index = $tables[$table];
		if (!array_key_exists($index, $reqs)) {
			continue;
		}
		$class = str_replace(" ", "",$table);
		echo '<reqtitle style = "font-size:20pt;">Status: <reqTitle class="'
		. $class . '">' . $table . '</reqTitle></reqtitle>
		';
		echo $table_start;
		echo $heads;
		foreach ($reqs[$index] as $req) {
			echo "<tr>
			";
			foreach ($req as $val) {
				echo "<td>" . $val . "</td>
				";
			}
			echo "</tr>
			";
		}
		echo '</table>
		';
	}
?>
