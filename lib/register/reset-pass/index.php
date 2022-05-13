<?php
session_start();
include_once("../../config/config.php");
include_once($root_path."lib/functions/general-functions.php");
include_once($root_path."lib/functions/user-functions.php");
require_once($root_path."lib/mail/swift_required.php");

$email = $_POST['email'];

$usuario = get_usuario_registrado($email);

if(!$usuario){
	$_SESSION['response_recover_password_false'] = "No existe ningún usuario registrado con este email.";
	header("Location: ../../../registro/recuperar/");
	exit;
}

$nueva_pass = generate_password(8);

if(!recuperar_pass($usuario['id'], $nueva_pass)){
	$_SESSION['response_recover_password_false'] = "Ha habido un error recuperando la contraseña del usuario. Por favor, inténtalo de nuevo más tarde.";
	header("Location: ../../../registro/recuperar/");
	exit;
}

$subject = "[FUNDACION COMILLAS] Contraseña reseteada";
$body = '
	<div>
		<p>Estimado '.$usuario['nombre'].' '.$usuario['apellidos'].',
		<p>La contraseña de su cuenta en el Portal de concursos y licitaciones de la Fundación Comillas se ha reseteado correctamente. Estos son sus nuevos datos de acceso: </p>
		<ul>
			<li>Usuario: '.$usuario['email'].'</li>
			<li>Contraseña: '.$nueva_pass.'</li>
		</ul>
	</div>
';

if(!send_email($usuario['email'], $subject, $body)){
	$_SESSION['response_recover_password_false'] = "Ha habido un error recuperando la contraseña del usuario. Por favor, inténtalo de nuevo más tarde.";
	header("Location: ../../../registro/recuperar/");
	exit;
}

$_SESSION['response_recover_password_true'] = "Tu contraseña se ha reseteado correctamente. Se ha enviado un email a tu dirección de correo electrónico con tus nuevos datos de acceso.";
header("Location: ../../../registro/recuperar/");
exit;
?>