<?php

//Get all the times from an specific subject grouped by user, session and day. It recovers:
/*
- First time compilation error
- Last time compilation error
- Mins to first compilation error from the beggining of the session
- Mins between first and last compilations with errors.
- Time working in mins from the beggining of the session.
*/
function get_colmena_times($colmena_subject_table){
	$link = connect();

	$query = sprintf("
		SELECT 
			user_id, 
			session_id,  
			DATE(TIMESTAMP(timestamp)) as day,
			
			MIN(TIME(TIMESTAMP(timestamp))) as first_time_compilation_error, 
			
			MAX(TIME(TIMESTAMP(timestamp))) as last_time_compilation_error,

            (SECOND(MAX(TIME(TIMESTAMP(timestamp))))) as secs_last_compilation_error_from_start,
            
            (MINUTE(MIN(TIME(TIMESTAMP(timestamp))))) as mins_first_compilation_error_from_start,
            
            (SECOND(MIN(TIME(TIMESTAMP(timestamp))))) as secs_first_compilation_error_from_start,
    		
    		(TIMESTAMPDIFF(MINUTE, min(TIMESTAMP(timestamp)), max(TIMESTAMP(timestamp)))) as mins_between_first_and_last_error,
    		
    		(TIMESTAMPDIFF(SECOND, min(TIMESTAMP(timestamp)), max(TIMESTAMP(timestamp)))) as secs_between_first_and_last_error,
            
            (MINUTE(MIN(TIME(TIMESTAMP(timestamp))))) + (TIMESTAMPDIFF(MINUTE, min(TIMESTAMP(timestamp)), max(TIMESTAMP(timestamp)))) as mins_working_from_start
           
		FROM %s
		GROUP BY user_id, session_id, day",
		$link->real_escape_string($colmena_subject_table)
	);

	$result = $link->query($query);

	//prepare the final array
	$results_array = array();
	
	//by user, session and day
	while($row = $result->fetch_assoc()){
		//extract all the variables
		$user_id = $row['user_id'];
		$session_id = $row['session_id'];
		$first_time_compilation_error = $row['first_time_compilation_error'];
		$last_time_compilation_error = $row['last_time_compilation_error'];
		$mins_first_compilation_error_from_start = $row['mins_first_compilation_error_from_start'];
		$secs_first_compilation_error_from_start = $mins_first_compilation_error_from_start * 60 + $row['secs_first_compilation_error_from_start'];
		$mins_between_first_and_last_error = $row['mins_between_first_and_last_error'];
		$secs_between_first_and_last_error = $row['secs_between_first_and_last_error'];
		$mins_working_from_start = $row['mins_working_from_start'];
		$secs_working_from_start = $mins_working_from_start * 60 + $row['secs_last_compilation_error_from_start'];;
		
		//Parse to 24H Format in case of times
		if($first_time_compilation_error < '09:00:00'){
			$first_time_compilation_error = date("H:i:s", strtotime($first_time_compilation_error . " PM"));
			$last_time_compilation_error = date("H:i:s", strtotime($last_time_compilation_error . " PM"));
		}

		//create general array if not exists
		if(!isset($results_array[$user_id][$session_id])){
			$results_array[$user_id][$session_id] = array();
		}

		//create particular arrays if not exist
		if(!isset($results_array[$user_id][$session_id]['first_time_compilation_error'])){
			$results_array[$user_id][$session_id]['first_time_compilation_error'] = array();
		}
		if(!isset($results_array[$user_id][$session_id]['last_time_compilation_error'])){
			$results_array[$user_id][$session_id]['last_time_compilation_error'] = array();
		}
		if(!isset($results_array[$user_id][$session_id]['mins_first_compilation_error_from_start'])){
			$results_array[$user_id][$session_id]['mins_first_compilation_error_from_start'] = array();
		}
		if(!isset($results_array[$user_id][$session_id]['secs_first_compilation_error_from_start'])){
			$results_array[$user_id][$session_id]['secs_first_compilation_error_from_start'] = array();
		}
		if(!isset($results_array[$user_id][$session_id]['mins_between_first_and_last_error'])){
			$results_array[$user_id][$session_id]['mins_between_first_and_last_error'] = array();
		}
		if(!isset($results_array[$user_id][$session_id]['secs_between_first_and_last_error'])){
			$results_array[$user_id][$session_id]['secs_between_first_and_last_error'] = array();
		}
		if(!isset($results_array[$user_id][$session_id]['mins_working_from_start'])){
			$results_array[$user_id][$session_id]['mins_working_from_start'] = array();
		}
		if(!isset($results_array[$user_id][$session_id]['secs_working_from_start'])){
			$results_array[$user_id][$session_id]['secs_working_from_start'] = array();
		}

		//push the time for this user, and session. We have to push instead of replace because
		//we can have more than one row with same user_id and session_id in different days
		array_push($results_array[$user_id][$session_id]['first_time_compilation_error'], $first_time_compilation_error);
		array_push($results_array[$user_id][$session_id]['last_time_compilation_error'], $last_time_compilation_error);
		array_push($results_array[$user_id][$session_id]['mins_first_compilation_error_from_start'], $mins_first_compilation_error_from_start);
		array_push($results_array[$user_id][$session_id]['secs_first_compilation_error_from_start'], $secs_first_compilation_error_from_start);
		array_push($results_array[$user_id][$session_id]['mins_between_first_and_last_error'], $mins_between_first_and_last_error);
		array_push($results_array[$user_id][$session_id]['secs_between_first_and_last_error'], $secs_between_first_and_last_error);
		array_push($results_array[$user_id][$session_id]['mins_working_from_start'], $mins_working_from_start);
		array_push($results_array[$user_id][$session_id]['secs_working_from_start'], $secs_working_from_start);

		//recalculate average of mins/secs from start
		$results_array[$user_id][$session_id]['mins_first_compilation_error_from_start_avg'] = calculate_avg_array($results_array[$user_id][$session_id]['mins_first_compilation_error_from_start']);
		$results_array[$user_id][$session_id]['secs_first_compilation_error_from_start_avg'] = calculate_avg_array($results_array[$user_id][$session_id]['secs_first_compilation_error_from_start']);

		//from intervals of time we sum, not avg, because we have to consider the whole process
		$results_array[$user_id][$session_id]['mins_between_first_and_last_error_sum'] = array_sum($results_array[$user_id][$session_id]['mins_between_first_and_last_error']);
		$results_array[$user_id][$session_id]['secs_between_first_and_last_error_sum'] = array_sum($results_array[$user_id][$session_id]['secs_between_first_and_last_error']);
		$results_array[$user_id][$session_id]['mins_working_from_start_sum'] = array_sum($results_array[$user_id][$session_id]['mins_working_from_start']);
		$results_array[$user_id][$session_id]['secs_working_from_start_sum'] = array_sum($results_array[$user_id][$session_id]['secs_working_from_start']);
	}
	//DEBUG
	//print_r($results_array);
	//exit();

	return $results_array;
}


?>