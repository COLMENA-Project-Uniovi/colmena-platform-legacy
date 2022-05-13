<?php

//Recovers the avg for a single subject
function get_avg_califications($selected_subject){
	$link = connect();

	$query = sprintf("
		SELECT 
			avg(first_partial_calification) as first_partial_calification,
			avg(second_partial_calification) as second_partial_calification,
			avg(practical_calification) as practical_calification,
			avg(theoretical_calification) as theoretical_calification,
			avg(calification) as calification
		FROM `colmena_user_subject` 
		WHERE subject_id = %s
		group by subject_id",
		$link->real_escape_string($selected_subject)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		$returned_array['first_partial_calification'] = round($row['first_partial_calification'], 2);
		$returned_array['second_partial_calification'] = round($row['second_partial_calification'], 2);
		$returned_array['practical_calification'] = round($row['practical_calification'], 2);
		$returned_array['theoretical_calification'] = round($row['theoretical_calification'], 2);
		$returned_array['calification'] = round($row['calification'], 2);
		$returned_array['interval_practical_calification'] = get_interval_calification($row['practical_calification']);
		$returned_array['interval_final_calification'] = get_interval_calification($row['calification']);
	}

	return $returned_array;
}

//add the calification information to the users grouped array
function add_calification_data($users, $selected_subjects){
	$data_with_calification = $users;
	
	foreach ($users as $user => $user_data) {
		$califications = get_califications($user, $selected_subjects);
		foreach ($califications as $key => $value){
			$data_with_calification[$user][$key] = $value;
		}

		$practical_calification = $data_with_calification[$user]['practical_calification'];
		$final_calification = $data_with_calification[$user]['calification'];

		
		$data_with_calification[$user]['interval_practical_calification'] = get_interval_calification($practical_calification);
		$data_with_calification[$user]['interval_final_calification'] = get_interval_calification($final_calification);
	}
	
	return $data_with_calification;
}

//Converts calification into intervals
function get_interval_calification($calification){
	$interval_calification = -1;

	if($calification >= 0 && $calification < 5){
		$interval_calification = 0;
	}else if($calification >= 5 && $calification < 7){
		$interval_calification = 1;
	}else if($calification >= 7 && $calification < 9){
		$interval_calification = 2;
	}else if($calification >= 9 && $calification <= 10){
		$interval_calification = 3;
	}

	return $interval_calification;
}

//ge the calification considering the first of the selected subjects by id
function get_califications($colmena_user_id, $selected_subjects){
	$link = connect();

	$query = sprintf("
		SELECT * FROM colmena_user_subject
		WHERE user_id = '%s'
		AND subject_id in (%s)
		ORDER BY subject_id asc
		LIMIT 1",
		$link->real_escape_string($colmena_user_id),
		$link->real_escape_string(implode(",",$selected_subjects))
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		$returned_array['first_partial_calification'] = $row['first_partial_calification'];
		$returned_array['second_partial_calification'] = $row['second_partial_calification'];
		$returned_array['practical_calification'] = $row['practical_calification'];
		$returned_array['theoretical_calification'] = $row['theoretical_calification'];
		$returned_array['calification'] = $row['calification'];
	}

	return $returned_array;
}

?>