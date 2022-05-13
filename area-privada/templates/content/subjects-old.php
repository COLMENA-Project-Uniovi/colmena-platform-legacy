	<section class="wrapper">
<?php if(isset($id) && $id != ''): ?>
		<script type="text/javascript">
		  // Load the Visualization API and the piechart package.
		  google.load('visualization', '1.0', {'packages':['corechart']});

		  // Set a callback to run when the Google Visualization API is loaded.
		  google.setOnLoadCallback(loadSubjectCharts);

		</script>
		<?php 			
			$subject_id = $id;
			$subject = get_asignatura($subject_id);
			$subject_name = $subject['subject_name'];
			$subject_year = $subject['academic_year'];					
		?>
		<h1 class="center-title"><?= $subject_name; ?> (<?= $subject_year; ?>) </h1> 	
		
		<section class="bloque-principal con-sidebar">			
			<section class="bloque-presentacion">
				<h2>FAMILIES ERRORS IN THIS SUBJECT</h2>
				<section class="grafico-totales-familia">
				<?php
					$families_frequency = get_totales_familia_asignatura($subject_id);

					foreach ($families_frequency as $family) {						
						$family_name = $family_names[$family['first_family']];
						$total_familia = $family['totales'];

				?>
					<section class="datos-errores-familia-grafico datos-grafico">
						<p class="nombre-familia-grafico"><?= $family_name; ?></p>
						<p class="total-familia-grafico"><?= $total_familia; ?></p>
					</section><!-- .datois-grafico -->
				<?php
					}
				?>
					<div id="errores_familia_chart_div"></div>
					<div class="clearboth"></div>
				</section><!-- .grafico-totales-familia -->

				<h2>EVOLUTION OF ERRORS THROUGH SESSIONS (BY FAMILY)</h2>
				<section class="grafico-evolicion-familias-juntas">
				<?php
					$sessions = get_sesiones_asignatura($subject_id);	
					foreach($sessions as $session){
						$session_id = $session['id'];
						$session_name = $session['session_name'];
				?>
				<section class="datos-grafico-sesion" id="<?= $session_name; ?>">	
					<?php	
							$families_frequency = get_totales_familia_sesion($session_id);
							foreach ($families_frequency as $family) {								
								$family_name = $family_names[$family['first_family']];
								$total_familia = $family['totales'];

					?>
						<section class="datos-errores-familias-juntas-grafico datos-grafico">
							<p class="nombre-familias-juntas-grafico"><?= $family_name; ?></p>
							<p class="total-familias-juntas-grafico"><?= $total_familia; ?></p>
						</section><!-- .datos-grafico -->
					<?php
						}
					?>
				</section><!-- .datos-grafico-sesion -->
				<?php
				}
				?>
					<div id="evolucion_errores_familias_juntas_chart_div"></div>
					<div class="clearboth"></div>
				</section><!-- .grafico-evolucion-familias-juntas -->
				
			
				<?php 
					//sacamos los usuarios de esa asignatura
					$usuarios_sesion = get_usuarios_asignatura($subject_id);
					//preparo array bidimensional de usuarios
					$usuarios_familias = array();
					//preparo array con las familias
					$array_familias = array();
				?>
					<h2>USERS/FAMILIES ERRORS IN SUBJECT '<?= $subject_name; ?>'</h2>
					<h3 class="centered">Total users in this subject: <?= count($usuarios_sesion) ?></h3>
					<table class="ancha centrada">
						<thead>
							<th>User ID</th>
				<?php 
							//rellenamos el array de las familias con las familias extraidas y imprimimos cabecera de la tabla
							$familias = get_familias_diferentes();							
							foreach ($familias as $key => $value) {								
								$nombre_familia = $value;
								if($nombre_familia != "Javadoc"){
									array_push($array_familias, $nombre_familia);
				?>
							<th><?= str_replace("Related", "", $nombre_familia); ?></th>
				<?php
								}
							}

				?>
						
						</thead>
				<?php
					
					foreach ($usuarios_sesion as $usuario_sesion) {						
						$nombre_usuario = $usuario_sesion['user_id'];
						//primera dimension del array (los usuarios)
						array_push($usuarios_familias, $nombre_usuario);
				?>
					<tr>
						<td><a href="./users/<?= $nombre_usuario; ?>"><?= $nombre_usuario; ?></a></td>
				<?php 
						//inicializo la segunda dimension de familias a 0
						foreach($array_familias as $family){
							$usuarios_familias[$nombre_usuario][$family] = 0 ;
						}

						//extraigo el numero de errores de una familia para ese usuario
						$familias_usuario_asignatura = get_totales_errores_familias_usuario_asignatura($nombre_usuario, $subject_id);
						foreach ($familias_usuario_asignatura as $familia) {							
							$nombre_familia_usuario = $family_names[$familia['first_family']];
							$totales_familia_usuario = $familia['totales'];
							//lo meto en el array bidimensional (reemplazando el 0 si hiciera falta)
							$usuarios_familias[$nombre_usuario][$nombre_familia_usuario] = $totales_familia_usuario;
						}

						//saco por pantalla
						foreach($array_familias as $family){
				?>
						<td><a href="./users/<?= $nombre_usuario; ?>"><?= $usuarios_familias[$nombre_usuario][$family]; ?></a></td>
				<?php
						}	
				?>
					</tr>
				<?php		
					}
				?> 
					</table>

				
				<h2>TOP 30 ERRROS BY FREQUENCY IN SUBJECT '<?= $subject_name; ?>'</h2>
				<?php $errors_in_all = get_errores_totales_asignatura($subject_id); ?>
				<h3 class="centered">Total errors in this subject: <?= $errors_in_all; ?></h3>
					
				<table  class="ancha">
					<thead>
						<th>% of total</th>
						<th>Error ID</th>
						<th>Frequency</th>
						<th>Error Family</th>
						<th>Error Name</th>
					</thead>

					<tbody>
					
				<?php 
					$errors = get_errores_asignatura($subject_id, 30);
					foreach ($errors as $error) {						
						$error_message = $error['custom_message'];
						$pattern_message = $error['message'];
						$totales_error = $error['totales'];
						$error_id = $error['error_id'];
						$error_family = $family_names[$error['first_family']]; 
				?>
					<tr>
						<td><a href="./errors/<?= $error_id; ?>"><?= round(($totales_error/$errors_in_all)*100, 2); ?> %</a></td>
						<td><a href="./errors/<?= $error_id; ?>"><?= $error_id; ?></a></td>
						<td><a href="./errors/<?= $error_id; ?>"><?= $totales_error; ?></a></td>
						<td><a href="./errors/<?= $error_id; ?>"><?= str_replace("Related", "", $error_family); ?></a></td>
						<td><a href="./errors/<?= $error_id; ?>"><?= $pattern_message; ?></a></td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>
			</section><!-- .bloque-presentacion -->
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->
		<?php include($root_private_area_path."templates/sidebar/sidebar-subjects.php"); ?>
		<div class="clearboth"></div>
<?php endif; ?>		
	</section> <!-- .wrapper -->