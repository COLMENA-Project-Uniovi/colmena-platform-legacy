<?php
session_start();
if(!isset($_SESSION['loginok'])){
	header("Location: " . $html_base . "login/");
	exit;
}
?>