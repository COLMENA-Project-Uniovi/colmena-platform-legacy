<?php 
session_start(); 
include_once("../../config/config.php");
include_once($root_path."lib/functions/general-functions.php");
include_once($root_path."lib/functions/user-functions.php");
include_once($root_path."lib/mail/swift_required.php");

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
$usuario['pass'] = $_POST['pass'];

$pass = $_POST['pass'];
$pass2 = $_POST['pass2'];

if($pass == "" || $pass2 == "" || $pass != $pass2){
	$_SESSION['response_create_user_false'] = "Las contraseñas introducidas no pueden estar en blanco y han de ser iguales.";
	header("Location: ../../../registro/");
	exit;
}

if($usuario["nombre"] == "" ||
$usuario["apellidos"] == "" ||
$usuario["email"] == "" ||
$usuario["nombre_empresa"] == "" ||
$usuario["razon_social"] == "" ||
$usuario["cif"] == ""){
	$_SESSION['response_create_user_false'] = "Debes introducir todos los datos obligatorios para completar la solicitud de registro.";
	header("Location: ../../../registro/");
	exit;
}

if(existe_usuario($usuario)){
	$_SESSION['response_create_user_false'] = "Ya existe un usuario registrado con este email.";
	header("Location: ../../../registro/");
	exit;
}

$usuario['pass'] = $pass;
$usuario['id_confirmacion'] = sha1(time().$usuario['email']);
$id_usuario = crear_solicitud_usuario($usuario);

if(!$id_usuario){
	$_SESSION['response_create_user_false'] = "Ha habido un error procesando tu registro. Por favor, inténtalo de nuevo más tarde.";
	header("Location: ../../../registro/");
	exit;
}


$subject = "[FUNDACION COMILLAS] Confirma tu registro";
$body = '
	<div>
		<p>Estimado '.$usuario['nombre'].' '.$usuario['apellidos'].',
		<p>Pa completar tu registro en el Portal de concursos y licitaciones de la Fundación Comillas debes pinchar en el siguiente <a href="http://sandbox.neozink.com/fundacion-comillas/registro/confirmar/?id='.$usuario['id_confirmacion'].'">enlace</a>. </p>

		<p>Una vez que hayas confirmado tu registro, ya podrás acceder al portal con tu email y la contraseña que has introducido en el formulario de registro.</p>
	</div>
';

if(!send_email($usuario['email'], $subject, $body)){
	eliminar_usuario($id_usuario);
	$_SESSION['response_create_user_false'] = "Ha habido un error procesando tu registro. Por favor, inténtalo de nuevo más tarde.";
	header("Location: ../../../registro/");
	exit;
}

$_SESSION['response_create_user_true'] = "Tu registro está a punto de completarse. Consulta tu correo electrónico para confirmar el registro en el Portal.";
header("Location: ../../../registro/");
exit;
?>