<?php
	require_once("../config/config.php");		
	include_once($root_path."lib/functions/lang-functions.php");	
	include_once($root_path."lib/functions/general-functions.php");
	include_once($root_path."lib/functions/asignaturas-functions.php");	
	include_once($root_path."lib/functions/sesiones-functions.php");	
	include_once($root_path."lib/functions/errores-functions.php");	
	include_once($root_path."lib/functions/familias-functions.php");	
	include_once($root_path."lib/functions/usuarios-functions.php");
	include_once($root_path."lib/functions/colmena-coeficient-functions.php");
	$subject_id = $_POST['data']['subject'];
	$user_id = $_POST['data']['user'];
	$user = get_user($user_id);
	$subject = get_asignatura($subject_id);
	$subject_table = $subject['table_name'];
	//CC DEL USUARIO
	//cc de la asignatura por sesiones
	$user_cc_subject_sessions = get_cc_user_subject($user_id, $subject_table, $subject_id, false);
	//junto y hago media para asignatura
	$user_cc_subject = merge_and_average($user_cc_subject_sessions);			
	//hago una media para tener un valor númerico
	$user_cc_average = ceil(array_sum($user_cc_subject) / count($user_cc_subject));
	//CÁLCULO DE LA ASISTENCIA
	$asistencia = 0;
	foreach ($user_cc_subject_sessions as $id_session_temp => $session_temp) {
		$asistencia += ($session_temp[1]) == -1 ? 0 : 1;
	}
	

	//obtenemos todos los usuarios activos y su cc
	$active_users = get_cc_users_family_in_subject($subject_table, $subject_id);
	$active_users = $active_users['active_users'];
	//ordenamos los usuarios de mejor cc a peor
	krsort($active_users);
	uasort($active_users, 'cmp');

	// cc medio de la asignatura por familias
	$cc_subject_by_families = get_cc_subject($subject_table, $subject_id, false);

	//saco indices y ubico al usuario
	$index = array_search($user_id, array_keys($active_users));			
	$interval = count($active_users)/4;
	$performance = intval($index/$interval) + 1;

	$email_subject = "[Colmena] Tu reporte de " . $subject['subject_name_es'] . " " . $subject['academic_year'];
	$email_body = "<h1>Tus datos en ". $subject['subject_name_es'] . " " . $subject['academic_year'] ."</h1>
				<p>Hola ". $user['name'] . " " . $user['surname'] .",</p>
				<p>Estos son tus datos de la asignatura hasta el momento:</p>
				<ul>
					<li>Tu posición en la asignatura: <strong>". ($index+1) ."</strong>/". count($active_users) ."</li>
					<li>Tu asistencia a sesiones: <strong>". $asistencia ."</strong>/". count($user_cc_subject_sessions) ."</li>
					<li>Tu coeficiente colmena: <strong>" . $user_cc_average . "</strong>/100</li>
				</ul>
				<p>Entra en <a href='http://www.pulso.uniovi.es/colmena/'>Colmena</a> para descubrir más</p>";
?>	
		
		<div class="admin-inner">
			<div class="admin-close">
				<i class="fa fa-close fa-2x"></i>
			</div>
			<h3>Enviar reporte de la asignatura <?= $subject['subject_name_es'] . ' ' . $subject['academic_year'] ?><br/> a <?= $user['name'] . ' ' . $user['surname'] ?> (<?= $user_id ?>)</h3>
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
						
					