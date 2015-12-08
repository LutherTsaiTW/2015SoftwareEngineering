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
?>