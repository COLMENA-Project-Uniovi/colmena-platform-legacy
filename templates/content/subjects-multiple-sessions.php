	<section class="wrapper">
		<?php 			 
			 $sessions_id = explode(",",$sessions_ids);
			 $session_names = array();
			 $cc_sessions = array();
			 
			 $sessions = get_data_sessions($sessions_id);

			 foreach($sessions as $id_session => $session):
			 	array_push($session_names, $session['session-data']['session_name' . $lang_sufix]);
			 	$aux_user_sort = get_cc_session($session['subject']['table_name'], $id_session);
			 	$aux_cc_session = get_cc_session($session['subject']['table_name'], $id_session, true);
			 	uasort($aux_user_sort, 'cmp');
			 	$cc_sessions[$id_session] = $aux_user_sort;
			 	$cc_avg_sessions[$id_session] = ceil(array_sum($aux_cc_session)/count($aux_cc_session));
			 	// número de markers de la sesión
				$markers_sessions[$id_session] = get_num_markers_by_session($id_session, $session['subject']['table_name']);
			 endforeach;	 			 

			 if(isset($limite)):
			 	$limite_sesiones = $limite;
			 else:
			 	$limite_sesiones = count($sessions_id);
			 endif;			 

		?>
		<h1 class="center-title"><?= $text[$lang_sufix]['comparison_title']; ?></h1>
		<section class="bloque-principal sin-sidebar">			
			<section id="accordion" class="bloque-presentacion">

				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-line-chart"></i> <?= $text[$lang_sufix]['comparison_resume']; ?> '<?= join(", ", $session_names); ?>'</h2>
					<section class="content table-sessions">
					<?php
						$first = true;
						foreach ($sessions as $this_id_session => $this_session) :
							$session_data = $this_session['session-data'];
							$this_markers = $markers_sessions[$this_id_session];
							$total_errors = $this_markers['total_errors'];
							$total_warnings = $this_markers['total_warnings'];
					?>					
						<article class="table-session">
							<h3><a href="subjects-session/<?= $this_id_session ?>" ><?= $session_data['session_name'.$lang_sufix] ?></a><br/>
							<span><?= $this_session['subject']['subject_name'.$lang_sufix] ?> (<?= $this_session['subject']['academic_year'] ?>)<br/>
							<?= $text[$lang_sufix]['week'] ?> <?= $session_data['week'] ?></span></h3>


							<div class='progress-circle-container'>
								<p><?= $text[$lang_sufix]['colmena_coeficient']; ?></p>
								<div class="radial-progress" data-score="<?= $cc_avg_sessions[$this_id_session] ?>">
									<div class="circle">
										<div class="mask full">
											<div class="fill"></div>
										</div>
										<div class="mask half">
											<div class="fill"></div>
											<div class="fill fix"></div>
										</div>
									</div>
									<div class="inset"><span class='big'><?= $cc_avg_sessions[$this_id_session] ?></span><span class='little'>/ 100</span></div>
								</div>
							</div>


							<div class="table-content">
							<?php
								if (!$first) :
							?>
								<div class="session-data">
									<p><?= $text[$lang_sufix]['total_users_actives'] ?> <?= count($this_session['users']) ?></p>
									<p><?= $text[$lang_sufix]['total_errors'] ?> <?= $this_markers['total'] ?></p>
									<p>Errors <?= number_format(($this_markers['total_errors'] * 100 / $this_markers['total']), 2, ',','.');  ?> %</p>
									<p>Warnings <?= number_format(($this_markers['total_warnings'] * 100 / $this_markers['total']), 2, ',','.'); ?> %</p>
								</div>
								<div class="charts chart-area" style="width: 199px; height: 300px;">
									<p id="total-warnings"><?= $total_warnings ?></p>
									<p id="total-errors"><?= $total_errors ?></p>						
								</div>
							<?php
								else :
							?>
								<div class="charts chart-area" style="width: 199px; height: 300px;">
									<p id="total-warnings"><?= $total_warnings ?></p>
									<p id="total-errors"><?= $total_errors ?></p>						
								</div>
								<div class="session-data">
									<p><?= $text[$lang_sufix]['total_users_actives'] ?> <?= count($this_session['users']) ?></p>
									<p><?= $text[$lang_sufix]['total_errors'] ?> <?= $this_markers['total'] ?></p>
									<p>Errors <?= number_format(($this_markers['total_errors'] * 100 / $this_markers['total']), 2, ',','.');  ?> %</p>
									<p>Warnings <?= number_format(($this_markers['total_warnings'] * 100 / $this_markers['total']), 2, ',','.'); ?> %</p>
								</div>
							<?php
								endif;
							?>
							</div><!-- .table-content -->
						</article>
					<?php
							$first = false;
						endforeach;
					?>
					</section><!-- .content -->
				</section><!-- .accordion-block -->
<!--
				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-line-chart"></i> <?= $text[$lang_sufix]['comparison_families']; ?> '<?= join(", ", $session_names); ?>'</h2>
					<section class="content">
						<div id="compare_sessiones_families" class="chart" style="width:100; height:300px">
							<?php 
								foreach ($familias as $id => $family) :
							?>
								<p class="family-title" data-id="<?= $id ?>"><?= $family['name'.$lang_sufix] ?></p>
							<?php
								endforeach;	
								foreach ($sessions as $key => $session) : 
							?>
								<div class="session">
									<p class="session_name"><?= $session['session-data']['session_name'. $lang_sufix] ?></p>
								<?php
									foreach ($familias as $id => $family_name) :
								?>
									<p class="total"><?= isset($session['errors'][$id])? $session['errors'][$id] : '0'; ?></p>
							<?php
									endforeach;
							?>
								</div>
							<?php
								endforeach; ?>
						</div>
						<div class="clearboth"></div>
					</section>
				</section>

-->
				
				
				<?php 

					$array_familias = array();

					//rellenamos el array de las familias con las familias extraidas y imprimimos cabecera de la tabla					
					foreach ($familias as $key => $value) :	
						$nombre_familia = $value['name'.$lang_sufix];						
						array_push($array_familias, $nombre_familia);						
					endforeach;

					$usuarios_sesiones = array();
					foreach ($sessions_id as $id_session) {
						$usuarios_sesiones[$id_session] = array_keys($cc_sessions[$id_session]);
					}
					$usuarios_comunes = call_user_func_array('array_intersect',$usuarios_sesiones);					

					
					if (!empty($usuarios_comunes)):
						$errores_usuarios_comunes = array();
						
						foreach($usuarios_comunes as $nombre_usuario){

							$usuario_familias = array();

							foreach($array_familias as $id_family => $family){
								$usuario_familias[$id_family+1] = 0 ;
							}						
							$total_cc_session = 0;
							foreach ($sessions_id as $session_id) {																							
								$familias_usuario_sesion = get_cc_user_subject_session($nombre_usuario, $sessions[$session_id]['subject']['table_name'], $session_id);
								foreach ($familias_usuario_sesion as $family_id => $familia_cc) :
									$nombre_familia_usuario = $family_names[$family_id];
									$totales_familia_usuario = $familia_cc;
									if($totales_familia_usuario != '-1'):
										$usuario_familias[$family_id] += $totales_familia_usuario;
										$total_cc_session += $totales_familia_usuario;
									endif;
								endforeach;
							}
							$usuario_familias['total'] = $total_cc_session;

							$errores_usuarios_comunes[$nombre_usuario] = $usuario_familias;

						}
						
						//$usuarios_asignatura = get_usuarios_asignatura_family($table_name, $sessions_id);
						// Ordenar e imprimir el array resultante
						uasort($errores_usuarios_comunes, 'cmp');
						$ancho_calculado = 1000;
						if(count($errores_usuarios_comunes) > 36)
							$ancho_calculado = count($errores_usuarios_comunes) * 1000 / 36;
				?>
					<section class="accordion-block">
						<h2 class="title"><i class="fa fa-users"></i> <?= count($usuarios_comunes) ?> usuarios comunes<br/>
						<a data-href="#listado-alumnos">Ver listado</a></h2>
					</section><!-- .accordion-block -->
				<?php
					else:
				?>
					<section class="accordion-block">
						<h2 class="title"><i class="fa fa-users"></i> <?= $text[$lang_sufix]['comparison_user_errors']; ?>  '<?= join(", ", $session_names); ?>'</h2>
						<section class="content">
							<p>No hay usuarios comunes a estas dos sesiones.</p>
						</section>
					</section>
				<?php
					endif;
				?>

				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-line-chart"></i> <?= $text[$lang_sufix]['top_10_best_users']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_users']; ?>"></span></h2>
					<section class="content table-sessions">
						<div class="two-tables">
					<?php
						$first = true;						
						foreach ($sessions as $this_id_session => $this_session) :
							$session_data = $this_session['session-data'];							
							$this_users = array_slice($cc_sessions[$this_id_session], 0, 10);							
					?>					
							<!--<h3><a href="subjects-session/<?= $this_id_session ?>" ><?= $session_data['session_name'.$lang_sufix] ?></a><br/>
							<span><?= $this_session['subject']['subject_name'.$lang_sufix] ?> (<?= $this_session['subject']['academic_year'] ?>)<br/>
							<?= $text[$lang_sufix]['week'] ?> <?= $session_data['week'] ?></span></h3>-->
								<!-- table top users -->
							<table>
								<caption><?= $session_data['session_name'.$lang_sufix] ?></caption>
								<thead>
									<tr>
										<th><?= $text[$lang_sufix]['total_users_home']; ?></th>
										<th><abbr title="<?= $text[$lang_sufix]['colmena_coeficient']; ?>">CC</abbr></th>
									</tr>
								</thead>
								<tbody>
								<?php 									
									foreach ($this_users as $user_id => $cc_user_families) :
										$cc_user = ceil($cc_user_families['total'] / (count($cc_user_families) - 1));
								?>
									<tr data-error="<?= $user_id ?>">
										<td><a href="./users/<?= $user_id ?>"><?= strtoupper($user_id) ?></a></td>
										<td><?= $cc_user ?></td>
									</tr>
								<?php 
									endforeach; 
								?>
								</tbody>
							</table>						
					<?php
							$first = false;
						endforeach;
					?>
						</div><!-- .two-tables -->
					</section><!-- .content -->
				</section><!-- .accordion-block -->


				<section class="accordion-block">
					<h2 class="title"><i class="fa fa-list-ol"></i> <?= $text[$lang_sufix]['top_10_errors_title']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_errors']; ?>"></span></h2>
					<section class="content table-sessions">
					<?php
						foreach ($sessions as $session) :
							$session_data = $session['session-data'];
							
					?>	
						<article class="table-session">
							<h3><a href="subjects-session/<?= $this_id_session ?>" ><?= $session_data['session_name'.$lang_sufix] ?></a></h3>
							<div class="charts chart-errors " style="width: 95%; height: 350px;">
							<?php 
								$errors = get_errores_sesion($session['session-data']['id'], $session['subject']['table_name'], 10);
								
								foreach ($errors as $error) :						
								?>
								<p class="error"><?= substr($error['message'], 0, 45) ?></p>
								<p class="error-gender"><?= strtolower($error['gender']) ?></p>
								<p class="error-id"><?= $error['error_id'] ?></p>
								<p class="totales"><?= $error['totales'] ?></p>
							<?php
								endforeach;
							?>
							</div>
						</article>
						<?php
							endforeach;
						?>
					</section><!-- .content -->
				</section>





				<section id="listado-alumnos" class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['comparison_common_users']; ?></h2>
					<section class="content">
				<?php 									
					
					if (!empty($usuarios_comunes)):
						$interval = count(array_shift($usuarios_sesiones))/4;					
						foreach ($usuarios_comunes as $index => $user_id) :							
							$user = get_user($user_id);						
							$rank = intval($index/$interval) + 1;							

							if($user['name'] != ''):
								$format = formatString($user['name']);
								$format2 = formatString($user['surname']);
								$iniciales = $format[0].$format2[0];
							else:
								$iniciales = $user['id'][0];
							endif;
				?>
						<a href="./users/<?= $user['id'] ?>" class="<?= $class ?> user">
							<div class="content-user">
								<div class="user-image color-<?= $rank ?>"><?= $iniciales ?></div>
								<div class="user-info">
									<h2><?= isset($user['name']) ? ucwords(strtolower($user['name'])) . '<br/>' .ucwords(strtolower($user['surname'])) : '' ?> (<?= $user['id'] ?>)</h2>														
								</div>
							</div>
						</a>
					<?php
						endforeach;
					else:
					?>
						<h3 class="centered"><?= $text[$lang_sufix]['no_similar_users']; ?><?= $user['name'] . ' ' . $user['surname'] ?></h3>
					<?php endif; ?>
						<div class="clearboth"></div>
					</section><!-- .content -->
				</section><!-- accordion-block -->
					

			</section><!-- .bloque-presentacion -->
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->
		<!--
		 TO DO 
		 - USERS WITH MORE ERRORS
		 - MULTIPLE SESSIONS SELECTOR
		-->
		<div class="clearboth"></div>
	</section> <!-- .wrapper -->