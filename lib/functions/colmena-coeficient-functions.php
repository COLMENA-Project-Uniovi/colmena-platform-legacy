<?php

//FUNCIONES PARA EL COEFICIENTE COLMENA (CC)

//Metodo que devuelve la media de los errores de todos los usuarios de una sesion
//Devuelve un array de la forma [id_familia] => [media de errores para esa sesion]
function get_average_errors_session($subject_table, $session_id){
	$link = connect();

	$query = sprintf("SELECT count(*) as total, ce.first_family, count(distinct cm.user_id) as total_users
						FROM  %s cm, colmena_error ce
						WHERE cm.active = '1'
						AND ce.error_id = cm.error_id
                        AND cm.session_id = '%s'
						GROUP BY ce.first_family
						ORDER BY ce.first_family",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($session_id)
	);
	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc())
		$returned_array[$row['first_family']] = $row['total'] / $row['total_users'];

	return $returned_array;
}

function has_participated($user_id, $user_cc, $subject_id = false){	
	if(array_sum($user_cc)/count($user_cc) > 0){
		return true;
	}
	return false;
}


/*
	BUSCA COMPILACIONES DE UN USUARIO PARA AVERIGUAR SI PARTICIPÓ
	Utilizado por get_cc_user_session()	
*/
function has_participated_in_session($user_id, $session_id, $subject_table){
	$subject_table = explode('_', $subject_table);
	array_shift($subject_table);
	array_shift($subject_table);
	$subject_table = implode('_', $subject_table);	
	$link = connect();

	$query = sprintf("SELECT *
						FROM  colmena_compilations_%s
						WHERE user_id = '%s'
						AND session_id = '%s'
						LIMIT 1",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($user_id),
		$link->real_escape_string($session_id)
	);
	$result = $link->query($query);

	return ($result->num_rows != 0);
}

//CC DE UN USUARIO DENTRO DE UNA ASIGNATURA Y DE UNA SESION PARA UNA FAMILIA CONCRETA
// Devuelve para un usuario la relacion de los coeficientes colmena que tiene para cada familia en una sesion concreta.
// En este caso son los errores que ha cometido

function get_cc_user_subject_session($user_id, $subject_table, $session_id, $consider_visible = true){
	$link = connect();

	if($consider_visible){
		$query = sprintf("SELECT count(*) as errors, ce.first_family
						FROM %s cm, colmena_error ce
						WHERE ce.error_id = cm.error_id
						AND cm.active = '1'
						AND cm.user_id = '%s'
						AND cm.session_id = '%s'
						GROUP by ce.first_family",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($user_id),
		$link->real_escape_string($session_id)
		);
	}else{
		$query = sprintf("SELECT count(*) as errors, ce.first_family
						FROM %s cm, colmena_error ce
						WHERE ce.error_id = cm.error_id
						AND cm.user_id = '%s'
						AND cm.session_id = '%s'
						GROUP by ce.first_family",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($user_id),
		$link->real_escape_string($session_id)
		);
	}
	

	$result = $link->query($query);
	
	$errors = array();
	$cc_array = array();
	$average_errors = get_average_errors_session($subject_table, $session_id);
	$families = get_familias();

	//asigno los errores para esa sesion y usuario
	while($row = $result->fetch_assoc()){
		$errors[$row['first_family']] = $row['errors'];
	}

	$has_participated = false;
	//recorro la lista de la media de errores
	foreach ($families as $family_id => $family) {
		//si no están vacios para esa familia

		if(!empty($errors[$family_id]) && !empty($average_errors[$family_id])){
			//calculo el coeficiente colmena
			$cc = 1 - ($errors[$family_id] / (2 * $average_errors[$family_id]));
			//asigno los límites
			if($cc > 1){
				$cc = 1;
			}else if( $cc < 0){
				$cc = 0;
			}
			//asigno
			$cc_array[$family_id] = ceil($cc * 100);
			$has_participated = true;
		}else{
			//en caso de no haber errores ponemos un valor que nos diferencie
			$cc_array[$family_id] = -1;
		}
	}
	if($has_participated || has_participated_in_session($user_id, $session_id, $subject_table)){		
		foreach ($cc_array as $family_id => $cc) {
			if($cc == -1)
				$cc_array[$family_id] = 100;
		}
	}
	return $cc_array;
	
}

//CC DE UN USUARIO DENTRO DE UNA ASIGNATURA;
//Es la media de los CC que tiene para cada sesion de esa asignatura

function get_cc_user_subject($user_id, $subject_table, $subject_id, $grouped_sessions = false, $consider_visible = true){
	$subject_sessions = get_sesiones_asignatura($subject_id, $consider_visible);
	$subject_cc = array();
	
	foreach($subject_sessions as $subject_session){
		$session_array = get_cc_user_subject_session($user_id, $subject_table, $subject_session['id'], $consider_visible);
		if(!empty($session_array)){
			$subject_cc[$subject_session['id']] = $session_array;
		}	
	}

	if($grouped_sessions){
		$subject_cc = merge_and_average($subject_cc);
	}

	return $subject_cc;
}

//CC DE UN USUARIO
//ES la media de los CC que tiene para cada asignatura
function get_cc_user($user_id, $grouped_sessions = false, $grouped_subjects = false, $consider_visible = true){
	$user_subjects = get_asignaturas_por_usuario($user_id);
	$user_cc = array();
	foreach($user_subjects as $user_subject){
		$user_cc[$user_subject['id']] = get_cc_user_subject($user_id, $user_subject['table_name'], $user_subject['id'], $grouped_sessions, $consider_visible);
	}

	if($grouped_subjects){
		$user_cc = merge_and_average($user_cc);
	}

	return $user_cc;
}	

//CC DE UNA SESION
//Es la media de los CC de los usuarios de la sesion

function get_cc_session($table_name, $session_id, $grouped_users = false, $consider_visible = true){
	$session_users = get_session_users($table_name, $session_id);
	$returned_array = array();
	foreach ($session_users as $user_id) {
		$aux = get_cc_user_subject_session($user_id, $table_name, $session_id);
		$returned_array[$user_id] = $aux;
		if(!$grouped_users)
			$returned_array[$user_id]['total'] = (array_sum($aux));
		
	}
	if($grouped_users)
		$returned_array = merge_and_average($returned_array);

	return $returned_array;
	
}

//CC DE UNA ASIGNATURA
//Es la media de los CC de sus usuarios

function get_cc_subject($subject_table, $subject_id, $grouped_sessions = false, $consider_visible = true){ 
	$sessions = get_sesiones_asignatura($subject_id, $consider_visible);	
	$returned_array = array();
	
	foreach ($sessions as $session) {
		$aux = get_cc_session($subject_table, $session['id'], true, $consider_visible);
		$returned_array[$session['id']] = $aux;
	}
	
	if(!$grouped_sessions)
		$returned_array = merge_and_average($returned_array);

	return $returned_array;
}

//CC DE UNA ASIGNATURA POR USUARIOS
//Devuelve un array con los coeficientes de familias de todos los usuarios de la asignatura
//Tiene un segundo parámetro opcional para indicar las sesiones concretas dentro de la asignatura,
//Si no se pasa, coge todas las sesiones de la asignatura
function get_cc_users_family_in_subject($subject_table, $subject_id, $consider_visible = true){
	$total_users = get_subject_users($subject_table, $consider_visible);
	
	$returned_array = array();
	$returned_array['total_users'] = 0;
	$returned_array['active_users'] = array();
	foreach ($total_users as $user) {
		$aux = get_cc_user_subject($user, $subject_table, $subject_id, true, $consider_visible);
		if(has_participated($user, $aux)){
			$total = array_sum($aux);
			$returned_array['active_users'][$user] = $aux;
			$returned_array['active_users'][$user]['total'] = $total;	
		}
		$returned_array['total_users']++;
	}
	return $returned_array;
}


//AUX
function merge_and_average($array){
	$merged_array = array();
	$number_of_sessions = array();
	
	//recorremos el array principal
	foreach ($array as $primary_id=>$subArray) {
		//recorro el subarray
		foreach ($subArray as $secondary_id=>$value) {
			//si hubo errroes
			if($value != -1){
				//incremento y cuento esa sesion como valida para hacer media
				$merged_array[$secondary_id]+=$value;
				$number_of_sessions[$secondary_id] += 1;
			}else{
				$merged_array[$secondary_id]+=0;
				//$number_of_sessions[$secondary_id] += 1;
			}
		}
	}

	//recorro el array de mezclas
	foreach ($merged_array as $id => $value) {
		//hacemos media y redondeamos
		$merged_array[$id] /= $number_of_sessions[$id];
		$merged_array[$id] = ceil($merged_array[$id]);
	}

	//ordenamos
	ksort($merged_array);

	return $merged_array;
}

?>