<?php
	session_start();
	include_once("../../config/config.php");
	include_once($root_path."lib/functions/general-functions.php");
	include_once($root_path."lib/functions/user-functions.php");

	$current_url = $_SERVER['HTTP_REFERER'];
	$current_url = str_replace("http://sandbox.neozink.com/fundacion-comillas/", "", $current_url);
	
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	$usuario = esta_registrado($user, $pass);

	if(!$usuario){
		$_SESSION['error'] = "Email/Contraseña incorrectos";
		header("Location: ../../../". $current_url);
		exit;
	}

	$session_id = sha1($usuario['email'].time());

	if(registrar_sesion($usuario['email'], $session_id)){
		$_SESSION['loginok'] = $session_id;
		header("Location: ../../../". $current_url);
		exit;
	}else{
		$_SESSION['error'] = "error";
		header("Location: ../../../". $current_url);
		exit;		
	}
?>