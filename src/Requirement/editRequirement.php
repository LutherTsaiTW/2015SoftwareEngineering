<?php
// [MKZ] Catch fatal error, transfer to json then echo.
function catch_fatal_error() {
	$error = error_get_last();
	$result = array();
	if ($error !== NULL) {
		$result['success'] = 0;
		$result['message'] = $error["message"] . " in " . $error["file"] . ":" . $error["line"];
		echo (json_encode($result));
		exit();
	}
}
register_shutdown_function('catch_fatal_error');

require_once '../assist/DBConfig.php';
$database = new mysqli($dburl, $dbuser, $dbpass, $db);
$database->query("SET NAMES 'UTF8'");
$id = $_POST['id'];
$name = $_POST['name'];
$type = $_POST['type'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$result = array();

$sql = "UPDATE `req` SET `rname` = ?, `rtype` = ?, `rdes` = ?, `rpriority` = ? WHERE `rid` = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param('sisii', $name, $type, $description, $priority, $id);
$stmt->execute();

$result['success'] = 1;
$result['affected_rows'] = $database->affected_rows;
echo (json_encode($result));

$stmt->close();
$database->close();
exit();
?>