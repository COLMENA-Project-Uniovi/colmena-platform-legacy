<?php
require_once("../functions/general-functions.php");
require_once("../base-data-model.php");

//3 subjects

//recover assistance of C2_a
$users_assistance_c2_a = get_users_assistance($selected_sessions_previous_c2_a, $minimun_sessions_asistance_c2_a);
//recover data of users by session
$data_to_model_c2_a = get_model_clustering_info($selected_sessions_previous_c2_a, $minimun_sessions_asistance_c2_a, $users_assistance_c2_a);

//format using user as index and session as subindex
$format_data_c2_a = format_data($data_to_model_c2_a);

//recover assistance of C2_b
$users_assistance_c2_b = get_users_assistance($selected_sessions_previous_c2_b, $minimun_sessions_asistance_c2_b);
//recover data of users by session
$data_to_model_c2_b = get_model_clustering_info($selected_sessions_previous_c2_b, $minimun_sessions_asistance_c2_b, $users_assistance_c2_b);

//format using user as index and session as subindex
$format_data_c2_b = format_data($data_to_model_c2_b);

//recover assistance of C2_c
$users_assistance_c2_c = get_users_assistance($selected_sessions_previous_c2_c, $minimun_sessions_asistance_c2_c);
//recover data of users by session
$data_to_model_c2_c = get_model_clustering_info($selected_sessions_previous_c2_c, $minimun_sessions_asistance_c2_c, $users_assistance_c2_c);

//format using user as index and session as subindex
$format_data_c2_c = format_data($data_to_model_c2_c);

//combine and remove duplicates (remove from c1_b users of c1_a)
$all_users_sessions_before_c2ab = combine_users($format_data_c2_a, $format_data_c2_b);
$all_users_sessions_before_c2 = combine_users($all_users_sessions_before_c2ab, $format_data_c2_c);

$all_users_before_c2 = array();

//prepare the array to export in CSV adding id and session at the same level and not [user][session]
foreach ($all_users_sessions_before_c2 as $user => $info) {
	$user_session_info = array();
	$user_session_info['colmena_user_id'] = $user;
	
	foreach ($info as $session => $data) {
		$user_session_info['colmena_user_session'] = $session;
	
		foreach(array_keys($data) as $key){
			$user_session_info[$key] = $data[$key];
		}

		array_push($all_users_before_c2, $user_session_info);
	}
}


$headers = array("colmena_user_id", "colmena_session_id", "errors_and_warnings", "errors", "warnings", "compilations_ok", "compilations_error", "mins_first_compilation_error_from_start", "secs_first_compilation_error_from_start", "mins_between_first_and_last_error", "secs_between_first_and_last_error", "mins_working_from_start", "secs_working_from_start", "type_errors", "structural_errors", "variable_errors", "method_errors", "constructor_errors", "syntax_errors", "import_errors", "type_warnings", "structural_warnings", "variable_warnings", "method_warnings", "constructor_warnings", "syntax_warnings", "import_warnings", "assistance");

array_to_csv_download($all_users_before_c2, "control2-usuarios-sesiones.csv", $headers);

?>