<?php
require_once("../functions/general-functions.php");
require_once("../base-data-model.php");

//2 years

//recover assistance of C1_a
$users_assistance_c1_a = get_users_assistance($selected_sessions_previous_c1_a, $minimun_sessions_asistance_c1_a);
//recover data of users by session
$data_to_model_c1_a = get_model_clustering_info($selected_sessions_previous_c1_a, $minimun_sessions_asistance_c1_a, $users_assistance_c1_a);

//format using user as index and session as subindex
$format_data_c1_a = format_data($data_to_model_c1_a);

//recover assistance of C1_b
$users_assistance_c1_b = get_users_assistance($selected_sessions_previous_c1_b, $minimun_sessions_asistance_c1_b);
//recover data of users by session
$data_to_model_c1_b = get_model_clustering_info($selected_sessions_previous_c1_b, $minimun_sessions_asistance_c1_b,
	$users_assistance_c1_b);

//format using user as index
$format_data_c1_b = format_data($data_to_model_c1_b);

//combine and remove duplicates (remove from c1_b users of c1_a)
$all_users_sessions_before_c1 = combine_users($format_data_c1_a, $format_data_c1_b);

//group by user, not considering sessions
$all_users_before_c1 = combine_user_sessions($all_users_sessions_before_c1, $calc_type);

//add califications
$all_users_before_c1 = add_calification_data($all_users_before_c1, $selected_subjects);

$headers = array("colmena_user_id", "errors_and_warnings", "errors", "warnings", "compilations_ok", "compilations_error", "mins_first_compilation_error_from_start", "secs_first_compilation_error_from_start", "mins_between_first_and_last_error", "secs_between_first_and_last_error", "mins_working_from_start", "secs_working_from_start", "type_errors", "structural_errors", "variable_errors", "method_errors", "constructor_errors", "syntax_errors", "import_errors", "type_warnings", "structural_warnings", "variable_warnings", "method_warnings", "constructor_warnings", "syntax_warnings", "import_warnings", "assistance", "first_partial_calification", "second_partial_calification", "practical_calification", "theoretical_calification", "calification", "interval_practical_calification", "interval_final_calification");

$filename =  "control1-usuarios-" . $calc_type . ".csv";

array_to_csv_download($all_users_before_c1, $filename, $headers);

?>