<?php
	require_once("../config/config.php");		
	require_once($root_path."lib/functions/lang-functions.php");
	require_once($root_path."lib/functions/general-functions.php");
	require_once($root_path."lib/functions/usuarios-functions.php");
	require_once($root_path."lib/mail/functions.php");
	$user_id = $_POST['user_id'];

	if (send_notification($user_id)) :
	//if (true) :
		set_notified_user($user_id, 1);
?>	
	<span class="sent-ok"><i class="fa fa-check"></i> Enviado a <?= $_POST['user_id']; ?></span>
<?php
	else:
?>
	<span class="sent-error"><i class="fa fa-close"></i> Error enviando a <?= $_POST['user_id']; ?></span>
<?php
	endif;
?>