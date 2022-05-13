<?php

//Obtain the raw data from clustering table and adds the assistance.
function get_model_clustering_info($selected_sessions, $minimun_sessions_asistance, $user_assistances){
	$link = connect();

	$query = sprintf("SELECT 
						colmena_user_id,
						colmena_session_id,
						errors_and_warnings, 
						errors, 
						warnings, 
						compilations_ok, 
						compilations_error, 
						mins_first_compilation_error_from_start, 
						secs_first_compilation_error_from_start, 
						mins_between_first_and_last_error, 
						secs_between_first_and_last_error, 
						mins_working_from_start, 
						secs_working_from_start, 
						type_errors, 
						structural_errors, 
						variable_errors, 
						method_errors, 
						constructor_errors, 
						syntax_errors, 
						import_errors, 
						type_warnings, 
						structural_warnings, 
						variable_warnings, 
						method_warnings, 
						constructor_warnings, 
						syntax_warnings, 
						import_warnings
					  FROM clustering_session
					  WHERE colmena_session_id IN (%s)
					  AND colmena_user_id IN (
					  	SELECT colmena_user_id from (
							SELECT  colmena_user_id, count(*) as sessions
							FROM clustering_session
							WHERE colmena_session_id IN (%s)
							GROUP BY colmena_user_id
							HAVING sessions >= %s
						) as tabla
					  )",
		$link->real_escape_string(implode(",", $selected_sessions)),
		$link->real_escape_string(implode(",",$selected_sessions)),
		$link->real_escape_string($minimun_sessions_asistance)
	);

	$result = $link->query($query);
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		$row['assistance'] = $user_assistances[$row['colmena_user_id']];
		array_push($returned_array, $row);
	}

	return $returned_array;
}


/*function create_avg_data($data_to_model){
	$sum_data = array(
			'assistance' => 0,
			'errors_and_warnings' => 0,
			'errors' => 0,
			'warnings' => 0,
			'compilations_ok' => 0,
			'compilations_error' => 0,
			'mins_first_compilation_error_from_start' => 0,
			'secs_first_compilation_error_from_start' => 0,
			'mins_between_first_and_last_error' => 0,
			'secs_between_first_and_last_error' => 0,
			'mins_working_from_start' => 0,
			'secs_working_from_start' => 0,
			'type_errors' => 0,
			'structural_errors' => 0,
			'variable_errors' => 0,
			'method_errors' => 0,
			'constructor_errors' => 0,
			'syntax_errors' => 0,
			'import_errors' => 0,
			'type_warnings' => 0,
			'structural_warnings' => 0,
			'variable_warnings' => 0,
			'method_warnings' => 0,
			'constructor_warnings' => 0,
			'syntax_warnings' => 0,
			'import_warnings' => 0
		);

	foreach ($data_to_model as $row) {
		$sum_data['assistance'] += $row['assistance'];
		$sum_data['errors_and_warnings'] += $row['errors_and_warnings'];
		$sum_data['errors'] += $row['errors'];
		$sum_data['warnings'] += $row['warnings'];
		$sum_data['compilations_ok'] += $row['compilations_ok'];
		$sum_data['compilations_error'] += $row['compilations_error'];
		$sum_data['mins_first_compilation_error_from_start'] += $row['mins_first_compilation_error_from_start'];
		$sum_data['secs_first_compilation_error_from_start'] += $row['secs_first_compilation_error_from_start'];
		$sum_data['mins_between_first_and_last_error'] += $row['mins_between_first_and_last_error'];
		$sum_data['secs_between_first_and_last_error'] += $row['secs_between_first_and_last_error'];
		$sum_data['mins_working_from_start'] += $row['mins_working_from_start'];
		$sum_data['secs_working_from_start'] += $row['secs_working_from_start'];
		$sum_data['type_errors'] += $row['type_errors'];
		$sum_data['structural_errors'] += $row['structural_errors'];
		$sum_data['variable_errors'] += $row['variable_errors'];
		$sum_data['method_errors'] += $row['method_errors'];
		$sum_data['constructor_errors'] += $row['constructor_errors'];
		$sum_data['syntax_errors'] += $row['syntax_errors'];
		$sum_data['import_errors'] += $row['import_errors'];
		$sum_data['type_warnings'] += $row['type_warnings'];
		$sum_data['structural_warnings'] += $row['structural_warnings'];
		$sum_data['variable_warnings'] += $row['variable_warnings'];
		$sum_data['method_warnings'] += $row['method_warnings'];
		$sum_data['constructor_warnings'] += $row['constructor_warnings'];
		$sum_data['syntax_warnings'] += $row['syntax_warnings'];
		$sum_data['import_warnings'] += $row['import_warnings'];
	}

	foreach($sum_data as $key => $value){
		$avg_data[$key] = ceil($value/count($data_to_model));
	}

	return $avg_data;

}*/



?>