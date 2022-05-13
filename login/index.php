<?php session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	require_once("../config/config.php");

	require_once($root_private_area_path. "lib/functions/general-functions.php");
	require_once($root_private_area_path. "lib/functions/user-functions.php");
	
	$user = array();
	$user['id'] = $_POST['user'];
	$user['password'] = $_POST['pass'];

	if($logged_user = is_registered($user)){
		$_SESSION['loginok'] = sha1($logged_user['id'].time());
		$_SESSION['loggedu'] = $logged_user;

		if(isset($_SESSION['source_url'])){
			$source_url = $_SESSION['source_url'];
			unset($_SESSION['source_url']);
			header("Location: " . $source_url);
			exit();
		}else {
			header("Location: " . $base_private_area);
			exit();
		}

	} else {
		$_SESSION['error'] = "Error";
		header("Location: " . $base);
		exit;
	}
}else{
	header("Location: " . $base);
	exit();
}
?>

