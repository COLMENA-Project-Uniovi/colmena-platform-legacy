<?php
session_start();
include_once("../../config/config.php");
include_once($root_path."lib/functions/general-functions.php");
include_once($root_path."lib/functions/errores-functions.php");

$example = array();

$example['error_id'] = $_POST['error_id'];

$example['start_line_wrong'] = $_POST['start-line-wrong'];
$example['end_line_wrong'] = $_POST['end-line-wrong'];
$example['source_code_wrong'] = $_POST['wrong-source-code'];

$example['start_line_right'] = $_POST['start-line-right'];
$example['end_line_right'] = $_POST['end-line-right'];
$example['source_code_right'] = $_POST['right-source-code'];

$example['explanation'] = $_POST['explanation'];
$example['solution'] = $_POST['solution'];
$example['references'] = $_POST['references'];
$example['user_id'] = $_POST['user-id'];


insert_ejemplo_error($example);

header("Location: ../../../errors/".$example['error_id']);
exit;

?>