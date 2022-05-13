<?php
session_start();
include_once("../../config/config.php");
include_once($root_path."lib/functions/general-functions.php");
include_once($root_path."lib/functions/user-functions.php");

$session_id = $_SESSION['loginok'];

if(!isset($_SESSION['loginok'])){
	header("Location: ../../../");
	exit;
}

$usuario = esta_logueado($session_id);

if(!$usuario){
	header("Location: ../../../");
	exit;
}

$current_pass = $_POST['current_pass'];
$new_pass = $_POST['new_pass'];
$new_pass2 = $_POST['new_pass2'];

if($new_pass == "" || $new_pass2 == "" || $new_pass != $new_pass2){
	$_SESSION['response_edit_user_false'] = "Las nuevas contraseñas introducidas no pueden estar en blanco y deben ser iguales.";
	header("Location: ../../../perfil/");
	exit;
}

if(!esta_registrado($usuario['email'], $current_pass)){
	$_SESSION['response_edit_user_false'] = "La contraseña actual introducida es incorrecta.";
	header("Location: ../../../perfil/");
	exit;
}

if(!editar_pass($usuario['id'], $new_pass)){
	$_SESSION['response_edit_user_false'] = "Ha habido un error cambiando tu contraseña. Por favor, inténtalo de nuevo más tarde.";
	header("Location: ../../../perfil/");
	exit;	
}

$_SESSION['response_edit_user_true'] = "Tu contraseña se ha editado correctamente.";
header("Location: ../../../perfil/");
exit;

?>