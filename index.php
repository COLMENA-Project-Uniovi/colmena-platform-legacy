<?php session_start();
include_once('./config/config.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="Title" content="COLMENA Project: assistance in programming learning"/>
	<meta name="Author" content="Carlos Fernández Medina · Julia Vallina García · PULSO RESEARCH TEAM"/>
	<meta name="Keywords" content="COLMENA"/>
	
	<!-- TITLE, FAVICON AND BASE -->
	<title>COLMENA Project: assistance in programming learning</title>
	<base href="<?= $base ?>" />
	<link rel="icon" href="./img/favicon.png" type="image/png" />
	
	<link rel="stylesheet" type="text/css" media="all" href="./css/reset.css" />
	<link rel="stylesheet" type="text/css" media="all" href="./css/style-login.css" />	
	<link rel="stylesheet" href="./css/fonts/awesome/css/font-awesome.min.css">
		
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,300italic,700' rel='stylesheet' type='text/css'>
</head>
<body>
	<div class="wrapper">
		<header>
			<section class="content-header">
				<p class="logo"><img src="./img/content/logo.png" /></p>
				<section class="login">
					<form method="post" action="./login/">
						<p><input type="text" name="user" autofocus placeholder="Usuario"/></p>
						<p><input type="password" name="pass" placeholder="Password"/></p>
						<p><input type="submit" value="Entrar / Login" /></p>
						<div class="clearboth"></div>
					</form>
					<?php
						if(isset($_SESSION['error'])){
					?>
							<div class="error">
								Usuario o contraseña incorrectos / Username or password invalid
							</div>
					<?php
							unset($_SESSION['error']);
						}
					?>
				</section><!-- .login -->
				<div class="clearboth"></div>
			</section><!-- .content-header -->
		</header>		
		
		<section class="slogan slogan1">
			<section class="lens">
				<h1></i> Conoce tus errores en programación</h1>
			</section><!-- .lens -->
		</section><!-- .slogan -->

		<section class="content">
			<section class="question">
				<h2>Qué es COLMENA</h2>
				<p>Colmena es una herramienta creada para monitorizar el rendimiento de los alumnos durante las sesiones de prácticas en asignaturas de programación. A través de una plataforma web, los usuarios registrados tienen a su disposición información y métricas sobre el proceso de desarrollo software.</p>
			</section><!-- .question -->

			<section class="question">
				<h2>Qué funcionaliades ofrece</h2>
				<p>Colmena pone al alcance del usuario información sobre los errores generados durante las prácticas que, de otra forma, serían desechados y no se podrían visualizar de ninguna forma. Colmena, a través de técnicas de análisis y visualización de la información, muestra los resultados de los errores generados en cada práctica y los usuarios que han generado más errores potencialmente.
				<p>Además, ofrece un indice con todos los errores generados por el compilador de Java y la posibilidad de documentarlos en más profundidad, para que sean más faciles de comprender y por tanto, de solucionar.</p>
			</section><!-- .question -->

			<section class="question">
				<h2>Quién lo utiliza</h2>
				<p>Colmena está centrado en dos tipos de usuario. El profesor y el alumno.</p>
				
				<section class="user-content">
					<section class="user">
						<h3><i class="fa fa-graduation-cap"></i>Profesores</h3>
						<p>El profesor puede controlar todo lo relacionado con las asignaturas que imparte. Entre las funciones que puede hacer, algunas de las más destacadas son:</p>

						<p class="feature"><i class="fa fa-check"></i>Ver el progreso general de una asignatura</p>
						<p class="feature"><i class="fa fa-check"></i>Ver el progreso general de una sesión</p>
						<p class="feature"><i class="fa fa-check"></i>Ver el progreso general de un estudiante</p>
						<p class="feature"><i class="fa fa-check"></i>Comparar sesiones de una asignatura</p>
						<p class="feature"><i class="fa fa-check"></i>Ver usuario en una asignatura</p>
						<p class="feature"><i class="fa fa-check"></i>Documentar y extender el índice de errores</p>
						
					</section>

					<section class="user">
						<h3><i class="fa fa-users"></i>Alumnos</h3>
						<p>El alumno, de manera individual, puede visualizar sus resultados.</p>

						<p class="feature"><i class="fa fa-check"></i>Ver su resumen general</p>
						<p class="feature"><i class="fa fa-check"></i>Conocer errores más comunes</p>
						<p class="feature"><i class="fa fa-check"></i>Ver su resumen en una asignatura</p>
						<p class="feature"><i class="fa fa-check"></i>Consutar el índice de errores</p>
						
					</section>
				</section><!-- .user-content -->

				<div class="clearboth"></div>


			</section><!-- .question -->
		</section><!-- .content -->
		
		<section class="slogan slogan2">
			<section class="lens">
				<h1>Un portal fácil de usar, para obtener feedback de manera visual</h1>
				<p><img src="./css/screenshot.png"/></p>
				<p><img src="./css/screenshot2.png"/></p>
				<p><img src="./css/screenshot3.png"/></p>
				<p><img src="./css/screenshot4.png"/></p>
				<p><img src="./css/screenshot5.png"/></p>
			</section><!-- .lens -->
		</section><!-- .slogan -->

		<footer>
			<p>Colmena es un proyecto del grupo de investigación PULSO</p>
			<p>Desarrollado por Carlos F. Medina, Julia Vallina y Juan Ramón Pérez</p>
			<p>2017 · PULSO RESEARCH TEAM</p>
		</footer>
	</div>
</body>
</html>