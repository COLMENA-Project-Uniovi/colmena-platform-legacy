<?php
	session_start();
	session_destroy();
	require_once("../../lib/config/config.php");
	require_once($root_path."lib/functions/user-functions.php");

	//logout($_SESSION['loginok']);
	session_destroy();

	header("Location: ../../");
	exit;
?>