<?php

//Get all total errors+warnings from a specific subject by user and session
function get_colmena_errors_warnings($colmena_subject_table){
	$link = connect();

	$query = sprintf("SELECT user_id, session_id, count(*) AS errors 
					  FROM %s 
					  GROUP BY user_id, session_id
					  ORDER BY user_id ASC",
		$link->real_escape_string($colmena_subject_table)
	);
	$result = $link->query($query);

	$returned_array = array();

	while($row = $result->fetch_assoc()){
		$user_id = $row['user_id'];
		$session_id = $row['session_id'];
		$returned_array[$user_id][$session_id] = $row['errors'];
	}

	return $returned_array;
}

//Get all markers (errors OR warnings) from a specific subject by user and session
function get_colmena_markers_type($colmena_subject_table, $colmena_error_type){
	$link = connect();

	$query = sprintf("SELECT user_id, session_id, count(*) AS errors 
					  FROM %s 
					  WHERE gender = '%s'
					  GROUP BY user_id, session_id
					  ORDER BY user_id ASC",
		$link->real_escape_string($colmena_subject_table),
		$link->real_escape_string($colmena_error_type)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		$user_id = $row['user_id'];
		$session_id = $row['session_id'];
		$returned_array[$user_id][$session_id] = $row['errors'];
	}

	return $returned_array;
}

// Get the specific quantity of erros by type of a specific subject, grouped by user and session
function get_colmena_markers_family($colmena_subject_table, $colmena_error_family, $colmena_error_type){
	$link = connect();

	$query = sprintf("
		SELECT user_id, session_id, count(*) as total
		FROM %s cm, colmena_error ce
		WHERE cm.error_id = ce.error_id
		AND ce.first_family = %s
		AND cm.gender = '%s'
		GROUP BY user_id, session_id",
		$link->real_escape_string($colmena_subject_table),
		$link->real_escape_string($colmena_error_family),
		$link->real_escape_string($colmena_error_type)
	);
	
	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		$user_id = $row['user_id'];
		$session_id = $row['session_id'];
		$returned_array[$user_id][$session_id] = $row['total'];
	}

	return $returned_array;
}

?>