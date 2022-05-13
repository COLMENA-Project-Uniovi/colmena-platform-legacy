<?php


/* USUARIOS */

/*
	USUARIOS POR ID
	Utilizada en users-template.php
	Update : 20150121
*/	
function get_user($id){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_user
					  WHERE id = '%s'
					  AND active = '1'",
		$link->real_escape_string($id)
	);

	$result = $link->query($query);
	$row = $result->fetch_assoc();

	return $row;
}

/*
	USUARIOS DE UNA SESION DE UNA ASIGNATURA
	Utilizada en users-template.php 
	Update : 20150121
*/
//20150121 : Este es el utilizado para el CC y creo que menos obsoleto que el anterior
function get_session_users($table_name, $session_id, $consider_visible = true){
	$link = connect();

	if($consider_visible){
		$query = sprintf("SELECT distinct cm.user_id FROM
					  %s cm
					  WHERE cm.session_id = '%s'
					  AND active = '1'
					  ",
		$link->real_escape_string($table_name),
		$link->real_escape_string($session_id)
		);

	}else{
		$query = sprintf("SELECT distinct cm.user_id FROM
					  %s cm
					  WHERE cm.session_id = '%s'
					  ",
		$link->real_escape_string($table_name),
		$link->real_escape_string($session_id)
		);
	}
	
	$result = $link->query($query);
	$returned_array = array();

	while ($row = $result->fetch_assoc())
		array_push($returned_array, $row['user_id']);

	return $returned_array;
}

/*
	
	USUARIOS DE UNA ASIGNATURA
	Utilizado en users.php

*/

// 20150121 : Usado para el CC, puede que sustituya a los anteriores por deprecated
function get_subject_users($table_name, $consider_visible = true){
	$link = connect();
	
	if($consider_visible){
		$query = sprintf("SELECT distinct cus.user_id
					  FROM colmena_user_subject cus , colmena_subject cs, colmena_user cu
					  WHERE cus.subject_id = cs.id
					  AND cu.id = cus.user_id
					  AND cu.active = '1'
					  AND cs.table_name = '%s'",
		$link->real_escape_string($table_name)
		);
	}else{
		$query = sprintf("SELECT distinct cus.user_id
					  FROM colmena_user_subject_pre cus , colmena_subject cs, colmena_user cu
					  WHERE cus.subject_id = cs.id
					  AND cu.id = cus.user_id
					  AND cs.table_name = '%s'",
		$link->real_escape_string($table_name)
		);
	}
	
	
	$result = $link->query($query);

	$returned_array = array();

	while ($row = $result->fetch_assoc())
		array_push($returned_array, $row['user_id']);

	return $returned_array;
}

/*
	DEVUELVE CADA USUARIO DE UNA ASIGNATURA CON LOS ERRORES POR CADA FAMILIA
	EL SEGUNDO PARÁMETRO ES OPCIONAL Y ES UN ARRAY DE IDS DE SESIONES
	Utilizada en subjects.php, users-template.php, subjects-multiple-sessions.php
*/
function get_usuarios_asignatura_family($subject_table, $sessions_id = null){
	$extra = '';
	if($sessions_id != null){
		array_walk($sessions_id, function(&$value, $key) { $value = "cm.session_id = '$value'"; });
		$extra = implode(' OR ', $sessions_id);
		$extra = "AND ($extra)";
	}
	
	$link = connect();
	$query = sprintf("SELECT user_id, count(*) as total, ce.first_family 
		FROM %s cm, colmena_error ce 			
		WHERE ce.error_id = cm.error_id
		AND cm.active = '1'
		%s
		GROUP BY cm.user_id, ce.first_family",
		$link->real_escape_string($subject_table),
		$extra		
	);

	$result = $link->query($query);

	if ($result->num_rows == 0) {
		return 0;
	}
	$returned_array = array();

	while($row = $result->fetch_assoc()){
		if(isset($returned_array[$row['user_id']]['total']))
			$returned_array[$row['user_id']]['total'] += $row['total'];
		else
			$returned_array[$row['user_id']]['total'] = $row['total'];
		$returned_array[$row['user_id']][$row['first_family']] = $row['total'];
	}

	return $returned_array;
}

/*
	DEVUELVE TODOS LOS USUARIOS DE UNA ASIGNATURA ORDENADOS POR CANTIDAD DE ERRORES
	Utilizada en users-template.php
*/
function get_all_usuarios_total_errors_asignatura($subject_table){
	$link = connect();
	$query = sprintf("SELECT user_id, count(user_id) as total
		FROM %s cm
		WHERE active = 1
		GROUP BY user_id
		ORDER BY count(user_id) ASC",
		$link->real_escape_string($subject_table)		
	);

	$result = $link->query($query);

	if ($result->num_rows == 0) {
		return 0;
	}
	$returned_array = array();

	while($row = $result->fetch_assoc()){
		array_push($returned_array, $row['user_id']);
	}

	return $returned_array;
}

/*
	Utilizada en subjects-session.php
*/
function get_usuarios_session_family($session_id, $subject_table){
	$link = connect();
	$query = sprintf("SELECT user_id, count(*) as total, ce.first_family 
		FROM %s cm, colmena_error ce 
		WHERE cm.session_id = '%s'
		AND cm.active = 1			
		AND ce.error_id = cm.error_id 
		GROUP BY cm.user_id, ce.first_family",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($session_id)
	);		

	$result = $link->query($query);

	if ($result->num_rows == 0) {
		return 0;
	}
	$returned_array = array();

	while($row = $result->fetch_assoc()){
		if(isset($returned_array[$row['user_id']]['total']))
			$returned_array[$row['user_id']]['total'] += $row['total'];
		else
			$returned_array[$row['user_id']]['total'] = $row['total'];
		$returned_array[$row['user_id']][$row['first_family']] = $row['total'];
	}

	return $returned_array;
}

function get_timestamp_usuario($id_usuario){
	$link = connect();
	$query = sprintf("SELECT max(timestamp) as timestamp from(
						SELECT user_id,timestamp FROM colmena_marker cm, colmena_session cs 
					 	WHERE cm.session_id = cs.id
					  	AND cm.active = '1'
					  	AND user_id = '%s'
					)as MyTable
					ORDER BY timestamp DESC",
		$link->real_escape_string($id_usuario)
	);

	$result = $link->query($query);

	$row = $result->fetch_assoc();
	
	return $row['timestamp'];
}

/*
	USUARIOS QUE HAN PARTICIPADO EN AL MENOS $LIMITE DE SESIONES
	Utilizada en subjects-multiple-sessions.php	
*/	
function get_usuarios_participantes_sesion_limite($sessions, $limite){
	$link = connect();
	$sessions_statement = join($sessions, ' OR cs.id =');
	$query = sprintf("SELECT user_id, count(user_id) as apariciones
						FROM 

						(
						SELECT cm.user_id, cs.session_name_en, cs.session_name_es
						FROM colmena_marker cm, colmena_session cs 
						WHERE cm.session_id = cs.id
					    AND cm.active = '1'
					 	AND (cs.id = %s )
						GROUP BY cm.user_id, cs.session_name_en, cs.session_name_es

						) AS usuarios_sesiones

						GROUP BY user_id
						HAVING apariciones >= %s
						ORDER BY apariciones asc",
		$link->real_escape_string($sessions_statement),
		$link->real_escape_string($limite)
	);

	$result = $link->query($query);
	$returned_array = array();

	while ($row = $result->fetch_assoc()) 
		array_push($returned_array, $row);

	return $returned_array;
}

/*
	Utilizada sidebar-session.php
*/	
function get_usuarios_mas_errores_sesion($session_id, $limit){
	$link = connect();

	$query = sprintf("SELECT user_id, count(*) as totales FROM colmena_marker cm
					  WHERE cm.session_id = '%s'
					  AND cm.active = '1'
					  GROUP BY user_id
					  ORDER BY totales desc
					  LIMIT %s",
		$link->real_escape_string($session_id),
		$link->real_escape_string($limit)
	);
	$result = $link->query($query);
	$returned_array = array();

	while ($row = $result->fetch_assoc()) 
		array_push($returned_array, $row);

	return $returned_array;
}


function get_usuarios_mas_errores_asignatura($subject_id, $limit){
	$link = connect();

	$query = sprintf("SELECT user_id, count(*) as totales FROM colmena_marker cm, colmena_session cs
					  WHERE cm.session_id = cs.id
					  AND cs.subject_id = '%s'
					  AND cm.active = '1'
					  GROUP BY user_id
					  ORDER BY totales desc
					  LIMIT %s",
		$link->real_escape_string($subject_id),
		$link->real_escape_string($limit)
	);

	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);

	return $returned_array;
}

/*
	DEVUELVE TODOS LOS ESTUDIANTES DEL SISTEMA (PARA ADMINISTRADORES)
	Utilizada en users.php
*/
function get_all_users($subject_id = null){
	$extra = '';
	if($subject_id != null){
		$extra = "AND us.subject_id = $subject_id";
	}
	$link = connect();
	$query = sprintf("SELECT u.*, us.subject_id FROM colmena_user u, colmena_user_subject us
					  WHERE u.role = 'student'
					  AND u.id = us.user_id
					  %s
					  AND u.active = '1'
					  ORDER BY u.surname, u.surname2",
					  $extra);



	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc()){
		if(!isset($returned_array[$row['id']])){
			$returned_array[$row['id']] = $row;
			$returned_array[$row['id']]['subjects'] = array();
		}
		array_push($returned_array[$row['id']]['subjects'], $row['subject_id']);			
	}

	return $returned_array;
}

/*
	DEVUELVE TODOS LOS ESTUDIANTES DE LAS ASIGNATURAS DE UN PROFESOR
	Utilizada en users.php
*/
function get_all_users_by_teacher($id){
	$subjects = get_asignaturas_por_usuario($id);
	$returned_array = array();

	foreach ($subjects as $s) {
		$aux = get_all_users($s['id']);		
		foreach ($aux as $key => $value) {
			if(!isset($returned_array[$key])){
				$returned_array[$key] = $value;
			}else{
				$returned_array[$key]['subjects'] = array_merge($returned_array[$key]['subjects'],$value['subjects']); 
			}
		}
	}

	return $returned_array;
}

/*
	DEVUELVE TODOS LOS ESTUDIANTES DEL SISTEMA QUE NO HAN SIDO NOTIFICADOS (PARA ADMINISTRADORES)
	Utilizada en admin/notify-users.php
*/
function get_all_notified_users($subject_id = null){
	$extra = '';
	if($subject_id != null){
		$extra = "AND us.subject_id = $subject_id";
	}
	$link = connect();
	$query = sprintf("SELECT u.id, u.name, u.surname, u.notified FROM colmena_user u, colmena_user_subject us
					  WHERE role = 'student'
					  AND u.id = us.user_id
					  %s
					  AND u.active = '1'",
					  $extra);	

	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc()){		
		array_push($returned_array, $row);			
	}

	return $returned_array;
}

/*
	ACTUALIZA EL VALOR DE NOTIFIED DE UN USUARIO CUANDO SE LE MANDA UN EMAIL DE BIENVENIDA
	(PARA ADMINISTRADORES)
	Utilizada en admin/send-notifications.php
*/
function set_notified_user($user_id, $value = 1){
	$link = connect();

	$query = sprintf("UPDATE colmena_user 
		SET notified = '%s' 
		WHERE id = '%s'",
		$value,
		$user_id);

	return $link->query($query);
}


?>