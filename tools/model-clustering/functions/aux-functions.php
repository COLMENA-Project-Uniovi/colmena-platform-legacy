<?php
//AUX Function for calculate time array average
function calculate_avg_time_array($time_array){
	return date('H:i:s', array_sum(array_map('strtotime', $time_array)) / count($time_array));
}

//AUX Function for calculate array average
function calculate_avg_array($array){
	return array_sum($array) / count($array);
}

//AUX function to calculate avg array with index
function merge_array_data($array, $calc_type = "avg"){
	$avg_array = array(
			"errors_and_warnings" => '',
			"errors" => '',
			"warnings" => '',
			"compilations_ok" => '',
			"compilations_error" => '',
			"mins_first_compilation_error_from_start" => '',
			"secs_first_compilation_error_from_start" => '',
			"mins_between_first_and_last_error" => '',
			"secs_between_first_and_last_error" => '',
			"mins_working_from_start" => '',
			"secs_working_from_start" => '',
			"type_errors" => '',
			"structural_errors" => '',
			"variable_errors" => '',
			"method_errors" => '',
			"constructor_errors" => '',
			"syntax_errors" => '',
			"import_errors" => '',
			"type_warnings" => '',
			"structural_warnings" => '',
			"variable_warnings" => '',
			"method_warnings" => '',
			"constructor_warnings" => '',
			"syntax_warnings" => '',
			"import_warnings" => '',
			"assistance" => ''
		);

	$size = count($array);
	//print_r($array);
	//echo "ahora la suma";
	
	foreach($array as $entry){
		if(!isset($entry['assistance'])){
			$entry['assistance'] = $size;
		}
		foreach($entry as $key => $value){
			if(isset($avg_array[$key])){
				$avg_array[$key] += $value;	
			}
		}
	}

	//print_r($avg_array);
	//echo "ahora la media";

	if($calc_type == "avg"){
		foreach ($avg_array as $key => $value){
			$avg_array[$key] = ceil($avg_array[$key]/$size);
		}
	}else{
		$avg_array['assistance'] =  ceil($avg_array["assistance"]/$size);
	}

	//print_r($avg_array);
	//exit();

	return $avg_array;
}

// Saves the complete marker into the database
function write_marker($marker){
	$link = connect();

	$query = sprintf("
		INSERT INTO clustering_session (
			colmena_user_id, 
			year, 
			colmena_subject_id, 
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
			)
		VALUES (
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		)",
		$link->real_escape_string($marker['colmena_user_id']),
		$link->real_escape_string($marker['year']),
		$link->real_escape_string($marker['colmena_subject_id']),
		$link->real_escape_string($marker['colmena_session_id']),
		$link->real_escape_string($marker['errors_and_warnings']),
		$link->real_escape_string($marker['errors']),
		$link->real_escape_string($marker['warnings']),
		$link->real_escape_string($marker['compilations_ok']),
		$link->real_escape_string($marker['compilations_error']),
		$link->real_escape_string($marker['mins_first_compilation_error_from_start']),
		$link->real_escape_string($marker['secs_first_compilation_error_from_start']),
		$link->real_escape_string($marker['mins_between_first_and_last_error']),
		$link->real_escape_string($marker['secs_between_first_and_last_error']),
		$link->real_escape_string($marker['mins_working_from_start']),
		$link->real_escape_string($marker['secs_working_from_start']),
		$link->real_escape_string($marker['type_errors']),
		$link->real_escape_string($marker['structural_errors']),
		$link->real_escape_string($marker['variable_errors']),
		$link->real_escape_string($marker['method_errors']),
		$link->real_escape_string($marker['constructor_errors']),
		$link->real_escape_string($marker['syntax_errors']),
		$link->real_escape_string($marker['import_errors']),
		$link->real_escape_string($marker['type_warnings']),
		$link->real_escape_string($marker['structural_warnings']),
		$link->real_escape_string($marker['variable_warnings']),
		$link->real_escape_string($marker['method_warnings']),
		$link->real_escape_string($marker['constructor_warnings']),
		$link->real_escape_string($marker['syntax_warnings']),
		$link->real_escape_string($marker['import_warnings'])
	);
	
	return $link->query($query);
}

//convert an array into a CSV 
function array_to_csv_download($array, $filename = "export.csv", $heading = "", $delimiter=";") {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w');
    // heading 
    fputcsv($f, $heading, $delimiter); 
    // loop over the input array
    foreach ($array as $line) { 
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter); 
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
}

//Convert the raw array into [user][session] => Data array
function format_data($data_to_model){
	$return_array = array();
	foreach ($data_to_model as $row) {
		//extract the index
		$colmena_user_id = strtoupper($row['colmena_user_id']);
		$colmena_session_id = $row['colmena_session_id'];

		//unset because we use them as index
		unset($row['colmena_user_id']);
		unset($row['colmena_session_id']);

		//check if exists
		if(!isset($return_array[$colmena_user_id])){
			$return_array[$colmena_user_id] = array();
		}
		if(!isset($return_array[$colmena_user_id][$colmena_session_id])){
			$return_array[$colmena_user_id][$colmena_session_id] = array();
		}

		//add the information
		$return_array[$colmena_user_id][$colmena_session_id] = $row;
	}

	return $return_array;
}

?>