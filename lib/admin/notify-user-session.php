<?php
	require_once("../config/config.php");		
	require_once($root_path."lib/functions/lang-functions.php");
	require_once($root_path."lib/functions/general-functions.php");
	require_once($root_path."lib/functions/usuarios-functions.php");
	require_once($root_path."lib/functions/asignaturas-functions.php");
	require_once($root_path."lib/functions/sesiones-functions.php");
	$id_session = $_POST['data']['session'];
	$id_user = $_POST['data']['user'];
	$user = get_user($id_user);
	$session = get_sesion($id_session);
	$subject = get_asignatura($session['subject_id']);

	$email_subject = "[Colmena] Tu reporte en la sesión " . $session['session_name_es'] . " de la asignatura " . $subject['subject_name_es'];
	$email_body = "<h1>Tus datos en la sessión ". $session['session_name_es'] . " de la asignatura " . $subject['subject_name_es'] ."</h1>
				<p>Hola ". $user['name'] . " " . $user['surname'] .",</p>
				<p>Estos son tus datos de la sesión:</p>
				
				<p>Accede a la <a href='http://www.pulso.uniovi.es/colmena/'>Plataforma Colmena</a> y conoce más detalles y estadísticas
				sobre tus errores en programación</p>";
?>	
		
		<div class="admin-inner">
			<div class="admin-close">
				<i class="fa fa-close fa-2x"></i>
			</div>
			<div class="admin-users-list">
				<h3>Enviar reporte de la sesión <?= $session['session_name_es'] ?>
				<br/>de la asignatura <?= $subject['subject_name_es'] ?>
				<br/>a <?= $user['name'] . ' ' . $user['surname'] ?> (<?= $id_user ?>)</h3>
			<div class="admin-email-content">
				<label>Asunto del email:</label>
				<div class="admin-email-subject" contenteditable="true">
					<?= $email_subject ?>
				</div>
				<label>Cuerpo del email:</label>
				<div class="admin-email-body" contenteditable="true">
					<p><?= $email_body ?></p>
				</div><!-- .admin-email-body -->
			</div>
			<div class="admin-wrapper-button">
				<button class='send-report' data-url="send-report" data-user="<?= $user_id ?>">Enviar</button>
			</div>

			</div>
		</div>
						
					