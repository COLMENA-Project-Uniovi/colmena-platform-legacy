<?php
/*ASIGNATURAS*/

// DEVUELVE TODAS LAS ASIGNATURAS
// Usada en users.php
function get_all_subjects(){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_subject WHERE visible = 1");

	$result = $link->query($query);
	
	$returned_array = array();

	while($row = $result->fetch_assoc()){
		$returned_array[$row['id']] = $row;
	}
	
	return $returned_array;
}

//function get_asignaturas_por_curso_academico(){
//Subjects ordered by academic year
function get_asignaturas_por_curso_academico(){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_subject
				      WHERE visible = 1
					  ORDER BY academic_year DESC");

	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc()){

		array_push($returned_array, $row);
	}

	return $returned_array;
}


/*
	ASIGNATURAS POR USUARIO/PROFESOR
	Utilizada en menu-principal.php, users.php
*/
function get_asignaturas_por_usuario($id_usuario, $consider_visible = true){
	$link = connect();

	if($consider_visible){
		$query = sprintf("SELECT cs.*
					  FROM colmena_user_subject cus, colmena_subject cs
					  WHERE cus.user_id = '%s' 
					  AND cs.visible = '1'
					  AND cus.subject_id = cs.id
					  ORDER BY academic_year DESC",
		$link->real_escape_string($id_usuario)
		);	
	}else{
		$query = sprintf("SELECT cs.*
					  FROM colmena_user_subject cus, colmena_subject cs
					  WHERE cus.user_id = '%s' 
					   AND cus.subject_id = cs.id
					  ORDER BY academic_year DESC",
		$link->real_escape_string($id_usuario)
		);	
	}
	

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

/*
	ASIGNATURAS DE UN USUARIO EN COMÚN CON UN PROFESOR
	Utilizada en users-template.php
*/
function get_asignaturas_usuario_profesor($user_id, $teacher_id){
	$link = connect();

	$query = sprintf("SELECT DISTINCT cs.* FROM colmena_subject cs, colmena_user_subject cus1, colmena_user_subject cus2					  
					  WHERE cus1.user_id != cus2.user_id
					  AND cus1.subject_id = cus2.subject_id
					  AND cus1.subject_id = cs.id
					  AND cs.visible = 1
					  AND cus1.user_id = '%s'
					  AND cus2.user_id = '%s'",
		$link->real_escape_string($user_id),
		$link->real_escape_string($teacher_id)
	);
	$result = $link->query($query);

	if($result -> num_rows == 0){
		return false;
	}

	$returned_array = array();
	while ($row = $result->fetch_assoc())
		array_push($returned_array, $row);
	
	return $returned_array;
}

//function get_asignatura($id){
function get_asignatura($id){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_subject					  
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

/* 
	ERRORES POR FAMILIA PARA UNA SESION O ASIGNATURA (SIN CONSIDERAR USUARIO)
	Utilizada en subjects.php
*/
function get_totales_familia_asignatura($subject_id, $subject_table){
	$link = connect();

	$query = sprintf("SELECT ce.first_family, cm.session_id, count(first_family) as totales
						FROM colmena_error ce, %s cm, colmena_session cs
						WHERE cm.session_id = cs.id
						AND cs.subject_id = '%s'
					  	AND cm.active = '1'
						AND ce.error_id = cm.error_id
						GROUP BY ce.first_family, cm.session_id
						ORDER BY ce.first_family ASC",
		$link->real_escape_string($subject_table),
		$link->real_escape_string($subject_id)
	);

	$result = $link->query($query);


	$returned_array = array();

	while ($row = $result->fetch_assoc()){		
		$returned_array[$row['first_family']][$row['session_id']] = $row['totales'];
	}

	return $returned_array;
}


/* 
	DIFERENTES NOMBRES DE FAMILIAS
	Utilizada en subjects-session.php
*/	
function get_familias_diferentes(){
	global $family_names;
	$link = connect();

	$query = sprintf("SELECT first_family FROM colmena_error
					  GROUP BY first_family");

	$result = $link->query($query);

	$returned_array = array();

	while ($row = $result->fetch_assoc()) {
		array_push($returned_array, $family_names[$row['first_family']]);
	}
	
	return $returned_array;
}

/*
	NÚMERO DE ERRORES Y WARNINGS POR ASIGNATURA
	Utilizada en subjects.php, users-template.php
*/
function get_num_markers_by_subject($subject_table){
	$link = connect();

	$query = sprintf("SELECT ce.first_family, cm.gender, count(cm.GENDER) as total
					  FROM %s cm, colmena_error ce
					  WHERE ce.error_id = cm.error_id					  
					  AND cm.active = '1'					  
					  GROUP BY cm.GENDER, ce.first_family",					  
					  $link->real_escape_string($subject_table));	

	$result = $link->query($query);	

	if ($result->num_rows == 0) {
		return 0;
	}
	$returned_array = array();
	$total = 0;

	while($row = $result->fetch_assoc()){
		$total += $row['total'];
		$returned_array[$row['first_family']][$row['gender']] = $row['total'];
		if(!isset($returned_array[$row['first_family']]['total']))
			$returned_array[$row['first_family']]['total'] = 0;
		$returned_array[$row['first_family']]['total'] += $row['total'];
	}
	$returned_array['total'] = $total;
	return $returned_array;
}

/*
	DEVUELVE TRUE O FALSE EN FUNCIÓN DE SI UN USUARIO PERTENECE A UNA ASIGNATURA
	Utilizada en user-in-subject-template.php
*/
function has_subject($subject_id, $user_id){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_user_subject
					  WHERE user_id = '%s'
					  AND subject_id = '%s'",
					  $link->real_escape_string($user_id),
					  $link->real_escape_string($subject_id)
					  );

	$result = $link->query($query);
	if($result->num_rows > 0)
		return true;
	else
		return false;
}

?>