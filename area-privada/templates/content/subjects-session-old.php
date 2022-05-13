	<section class="wrapper">
		<script type="text/javascript">
		  // Load the Visualization API and the piechart package.
		  google.load('visualization', '1.0', {'packages':['corechart']});

		  // Set a callback to run when the Google Visualization API is loaded.
		  google.setOnLoadCallback(loadSessionCharts);

		</script>
	
		<?php 
			$session_id = $id;
			$session = get_sesion($session_id);
		  	$session_name = $session['session_name'];
		  	$session_week = $session['week'];
		  	$subject_parent = get_asignatura($session['subject_id']);
		?>

		<h1 class="center-title"><a href="./subjects/<?= $subject_parent['id']; ?>"><?= $subject_parent['subject_name'];?> </a> » <?= $session_name; ?> (week <?= $session_week; ?>) </h1> 
		<section class="bloque-principal con-sidebar">			
			<section class="bloque-presentacion">
				<h2 class="titulo-seccion">FAMILIES ERRORS IN THIS SESSION</h2>
				<section class="grafico-totales-familia">
				<?php
					$families_frequency = get_totales_familia_sesion($session_id);					
					foreach ($families_frequency as $id => $family) {
						$family_name = $family_names[$id];
						$total_familia = $family;

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
				<?php 
					//preparo array bidimensional de usuarios
					$usuarios_familias = array();
					//preparo array con las familias
					$array_familias = array();
					//sacamos los usuarios de esa sesion
					$usuarios_sesion = get_usuarios_sesion($session_id);
					
				?>
					<h2> USERS/FAMILIES ERRORS IN THIS SESSION</h2>
					<h3 class="centered">Total users in this session: <?= count($usuarios_sesion)?></h3>
					
					<table class="ancha centrada">
						<thead>
							<th>User ID</th>
				<?php 
							//rellenamos el array de las familias con las familias extraidas y imprimimos cabecera de la tabla
							$familias = get_familias_diferentes();
							foreach ($familias as $familia) {
								$nombre_familia = $familia;
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
						$familias_usuario_sesion = get_errores_familias_usuario_sesion($nombre_usuario, $session_id);
						foreach ($familias_usuario_sesion as $familia) {
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

				
				
				<h2>TOP 20 ERRROS BY FREQUENCY IN SESSION '<?= $session_name; ?>'</h2>
				<?php $errors_in_all =  get_errores_totales_sesion($session_id); ?>
				<h3 class="centered">Total errors in this session: <?= $errors_in_all; ?></h3>
				
				<table  class="ancha">
					<thead>
						<th> % of total </th>
						<th>Error ID</th>
						<th>Frequency</th>
						<th>Error Family</th>
						<th>Error Name</th>
					</thead>

					<tbody>
					
				<?php 
					$errors = get_errores_sesion($session_id, 20);
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
		<?php include($root_private_area_path."templates/sidebar/sidebar-session.php"); ?>	
		<div class="clearboth"></div>
	</section> <!-- .wrapper -->