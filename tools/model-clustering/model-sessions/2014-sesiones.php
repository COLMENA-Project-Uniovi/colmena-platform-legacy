<?php
require_once("../functions/general-functions.php");
require_once("../base-data-model.php");

//2013/2014
$avg_session_data = array();
//recover data for first year (sessions up to first control)
$session_data_c1_a = get_sessions_clustering_data($selected_sessions_previous_c1_a);

//merge data of sessions in year
foreach($session_data_c1_a as $session => $session_data_info){
	$avg_session_data[$session] = merge_array_data($session_data_info, $calc_type);
}

//recover data for second year (sessions up to first control)
$session_data_c2_b = get_sessions_clustering_data($selected_sessions_previous_c2_b);

//merge data of sessions in year
foreach($session_data_c2_b as $session => $session_data_info){
	$avg_session_data[$session] = merge_array_data($session_data_info, $calc_type);
}

$all_sessions_second_year = merge_array_data($avg_session_data, $calc_type);

//add califications

//write to file
$headers = array("errors_and_warnings", "errors", "warnings", "compilations_ok", "compilations_error", "mins_first_compilation_error_from_start", "secs_first_compilation_error_from_start", "mins_between_first_and_last_error", "secs_between_first_and_last_error", "mins_working_from_start", "secs_working_from_start", "type_errors", "structural_errors", "variable_errors", "method_errors", "constructor_errors", "syntax_errors", "import_errors", "type_warnings", "structural_warnings", "variable_warnings", "method_warnings", "constructor_warnings", "syntax_warnings", "import_warnings", "assistance");


$filename =  "2014-sesiones-" . $calc_type . ".csv";

array_to_csv_download(array($all_sessions_second_year), $filename, $headers);

?>