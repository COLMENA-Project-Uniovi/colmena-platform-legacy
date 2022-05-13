<?php

/* BD */
$mysqli; 
date_default_timezone_set('Europe/London');

// -------------------------------------------------------------------
// DATABASE CONFIG
$bd_host = "colmenaproject.es";
$bd_user = "colmena";
$bd_pass = "C0lm3n4";
$bd_database = "colmena_test";

function connect(){
	global $bd_host;
	global $bd_user;
	global $bd_pass;
	global $bd_database;	
	$mysqli = new mysqli($bd_host, $bd_user, $bd_pass, $bd_database) or die("Error " . mysqli_error($mysqli)); 
	$mysqli->query("SET NAMES UTF8");

	return $mysqli;
}

?>