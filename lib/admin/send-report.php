<?php
	require_once("../config/config.php");		
	require_once($root_path."lib/mail/functions.php");
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	$to = $_POST['user'];
	$to = 'soyjulis@gmail.com';

	if (send_email($to, $subject, $body)) :
	//if (true) :

?>	
	<span class="sent-ok"><i class="fa fa-check"></i>Envío correcto</span>
<?php
	else:
?>
	<span class="sent-error"><i class="fa fa-close"></i>Error en el envío</span>
<?php
	endif;
?>