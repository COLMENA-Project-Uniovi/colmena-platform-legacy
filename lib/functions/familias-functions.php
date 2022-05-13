<?php

/* FAMILIAS */

function get_familias(){
	$link = connect();

	$query = sprintf("SELECT id, name_es, name_en FROM colmena_family WHERE visible = 1");

	$result = $link->query($query);

	if($result -> num_rows == 0){
		return false;
	}

	
	$returned_array = array();

	while($row = $result->fetch_assoc()){
		$returned_array[$row['id']] = $row;
	}
	
	return $returned_array;
}

function get_familia($id){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_family					  
					  WHERE id = '%s'
					  LIMIT 1",
		$link->real_escape_string($id)
	);

	$result = $link->query($query);

	if($result -> num_rows == 0){
		return false;
	}

	$row = $result->fetch_assoc();
	
	return $row;
}

function get_nombres_familias(){
	global $lang_sufix;
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_family
					  WHERE visible = 1");

	$result = $link->query($query);

	if($result -> num_rows == 0){
		return false;
	}

	
	$returned_array = array();

	while($row = $result->fetch_assoc()){
		$returned_array[$row['id']] = $row['name'. $lang_sufix];
	}


	return $returned_array;
}


function get_todos_errores_familia($familia){
	$link = connect();

	$query = sprintf("SELECT *
						FROM colmena_error ce
						WHERE  ce.first_family = '%s'",
		$link->real_escape_string($familia)
	);
	 
	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc()){
		array_push($returned_array, $row);
	}
	
	return $returned_array;
}

/*
	LAS DIFERENTES FAMILIAS DE ERROR QUE TIENE UN USUARIO EN UNA ASIGNATURA
	Utilizada en users-template.php
*/	
function get_family_errors_user_subject($id_usuario, $subject_table){
	$link = connect();

	$query = sprintf("SELECT count(*) as total, first_family
						FROM %s cm, colmena_error ce
						WHERE cm.error_id = ce.error_id										
						AND cm.user_id = '%s'
						AND cm.active = 1
						GROUP BY ce.first_family",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($id_usuario)
	);				
	 
	$result = $link->query($query);
	$returned_array = array();
	$total = 0;
	while($row = $result->fetch_assoc()){
		$total += $row['total'];
		$returned_array[$row['first_family']] = $row['total'];
	}
	$returned_array['total'] = $total;

	return $returned_array;
}
/*
	FUNCIÓN AUXILIAR QUE DEVUELVE EL NÚMERO DISTINTO DE ERRORES DETECTADOS PARA UNA FAMILIA
	Utilizada por get_all_family_errors() y get_family_errors()
*/
function get_distinct_errors_family($subject_table, $family_id = null){
	$link = connect();

	$extra = '';
	if($family_id != null)
		$extra = "AND ce.first_family = " . $family_id;

	$query = sprintf("SELECT cm.error_id, 
							 ce.first_family, 
							 count(cm.error_id) as total, 
							 ce.name, 
							 ce.message,
							 cm.gender, 
							 count(distinct cm.user_id) as num_users,
							 1 as num_subjects
						FROM %s cm, colmena_error ce
						WHERE cm.error_id = ce.error_id
						AND cm.active = 1
						%s
						GROUP BY (cm.error_id)",
		$link->real_escape_string($subject_table),
		$extra
	);
	 
	$result = $link->query($query);
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		if(!isset($returned_array[$row['first_family']]))
			$returned_array[$row['first_family']] = array();
		$returned_array[$row['first_family']][$row['error_id']] = $row;
	}

	return $returned_array;
}
/*
	FUNCIÓN AUXILIAR QUE DEVUELVE EL NÚMERO DISTINTO DE ERRORES DETECTADOS PARA UNA FAMILIA
	POR TODOS LOS ALUMNOS Y POR UNO EN CONCRETO
	Utilizada por get_family_errors()
*/
function get_distinct_errors_family_from_user($subject_table, $id_user, $family_id = null){
	$link = connect();

	$extra = '';
	if($family_id != null)
		$extra = "AND ce.first_family = " . $family_id;

	$query = sprintf("SELECT cm.error_id, 
							 ce.first_family, 
							 count(cm.error_id) as total, 
							 ce.name, 
							 ce.message,
							 cm.gender
						FROM %s cm, colmena_error ce
						WHERE cm.error_id = ce.error_id
						AND cm.active = 1
						%s
						GROUP BY (cm.error_id)",
		$link->real_escape_string($subject_table),
		$extra
	);
	 
	$result = $link->query($query);
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		if(!isset($returned_array[$row['first_family']]))
			$returned_array[$row['first_family']] = array();
		$returned_array[$row['first_family']][$row['error_id']] = $row;
		$returned_array[$row['first_family']][$row['error_id']]['total_user'] = 0;
	}

	$query_user = sprintf("SELECT cm.error_id, 
							 ce.first_family, 
							 count(cm.error_id) as total
						FROM %s cm, colmena_error ce
						WHERE cm.error_id = ce.error_id
						AND cm.active = 1
						AND cm.user_id = '%s'
						%s
						GROUP BY (cm.error_id)",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($id_user),
		$extra
	);
	 
	$result_user = $link->query($query_user);

	while($row = $result_user->fetch_assoc()){
		$returned_array[$row['first_family']][$row['error_id']]['total_user'] = $row['total'];
	}
	return $returned_array;
}

/*
	SACAMOS LOS DISTINTOS ERRORES POR FAMILIAS DE LAS ASIGNATURAS DEL USUARIO
	Utilizada en families.php
*/
function get_all_family_errors($id_user = null){
	if($id_user == null)
		$subjects = get_all_subjects();
	else
		$subjects = get_asignaturas_por_usuario($id_user);

	$returned_array = array();
	foreach ($subjects as $key => $value) {
		$errors = get_distinct_errors_family($value['table_name']);
		
		foreach ($errors as $id => $error) {
			if(!isset($returned_array[$id])){
				$returned_array[$id] = $error;
				continue;
			} else{
				foreach ($error as $id_error => $value) {
					if(!isset($returned_array[$id][$id_error])){
						$returned_array[$id][$id_error] = $value;
						continue;
					} else{
						$returned_array[$id][$id_error]['total'] += $value['total'];
					}
				}
			}

		}

	}
	return $returned_array;
}

/*
	SACAMOS LOS DISTINTOS ERRORES DE UNA FAMILIA DE LAS ASIGNATURAS DEL USUARIO
	Utilizada en families-template.php
*/
function get_family_errors($family_id, $id_user = null, $is_student = false){
	if($id_user == null)
		$subjects = get_all_subjects();
	else
		$subjects = get_asignaturas_por_usuario($id_user);

	foreach ($subjects as $key => $value) {
		if ($is_student) 
			$errors = get_distinct_errors_family_from_user($value['table_name'], $id_user, $family_id);
		else
			$errors = get_distinct_errors_family($value['table_name'], $family_id);

		foreach ($errors as $id => $error) {
			if(!isset($returned_array)){
				$returned_array = $error;
				continue;
			} else{
				foreach ($error as $id_error => $value) {
					if(!isset($returned_array[$id_error])){
						$returned_array[$id_error] = $value;
						continue;
					} else{
						$returned_array[$id_error]['total'] += $value['total'];
						$returned_array[$id_error]['num_users'] += $value['num_users'];
						$returned_array[$id_error]['num_subjects'] += 1;
						if($is_student)
							$returned_array[$id_error]['total_user'] += $value['total_user'];
					}
				}
			}

		}

	}
	return $returned_array;
}




?>