<?php

/* ERRORES */
function get_error($error_id){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_error					  
					  WHERE error_id = '%s'
					  LIMIT 1",
		$link->real_escape_string($error_id)
	);

	$result = $link->query($query);

	if($result -> num_rows == 0){
		return false;
	}

	$row = $result->fetch_assoc();
	
	return $row;
}

function edit_error($error){
	$link = connect();

	$query = sprintf("UPDATE colmena_error					  
					  set problem_reason = '%s',
					   reference = '%s'
					  WHERE error_id = '%s'
					 ",
		$link->real_escape_string($error['problem_reason']),
		$link->real_escape_string($error['references']),
		$link->real_escape_string($error['error_id'])
	);

	$result = $link->query($query);

	return $result;
}

/* EJEMPLOS DE ERROR */


/*
	Utilizada en errors-template.php
*/
function get_ejemplos_error($error_id){
	$link = connect();

	$query = sprintf("SELECT cee.*
				      FROM colmena_error_examples cee
				      WHERE cee.error_id = '%s'
				     ",
	$link->real_escape_string($error_id)
	);

	$result = $link->query($query);
	$returned_array = array();
	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);
	return $returned_array;
}
function get_ejemplo($ejemplo_id){
	$link = connect();

	$query = sprintf("SELECT cee.*
				      FROM colmena_error_examples cee
				      WHERE cee.id = '%s'
				      LIMIT 1",
	$link->real_escape_string($ejemplo_id)
	);

	$result = $link->query($query);
	
	$row = $result->fetch_assoc();		
	return $row;
}

function update_ejemplo_error($example){
	$link = connect();

	$pre_query = "UPDATE colmena_error_examples SET ";
	$flag = true;
	foreach ($example as $key => $value) {
		if(!$flag)
			$pre_query .= ', ';
		$pre_query .= $key." = '".$value."'";
		$flag = false;
	}
	$pre_query .= "WHERE id = '".$example['id']."'";	
	$query = sprintf($pre_query);
	
	return $link->query($query);
}

function insert_ejemplo_error($example){
	$link = connect();
	
	$query = sprintf("INSERT INTO colmena_error_examples (error_id, start_line_wrong, end_line_wrong, source_code_wrong, start_line_right, end_line_right, source_code_right, explanation, solution, reference, user_id) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
		$link -> real_escape_string($example['error_id']),
		$link -> real_escape_string($example['start_line_wrong']),
		$link -> real_escape_string($example['end_line_wrong']),
		$link -> real_escape_string($example['source_code_wrong']),
		$link -> real_escape_string($example['start_line_right']),
		$link -> real_escape_string($example['end_line_right']),
		$link -> real_escape_string($example['source_code_right']),
		$link -> real_escape_string($example['explanation']),
		$link -> real_escape_string($example['solution']),
		$link -> real_escape_string($example['references']),
		$link -> real_escape_string($example['user_id'])


	);

	return $link->query($query);
}


/* ------------------------- COLMENA 2014 ------------------------- *()
/* ------- FUNCTIONS WITH MARKERS IN DIFFERENT SUBJECTS TABLES ------- */


/*
* DEVUELVE LOS TIPOS DE ERROR DE UN USUARIO ORDENADOS DE MÁS COMÚN A MENOS
* RECIBE EL ID DEL USUARIO Y LAS TABLAS DE LAS ASIGNATURAS DE LAS QUE QUEREMOS SACAR LOS ERRORES
* Utilizada en users-template.php
*/
function get_most_usual_error_for_user($user_id, $subjects_tables, $sortby = 'total', $desc = true){
	$link = connect();
	$array_errors = array();
	$total_errors = 0;
	foreach ($subjects_tables as $key => $value) {
		$query = sprintf("SELECT t.error_id, e.name, e.first_family, e.message, t.gender, count(t.error_id) as total
						FROM %s t, colmena_error e
						WHERE user_id = '%s'
						AND t.error_id = e.error_id
						GROUP BY t.error_id
						ORDER BY count(t.error_id) desc",
						$link->real_escape_string($value),
						$user_id
		);

		$result = $link->query($query);
		while ($row = $result->fetch_assoc()) {
			if(!isset($array_errors[$row['error_id']])){
				$array_errors[$row['error_id']]['total'] = 0;
				$array_errors[$row['error_id']]['name'] = $row['name'];
				$array_errors[$row['error_id']]['first_family'] = $row['first_family'];
				$array_errors[$row['error_id']]['message'] = $row['message'];
				$array_errors[$row['error_id']]['id'] = $row['error_id'];
				$array_errors[$row['error_id']]['gender'] = $row['gender'];
			}
			$array_errors[$row['error_id']]['total'] += $row['total'];
			$array_errors[$row['error_id']]['subject-'.$key] = $row['total'];
			$total_errors += $row['total'];
		}
	}

	function sortByDesc($key) {
	    return function ($a, $b) use ($key) {	        
	        if ($a[$key] == $b[$key]) {
		        return 0;
		    }
	    	return ($a[$key] > $b[$key]) ? -1 : 1;
	    };
	}
	function sortByAsc($key) {
	    return function ($a, $b) use ($key) {	        
	        if ($a[$key] == $b[$key]) {
		        return 0;
		    }
	    	return ($a[$key] < $b[$key]) ? -1 : 1;
	    };
	}
	if($desc)
		usort($array_errors, sortByDesc($sortby));
	else
		usort($array_errors, sortByAsc($sortby));


	$array_errors['total'] = $total_errors;

	return $array_errors;
}


/*
	CANTIDAD TOTAL DE ERRORES POR UNA ASIGNATURA (SIN SEPARAR POR TIPO DE ERROR)
	Utilizada en subjects.php
*/	
function get_errores_totales_asignatura($subject_id,$subject_table){
	$link = connect();

	$query = sprintf("SELECT count(*) as totales
					  FROM %s cm, colmena_session cs
					  WHERE cm.session_id = cs.id
					  AND cm.active = '1'
					  AND cs.subject_id = '%s'",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($subject_id)
	);
	
	$result = $link->query($query);
	
	$row = $result->fetch_assoc();
	
	return $row['totales'];
}

/*
	CANTIDAD TOTAL DE COMPILACIONES POR UNA ASIGNATURA (SIN SEPARAR POR TIPO DE ERROR)
	Utilizada en subjects.php
*/	
function get_compilaciones_totales_asignatura($subject_id,$subject_table){
	$subject_table = explode('_', $subject_table);
	array_shift($subject_table);
	array_shift($subject_table);
	$subject_table = implode('_', $subject_table);	
	$link = connect();

	$query = sprintf("SELECT count(*) as totales
					  FROM colmena_compilations_%s cm, colmena_session cs
					  WHERE cm.session_id = cs.id
					  AND cs.subject_id = '%s'",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($subject_id)
	);
	
	$result = $link->query($query);
	
	$row = $result->fetch_assoc();
	
	return $row['totales'];
}


/*
	CANTIDAD TOTAL DE ERRORES POR UNA SESIÓN (SIN SEPARAR POR TIPO DE ERROR)
	Utilizada en subjects-session.php
*/	
function get_errores_totales_sesion($session_id, $subject_table){
	$link = connect();

	$query = sprintf("SELECT *
					  FROM %s cm
					  WHERE cm.session_id = '%s'
					  AND cm.active = '1'
					  ORDER BY timestamp",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($session_id)
	);

	$result = $link->query($query);

	$total = $result->num_rows;

	$returned_array = array();
	$returned_array['total'] = $total;
	while($row = $result->fetch_assoc()){
		$date = date('d-m-Y', strtotime($row['timestamp']));
		$hour = date('H:i', strtotime($row['timestamp']));		
		if(!isset($returned_array[$date]))
			$returned_array[$date] = array();		
		if(!isset($returned_array[$date][$hour]))
			$returned_array[$date][$hour] = 0;		

		$returned_array[$date][$hour]++;
	}
	
	return $returned_array;
}



// ERRORES GENERALES SIN SEPARAR POR FAMILIA

/*
	POR SESION INDEPENDIENTEMENTE DEL USUARIO
	Utilizada por subjects-session.php, users-template.php
*/	
function get_errores_sesion($sesion_id, $subject_table, $limit){
	$link = connect();

	$query = sprintf("SELECT count(cm.error_id) as totales, cm.custom_message,ce.message, cm.error_id, ce.first_family, cm.gender
						FROM colmena_error ce, %s cm
						WHERE ce.error_id = cm.error_id
						AND cm.session_id = '%s'
					  	AND cm.active = '1'
						GROUP by cm.error_id
						ORDER BY totales desc
						LIMIT %s",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($sesion_id),
		$link->real_escape_string($limit)
	);
	$result = $link->query($query);
	$returned_array = array();
	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);

	return $returned_array;
}
/*
	POR ASIGNATURA INDEPENDIENTEMENTE DEL USUARIO
	Utilizada en subjects.php
*/	
function get_errores_asignatura($subject_id, $subject_table, $limit){
	$link = connect();

	$query = sprintf("SELECT count(cm.error_id) as totales, cm.custom_message, ce.message, cm.error_id, ce.first_family, cm.gender
						FROM colmena_error ce, %s cm, colmena_session cs
						WHERE ce.error_id = cm.error_id
						AND cs.id = cm.session_id
						AND cs.subject_id = '%s'
					 	AND cm.active = '1'
						GROUP by cm.error_id
						ORDER BY totales desc
						LIMIT %s",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($subject_id),
		$link->real_escape_string($limit)
	);

	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);

	return $returned_array;
}


// ERRORES PARA UN USUARIO
/*
	ERRORES DE UN USUARIO EN UNA ASIGNATURA AGRUPADOS POR SESSIÓN
	Utilizada en user-in-subject-template.php
*/	
function get_errors_user_subject_group_by_session($user_id, $subject_table){
	$link = connect();

	$query = sprintf("SELECT count(*) as total, cm.session_id
						FROM %s cm
						WHERE cm.active = '1'
						AND cm.user_id = '%s'
						GROUP by cm.session_id
						ORDER BY cm.session_id",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($user_id)
	);

	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc())
		$returned_array[$row['session_id']] = $row;

	return $returned_array;
}

/*
	MEDIA DE ERRORES DE LOS USUARIOS EN UNA ASIGNATURA AGRUPADOS POR SESSIÓN
	Utilizada en user-in-subject-template.php
*/	
function get_average_errors_group_by_session($subject_table){
	$link = connect();

	$query = sprintf("SELECT count(*) as total, cm.session_id, count(distinct cm.user_id) as total_users
						FROM %s cm
						WHERE cm.active = '1'
						GROUP by cm.session_id
						ORDER BY cm.session_id",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($user_id)
	);

	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc())
		$returned_array[$row['session_id']] = $row['total']/$row['total_users'];

	return $returned_array;
}


// DATOS DE UN ERROR
/*
	DEVUELVE UN ARRAY CON ESTADISITICAS DE UN ERROR: NÚMERO DE VECES QUE APARECE, 
	USUARIOS DISTINTOS QUE LO TIENEN Y ASIGNATURAS DIFERENTES DONDE APARECE.
	Utilizada en errors-template.php
*/
function get_data_error($error_id, $id_user = null, $is_student = false){
	if($id_user == null)
		$subjects = get_all_subjects();
	else
		$subjects = get_asignaturas_por_usuario($id_user);

	foreach ($subjects as $key => $value) {
		if ($is_student)
			$data = get_data_error_from_subject_from_user($value['table_name'], $error_id, $id_user);
		else
			$data = get_data_error_from_subject($value['table_name'], $error_id);

		if(!isset($returned_array)){
			if( $data['total'] != 0 ){
				$returned_array = $data;
				$returned_array['num_subjects'] = 1;
				if($is_student)
					$returned_array['total_user'] = $data['total_user'];
			}
			continue;
		} else{
			
			if( $data['total'] != 0 ){
				$returned_array['total'] += $data['total'];
				$returned_array['num_users'] += $data['num_users'];
				$returned_array['num_subjects'] += 1;
				if($is_student)
					$returned_array['total_user'] += $data['total_user'];
			} 
				continue;
		}
	}
	return $returned_array;
}

/*
	FUNCIÓN AUXILIAR QUE DEVUELVE EL NÚMERO DISTINTO DE ERRORES DETECTADOS PARA UNA FAMILIA
	Utilizada por get_all_family_errors()
*/
function get_data_error_from_subject($subject_table, $error_id){
	$link = connect();

	$query = sprintf("SELECT count(*) as total, count(distinct user_id) as num_users
						FROM %s
						WHERE active = 1
						AND error_id = '%s'",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($error_id)
	);
	 
	$result = $link->query($query);
	
	$returned_array = $result->fetch_assoc();	

	return $returned_array;
}
/*
	FUNCIÓN AUXILIAR QUE DEVUELVE EL NÚMERO DE VECES DE QUE UN ERROR HA SIDO DETECTADO
	PARA TODOS LOS USUARIOS Y PARA PARA UN USUARIO CONCRETO
	Utilizada por get_all_family_errors()
*/
function get_data_error_from_subject_from_user($subject_table, $error_id, $id_user){
	$link = connect();

	$query = sprintf("SELECT count(*) as total
						FROM %s
						WHERE active = 1
						AND error_id = '%s'",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($error_id)
	);
	 
	$result = $link->query($query);
	
	$returned_array = $result->fetch_assoc();

	$query_user = sprintf("SELECT count(*) as total
						FROM %s
						WHERE active = 1
						AND error_id = '%s'
						AND user_id = '%s'",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($error_id),
		$link->real_escape_string($id_user)
	);

	$result_user = $link->query($query_user);

	$row = $result_user->fetch_assoc();

	$returned_array['total_user'] = $row['total'];


	return $returned_array;
}

/*
	DEVUELVE EL TOP DE ERRORES EN UNA ASIGNATURA. SI SE LE INDICA UN USUARIO, DEVUELVE EL TOP DE ESE USUARIO
	Utilizada en user-in-subject-template.php
*/
function get_top_errors_in_session($subject_table, $session_id, $user = null){
	$extra_query = '';
	if($user != null){
		$extra_query = "AND user_id = '$user'";
	}
	$link = connect();

	$query = sprintf("SELECT t.error_id, t.gender, e.name, count(*) as total
						FROM %s t, colmena_error e
						WHERE t.active = 1
						AND t.session_id = '%s'
						AND e.error_id = t.error_id
						%s
						GROUP BY t.error_id
						ORDER BY count(*) DESC
						LIMIT 5",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($session_id),
		$extra_query
	);

	$result = $link->query($query);
	
	$returned_array = array();

	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);

	return $returned_array;
}


/*
	FUNCIÓN AUXILIAR QUE DEVUELTE EL TOP $limit DE MENSAJES DE EJEMPLO MÁS FRECUENTES
	DE UN TIPO DE ERROR PARA UN USUARIO EN UNA ASIGNATURA
	Utilizada en errors-template.php
*/
function get_examples_error_user_in_subject($error_id, $subject_table, $limit = null, $user_id = null){
	$extra_limit = '';
	$extra_user = '';
	if($limit != null)
		$extra_limit = "LIMIT $limit";
	if($user_id != null)
		$extra_user = "AND user_id = '$user_id'";
	$link = connect();

	$query = sprintf("SELECT count(*) as total, custom_message
						FROM %s
						WHERE active = 1
						AND error_id = '%s'
						%s
						GROUP BY custom_message
						ORDER BY count(*) DESC
						%s",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($error_id),
		$extra_user,
		$extra_limit
	);
	 
	$result = $link->query($query);
	
	$returned_array = array();

	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);

	return $returned_array;
}
/*
	DEVUELTE EL TOP $limit DE MENSAJES DE EJEMPLO MÁS FRECUENTES DE UN TIPO DE ERROR PARA UN USUARIO
	Utilizada en errors-template.php
*/
function get_examples_error_user($error_id, $user_id, $limit = null, $is_teacher = false){
	$subjects = get_asignaturas_por_usuario($user_id);
	$examples = array();
	foreach ($subjects as $key => $value) {
		if($is_teacher)
			$aux = get_examples_error_user_in_subject($error_id, $value['table_name'], $limit);
		else
			$aux = get_examples_error_user_in_subject($error_id, $value['table_name'], $limit, $user_id);
		if(!empty($aux)){
			$examples[$key] = $value;
			$examples[$key]['examples'] = $aux;
		}
	}

	return $examples;
}

/*
	DEVUELVE LAS COMPILACIONES DE UN USUARIO EN UNA SESIÓN ORDENADAS POR TIMESTAMP
	Utilizada en user-in-subject-template.php
*/
function get_user_compilations_in_session($user_id, $session_id, $subject_table){
	$subject_table = explode('_', $subject_table);
	array_shift($subject_table);
	array_shift($subject_table);
	$subject_table = implode('_', $subject_table);	
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_compilations_%s					  
					  WHERE user_id = '%s'
					  AND session_id = '%s'
					  ORDER BY timestamp ASC",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($user_id),
		$link->real_escape_string($session_id)
	);	

	$result = $link->query($query);
	$returned_array = array();
	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);
	return $returned_array;
}

?>