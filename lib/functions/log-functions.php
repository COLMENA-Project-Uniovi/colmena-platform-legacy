<?php

/* LOG */
function save_log_file($user_id, $file, $id_elemento, $tipo_elemento){
	
	$link = connect();
	
	$query = sprintf("INSERT INTO log_files (user_id, file_name, id_elemento, tipo_elemento, date_time, ip, browser) VALUES ('%s','%s','%s','%s','%s','%s','%s')",
		$link -> real_escape_string($user_id),
		$link -> real_escape_string($file),
		$link -> real_escape_string($id_elemento),
		$link -> real_escape_string($tipo_elemento),
		$link -> real_escape_string(date("Y-m-d H:i:s")),
		$_SERVER['REMOTE_ADDR'],
		$_SERVER['HTTP_USER_AGENT']
	);

	return $link->query($query);
}

?>