<?php session_start();

unset($_SESSIN['lang_sufix']);

$_SESSION['lang_sufix'] = $_GET['l'];

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>