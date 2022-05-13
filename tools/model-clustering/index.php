<?php
require_once("functions/general-functions.php");
require_once("base-data-model.php");
?>

<!DOCTYPE HTML>
<head>
	<title>Colmena Generador de Modelos</title>
</head>

<body>
	<h1>Generador de modelo para estudio Clustering Colmena</h1>
	<h2>Variables analizadas para el cálculo</h2>
	<p>Sesiones consideradas antes del control 1 </p>

	<ul>

<?php
$merged_sessions_c1 = array_merge($selected_sessions_previous_c1_a, $selected_sessions_previous_c1_b);
foreach ($merged_sessions_c1 as $session):
?>
		<li>Sesión de practicas de ID <?= $session ?> </li>

<?php endforeach; ?>
	
	</ul>

	<p>Sesiones consideradas antes del control 2 </p>

	<ul>
<?php
$merged_sessions_c2 = array_merge($selected_sessions_previous_c2_a, $selected_sessions_previous_c2_b, $selected_sessions_previous_c2_c);
foreach ($merged_sessions_c2 as $session):
?>
		<li>Sesión de practicas de ID <?= $session ?> </li>

<?php endforeach; ?>
	
	</ul>

	<p>Asistencia mínima considearda: <?= $minimun_sessions_asistance_percentage ?> % de las sesiones</p>
	<p>Asistencia específica para cada grupo de asignaturas</p>

	<ul>
		<li>Hasta el Control 1 en 2013/2014: <?= $minimun_sessions_asistance_c1_a ?> sesiones </li>
		<li>Hasta el Control 1 en 2014/2015: <?= $minimun_sessions_asistance_c1_b ?> sesiones </li>
		<li>Hasta el Control 2 en 2012/2013: <?= $minimun_sessions_asistance_c2_a ?> sesiones </li>
		<li>Hasta el Control 2 en 2013/2014: <?= $minimun_sessions_asistance_c2_b ?> sesiones </li>
		<li>Hasta el Control 2 en 2014/2015: <?= $minimun_sessions_asistance_c2_c ?> sesiones </li>
	</ul>

	<h2>Resultado del cálculo del modelo BASADO EN USUARIOS</h2>

	<ul>
		<li><a href='./model-users/control1-usuarios.php'>Datos antes del control 1 (Agrupado por usuarios y con Media)</a></li>
		<li><a href='./model-users/control1-usuarios.php?calc_type=sum'>Datos antes del control 1 (Agrupado por usuarios y con Sumas)</a></li>
		<li><a href='./model-users/control1-usuarios-sesiones.php'>Datos antes del control 1 (Agrupado por usuarios pero dejando las sesiones)</a></li>
		<li><a href='./model-users/control2-usuarios.php'>Datos antes del control 2 (Agrupado por usuarios y con Media)</a></li>
		<li><a href='./model-users/control2-usuarios.php?calc_type=sum'>Datos antes del control 2 (Agrupado por usuarios y con Sumas)</a></li>
		<li><a href='./model-users/control2-usuarios-sesiones.php'>Datos antes del control 2 (Agrupado por usuarios pero manteniendo las sesiones)</a></li>
		<li><a href='./model-users/total-usuarios.php'>Todos los datos de los usuarios (Agrupado por usuarios y con Media)</a></li>
		<li><a href='./model-users/total-usuarios.php?calc_type=sum'>Todos los datos de los usuarios (Agrupado por usuarios y con Sumas)</a></li>
	</ul>

	<h2>Resultado del cálculo del modelo BASADO EN SESIONES</h2>
	<ul>
		<li><a href='./model-sessions/control1-sesiones.php'>Datos antes del control 1 (Agrupado por sesiones y con Media)</a></li>
		<li><a href='./model-sessions/control1-sesiones.php?calc_type=sum'>Datos antes del control 1 (Agrupado por sesiones y con Suma)</a></li>
		<li><a href='./model-sessions/control2-sesiones.php'>Datos antes del control 2 (Agrupado por sesiones y con Media)</a></li>
		<li><a href='./model-sessions/control2-sesiones.php?calc_type=sum'>Datos antes del control 2 (Agrupado por sesiones y con Suma)</a></li>
		<li><a href='./model-sessions/2013-sesiones.php'>Datos antes del curso 2012/2013 (Agrupado por sesiones y con Media)</a></li>
		<li><a href='./model-sessions/2013-sesiones.php?calc_type=sum'>Datos antes del curso 2012/2013 (Agrupado por sesiones y con Suma)</a></li>
		<li><a href='./model-sessions/2014-sesiones.php'>Datos antes del curso 2013/2014 (Agrupado por sesiones y con Media)</a></li>
		<li><a href='./model-sessions/2014-sesiones.php?calc_type=sum'>Datos antes del curso 2013/2014 (Agrupado por sesiones y con Suma)</a></li>
		<li><a href='./model-sessions/2015-sesiones.php'>Datos antes del curso 2014/2015 (Agrupado por sesiones y con Media)</a></li>
		<li><a href='./model-sessions/2015-sesiones.php?calc_type=sum'>Datos antes del curso 2014/2015 (Agrupado por sesiones y con Suma)</a></li>
		<li><a href='./model-sessions/total-sesiones.php'>Todos los datos de los usuarios (Agrupado por sesiones y con Media)</a></li>
		<li><a href='./model-sessions/total-sesiones.php?calc_type=sum'>Todos los datos de los usuarios (Agrupado por sesiones y con Suma)</a></li>
	</ul>

</body>





