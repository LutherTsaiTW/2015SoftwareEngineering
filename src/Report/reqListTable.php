<?php
	require_once '../assist/DBConfig.php';
	$mysqli = new mysqli($dburl, $dbuser, $dbpass, $db);
	$mysqli->query("SET NAMES 'UTF8'");
	$project = $_GET['pid'];
	$reqs = array(1 => array(), 2 => array(), 3 => array(), 4 => array());
	$stasus = array('Open' => 1, 'In Review' => 2, 'Approved' => 3, 'Disapproved' => 4);
	$types = ['non-Functional', 'Functional'];
	$priorities  = ['Low', 'Medium', 'High'];
	$query = "SELECT rnumber, rname, version, rtype, rpriority, rstatus "
	. "FROM req WHERE rproject = ". $project. " AND rstatus BETWEEN 1 AND 4";
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
	$heads = "<th width = 100>Number</th>
	<th width = 300>Name</th>
	<th width = 100>Version</th>
	<th width = 200>Type</th>
	<th width = 150>Priority</th>";
?>
<font size="12px">Status: Open</font>
<table border = 1px style = "font-size:6px; border-collapse: collapse; text-align: center;">
	<tr><?php echo $heads?><tr>

		<?php
			foreach ($reqs[1] as $req) {
				echo "<tr>";
				foreach ($req as $val) {
					echo "<td>" . $val . "</td>";
				}
				echo "</tr>";
			}
		?>
		<td>Number</td>
		<td>Name</td>
		<td>Version</td>
		<td>Type</td>
		<td>Priority</td>
</table>