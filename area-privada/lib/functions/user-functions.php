<?php
/* LOGIN */
function is_registered($user){
	$link = connect();

	$query = sprintf("SELECT * FROM colmena_user WHERE id = '%s' and password = '%s' and active = '1' LIMIT 1",
		$link->real_escape_string($user['id']),
		$link->real_escape_string($user['password'])
	);

	$result = $link->query($query);

	if($result->num_rows == 0){
		return false;
	}
	
	return $result->fetch_assoc();
}
function register_session($user){
	unregister_sessions();
	$link = connect();

	$query = sprintf("INSERT INTO user_sessions (id, user, date_time, ip, browser) VALUES ('%s','%s','%s','%s','%s')",
		$link -> real_escape_string(sha1($user.time())),
		$link -> real_escape_string($user),
		$link -> real_escape_string(date("Y-m-d H:i:s")),
		$_SERVER['REMOTE_ADDR'],
		$_SERVER['HTTP_USER_AGENT']
	);
		
	return $link->query($query);
}

function unregister_sessions(){
	$link = connect();

	global $session_timeout;

	$query = sprintf("UPDATE user_sessions SET active = '0' WHERE date_time < '%s'",
		date("Y-m-d H:i:s",time()-$session_timeout));

	return $link->query($query);
}
