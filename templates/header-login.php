<?php
	if(isset($_SESSION['loginok']) && isset($_SESSION['loggedu'])){
		$logged_user = $_SESSION['loggedu'];

		$role_student = false;
		$role_teacher = false;
		$role_admin = false;
		
		switch ($logged_user['role']) {
			case 'student':
				$role_student = true;
				break;
			case 'teacher':
				$role_teacher = true;
				break;
			case 'admin':
				$role_admin = true;
				break;
		}
	}else{	
		$_SESSION['source_url'] = $_SERVER['REQUEST_URI'];
		header("Location: ". $base . "login/");
		exit;
	}
?>