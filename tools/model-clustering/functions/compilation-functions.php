<?php

//Get all the compilations error from an specific subject by user and session
function get_colmena_compilations_error($colmena_subject_table){
	$link = connect();

	$query = sprintf("SELECT user_id, session_id, count(*) as compilations 
					  FROM(
						  SELECT user_id, session_id, timestamp
						  FROM %s 
						  GROUP BY user_id, session_id, timestamp
				  	  ) as portimestamps
					  GROUP BY user_id, session_id",
		$link->real_escape_string($colmena_subject_table)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc()){
		$user_id = $row['user_id'];
		$session_id = $row['session_id'];
		$returned_array[$user_id][$session_id] = $row['compilations'];
	}

	return $returned_array;
}

//TODO: COMPILATIONS OK
function get_colmena_compilation_ok($colmena_subject_table){
	return "";
}

?>