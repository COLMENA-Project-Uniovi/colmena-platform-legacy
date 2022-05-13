<?php
	session_start();
	session_destroy();
	require_once("../../config/config.php");
	require_once($root_private_area_path."lib/functions/user-functions.php");

	session_destroy();

	header("Location: ../../");
	exit;
?>