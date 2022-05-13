<?php
function send_email($to, $subject, $body){

	global $mail_key;
	global $mail_username;
	global $mail_nickname;
	global $mail_subaccount;
	global $root_path;
	global $template_name;	

	require_once($root_path.'lib/mail/mandrill-api-php/src/Mandrill.php');	
	try {		
		$mandrill = new Mandrill($mail_key);	

		$message = array(
			'html' => $body,	        
			'subject' => $subject,
			'from_email' => $mail_username,
			'from_name' => $mail_nickname,
			'to' => array(
				array(
					'email' => $to	                
				)
			),	        
//	        'important' => false,
			'track_opens' => null,
			'track_clicks' => null,
			'auto_text' => true,
//	        'auto_html' => null,
			'inline_css' => true
//	        'url_strip_qs' => null,
//	        'preserve_recipients' => null,
//	        'view_content_link' => null,
//	        'bcc_address' => 'message.bcc_address@example.com',
//	        'tracking_domain' => null,
//	        'signing_domain' => null,
//	        'return_path_domain' => null,	        
//	        'tags' => array('carmen villazan')
//			'subaccount' => $mail_subaccount,
//			'google_analytics_domains' => array('example.com'),
//			'google_analytics_campaign' => 'message.from_email@example.com',
//			'metadata' => array('website' => 'www.example.com'),	       	        
		);

		$template_content = array(
						        array(
						            'name' => 'main',
						            'content' =>$body
						        )
						    );
		$async = false;
		$ip_pool = 'Main Pool';
		//$send_at = date('Y-m-d h:i:s', time());	    
		$result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool);
		//$result = $mandrill->messages->send($message, $async, $ip_pool);
		
		return $result[0]['status'] == 'sent' || $result[0]['status'] == 'queued';

	} catch(Mandrill_Error $e) {
		// Mandrill errors are thrown as exceptions
		echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
		throw $e;
	}
}

function send_notification($user_id){	
	$user = get_user($user_id);	
	//$to = $user_id . '@uniovi.es';
	$to = 'soyjulis@gmail.com';
	$subject = 'Bienvenido a Colmena';
	$body = '<h1>Hola ' . $user['name'] . ' ' . $user['surname'] .'</h1>
		<p>Tus datos de ingreso en Colmena son:</p>
		<ul>
			<li>Usuario: ' . $user_id . '</li>
			<li>Contraseña: ' . $user['password'] . '</li>
		</ul>
		<p>Haz click en el siguiente enlace para entrar en el portal: <a href="http://www.pulso.uniovi.es/colmena/">Proyecto Colmena</a></p>';

	return send_email($to, $subject, $body);
}
