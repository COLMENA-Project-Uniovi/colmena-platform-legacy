<?php 
	if(isset($id) && $id != '') : 
		include ($root_path."templates/content/families-template.php"); 	
	else:
		//sacamos los errores de cada familia
		if($role_admin):
			$errores_detectados = get_all_family_errors();
		elseif($role_teacher || $role_student):
			$errores_detectados = get_all_family_errors($logged_user['id']);
		endif;
?>
	<section class="wrapper">
		
		<h2 class="center-title"><?= $text[$lang_sufix]['families_title']; ?></h2>
		<section class="center-subtitle">
			<section class="content-center-subtitle">
		<?php
			if($role_student):
		?>
				<p><?= $text[$lang_sufix]['families_description_student']; ?></p>
		<?php
			else:
		?>
				<p><?= $text[$lang_sufix]['families_description']; ?></p>
		<?php
			endif;
		?>
			</section><!-- .content-center-subtitle -->
		</section><!-- .bloque-presentacion -->

		<section class="bloque-principal sin-sidebar">			
			<section class="bloque-presentacion">
				<section class="bloques-familia">
			<?php 

				//rellenamos el array de las familias con las familias extraidas y imprimimos cabecera de la tabla
				if(!isset($familias))
					$familias = get_familias();
				foreach($familias as $familia):					
					$nombre_familia = $familia['name'. $lang_sufix];
					$id_familia = $familia['id'];
					$num_errores_detectados = count($errores_detectados[$id_familia]);
			?>	
				
					<a href="./families/<?= $id_familia; ?>">
					<section class="bloque-familia entidad">
						<h2><?= $nombre_familia; ?></h2>
						
						<p class="num-errores-familia"><?= $num_errores_detectados; ?></p>
						<p class="texto-errores-familia"><?= $text[$lang_sufix]['detected_errors']; ?></p>
						<div class="clearboth"></div>
					</section><!-- .bloque-familia.entidad -->
					</a>
			<?php
				endforeach;
			?>
				<div class="clearboth"></div>
				</section><!-- .bloques-familia -->
			</section><!-- .bloque-presentacion -->
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->
		<div class="clearboth"></div>
	</section> <!-- .wrapper -->
<?php endif; ?>	