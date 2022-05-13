<?php

//Recover all users from a specific subject
function get_colmena_users($colmena_subject_table){
	$link = connect();

	$query = sprintf("SELECT DISTINCT user_id
					  FROM %s",
		$link->real_escape_string($colmena_subject_table)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc())
		array_push($returned_array, $row['user_id']);

	return $returned_array;
}

//obtain the users who assisted at minimum input sessions of the selected sessions. 
function get_users_assistance($selected_sessions, $minimun_sessions_asistance){
	$link = connect();

	$query = sprintf("SELECT  colmena_user_id, count(*) as sessions
					  FROM clustering_session
					  WHERE colmena_session_id IN (%s)
					  GROUP BY colmena_user_id
					  HAVING sessions >= %s
					  ",
		$link->real_escape_string(implode(",",$selected_sessions)),
		$link->real_escape_string($minimun_sessions_asistance)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc())
		$returned_array[$row['colmena_user_id']] = $row['sessions'];

	return $returned_array;
}

//Combine all the information grouped by user and session in one single row by user, containing the avg of all his sessions.
function combine_user_sessions($all_users_sessions, $calc_type = "avg"){
	$returned_array = array();

	foreach($all_users_sessions as $colmena_user_id => $sessions_info){
		$total_user_sessions = count($sessions_info);
		//reset the sum array
		$sum_array = array(
			"errors_and_warnings" => "",
			"errors" => "",
			"warnings" => "",
			"compilations_ok" => "",
			"compilations_error" => "",
			"mins_first_compilation_error_from_start" => "",
			"secs_first_compilation_error_from_start" => "",
			"mins_between_first_and_last_error" => "",
			"secs_between_first_and_last_error" => "",
			"mins_working_from_start" => "",
			"secs_working_from_start" => "",
			"type_errors" => "",
			"structural_errors" => "",
			"variable_errors" => "",
			"method_errors" => "",
			"constructor_errors" => "",
			"syntax_errors" => "",
			"import_errors" => "",
			"type_warnings" => "",
			"structural_warnings" => "",
			"variable_warnings" => "",
			"method_warnings" => "",
			"constructor_warnings" => "",
			"syntax_warnings" => "",
			"import_warnings" => "",
			"assistance" => ""
		);

		//for each sesssion of the uer
		foreach ($sessions_info as $session_id => $data) {
			//sum each keay 
			foreach(array_keys($data) as $key){			
				$sum_array[$key] += $data[$key];
			}
		}

		$returned_array[$colmena_user_id]['colmena_user_id'] = $colmena_user_id;
			
		//makes the avg/sum in returned array
		foreach (array_keys($sum_array) as $key){
			if($calc_type == "avg"){
				$returned_array[$colmena_user_id][$key] = ceil($sum_array[$key]/$total_user_sessions);
			}else if ($calc_type == "sum"){
				$returned_array[$colmena_user_id][$key] = $sum_array[$key];
			}
		}
	}

	return $returned_array;	
}


//remove duplicates combining first and second array
function combine_users($first_array, $second_array){
	$returned_array = $first_array;

	foreach ($second_array as $colmena_user_id => $info) {
		//check if user already in the first array
		if(!array_key_exists($colmena_user_id, $first_array)){
			$returned_array[$colmena_user_id] = $info;
		}
	}

	return $returned_array;
}


?>