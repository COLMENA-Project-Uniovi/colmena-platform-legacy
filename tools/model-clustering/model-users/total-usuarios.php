<?php
require_once("../functions/general-functions.php");
require_once("../base-data-model.php");

//First year, only c2
//recover assistance of C2_a
$users_assistance_c2_a = get_users_assistance($selected_sessions_previous_c2_a, $minimun_sessions_asistance_c2_a);
//recover data of users by session
$data_to_model_c2_a = get_model_clustering_info($selected_sessions_previous_c2_a, $minimun_sessions_asistance_c2_a, $users_assistance_c2_a);

//format using user as index and session as subindex
$format_data_first_year = format_data($data_to_model_c2_a);

//Second year, merge c1 and c2
//recover assistance of C1_a
$users_assistance_c1_a = get_users_assistance($selected_sessions_previous_c1_a, $minimun_sessions_asistance_c1_a);
//recover data of users by session
$data_to_model_c1_a = get_model_clustering_info($selected_sessions_previous_c1_a, $minimun_sessions_asistance_c1_a, $users_assistance_c1_a);


//recover assistance of C2_b
$users_assistance_c2_b = get_users_assistance($selected_sessions_previous_c2_b, $minimun_sessions_asistance_c2_b);
//recover data of users by session
$data_to_model_c2_b = get_model_clustering_info($selected_sessions_previous_c2_b, $minimun_sessions_asistance_c2_b, $users_assistance_c2_b);

$data_second_year = array_merge($data_to_model_c1_a, $data_to_model_c2_b);
//format using user as index and session as subindex
$format_data_second_year = format_data($data_second_year);


//Third year, merge c1 and c2
//recover assistance of C1_b
$users_assistance_c1_b = get_users_assistance($selected_sessions_previous_c1_b, $minimun_sessions_asistance_c1_b);
//recover data of users by session
$data_to_model_c1_b = get_model_clustering_info($selected_sessions_previous_c1_b, $minimun_sessions_asistance_c1_b,
	$users_assistance_c1_b);

//recover assistance of C2_c
$users_assistance_c2_c = get_users_assistance($selected_sessions_previous_c2_c, $minimun_sessions_asistance_c2_c);
//recover data of users by session
$data_to_model_c2_c = get_model_clustering_info($selected_sessions_previous_c2_c, $minimun_sessions_asistance_c2_c, $users_assistance_c2_c);


$data_third_year = array_merge($data_to_model_c1_b, $data_to_model_c2_c);
//format using user as index and session as subindex
$format_data_third_year = format_data($data_third_year);


//starts combining
//combine and remove duplicates (remove from second year users of first year)
$combine_first_second = combine_users($format_data_first_year, $format_data_second_year);
//combine and remove duplicates (remove from third year users of first and second year)
$combine_all = combine_users($combine_first_second, $format_data_third_year);

//group by user, not considering sessions
$all_users = combine_user_sessions($combine_all, $calc_type);

//add califications
$all_users = add_calification_data($all_users, $selected_subjects);


$headers = array("colmena_user_id", "errors_and_warnings", "errors", "warnings", "compilations_ok", "compilations_error", "mins_first_compilation_error_from_start", "secs_first_compilation_error_from_start", "mins_between_first_and_last_error", "secs_between_first_and_last_error", "mins_working_from_start", "secs_working_from_start", "type_errors", "structural_errors", "variable_errors", "method_errors", "constructor_errors", "syntax_errors", "import_errors", "type_warnings", "structural_warnings", "variable_warnings", "method_warnings", "constructor_warnings", "syntax_warnings", "import_warnings", "assistance", "first_partial_calification", "second_partial_calification", "practical_calification", "theoretical_calification", "calification", "interval_practical_calification", "interval_final_calification");

$filename =  "total-usuarios-" . $calc_type . ".csv";

array_to_csv_download($all_users, $filename, $headers);

?>