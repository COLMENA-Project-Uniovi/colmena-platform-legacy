<?php
//DEV
//$root_path = $_SERVER['DOCUMENT_ROOT']."/colmena/";
//$root_private_area_path = $root_path ."/area-privada/";
//$base = 'http://'.$_SERVER['HTTP_HOST']."/colmena/";

//PROD
$root_path = $_SERVER['DOCUMENT_ROOT']."/";
$root_private_area_path = $root_path ."/area-privada/";
$base = 'https://'.$_SERVER['HTTP_HOST']."/";
$base_private_area = $base. "area-privada/";

//DATABASE DEV
//$bd_host = "colmenaproject.es";
// DATABASE PRDO
$bd_host = "localhost";

//DATABASE PARAMETERES
$bd_user = "colmena_portal";
$bd_pass = "C0lm3n4Portal";
$bd_database = "colmena_portal";

//MANDRILL INTEGRATION
$mail_host = "smtp.mandrillapp.com";
$mail_port = 587;
$mail_encryption = "";
$mail_nickname = "Proyecto Colmena";
$mail_username = "colmena.project@gmail.com";
$mail_key = "-GTXFKjhBHBIFCNGRVAnHA";
$mail_subaccount = "welcome";
$template_name = "welcome";
?>