<?php
/* PRACTICAS DENTRO DE UNA ASIGNATURA */

function get_sesiones_asignatura($id, $consider_visible = true){
	$link = connect();

	if($consider_visible){
		$query = sprintf("SELECT * FROM colmena_session
						  WHERE subject_id = '%s'
						  AND visible = 1
						  ORDER BY week ASC",
			$link->real_escape_string($id)
		);	
	}else{

		$query = sprintf("SELECT * FROM colmena_session
						  WHERE subject_id = '%s'
						  ORDER BY week ASC",
			$link->real_escape_string($id)
		);
	}

	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc()){
		array_push($returned_array, $row);
	}
	
	return $returned_array;
}

function get_sesion($id){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_session
					  WHERE id = '%s'
					  LIMIT 1",
		$link->real_escape_string($id)
	);

	$result = $link->query($query);

	if($result -> num_rows == 0){
		return false;
	}
	
	return $result->fetch_assoc();
}
function get_nombre_sesion($id){
	global $lang_sufix;
	$link = connect();

	$query = sprintf("SELECT session_name_es, session_name_en FROM colmena_session
					  WHERE id = '%s'
					  LIMIT 1",
		$link->real_escape_string($id)
	);

	$result = $link->query($query);

	$row = $result->fetch_assoc();
	
	return $row['session_name'. $lang_sufix];
}



// ---------------------------- NUEVAS FUNCIONES ----------------------------

/*
	DEVUELVE LOS DATOS DE LA ASIGNATURA A LA QUE PERTENECE UNA SESIÓN
	Usado por get_data_sessions()
*/
function get_subject_from_session($session_id){
	$link = connect();

	$query = sprintf("SELECT su.* FROM colmena_session se, colmena_subject su
					  WHERE se.id = '%s'
					  AND se.subject_id = su.id
					  LIMIT 1",
		$link->real_escape_string($session_id)
	);

	$result = $link->query($query);
	
	$row = $result->fetch_assoc();
	
	return $row;
}

/*
	NÚMERO DE ERRORES Y WARNINGS POR SESIÓN
	Utilizada en subjects-session.php
*/
function get_num_markers_by_session($session_id, $subject_table){
	$link = connect();

	$query = sprintf("SELECT ce.first_family, cm.gender, count(cm.GENDER) as total
					  FROM %s cm, colmena_error ce
					  WHERE ce.error_id = cm.error_id
					  AND cm.active = '1'
					  AND cm.session_id = '%s'					  
					  GROUP BY cm.GENDER, ce.first_family",					  
					  $link->real_escape_string($subject_table),	
					  $link->real_escape_string($session_id));		

	$result = $link->query($query);

	if ($result->num_rows == 0) {
		return 0;
	}
	$returned_array = array();
	$aux_array = array('WARNING' => 0, 'ERROR' => 0);
	$total = 0;
	while($row = $result->fetch_assoc()){
		$total += $row['total'];
		$returned_array[$row['first_family']][$row['gender']] = $row['total'];
		$aux_array[$row['gender']] += $row['total'];
	}
	$returned_array['total'] = $total;
	$returned_array['total_warnings'] = $aux_array['WARNING'];
	$returned_array['total_errors'] = $aux_array['ERROR'];
	
	return $returned_array;
}

/*
	NÚMERO DE ERRORES DIFERENTES DE CADA FAMILIA PARA UNA SESIÓN
	Función auxiliar utilizada por get_data_sessions()
*/
function get_family_errors_by_session($session_id, $subject_table){
	$link = connect();

	$query = sprintf("SELECT ce.first_family, cs.*, count(ce.first_family) as total
					  FROM %s cm, colmena_error ce, colmena_session cs
					  WHERE ce.error_id = cm.error_id
					  AND cm.session_id = cs.id
					  AND cm.active = '1'
					  AND cm.session_id = '%s'					  
					  GROUP BY ce.first_family",					  
					  $link->real_escape_string($subject_table),	
					  $link->real_escape_string($session_id));		

	$result = $link->query($query);

	if ($result->num_rows == 0) {
		return 0;
	}
	$returned_array = array();
	$total = 0;
	while($row = $result->fetch_assoc()){
		$total += $row['total'];
		$returned_array['session-data'] = $row;
		$returned_array['errors'][$row['first_family']] = $row['total'];
	}
	$returned_array['total'] = $total;
	
	return $returned_array;
}

/*
	RECUPERA INFORMACIÓN DE VARIAS SESIONES QUE SE PASA SUS IDS EN EL ARRAY $SESSIONS_IDS
	Utilizada en subjects-multiple-sessions.php
*/
function get_data_sessions($sessions_ids){
	$returned_array = array();
	
	foreach ($sessions_ids as $key => $id) {
		$subject = get_subject_from_session($id);		
		$aux = get_family_errors_by_session($id, $subject['table_name']);
		$aux['subject'] = $subject;
		$aux_users = get_session_users($subject['table_name'], $id);
		$aux['users'] = $aux_users;
		$returned_array[$id] = $aux;
	}
	return $returned_array;	
}
?>