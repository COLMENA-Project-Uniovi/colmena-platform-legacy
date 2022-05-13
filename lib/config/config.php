<?php
$root_path = $_SERVER['DOCUMENT_ROOT']."/";
$base = 'https://'.$_SERVER['HTTP_HOST']."/";

// DATABASE CONFIG
$bd_host = "localhost";
$bd_user = "admin_colmena";
$bd_pass = "c0lm3n4pr0j3c7_$";
$bd_database = "admin_colmena";

/*
$bd_host = "www.pulso.uniovi.es";
$bd_user = "root";
$bd_pass = "M01.R13-G0n";
//$bd_user = "colmena_admin";
$bd_database = "colmena_julia";
*/

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