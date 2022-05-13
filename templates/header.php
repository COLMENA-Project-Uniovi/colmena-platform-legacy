<?php
	
	include_once($root_path."lib/functions/lang-functions.php");
	include_once($root_path."lib/functions/user-functions.php");
	include_once($root_path."lib/functions/general-functions.php");
	include_once($root_path."lib/functions/asignaturas-functions.php");	
	include_once($root_path."lib/functions/sesiones-functions.php");	
	include_once($root_path."lib/functions/errores-functions.php");	
	include_once($root_path."lib/functions/familias-functions.php");	
	include_once($root_path."lib/functions/usuarios-functions.php");
	include_once($root_path."lib/functions/colmena-coeficient-functions.php");

?>
<!DOCTYPE html>
<html>
	<?php include("head.php"); ?>

	<?php
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