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

$usuario['nombre'] = $_POST['nombre'];
$usuario['apellidos'] = $_POST['apellidos'];
$usuario['email'] = $_POST['email'];
$usuario['nombre_empresa'] = $_POST['nombre_empresa'];
$usuario['razon_social'] = $_POST['razon_social'];
$usuario['cif'] = $_POST['cif'];
$usuario['direccion'] = $_POST['direccion'];
$usuario['poblacion'] = $_POST['poblacion'];
$usuario['cp'] = $_POST['cp'];
$usuario['telefono'] = $_POST['telefono'];

if($usuario["nombre"] == "" ||
	$usuario["apellidos"] == "" ||
	$usuario["email"] == "" ||
	$usuario["nombre_empresa"] == "" ||
	$usuario["razon_social"] == "" ||
	$usuario["cif"] == ""){

	$_SESSION['response_edit_user_false'] = "Debes completar todos los datos obligatorios al editar tu perfil.";
	header("Location: ../../../perfil/");
	exit;
}

if(es_usuario($usuario['id'], $usuario['email'])){
	$_SESSION['response_edit_user_false'] = "No puedes cambiar tu email porque ya existe otro usuario registrado con este email.";
	header("Location: ../../../perfil/");
	exit;
}

if(!editar_usuario($usuario)){
	$_SESSION['response_edit_user_false'] = "Ha habido un error editando tu perfil. Por favor, inténtalo de nuevo más tarde.";
	header("Location: ../../../perfil/");
	exit;
}

logout($_SESSION['loginok']);
$session_id = sha1($usuario['email'].time());

registrar_sesion($usuario['email'], $session_id);
$_SESSION['loginok'] = $session_id;


$_SESSION['response_edit_user_true'] = "Tu perfil se ha editado correctamente.";
header("Location: ../../../perfil/");
exit;

?>