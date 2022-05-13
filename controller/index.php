<?php
session_start();
// configuration
include_once("../lib/config/config.php");
include($root_path."templates/header-login.php");

$template = $_GET['template'];
if(isset($_GET['id'])) {
	$id = $_GET['id'];	
	if(substr($id, -1) == '/')
		$id = substr($id, 0, strlen($id) -1);	
}
if(isset($_GET['limite'])) {
	$limite = $_GET['limite'];	
	if(substr($limite, -1) == '/')
		$limite = substr($limite, 0, strlen($limite) -1);	
}
if(isset($_GET['ida'])) {
	$ida = $_GET['ida'];	
	if(substr($ida, -1) == '/')
		$ida = substr($ida, 0, strlen($ida) -1);	
}
if(isset($_GET['subject_id'])) {
	$get_subject_id = $_GET['subject_id'];	
	if(substr($get_subject_id, -1) == '/')
		$get_subject_id = substr($get_subject_id, 0, strlen($get_subject_id) -1);	
}
if(isset($_GET['sessions_ids'])) {
	$sessions_ids = $_GET['sessions_ids'];	
	if(substr($sessions_ids, -1) == '/')
		$sessions_ids = substr($sessions_ids, 0, strlen($sessions_ids) -1);	
}
if(substr($template, -1) == '/'){
	$template = substr($template, 0, strlen($template) -1);
}


if(!file_exists($root_path."templates/content/".$template.".php")){
	http_response_code(404);	
	include ($root_path."templates/content/404.php");
	include($root_path."templates/footer.php");
	exit;
}

//$page_title = ucfirst(str_replace('-', ' ',  $template));
include($root_path."templates/header.php"); 
include($root_path."templates/menu/menu-principal.php");

// expecific content

include ($root_path."templates/content/".$template.".php"); 	


// footer
if($template == "errors-add-example"){
	include($root_path."templates/footer.php");
}else{
	include($root_path."templates/footer.php");
}

?>