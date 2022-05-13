<?php
/* BD */
$mysqli; 

function connect(){
	global $bd_host;
	global $bd_user;
	global $bd_pass;
	global $bd_database;	
	$mysqli = new mysqli($bd_host, $bd_user, $bd_pass, $bd_database) or die("Error " . mysqli_error($mysqli)); 
	$mysqli->query("SET NAMES UTF8");

	return $mysqli;
}

/* FUNCIONES AUXILIARES */
// Función de comparación
function cmp($a, $b) {
    if ($a['total'] == $b['total']) {
        return 0;
    }
    return ($a['total'] > $b['total']) ? -1 : 1;
}

function cmp_average($a, $b) {
	$first = $a['total'] / (count($a) - 2);
	$second = $b['total'] / (count($b) - 2);
    if ($first == $second) {
        return 0;
    }
    return ($first > $second) ? -1 : 1;
}

function cmp_nat($a, $b) {
    if ($a == $b) {
        return 1;
    }
    return ($a > $b) ? -1 : 1;
}

/* FECHAS */
function parse_fecha($datetime){
	return date("d-m-Y", strtotime($datetime));
}

function format_date_to_datetime($date){
	return date("Y-m-d H:i:s", strtotime($date));
}

function format_date_eng_to_sp($date){
	return date("d-m-Y", strtotime($date));
}

function format_date_sp_to_en($date){
	return date("Y-m-d", strtotime($date));
}

function get_fecha_texto($datetime){
	setlocale(LC_ALL,"es_ES");
	$date = strtotime($datetime);

	$date = strftime("%e de %B de %Y", $date);

	return $date;
}

function is_fecha_valida($datetime){
	$fecha_parseada = date_parse($datetime);
	return checkdate($fecha_parseada['month'], $fecha_parseada['day'], $fecha_parseada['year']);
}


function get_fecha_para_agenda($datetime){
	setlocale(LC_ALL,"es_ES");
	$date = strtotime($datetime);

	$date = strftime("%e de %B de %Y", $date);

	return $date;
}

/* RESUMEN */

function get_resume($text){
	$words = explode(" ", $text, 19);
	$words[count($words)-1] = "";
	return implode(" ", $words)."...";
}

/* DIRECOTRIOS DE IMAGENES */

function get_img_principal($id, $path){
					
	$nombre_directorio_absoluto = $path . $id . "/";
							
	$nombre_fichero_jpg = 'principal.jpg';
	$nombre_fichero_png = 'principal.png';
	$imagen_principal = "generic/principal.png";
	
	if (file_exists($nombre_directorio_absoluto .  $nombre_fichero_jpg)) {
		$imagen_principal = $id . "/" . $nombre_fichero_jpg;
	}else if(file_exists($nombre_directorio_absoluto .  $nombre_fichero_png)){
		$imagen_principal = $id . "/" . $nombre_fichero_png;
	}

	return $imagen_principal;
}

function existe_archivo($file){
	return file_exists($file);
}


/* DESCARGAR FICHERO */

function descargar_archivo($basename, $filename){
	$basefichero = basename($basename);
	header( "Content-Type: application/octet-stream");
	header( "Content-Length: ".filesize($filename));
	header( "Content-Disposition:attachment;filename=" .$basefichero);
	readfile($filename);
}


// EMVIAR EMAIL OBSOLETO
/*
function send_email($to, $subject, $body){
	global $mail_host;
	global $mail_port;
	global $mail_encryption;
	global $mail_nickname;
	global $mail_username;
	global $mail_pass;

	try{
		// Create the Transport
		$transport = Swift_SmtpTransport::newInstance()
			->setHost($mail_host)
			->setPort($mail_port)
			->setEncryption($mail_encryption)
			->setUsername($mail_username)
			->setPassword($mail_pass)
		;

		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
					
			->setCharset("utf-8")
			// Give the message a subject
			->setSubject($subject)

			// Set the From address with an associative array
			->setFrom(array($mail_username => $mail_nickname))

			// Set the To addresses with an associative array
			//->setTo(array($envio['email'] => 'Peiblox Gmail'))
			->setTo(array(trim($to) => ''))

			// Give it a body
			->setBody($body, 'text/html')

			// And optionally an alternative body
			//->addPart('<h1>Cuerpo del mensaje</h1>', 'text/html')

			// Optionally add any attachments
			//->attach(Swift_Attachment::fromPath('my-document.pdf'))
			;

		// Pass a variable name to the send() method
		return $mailer->send($message, $failures);
	}catch(Exception $e){
		echo $e->getMessage();
		return false;
	}
}
*/

function formatString($string){

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
    return $string;
}


?>