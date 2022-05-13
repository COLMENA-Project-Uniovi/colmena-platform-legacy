<?php
	session_start();
	session_destroy();
	include_once("../../config/config.php");
	include_once($root_path."lib/functions/general-functions.php");
	include_once($root_path."lib/functions/user-functions.php");

	$current_url = $_SERVER['HTTP_REFERER'];
	$current_url = str_replace("http://sandbox.neozink.com/fundacion-comillas/", "", $current_url);
		
	logout($_SESSION['loginok']);

	header("Location: ../../../". $current_url);
	exit;
?>