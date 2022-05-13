<section class="wrapper">
<?php 

	$family_id = $id; 
	$family = get_familia($family_id);
	$family_name = $family['name'. $lang_sufix];
	$family_description = $family['description' . $lang_sufix];

	//sacamos los errores de cada familia
	if ($role_admin) :
		$errores_familia = get_family_errors($family_id);
	elseif($role_teacher) :
		$errores_familia = get_family_errors($family_id, $logged_user['id']);
	else :
		$errores_familia = get_family_errors($family_id, $logged_user['id'], true);
	endif;	
					
?>
	<h1 class="center-title"><?= $family_name; ?></h1>
	
<?php
	if(!empty($family_description)):
?>	
	<section class="center-subtitle">	
		<section class="content-center-subtitle">
			<?= $family_description; ?>	
		</section><!-- .content-center-subtitle -->
	</section><!-- .bloque-presentacion -->

<?php
	endif;
?>

	<div class="clearboth"></div>

	<section class="bloque-principal">			
		<section class="bloque-presentacion">

			<section id="options">
				<div class="first-search-parameter">
					<h3><?= $text[$lang_sufix]['sort_by']; ?></h3>
					<ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
				    <!--  <li><a href="./errors/#sortBy=original-order" data-option-value="original-order" data>Original</a></li>-->
				      <li><a href="./errors/#sortBy=name" data-option-value="name"><?= $text[$lang_sufix]['name']; ?></a></li>
				      <li><a href="./errors/#sortBy=message" data-option-value="message"><?= $text[$lang_sufix]['message']; ?></a></li>
				      <li><a href="./errors/#sortBy=times" data-option-value="times" class="selected"><?= $text[$lang_sufix]['times_that_appear']; ?></a></li>
				  	<?php
				  		if ($role_student) :
				  	?>
				      <li><a href="./errors/#sortBy=timesuser" data-option-value="timesuser"><?= $text[$lang_sufix]['times_that_appear_user']; ?></a></li>
				    <?php
				    	else:
				    ?>
				      <li><a href="./errors/#sortBy=users"  data-option-value="users"><?= $text[$lang_sufix]['users_who_have']; ?></a></li>
				      <li><a href="./errors/#sortBy=subjects" data-option-value="subjects"><?= $text[$lang_sufix]['subjects_where_appear']; ?></a></li>
				    <?php
				    	endif;
				    ?>
				   	  <div class="clearboth"></div>
				    </ul>
				 </div><!-- .first-search-parameter -->
				 <div class="second-search-parameter">
				    <h3><?= $text[$lang_sufix]['sort_direction']; ?></h3>

				    <ul id="sort-direction" class="option-set clearfix" data-option-key="sortAscending">
			    		<li><a href="./errors/#sortAscending=false" data-option-value="false" class="selected">↓</a></li>
				     	<li><a href="./errors/#sortAscending=true" data-option-value="true" >↑</a></li>
				      <div class="clearboth"></div>
				    </ul>
				</div><!-- .second-search-parameter -->
				<div class="clearboth"></div>
				<div class="search-box">
					<h3><?= $text[$lang_sufix]['direct_search']; ?></h3>
					<select class="select-errores">
						<?php 
							foreach ($errores_familia as $error){
								$error_id = $error['error_id'];
								$error_name = $error['name'];
						?>
							<option value="<?= $error_id; ?>"><?= $error_name; ?></option>
						<?php
							}
						?>
					</select>
				</div><!-- .search-box-->
				<div class="clearboth"></div>
			</section>

			<div class="clearboth"></div>

			<section class="bloque-familia error-list">
			
			<?php 
				$metadata_errores = array();	
			?>
				<section class="bloques-errores <?= strtolower(str_replace(" ", "_", $family_name)); ?>">		
			
			<?php				
				foreach ($errores_familia as $error):
					$error_id = $error['error_id'];
					$error_name = $error['name'];
					$error_message = $error['message'];
					$error_total = $error['total'];
					$error_num_subjects = $error['num_subjects'];
					$error_num_users = $error['num_users'];
					$error_gender = strtolower($error['gender']);
					$icon = $error_gender == 'warning' ? 'warning' : 'times-circle';
			?>
						<section class="bloque-error show bloque-familia entidad largo">
							<div class="mensaje-error">
								<div class="<?= $error_gender ?>"><i class="fa fa-<?= $icon ?> fa-lg"></i></div>
								<h2><a href="./errors/<?= $error_id; ?>">[<?= $error_name; ?>] <?= $error_message; ?></a></h2>
							</div>
							<section class="parametros-error">
								<section class="parametro-bloque-error times">									
									<p class="num-errores-familia cantidad-parametro-bloque-error "><?= $error_total; ?></p>
									<p class="texto-errores-familia nombre-parametro-bloque-error"><?= $text[$lang_sufix]['times_that_appear']; ?></p>
									<div class="clearboth"></div>
								</section><!-- .familia-bloque-usuario <?= $family; ?> -->
							<?php
								if ($role_student) :
							?>
								<section class="parametro-bloque-error timesuser">									
									<p class="num-errores-familia cantidad-parametro-bloque-error "><?= $error['total_user']; ?></p>
									<p class="texto-errores-familia nombre-parametro-bloque-error"><?= $text[$lang_sufix]['times_that_appear_user']; ?></p>
									<div class="clearboth"></div>
								</section><!-- .familia-bloque-usuario <?= $family; ?> -->
							<?php
								else :
							?>

								<section class="parametro-bloque-error users">
									<p class="num-errores-familia cantidad-parametro-bloque-error"><?= $error_num_users; ?></p>
									<p class="texto-errores-familia nombre-parametro-bloque-error"><?= $text[$lang_sufix]['users_who_have']; ?></p>			
									<div class="clearboth"></div>
								</section><!-- .familia-bloque-usuario -->

								<section class="parametro-bloque-error subjects">									
									<p class="num-errores-familia cantidad-parametro-bloque-error "><?= $error_num_subjects; ?></p>
									<p class="texto-errores-familia nombre-parametro-bloque-error"><?= $text[$lang_sufix]['subjects_where_appear']; ?></p>
				
									<div class="clearboth"></div>
								</section><!-- .familia-bloque-usuario -->
							<?php
								endif;
							?>
								<div class="clearboth"></div>
							</section><!-- parametros-error -->
							<div class="clearboth"></div>
						</section><!-- .bloque-error -->

				<?php
					endforeach;
				?> 

					<div class="clearboth"></div>
					</section><!-- .bloques-errores -->
					<div class="clearboth"></div>

				</section><!-- .error-list -->
			</section><!-- .bloque-presentacion -->
		</section><!-- .bloque-principal -->
		<div class="clearboth"></div>
	</section> <!-- .wrapper -->	
	<script type="text/javascript">
		loadIsotopeErrors();
	</script>