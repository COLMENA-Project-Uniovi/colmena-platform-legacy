<?php 	
	if($role_student && $logged_user['id'] != $id):
		http_response_code(403);
		include($root_path."templates/content/403.php");
	else:
		if ($role_teacher && !has_subject($ida,$logged_user['id'])) :
			http_response_code(403);
			include($root_path."templates/content/403.php");
		else:
			//convenio de variables
			$user_id = $id;
			$subject_id = $ida;
			//recupero el usuario
			$user = get_user($user_id);
			
			//recupero la asignatura
			$subject = get_asignatura($subject_id);
			$subject_name = $subject['subject_name'.$lang_sufix];
			$subject_table = $subject['table_name'];

			//recupero las sesiones de la asignatura
			$sessions = get_sesiones_asignatura($subject_id);			

			//CC DEL USUARIO
			//cc de la asignatura por sesiones
			$user_cc_subject_sessions = get_cc_user_subject($user_id, $subject_table, $subject_id, false);
			//junto y hago media para asignatura
			$user_cc_subject = merge_and_average($user_cc_subject_sessions);			
			//hago una media para tener un valor númerico
			$user_cc_average = ceil(array_sum($user_cc_subject) / count($user_cc_subject));

			//CÁLCULO DE LA ASISTENCIA
			$asistencia = 0;
			foreach ($user_cc_subject_sessions as $id_session_temp => $session_temp) {
				$asistencia += ($session_temp[1]) == -1 ? 0 : 1;
			}
			

			//obtenemos todos los usuarios activos y su cc
			$active_users = get_cc_users_family_in_subject($subject_table, $subject_id);
			$active_users = $active_users['active_users'];
			//ordenamos los usuarios de mejor cc a peor
			krsort($active_users);
			uasort($active_users, 'cmp');

			// cc medio de la asignatura por familias
			$cc_subject_by_families = get_cc_subject($subject_table, $subject_id, false);

			//saco indices y ubico al usuario
			$index = array_search($user_id, array_keys($active_users));			
			$interval = count($active_users)/4;
			$performance = intval($index/$interval) + 1;
			

			//en funcion del nivel (1-Excelente, 2-bueno, 3-regular, 4-bajo)
			switch ($performance) {
				case '1':
					//si es excelente controlo la posicion para hacer top-3
					if($index == 0):
						$performance_text = $text[$lang_sufix]['p_numberone'];
						$icon = '<i class="fa fa-trophy"></i>';
					elseif($index == 1):
						$performance_text = $text[$lang_sufix]['p_numbertwo'];
						$icon = '<i class="fa fa-trophy"></i>';
					elseif($index == 2):
						$performance_text = $text[$lang_sufix]['p_numberthree'];
						$icon = '<i class="fa fa-trophy"></i>';
					else:
						$performance_text = $text[$lang_sufix]['p_excelent'];
						$icon = '<i class="fa fa-smile-o"></i>';
					endif;
					$performance_class = "excellent";
					break;
				case '2':
					$performance_text = $text[$lang_sufix]['p_good'];
					$performance_class = "good";
					$icon = '<i class="fa fa-smile-o"></i>';
					break;
				case '3':
					$performance_text = $text[$lang_sufix]['p_regular'];
					$performance_class = "regular";
					$icon = '<i class="fa fa-meh-o"></i>';
					break;
				case '4':
					$performance_text = $text[$lang_sufix]['p_low'];
					$performance_class = "low";
					$icon = '<i class="fa fa-frown-o"></i>';
					break;
				default:
					$performance_text = $text[$lang_sufix]['p_unknow'];
					break;
			}

	if($role_admin):
?>
	<section class="admin-bar">
		<a id="admin_notify_user_subject" title="Mandar email al usuario con el resumen de la asignatura" 
		data-subject="<?= $subject_id ?>" data-user="<?= $user_id ?>" data-url="notify-user-subject" class="admin-button"><i class="fa fa-envelope"></i>Enviar reporte de asignatura al usuario</a>
	</section>
	<section class="admin-content">
	</section>
<?php
	endif;
?>	
	<section class="wrapper">		
		<h1 class="center-title"><?= ($user['name'] != '') ? strtoupper($user['name']) . ' ' . strtoupper($user['surname']) .' ' . strtoupper($user['surname2']) . '<br/>' : $user['id'] . '<br/>' ; ?> <?= $text[$lang_sufix]['in']; ?> <?= $subject_name ?></h1> 
		<section class="bloque-principal sin-sidebar">					
			<section id="accordion" class="bloque-presentacion">
				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['user_summary']; ?> / <?= $subject_name; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_user_summary']; ?>"></span></h2>
					<section class="content">
						
						<div class="left-summary">
								
							<div class="skillset main-skillset color-<?= $performance ?> get-ranking-color"></div><!-- .skillset-->
							<div class="skillset-data main-skillset-data">
						<?php 
							foreach ($family_names as $key => $value) :
						?>
								<div class="skillset-block">
									<p class="total-errors"><?= $user_cc_subject[$key]; ?></p>
									<p class="average-errors"><?= $cc_subject_by_families[$key]; ?></p>
									<p class="legend"><?= $value; ?></p>
									<p class="family-average"><?= $text[$lang_sufix]['average']; ?> : <?= $cc_subject_by_families[$key]; ?></p>
								</div><!-- skillset-block -->
						<?php 
							endforeach; 
						?>
							</div><!-- .skillset-data -->
							
						</div><!-- .left-summary -->

						<div class="right-summary">
						

							<div class="total">
								<p class="cc-average-title"><?= $performance_text ?></p>
								<p class="cc-average-number"><?= $icon; ?> </p>
							</div>

							<div class="total">
								<p class="cc-average-title"><?= $text[$lang_sufix]['position_long']; ?></p>
								<p class="cc-average-number"><?= $index + 1 ?><span class="max-rank">/<?= count($active_users) ?></span></p>
							</div>
						
							<div class="total">
								<p class="cc-average-title"><?= $text[$lang_sufix]['total_participation']; ?></p>
								<p class="cc-average-number"><?= $asistencia ?><span class="max-rank">/<?= count($sessions) ?></span></p>
							</div>
						
							<div class="total">
								
								<p class="cc-average-title"><?= $text[$lang_sufix]['colmena_coeficient_average']; ?> <?= $text[$lang_sufix]['in_subject']; ?>:</p>
								<div class='progress-circle-container cc-average-number'>									
									<div class="radial-progress" data-score="<?= $user_cc_average ?>">
										<div class="circle">
											<div class="mask full">
												<div class="fill"></div>
											</div>
											<div class="mask half">
												<div class="fill"></div>
												<div class="fill fix"></div>
											</div>
										</div>
										<div class="inset"><span class='big'><?= $user_cc_average ?></span><span class='little'>/ 100</span></div>
									</div>
								</div>
								<!--<p class="cc-average-number"><?= $user_cc_average; ?><span class="max-rank">/100</span></p>-->
							</div>

						</div><!-- .right-summary -->
						<div class="clearboth"></div>
					</section><!-- .content -->
				</section><!-- .accordion-block -->
				

			<?php
				$id_usuario = $id;	
			?>			
				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-line-chart"></i> <?= $text[$lang_sufix]['evolution_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_evolution_erros_title']; ?>"></span></h2>
					<section class="content">
						<div id="user-evolution-subject" class="charts" style="width: 1000px; height: 350px;">
				<?php					
					// recuperamos el cc por sesión del usuario
					$cc_user_by_session = get_cc_user_subject($id_usuario, $subject_table, $subject_id);					
					//recuperamos la media de errores en esa asignatura
					$average_cc_by_session = get_cc_subject($subject_table, $subject_id, true);
					foreach ($sessions as $key => $session) :						
						$cc_session = array_sum($cc_user_by_session[$session['id']]);
						$cc_session /= count($cc_user_by_session[$session['id']]);
						$cc_session = ceil($cc_session);

						$average_cc_session = array_sum($average_cc_by_session[$session['id']]);
						$average_cc_session /= count($average_cc_by_session[$session['id']]);
						$average_cc_session = ceil($average_cc_session);			
						
				?>
							<p class="session_name"><?= $session['session_name'.$lang_sufix] ?></p>
							<p class="session_errors"><?= $cc_session != -1 ? $cc_session : '0' ?></p>
							<p class="average_errors"><?= isset($average_cc_by_session[$session['id']]) ? number_format($average_cc_session, 0, '.', ',') : '0' ?></p>

				<?php 
					endforeach; 
				?>
						</div>
						<div class="clearboth"></div>
					</section><!-- evolution .content --> 					
				</section>

				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-list-ol"></i> <?= $text[$lang_sufix]['top_user_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_top_user_errors_title']; ?>"></span></h2>
					<section id="ajax-result" class="content">
						<input type='hidden' id="id_user" value="<?= $id ?>">
						<input type='hidden' id="subjects_tables" value="<?= $subject_table ?>">
						<?php 
							//obtenemos los errores más frecuentes del usuario
							$usual_errors = get_most_usual_error_for_user($id, array($subject_table));
							$desc = 1;
							$sortby = 'total';
							include ('elements/table-user-in-subject-template.php'); 
						?>
					</section><!-- .content -->
				</section><!-- top errors -->


				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-list-ol"></i> <?= $text[$lang_sufix]['sessions_report_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_sessions_report']; ?>"></span></h2>
					<section class="content">
						<section class="sessions-content">
						<?php
							//recuperamos los errores generales del usuario en esa asignatura				
							$errors_by_session = get_errors_user_subject_group_by_session($id_usuario, $subject_table);
							//recuperamos la media de errores en esa asignatura
							$average_by_session = get_average_errors_group_by_session($subject_table);
							foreach ($sessions as $session) :
								$compilations = get_user_compilations_in_session($user_id, $session['id'], $subject_table);
								$user_error_in_session = isset($errors_by_session[$session['id']])  ? $errors_by_session[$session['id']]['total'] : 0;
								$average_error_in_session = isset($average_by_session[$session['id']]) ? $average_by_session[$session['id']] : 0;
								$average_error_in_session = number_format($average_error_in_session, 0, ',', '.');
								$diference = $user_error_in_session - $average_error_in_session;		
								
						?>
							<article class="session">
								<h1><?= $text[$lang_sufix]['session'] . ' ' . $session['week'] ?>: <?= $session['session_name'.$lang_sufix] ?></h1>
							<?php
								if($role_admin):
							?>
								<section class="admin-stick">
									<a id="admin_notify_user_session" title="Enviar reporte de sesión al usuario" 
									data-session="<?= $session['id'] ?>" data-user="<?= $user_id ?>" data-url="notify-user-session" class="admin-button"><i class="fa fa-envelope"></i></a>
								</section>
							<?php
								endif;
							?>		
								<div class="session-resume">
									<div class="cc-session">
										<h2><?= $text[$lang_sufix]['colmena_coeficient']; ?>
										<?php if ($user_error_in_session > 0): ?>
											<span class="info" data-info="<?= $text[$lang_sufix]['legend_colmena_coeficient']; ?>"></span>
										<?php endif; ?>
										</h2>
										<div class="content-cc-session">
									<?php 
										foreach ($family_names as $key => $value) :
											$user_cc_subject_session = $user_cc_subject_sessions[$session['id']][$key];
											if($user_cc_subject_session == -1)
												$user_cc_subject_session = "-"
									?>
											<div class="number-and-text-block">
												<p class="session-block-number">
													<?= $user_cc_subject_session; ?>
												</p>

												<p class="session-block-explanation">
													<?= strtoupper(substr(str_replace("é", "e", $value), 0,4)) . "." ?>
												</p>
											</div><!-- .number-and-text-block -->
									<?php 
										endforeach; 
									?>
										</div><!-- .content-cc-session -->
									</div><!-- .cc-session -->

									
									<div class="session-comparison">
										<h2><?= $text[$lang_sufix]['generated_errors']; ?>
										<?php if ($user_error_in_session > 0): ?>
											<span class="info" data-info="<?= $text[$lang_sufix]['legend_generated_errors']; ?>"></span>
										<?php endif; ?>
										</h2>
										<div class="content-session-comparison">
											
									<?php if ($user_error_in_session == 0): ?>
										<p class="alert-message"><?= $text[$lang_sufix]['session_ausence'] ?></p> 
									<?php else:
											$top_user_errors = get_top_errors_in_session($subject_table, $session['id'], $user['id']);
											$top_average_errors = get_top_errors_in_session($subject_table, $session['id']);
											
											if($user_error_in_session > $average_error_in_session): 
												$indicator = '<i class="fa fa-thumbs-down"></i>';
													$message = $text[$lang_sufix]['more_than_average'];
													$color = "color-4";
												elseif ($user_error_in_session < $average_error_in_session):
													$indicator = '<i class="fa fa-thumbs-up"></i>';
													$message = $text[$lang_sufix]['less_than_average'];
													$color = "color-1";
												else:
													$indicator = '<i class="fa fa-thumbs-up"></i>';
													$message = $text[$lang_sufix]['in_the_average'];
												endif;
									?>
											<div class="number-and-text-block">
												<p class="session-block-number">
													<?= $user_error_in_session ?> <?= $text[$lang_sufix]['total_errors_in_subject']; ?>
												</p>
												<p class="session-block-explanation">
													<?= $text[$lang_sufix]['session']; ?>
												</p>
											</div><!-- .number-and-text-block -->

											<div class="number-and-text-block">
												<p class="session-block-number indicator <?= $color; ?>">
													<?= $indicator; ?>
												</p>
												<p class="session-block-explanation <?= $color; ?>">
													<?= abs($average_error_in_session - $user_error_in_session); ?> <?= $message; ?>
												</p>
											</div><!-- .number-and-text-block -->
										
										
										</div><!-- .content-session-comparison -->
									</div><!-- .info-errors -->
									
								</div><!-- .session-resume -->


								<!-- table user top errors -->
								<div class="two-tables">
									<table>
										<caption><?= $text[$lang_sufix]['your_top5_errors']; ?></caption>
										<thead>
											<tr>
												<th><?= $text[$lang_sufix]['name']; ?></th>
												<th><?= $text[$lang_sufix]['times']; ?></th>
											</tr>
										</thead>
										<tbody>
										<?php 
											foreach ($top_user_errors as $top_error):
										?>
											<tr class="<?= $top_error['gender'] ?>" data-error="<?= $top_error['error_id'] ?>">
												<td><a href="./errors/<?= $top_error['error_id'] ?>"><?= $top_error['name'] ?></a></td>
												<td><?= $top_error['total'] ?></td>
											</tr>
										<?php
											endforeach;
										?>
										</tbody>
									</table>
									<!-- table average top errors -->
									<table>
										<caption><?= $text[$lang_sufix]['average_top5_errors']; ?></caption>
										<thead>
											<tr>
												<th><?= $text[$lang_sufix]['name']; ?></th>
												<th><?= $text[$lang_sufix]['times']; ?></th>
											</tr>
										</thead>
										<tbody>
										<?php
											foreach ($top_average_errors as $top_error):
										?>
											<tr class="<?= $top_error['gender'] ?>" data-error="<?= $top_error['error_id'] ?>">
												<td><a href="./errors/<?= $top_error['error_id'] ?>"><?= $top_error['name'] ?></a></td>
												<td><?= $top_error['total'] ?></td>
											</tr>
										<?php
											endforeach;
										?>
										</tbody>
									</table>
								</div><!-- .two-tables -->

								<div class="compilations">
									<h2><?= $text[$lang_sufix]['compilations_in_session']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_compilations_in_session']; ?>"></span></h2>
									<div class="compilations-in-session chart">
									<!-- chart .compilations-in-session -->
								<?php
									foreach ($compilations as $comp) :
										$date = strtotime($comp['timestamp']);
										$year = date('Y', $date);
										$month = date('n', $date);
										$day = date('j', $date);
										$hour = date('g', $date);
										$minute = date('i', $date);
										$seconds = date('s', $date);
										$number = $comp['num_markers'];
								?>
										<div class="compilation">
											<p class="year"><?= $year ?></p>
											<p class="month"><?= $month ?></p>
											<p class="day"><?= $day ?></p>
											<p class="hour"><?= $hour ?></p>
											<p class="minute"><?= $minute ?></p>
											<p class="seconds"><?= $seconds ?></p>
											<p class="number"><?= $number ?></p>
										</div>

								<?php
									endforeach;
								?>		
									</div><!-- .compilations-in-session -->
								</div>
							<?php
								endif;
							?>
							</article>
					<?php
						endforeach;
					?>
						</section><!-- .sessions-content -->
					</section><!-- .content -->
				</section><!-- top errors -->

				
			</section>
		</section>	
	</section> <!-- .wrapper -->
	<?php endif; ?>	
<?php endif; ?>	