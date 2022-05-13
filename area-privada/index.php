<?php session_start();
	include_once("../config/config.php");
	include($root_private_area_path."templates/header-login.php");

	if($logged_user['role']=='student'){

		header("Location: " . $base_private_area . "users/".$logged_user['id']);
		exit();
	}

	include($root_private_area_path."templates/header.php"); 
	include($root_private_area_path."templates/menu/menu-principal.php");
	include($root_private_area_path."templates/content/home.php");
	include($root_private_area_path."templates/footer.php"); 
?>