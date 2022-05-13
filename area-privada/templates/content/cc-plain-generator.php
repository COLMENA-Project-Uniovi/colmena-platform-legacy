	<section class="wrapper">
		<?php 
			$subject_id = 7;
			$subject = get_asignatura($subject_id);
			$subject_table = $subject['table_name'];
			$subject_name = $subject['subject_name'. $lang_sufix];
			$subject_description = $subject['subject_description'. $lang_sufix];

		
			//sacamos los usuarios de esa asignatura
			$sessions = get_sesiones_asignatura($subject_id, false);
			//print_r($sessions);
			//calculamos el cc para esos usuarios
			$active_users = get_cc_users_family_in_subject($subject_table, $subject_id, false);
			//print_r($active_users);
			//guardamos en una variable el num total de usuarios participantes o no
			$total_users_in_subject = $active_users['total_users'];
			//eliminamos los no participantes
			$active_users = $active_users['active_users'];
			$errors_in_all = get_errores_totales_asignatura($subject_id, $subject_table);

			$cc_subject_by_sessions = get_cc_subject($subject_table, $subject_id, true, false);

			//print_r($cc_subject_by_sessions);

			$cc_subject_by_families = merge_and_average($cc_subject_by_sessions);
			$cc_subject = ceil(array_sum($cc_subject_by_families) / count($cc_subject_by_families));
			//echo $cc_subject;		
		?>
		<h1 class="center-title"><?= $subject_name; ?> (<?= $subject['academic_year']; ?>) </h1> 	
		
		<section class="params">
			<div class="content-params">
				<div class="param">
					<p><i class="fa fa-users"></i> <?= $text[$lang_sufix]['total_users_actives']; ?><?= number_format(count($active_users), "0", ",", $text[$lang_sufix]['point']); ?> de <?= $total_users_in_subject ?></p>
				</div>
				<div class="param">
					<p><i class="fa fa-bar-chart"></i> <?= $text[$lang_sufix]['total_errors']; ?><?= number_format($errors_in_all, "0", ",", $text[$lang_sufix]['point']); ?></p>
				</div>
				<div class="param">
					<div class='progress-circle-container small'>
						<div class="radial-progress small" data-score="<?= $cc_subject ?>">
							<div class="circle">
								<div class="mask full">
									<div class="fill"></div>
								</div><!-- .mask.full -->
								<div class="mask half">
									<div class="fill"></div>
									<div class="fill fix"></div>
								</div><!-- .mask.half -->
							</div><!-- .circle -->
							<div class="inset"><span class='big'><?= $cc_subject ?></span><span class='little'>/ 100</span></div>
						</div><!-- .radial-progress -->
					</div><!-- .progress-circle-container -->
					<p class="progress-circle-explanation"><?= $text[$lang_sufix]['colmena_coeficient']; ?></p>
					<div class="clearboth"></div>
				</div>
		</section><!-- .params -->


		<section class="center-subtitle">
			<section class="content-center-subtitle">
				<?php
				if(!empty($subject_description))
					echo $subject_description; 
				?>
			</section><!-- .content-center-subtitle -->
		</section><!-- .center-subtitle -->
		<section class="bloque-principal sin-sidebar">
			
			<section id="accordion" class="bloque-presentacion">
				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-calendar"></i> <?= $text[$lang_sufix]['sessions_subject_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_sessions']; ?>"></span></h2>
					<section id="selector" class="all-sessions content">
				<?php 
					foreach ($sessions as $session) :
				?>
					<a data-id="<?= $session['id'] ?>" data-href="./subjects/session/<?= $session['id'] ?>" class="session-block selectable" title="Go to this session statistics">					
						<h3><?= $session['session_name' . $lang_sufix] ?></h3>
						<p><?= $text[$lang_sufix]['week']; ?> <?= $session['week'] ?></p>					
					</a>
				<?php
					endforeach;
				?>	
					<div class="clearboth"></div>
					<input type="hidden" name="subject_id" value="<?= $subject_id ?>" />
					<div class="hidden-data">
						<div class="submit-button"></div>
						<div class="select-button"><span><a>Compara con</a></span>
							<select id="session_id">
								<option value="0">-- <?= $text[$lang_sufix]['select'] ?> --</option>
						<?php
							foreach ($all_subjects as $key => $value) :	

								$this_sessions = get_sesiones_asignatura($key);	
								foreach ($this_sessions as $this_session) :
									$option_name = $value['subject_name'.$lang_sufix] . ' ' . $value['academic_year'] . ' - ' . $this_session['session_name'.$lang_sufix];
						?>
								<option value="<?= $this_session['id'] ?>"><?= $option_name ?></option>
						<?php
								endforeach;
							endforeach;
						?>
							</select>
						</div>
					</div>
					<div class="legend">
						<p><?= $text[$lang_sufix]['legend_subject_sessions']; ?></p>
						<div class="clearboth"></div>
					</div><!-- .legend -->
					</section><!-- .all-sessions -->
					
				</section><!-- .accordion-block -->

				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-pie-chart"></i> <?= $text[$lang_sufix]['total_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_warnings']; ?>"></span></h2>
					<section class="content">					
						<div id="chart-bar" class="charts" style="width: 749px; height: 300px;">					
							<?php 
							$markers = get_num_markers_by_subject($subject_table);
							$total_warnings = 0;
							$total_errors = 0;
							foreach ($family_names as $key => $value) :
								if(isset($markers[$key])):
									$warnings = isset($markers[$key]['WARNING']) ? $markers[$key]['WARNING'] : 0;
									$errors = isset($markers[$key]['ERROR']) ? $markers[$key]['ERROR'] : 0;
								else:
									$warnings = 0;
									$errors = 0;
								endif;
								$total_warnings += $warnings;
								$total_errors += $errors;
								$aux = explode(' ', $value);
							?>
							<p class="labels"><?= $aux[0] ?></p>
							<p class="warnings"><?= $warnings ?></p>
							<p class="errors"><?= $errors ?></p>
							<?php 
							endforeach;
							?>

						</div>	
						<!--</canvas>
						<canvas id="chart-area" width="200" height="300">-->
						<div class="charts chart-area" style="width: 199px; height: 300px;">
							<p id="total-warnings"><?= $total_warnings ?></p>
							<p id="total-errors"><?= $total_errors ?></p>						
						</div>
						<!--</canvas>-->

						<div class="clearboth"></div>
						<div class="legend">
							<p><?= $text[$lang_sufix]['legend_subject_warnings']; ?></p>
							<div class="clearboth"></div>
						</div><!-- .legend -->
					</section>

				</section><!-- .accordion-block -->

				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-line-chart"></i> <?= $text[$lang_sufix]['evolution_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_evolution']; ?>"></span></h2>
					<section class="content">
						<!--<canvas id="chart-line" height="450" width="1000">-->
						<div id="chart-line" class="charts" style="width: 1000px; height: 350px;">
						<?php 
							foreach ($family_names as $id=>$family) :						
									$aux = explode(' ', $family);
									$name = $aux[0];
						?>
							<p class="family-title"><?= $name ?></p>
						<?php
							endforeach;
							foreach($sessions as $session):
								$cc_session = $cc_subject_by_sessions[$session['id']];								
						?>
								<p class="labels"><?= $session['session_name' . $lang_sufix] ?></p>
							<?php
								foreach ($family_names as $id=>$family) :							
									$aux = explode(' ', $family);
									$name = $aux[0];
							?>																		
							<p class="total-<?= $name ?>"><?= isset($cc_session[$id]) ? $cc_session[$id] : '0'  ?></p>
						<?php 	
								endforeach;
						 	endforeach;
						?>
						</div>
							<!-- </canvas>	-->
						<div class="clearboth"></div>	
						<div class="legend">
							<p><?= $text[$lang_sufix]['legend_subject_evolution']; ?></p>
							<div class="clearboth"></div>
						</div><!-- .legend -->		
					</section><!-- .content -->
				</section><!-- .accordion-block -->
				
			
				<?php 
					// Ordenar e imprimir el array resultante
					uasort($active_users, 'cmp');
					$ancho_calculado = 1000;
					$num_familias = count($family_names);
					if(count($active_users) > 36)
						$ancho_calculado = count($active_users) * 1000 / 36;
				?>
				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-users"></i> <?= $text[$lang_sufix]['users_family_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_users']; ?>"></span></h2>
					<section class="content">
						<div class="wrapper-chart-full">
							<div id="chart-column" class="charts" style="width: <?= $ancho_calculado ?>px; height: 550px;">
								<?php foreach ($family_names as $id => $family) :
									$aux = explode(' ', $family);
									$name = $aux[0];
								?>												
								<p class="labels"><?= $aux[0] ?></p>
								<?php endforeach; ?>
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
						<div class="legend">
							<p><?= $text[$lang_sufix]['legend_subject_users']; ?></p>
							<div class="clearboth"></div>
						</div><!-- .legend -->
					</section><!-- .content -->
				</section><!-- .accordion-block -->



				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-list-ol"></i> <?= $text[$lang_sufix]['top_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_errors']; ?>"></span></h2>
					<section class="content">
						<div id="chart-errors" class="charts chart-errors" style="width: 1000px; height: 550px;">
						<?php 
							$errors = get_errores_asignatura($subject_id, $subject_table, 30);
							foreach ($errors as $error) :
						?>
							<p class="error"><?= substr(str_replace('"', '', $error['message']), 0, 50); ?></p>
							<p class="error-gender"><?= strtolower($error['gender']) ?></p>
							<p class="error-id"><?= $error['error_id'] ?></p>
							<p class="totales"><?= $error['totales'] ?></p>
						<?php
							endforeach;
						?>
						</div>
						<div class="legend">
							<p><?= $text[$lang_sufix]['legend_subject_errors']; ?></p>
							<div class="clearboth"></div>
						</div><!-- .legend -->
					</section>									
				</section><!-- .accordion-block -->
			</section><!-- .bloque-presentacion -->
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->		
		<div class="clearboth"></div>

	</section> <!-- .wrapper -->