	<section class="wrapper">
		<?php 
		if(isset($id) && $id != ''):
			$session_id = $id;
			$session = get_sesion($session_id);
		  	$session_name = $session['session_name'. $lang_sufix];
		  	$session_objectives = $session['objectives'. $lang_sufix];
		  	$session_week = $session['week'];
		  	$subject_parent = get_asignatura($session['subject_id']);
		  	$subject_table = $subject_parent['table_name'];
		  	// número de markers de la sesión
			$markers = get_num_markers_by_session($session_id, $subject_table);
		  	// número de markers de la asignatura
			$markers_subject = get_num_markers_by_subject($subject_table);
			$percentage_markers = ceil($markers['total'] * 100 / $markers_subject['total']);

			//sacamos los usuarios de esa sesion
			$usuarios_sesion = get_usuarios_session_family($session_id,$subject_table);	
			// sacamos los usuarios totales de la asignatura
			$total_users_subject = get_subject_users($subject_table);
			$total_users_subject = count($total_users_subject);
			$errors_in_all = get_errores_totales_sesion($session_id, $subject_table);
 
			//calculamos el cc para esos usuarios
			$active_users = get_cc_session($subject_table, $session_id, false);

			//PARA SACAR CC INFORME CSV ESTADISTICO DE JUAN RAMON
			/*foreach ($active_users as $user => $data) {
				$cc = ceil($data['total'] / count($family_names));
				echo $session_id . "," . $cc . "," . str_replace("UO", "", $user). ";\n";
			}*/

			$cc_session_families = get_cc_session($subject_table, $session_id, true);
			$cc_session = ceil(array_sum($cc_session_families) / count($cc_session_families));			
		?>

		<h1 class="center-title"><a href="./subjects/<?= $subject_parent['id']; ?>"><?= $subject_parent['subject_name'. $lang_sufix];?> </a> » <?= $session_name; ?> (week <?= $session_week; ?>) </h1> 
		
		<section class="params">
			<div class="content-params">
			<div class="param">
				<p><i class="fa fa-users"></i> <?= $text[$lang_sufix]['total_users_actives']; ?><?= count($active_users) ?> de <?= $total_users_subject ?></p>
			</div>
			<div class="param">
				<p><i class="fa fa-bar-chart"></i> <?= $text[$lang_sufix]['total_errors']; ?><?= number_format($errors_in_all['total'], "0", ",", $text[$lang_sufix]['point']); ?></p>
			</div>

			<div class="param">
				<div class='progress-circle-container small'>
					<div class="radial-progress small" data-score="<?= $cc_session ?>">
						<div class="circle">
							<div class="mask full">
								<div class="fill"></div>
							</div><!-- .mask.full -->
							<div class="mask half">
								<div class="fill"></div>
								<div class="fill fix"></div>
							</div><!-- .mask.half -->
						</div><!-- .circle -->
						<div class="inset"><span class='big'><?= $cc_session ?></span><span class='little'>/ 100</span></div>
					</div><!-- .radial-progress -->
				</div><!-- .progress-circle-container -->
				<p class="progress-circle-explanation"><?= $text[$lang_sufix]['colmena_coeficient']; ?></p>
				<div class="clearboth"></div>
			</div>
		</section><!-- .params -->

		
		<section class="center-subtitle">
			<section class="content-center-subtitle">
				<?php
				if(!empty($session_objectives)){
					echo $session_objectives; 
				}

				?>
			</section><!-- .content-center-subtitle -->
		</section><!-- .center-subtitle -->	
		
		<!--<p><?= $markers['total'] ?> errors of <?= $markers_subject['total'] ?> total errors in "<?= $subject_parent['subject_name']?>"</p>-->
		
		<section class="bloque-principal sin-sidebar">					
			<section id="accordion" class="bloque-presentacion">
				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-tachometer"></i> <?= $text[$lang_sufix]['errors_over_total']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_errors_over_total']; ?>"></span></h2>
					<section class="content">
						<div id="chart-session-to-subject" style="width: 100%; height: 50px; ">
							<p id="session_total"><?= $percentage_markers ?></p>
							<p id="subject_total">100</p>
						</div>		
					</section><!-- .content -->
				</section><!-- .accordion-block -->
				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-pie-chart"></i> <?= $text[$lang_sufix]['total_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_warnings']; ?>"></span></h2>
					<section class="content">
						<div id="chart-bar" class="charts" style="width: 749px; height: 300px;">					
						<?php 						
							$total_warnings = 0;
							$total_errors = 0;
							foreach ($family_names as $key => $value) { 
								if(isset($markers[$key])){
									$warnings = isset($markers[$key]['WARNING']) ? $markers[$key]['WARNING'] : 0;
									$errors = isset($markers[$key]['ERROR']) ? $markers[$key]['ERROR'] : 0;
								} else{
									$warnings = 0;
									$errors = 0;
								}
								$total_warnings += $warnings;
								$total_errors += $errors;
								$aux = explode(' ', $value);
							?>
							<p class="labels"><?= $aux[0] ?></p>
							<p class="warnings"><?= $warnings ?></p>
							<p class="errors"><?= $errors ?></p>
							<?php } ?>
						</div>	
						<div class="charts chart-area" style="width: 199px; height: 300px;">
							<p id="total-warnings"><?= $total_warnings ?></p>
							<p id="total-errors"><?= $total_errors ?></p>						
						</div>		
						<div class="clearboth"></div>		
					</section><!-- .content TOTAL ERRORS AND WARNINGS BY FAMILY -->
				</section><!-- .accordion-block -->
				<!--
					<h2 class="title">EVOLUTION NUM ERRORS THROUGH SESSION</h2>
					<section class="content">
					<?php $all_errors = get_errores_totales_sesion($session_id, $subject_table); ?>

					<?php foreach ($all_errors as $date => $e) { ?>
						<p><?= $date ?> - <?php print_r($e) ?></p>
					<?php } ?>
						
				</section>-->
				<!-- .content EVOLUTION NUM ERRORS -->
	

				<section class="accordion-block">
					<?php 
											
						// Ordenar e imprimir el array resultante
						uasort($active_users, 'cmp');		
						$ancho_calculado = 1000;
						$num_familias = count($family_names);
						if(count($active_users) > 36)
							$ancho_calculado = count($active_users) * 1000 / 36;	
					?>
					<h2 class="title"><i class="fa fa-users"></i> <?= $text[$lang_sufix]['users_family_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_users']; ?>"></span></h2>
					<section class="content">
						<div class="wrapper-chart-full">
							<div id="chart-column" class="charts" style="width: <?= $ancho_calculado ?>px; height: 550px;">
								<?php foreach ($family_names as $id => $family) :
									$aux = explode(' ', $family);
									$name = $aux[0];
								?>												
								<p class="labels"><?= $aux[0] ?></p>
								<?php endforeach;	?>
								<?php foreach ($active_users as $user => $value) : ?>
									<p class="user"><?= $user ?></p>
									<?php foreach ($family_names as $id => $family) : 
										$aux = explode(' ', $family);
										$name = $aux[0];
										?>												
										<p class="total-<?= $name ?>"><?= isset($value[$id]) ? ($value[$id]/$num_familias) : '0'  ?></p>
									<?php endforeach;	?>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="clearboth"></div>		
					</section><!-- .content USERS/FAMILIES ERRORS -->
				</section>
				
				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-list-ol"></i> <?= $text[$lang_sufix]['top_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_errors']; ?>"></span></h2>
					<section class="content">
						<div id="chart-errors" class="charts chart-errors" style="width: 1000px; height: 550px;">
						<?php 
							$errors = get_errores_sesion($session_id, $subject_table, 30);
							foreach ($errors as $error) :						
							?>
							<p class="error"><?= substr($error['message'], 0, 50) ?></p>
							<p class="error-gender"><?= strtolower($error['gender']) ?></p>
							<p class="error-id"><?= $error['error_id'] ?></p>
							<p class="totales"><?= $error['totales'] ?></p>
						<?php
							endforeach;
						?>
						</div>
						<div class="clearboth"></div>
					</section><!-- .content TOP 30 ERRROS BY FREQUENCY -->
				</section>


			</section><!-- .bloque-presentacion -->
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->
		
	<?php endif; ?>
	</section> <!-- .wrapper -->