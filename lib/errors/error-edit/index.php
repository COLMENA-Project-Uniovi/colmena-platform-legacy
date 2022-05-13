<?php
session_start();
include_once("../../config/config.php");
include_once($root_path."lib/functions/general-functions.php");
include_once($root_path."lib/functions/errores-functions.php");

$error = array();

$error['error_id'] = $_POST['error_id'];

$error['problem_reason'] = $_POST['problem_reason'];
$error['references'] = $_POST['references'];

edit_error($error);

header("Location: ../../../errors/".$error['error_id']);
exit;

?>