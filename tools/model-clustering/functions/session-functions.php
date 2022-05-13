<?php

function get_sessions_clustering_data($selected_sessions){
	$returned_array = array();
	foreach($selected_sessions as $session){

		$returned_array[$session] = get_session_clustering_data($session);
	}

	return $returned_array;
}

function get_session_clustering_data($session_id){
	$link = connect();

	$query = sprintf("SELECT * 
					  FROM clustering_session
					  WHERE colmena_session_id = %s
					  ",
		$link->real_escape_string($session_id)
	);

	$result = $link->query($query);
	
	$returned_array = array();
	while($row = $result->fetch_assoc())
		array_push($returned_array, $row);

	return $returned_array;
}

?>