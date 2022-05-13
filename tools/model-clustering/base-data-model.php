<?php
//variables for the modeling
$minimun_sessions_asistance_percentage = 80;
$selected_subjects = array(2,7,9);
$selected_sessions_previous_c1_a = array(49, 50, 51, 52, 53, 54);
$selected_sessions_previous_c1_b = array(68, 69, 70, 71, 72, 73);

$selected_sessions_previous_c2_a = array(8, 11, 14, 13, 15);
$selected_sessions_previous_c2_b = array(55, 57, 58);
$selected_sessions_previous_c2_c = array(74, 75, 76);

$minimun_sessions_asistance_c1_a = ceil((count($selected_sessions_previous_c1_a) * $minimun_sessions_asistance_percentage) / 100);
$minimun_sessions_asistance_c1_b = ceil((count($selected_sessions_previous_c1_b) * $minimun_sessions_asistance_percentage) / 100);

$minimun_sessions_asistance_c2_a = ceil((count($selected_sessions_previous_c2_a) * $minimun_sessions_asistance_percentage) / 100);
$minimun_sessions_asistance_c2_b = ceil((count($selected_sessions_previous_c2_b) * $minimun_sessions_asistance_percentage) / 100);
$minimun_sessions_asistance_c2_c = ceil((count($selected_sessions_previous_c2_c) * $minimun_sessions_asistance_percentage) / 100);

//avg/sum variables for users
if(isset($_GET['calc_type'])){
	$calc_type = $_GET['calc_type'];
}else{
	$calc_type = "avg";
}

?>