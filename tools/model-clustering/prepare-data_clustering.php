<?php

require_once("../functions/general-functions.php");

//set year and subject
//$colmena_year = 2013;
//$colmena_year = 2014;
$colmena_year = 2015;

//MP
//2013:2, 2014:7, 2015:9
//$colmena_subject_id = 2;
//$colmena_subject_id = 7;
$colmena_subject_id = 9;

//ALG
//2013:1 , 2014:6 , 2015:8
//$colmena_subject_id = 1;
//$colmena_subject_id = 6;
//$colmena_subject_id = 8;


//$colmena_subject_table = 'colmena_marker_mp_2013';
//$colmena_subject_table = 'colmena_marker_mp_2014';
$colmena_subject_table = 'colmena_marker_mp_2015';
//$colmena_subject_table = 'colmena_marker_al_2013';
//$colmena_subject_table = 'colmena_marker_al_2014';
//$colmena_subject_table = 'colmena_marker_al_2015';

$family_type = 1;
$family_structural = 2;
$family_field = 3;
$family_method = 4;
$family_constructor = 5;
$family_syntax = 6;
$family_import = 7;

//recover all users of the subject
$colmena_users = get_colmena_users($colmena_subject_table);

//ERRORS AND WARNINGS
//Obtain errors and warnings
$colmena_errors_warnings = get_colmena_errors_warnings($colmena_subject_table);
//Obtain only errors
$colmena_errors = get_colmena_markers_type($colmena_subject_table, "ERROR");
//Obtain only warnings
$colmena_warnings = get_colmena_markers_type($colmena_subject_table, "WARNING");


//COMPILATIONS
//Obtain compilations error
$colmena_compilations_error = get_colmena_compilations_error($colmena_subject_table);
//TODO: Obtain compilations OK (ONLY 2015)
//$colmena_compilations_ok = get_colmena_compilations_ok($colmena_subject_table);


//TIMES
//Obtain Colmena Times
$colmena_times = get_colmena_times($colmena_subject_table);


//ERRORS
//Obtain all errors by type
$colmena_errors_type = get_colmena_markers_family($colmena_subject_table, $family_type, "ERROR");
$colmena_errors_structural = get_colmena_markers_family($colmena_subject_table, $family_structural, "ERROR");
$colmena_errors_field = get_colmena_markers_family($colmena_subject_table, $family_field, "ERROR");
$colmena_errors_method = get_colmena_markers_family($colmena_subject_table, $family_method, "ERROR");
$colmena_errors_constructor = get_colmena_markers_family($colmena_subject_table, $family_constructor, "ERROR");
$colmena_errors_syntax = get_colmena_markers_family($colmena_subject_table, $family_syntax, "ERROR");
$colmena_errors_import = get_colmena_markers_family($colmena_subject_table, $family_import, "ERROR");

//WARNINGS
//Obtain all warnings by type
$colmena_warnings_type = get_colmena_markers_family($colmena_subject_table, $family_type, "WARNING");
$colmena_warnings_structural = get_colmena_markers_family($colmena_subject_table, $family_structural, "WARNING");
$colmena_warnings_field = get_colmena_markers_family($colmena_subject_table, $family_field, "WARNING");
$colmena_warnings_method = get_colmena_markers_family($colmena_subject_table, $family_method, "WARNING");
$colmena_warnings_constructor = get_colmena_markers_family($colmena_subject_table, $family_constructor, "WARNING");
$colmena_warnings_syntax = get_colmena_markers_family($colmena_subject_table, $family_syntax, "WARNING");
$colmena_warnings_import = get_colmena_markers_family($colmena_subject_table, $family_import, "WARNING");

//Prepare the markers
$all_markers = array();

//DEBUG
//echo "Empezamos a recorrer los usuarios</br>";

foreach ($colmena_users as $colmena_user){	
	$colmena_user_sessions = array_keys($colmena_errors_warnings[$colmena_user]);
	
	foreach ($colmena_user_sessions as $session_id) {
		
		//Prepare the array
		$marker = array(
				'colmena_user_id' => '',
				'year' => '',
				'colmena_subject_id' => '',
				'colmena_session_id' => '',
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

		//Fill general data
		$marker['colmena_user_id'] = $colmena_user;
		$marker['year'] = $colmena_year;
		$marker['colmena_subject_id'] = $colmena_subject_id;
		$marker['colmena_session_id'] = $session_id;

		//Errors and warnings
		$marker['errors_and_warnings'] = $colmena_errors_warnings[$colmena_user][$session_id];	
		
		//Errors
		if(array_key_exists($colmena_user, $colmena_errors) && array_key_exists($session_id, $colmena_errors[$colmena_user])){
			$marker['errors'] = $colmena_errors[$colmena_user][$session_id];
		}

		//Warnings
		if(array_key_exists($colmena_user, $colmena_warnings) &&  array_key_exists($session_id, $colmena_warnings[$colmena_user])){
			$marker['warnings'] = $colmena_warnings[$colmena_user][$session_id];
		}
		

		//Compilations Error
		if(array_key_exists($colmena_user, $colmena_compilations_error) &&  array_key_exists($session_id, $colmena_compilations_error[$colmena_user])){
			$marker['compilations_error'] = $colmena_compilations_error[$colmena_user][$session_id];
		}

		//Compilations OK
		//2013, 2014 0
		$marker['compilations_ok'] = 0;
		//TODO For 2015

		//Times
		if(array_key_exists($colmena_user, $colmena_times) &&  array_key_exists($session_id, $colmena_times[$colmena_user])){
			//Insert the AVG and SUM Values precalculated
			$marker['mins_first_compilation_error_from_start'] = $colmena_times[$colmena_user][$session_id]['mins_first_compilation_error_from_start_avg'];
			$marker['secs_first_compilation_error_from_start'] = $colmena_times[$colmena_user][$session_id]['secs_first_compilation_error_from_start_avg'];
			$marker['mins_between_first_and_last_error'] = $colmena_times[$colmena_user][$session_id]['mins_between_first_and_last_error_sum'];
			$marker['secs_between_first_and_last_error'] = $colmena_times[$colmena_user][$session_id]['secs_between_first_and_last_error_sum'];
			$marker['mins_working_from_start'] = $colmena_times[$colmena_user][$session_id]['mins_working_from_start_sum'];
			$marker['secs_working_from_start'] = $colmena_times[$colmena_user][$session_id]['secs_working_from_start_sum'];
		}

		//Errors by type
		if(array_key_exists($colmena_user, $colmena_errors_type) &&  array_key_exists($session_id, $colmena_errors_type[$colmena_user]))
		{
			$marker['type_errors'] = $colmena_errors_type[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_errors_structural) &&  array_key_exists($session_id, $colmena_errors_structural[$colmena_user]))
		{
			$marker['structural_errors'] = $colmena_errors_structural[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_errors_field) &&  array_key_exists($session_id, $colmena_errors_field[$colmena_user]))
		{
			$marker['variable_errors'] = $colmena_errors_field[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_errors_method) &&  array_key_exists($session_id, $colmena_errors_method[$colmena_user]))
		{
			$marker['method_errors'] = $colmena_errors_method[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_errors_constructor) &&  array_key_exists($session_id, $colmena_errors_constructor[$colmena_user]))
		{
			$marker['constructor_errors'] = $colmena_errors_constructor[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_errors_syntax) &&  array_key_exists($session_id, $colmena_errors_syntax[$colmena_user]))
		{
			$marker['syntax_errors'] = $colmena_errors_syntax[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_errors_import) &&  array_key_exists($session_id, $colmena_errors_import[$colmena_user]))
		{
			$marker['import_errors'] = $colmena_errors_import[$colmena_user][$session_id];
		}

		//Warning by types
		if(array_key_exists($colmena_user, $colmena_warnings_type) &&  array_key_exists($session_id, $colmena_warnings_type[$colmena_user]))
		{
			$marker['type_warnings'] = $colmena_warnings_type[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_warnings_structural) &&  array_key_exists($session_id, $colmena_warnings_structural[$colmena_user]))
		{
			$marker['structural_warnings'] = $colmena_warnings_structural[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_warnings_field) &&  array_key_exists($session_id, $colmena_warnings_field[$colmena_user]))
		{
			$marker['variable_warnings'] = $colmena_warnings_field[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_warnings_method) &&  array_key_exists($session_id, $colmena_warnings_method[$colmena_user]))
		{
			$marker['method_warnings'] = $colmena_warnings_method[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_warnings_constructor) &&  array_key_exists($session_id, $colmena_warnings_constructor[$colmena_user]))
		{
			$marker['constructor_warnings'] = $colmena_warnings_constructor[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_warnings_syntax) &&  array_key_exists($session_id, $colmena_warnings_syntax[$colmena_user]))
		{
			$marker['syntax_warnings'] = $colmena_warnings_syntax[$colmena_user][$session_id];
		}
		if(array_key_exists($colmena_user, $colmena_warnings_import) &&  array_key_exists($session_id, $colmena_warnings_import[$colmena_user]))
		{
			$marker['import_warnings'] = $colmena_warnings_import[$colmena_user][$session_id];
		}

		// Push the generated marker in the global markers array.
		array_push($all_markers, $marker);
	}
}

//Write all the markers
foreach($all_markers as $marker){
	echo "escribo marker " .  $marker['colmena_user_id'] . " para la sesion " . $marker['colmena_session_id'] . "</br>";

	//DEBUG
	print_r($marker);
	exit();
	
	//Uncomment for writting int DB
	//write_marker($marker);
	
	//DEBUG
	//exit();
}

//DEBUG
echo "Escritos " .  count($all_markers) . " registros";

?>