<?php
session_start();
include_once("../config/config.php");
include_once($root_path."lib/functions/general-functions.php");
include_once($root_path."lib/functions/user-functions.php");
include_once($root_path."lib/functions/log-functions.php");

$session_id = $_SESSION['loginok'];
$file = $_GET['file'];
$id_elemento = $_GET['id_elemento'];
$tipo_elemento = $_GET['tipo_elemento'];


if(!isset($_SESSION['loginok'])){
	header("Location: ../../");
	exit;
}

$usuario = esta_logueado($session_id);

if(!$usuario){
	header("Location: ../../");
	exit;
}

save_log_file($usuario['id'], $file, $id_elemento, $tipo_elemento);
$_SESSION['download'] = 1;
$_SESSION['file_name'] = $file;
header("Location: ../../licitaciones/ficha/?id=". $id_elemento);
exit;

?>