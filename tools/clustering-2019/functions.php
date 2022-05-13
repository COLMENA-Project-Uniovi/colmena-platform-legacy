<?php
/* BD */
$mysqli; 
date_default_timezone_set('Europe/London');

// -------------------------------------------------------------------
// DATABASE CONFIG
$bd_host = "colmenaproject.es";
$bd_user = "colmena";
$bd_pass = "C0lm3n4";
$bd_database = "colmena_test";

function connect(){
	global $bd_host;
	global $bd_user;
	global $bd_pass;
	global $bd_database;	
	$mysqli = new mysqli($bd_host, $bd_user, $bd_pass, $bd_database) or die("Error " . mysqli_error($mysqli)); 
	$mysqli->query("SET NAMES UTF8");

	return $mysqli;
}

// END CONFIG  
// -------------------------------------------------------------------


// -------------------------------------------------------------------
// USERS
function initialize_users($colmena_subject_table, $sessions){
	$link = connect();

	$query = sprintf("SELECT DISTINCT user_id
					  FROM %s",
		$link->real_escape_string($colmena_subject_table)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		$user_id = strtoupper($row['user_id']);
		$returned_array[$user_id] = array();
		foreach($sessions as $s){
			$returned_array[$user_id][$s] = 0;
		}
	}

	return $returned_array;
}

function get_user_errors_section($colmena_subject_table, $colmena_subject_session_id, $type){
	$link = connect();

	$query = sprintf("SELECT user_id, count(*) as total
					FROM %s
					where session_id = %s
					and gender = '%s'
					group by user_id
					order by user_id asc
					LIMIT 500",
		$link->real_escape_string($colmena_subject_table),
		$link->real_escape_string($colmena_subject_session_id),
		$link->real_escape_string($type)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);

	return $returned_array;
}

