<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>		
		<meta name="Author" content="Carlos Fernández Medina · PULSO RESEARCH TEAM"/>
		<meta name="Keywords" content="COLMENA"/>
		
		<!-- TITLE, FAVICON AND BASE -->
		<title>COLMENA: Monitoring learning improvement</title>
		<base href="<?= $base ?>" />
		<link rel="icon" href="./img/favicon.png" type="image/png" />
		
		<!-- CSS SPECIFIC FOR WEBSITE-->
		<link rel="stylesheet" type="text/css" media="all" href="./css/reset.css" />
		
		<link rel="stylesheet" type="text/css" media="all" href="./css/style.css" />
		
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,300italic,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
		
		<!-- CSS GENERAL LIBRARIES -->	
		<!-- Font awesome -->
		<link rel="stylesheet" href="./css/fonts/awesome/css/font-awesome.min.css">
		<!-- Jquery -->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<!-- JS SPECIFIC FOR WEBSITE-->
		<script type="text/javascript" src="./js/plugins.js"></script>
		<script type="text/javascript" src="./js/colmena/submenu.js"></script>
		<script type="text/javascript" src="./js/colmena/charts.js"></script>
		<script type="text/javascript" src="./js/colmena/isotope.js"></script>
		<script type="text/javascript" src="./js/colmena/search.js"></script>

		
		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<link rel="stylesheet" type="text/css" media="all" href="./css/style-ie-lw.css" />
		<![endif]-->
		
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" media="all" href="./css/style-ie.css" />
		<![endif]-->
		
	</head>

	<?php
		include($root_path. 'lib/functions/general-functions.php');
		include($root_path. 'lib/functions/lang-functions.php');
		include($root_path. 'lib/functions/familias-functions.php');
		include($root_path. 'lib/functions/asignaturas-functions.php');
		$family_names = get_nombres_familias();
	?>

	<body>

		<script type="text/javascript">
			$(document).ready(function(){
				//plugins
				loadPlugins();
				
			});
	    </script>
	<div id="wrapper">
	<header>
		<section id="content-header">
			<section class="col-izq-header">
				<a href="./">
					<section id="logo">
						<img alt="COLMENA" src="./img/content/logo.png" />
					</section><!-- #logo -->
				</a>
			</section><!-- .col-izq-header -->
					
			<section class="col-der-header">
				<section class="barra-top">
					<div class="clearboth"></div>
				</section><!-- .barra-top -->
				<section class="slogan">
					<section class="texto-slogan">
						<?= $text[$lang_sufix]['header_slogan']; ?>
					</section><!-- .texto-slogan -->

					<p class="langs">
	<?php
						$es_class = "";
						$en_class = "";

						if($lang_sufix == "_es"){
							$es_class = "current";
						}else{
							$es_class = "";
						}

						if($lang_sufix == "_en"){
							$en_class = "current";
						}else{
							$en_class = "";
						}

	?>
						<a class="<?= $es_class; ?>" href="./lib/lang/?l=_es">ES</a> | 
						<a class="<?= $en_class; ?>" href="./lib/lang/?l=_en">EN</a>
					</p><!-- .langs -->
					<div class="clearboth"></div>
				</section><!-- .slogan -->
			</section><!--.col-der-header -->
			
			<div class="clearboth"></div>
		</section><!-- #content-header -->
	</header>	
<?php 
include($root_path . 'templates/menu/menu-principal.php');
?>
<section class="wrapper portada">
	<section class="bloque-principal">						
		<h2 class="center-title"><?= $text[$lang_sufix]['error-404'] ?></h2>
		<h2 class="center-subtitle"><?= $text[$lang_sufix]['error-404-message'] ?></h2>
	</section>
</section> <!-- .wrapper .portada -->