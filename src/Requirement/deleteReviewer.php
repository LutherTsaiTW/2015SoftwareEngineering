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
$reviewID = $_GET['rvid'];
$result = array();

$sql = "DELETE FROM `req_review` WHERE `reqreviewID` = ?";
$stmt = $database->prepare($sql); //[MKZ] http://blog.roga.tw/2010/06/%E6%B7%BA%E8%AB%87-php-mysql-php-mysqli-pdo-%E7%9A%84%E5%B7%AE%E7%95%B0/
$stmt->bind_param('i', $reviewID); //[MKZ] http://php.net/manual/en/mysqli-stmt.bind-param.php
$stmt->execute();

$result['success'] = 1;
$result['reviewID'] = $reviewID;
$result['deleted_rows'] = $database->affected_rows;
echo (json_encode($result));

$stmt->close();
$database->close();
exit();
?>