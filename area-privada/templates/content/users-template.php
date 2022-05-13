<?php 
	if(isset($ida) && $ida != ''): 
		include ($root_private_area_path."templates/content/user-in-subject-template.php");
	else:
		//si no tiene permisos le redirigimos al error 403
		if($role_student && $logged_user['id'] != $id):
			http_response_code(403);
			include($root_private_area_path."templates/content/403.php");
		else :
			//asigno el usuario
			$user = get_user($id);
			if(empty($user)){				
				include($root_private_area_path."templates/content/404.php");
				include($root_private_area_path."templates/footer.php");
				exit();
			}
			$user_id = $id;	

			// obtenemos las asignaturas en función del usuario logueado									
			if($role_admin || $role_student):
				$subjects = get_asignaturas_por_usuario($user_id);
			elseif($role_teacher):
				$subjects = get_asignaturas_usuario_profesor($user_id, $logged_user['id']);
			endif;

			//cc del usuario por asignaturas y familias
			$user_cc_subjects = get_cc_user($user_id, true, false);
			//cc del usuario total por familias
			$user_cc = merge_and_average($user_cc_subjects);
			//hago una media para tener un valor de todas las familias agrupado
			$user_cc_average = ceil(array_sum($user_cc) / count($user_cc));
			
			//controlo la participacion del usuario
			$user_general_participation = false;		
			
			//compruebo si participó o no
			if(has_participated($user_id, $user_cc)):
				$user_general_participation = true;
			endif;

			//nombres de las tablas de las asignaturas
			$subjects_tables = array();
			//arrays para calcular los CC
			$all_users = array();
			$all_users_cc_subject = array();
			$all_users_cc_general = array();
			$all_users_cc_subject_average_rank  = array();
			$all_users_cc_general_average_rank = array();

			//ranking				
			$rank = 0;

			//recorro las asignaturas
			foreach ($subjects as $key => $subject) :
				//asigno nombre de asignatura
				$subjects_tables[$subject['id']] = $subject['table_name'];
				//obtengo los usuarios de una asignatura, hayan participado o no
				$users_in_subject = get_cc_users_family_in_subject($subject['table_name'], $subject['id']);
				//los asigno
				$users_in_subject = $users_in_subject['active_users'];
				
				// Ordenamos array resultante de mejor a peor
				natsort($users_in_subject);
				uasort($users_in_subject, 'cmp_average');				
				$all_users[$subject['id']] = array_keys($users_in_subject);

				//los añado en un array separado por asignatura
				$all_users_cc_subject[$subject['id']] = $users_in_subject;
				//los añado a un array sin distincion de asignatura
				$all_users_cc_general = $users_in_subject;
				foreach ($users_in_subject as $user_id_temp => $user_cc_temp) {					
					//genero una media por asignatura
					$all_users_cc_subject_average_rank[$subject['id']][$user_id_temp] = $user_cc_temp['total'];
					//genero una media sin distinción de asignatura
					$all_users_cc_general_average_rank[$user_id_temp] = $user_cc_temp['total'];
				}
			endforeach;

			//si el usuario participo
			if($user_general_participation):

				//en la que no tiene distincion, junto para conseguir un unico array
				$all_users_cc_general = merge_and_average($all_users_cc_general);
				//obtengo el indice, el intervalo y la posicion para sacar el rendimiento
				$index_in_class = array_search($user_id, array_keys($all_users_cc_general_average_rank));
				$interval = count($all_users_cc_general_average_rank)/4;
				$rank += intval($index_in_class/$interval) + 1;
				$performance = intval($rank / count($subjects));
				
				//saco los errores frecuentes de este usuario
				$usual_errors = get_most_usual_error_for_user($user_id, $subjects_tables);
				
				//Máximo de errores que quiero sacar del usuario
				$number_errors = 3;
			
			else:
				$performance = 0;
			endif;

			if($role_admin)
				echo '<section class="admin-content"></section>';
	?>
		
	<section class="wrapper">		
		<h1 class="center-title"><?= ($user['name'] != '') ? strtoupper($user['name']) . ' ' . strtoupper($user['surname']). ' ' . strtoupper($user['surname2']) . '<br/>' : ''; ?><?= strtoupper($id); ?></h1> 
		<section class="bloque-principal sin-sidebar">					
			<section id="accordion" class="bloque-presentacion">
				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['user_summary']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_user_summary']; ?>"></span></h2>
				<?php
					//si el usuario participo
					if($user_general_participation):
				?>		
					<section class="content">
						<!--<p class="total-ranking color-<?= $performance ?> get-ranking-color"><?= $performance_string ?></p>-->

						<div class="left-summary">
							<h3 class="subtitle"><?= $text[$lang_sufix]['colmena_coeficient_average']; ?> : <?= $user_cc_average; ?></h3>
							
							<div class="skillset main-skillset color-<?= $performance ?>"></div><!-- .skillset-->
							<div class="skillset-data main-skillset-data">
						<?php foreach ($family_names as $key => $value) :?>
								<div class="skillset-block">
									<p class="total-errors"><?= $user_cc[$key]; ?></p>
									<p class="average-errors"><?= $all_users_cc_general[$key]; ?></p>
									<p class="legend"><?= $value; ?></p>	
									<p class="family-average"><?= $text[$lang_sufix]['average']; ?> : <?= $all_users_cc_general[$key]; ?></p>
								</div><!-- skillset-block -->
						<?php endforeach; ?>
							</div><!-- .skillset-data -->
							
						</div><!-- .left-summary -->
						<div class="right-summary">
							
					<?php
						if(!empty($usual_errors[0]['total'])):
					?>	
							<h3 class="subtitle"><?= $text[$lang_sufix]['top_3_errors']; ?></h3>
					<?php
							//si los errores son menos que los minimos a mostrar
							if(count($usual_errors) < $number_errors)
								//los minimos son los máximos que tenga el usuario
								$number_errors = count($usual_errors);
							//por el numero de errores a mostrar saco los datos del error
							for($i = 0; $i < $number_errors; $i++):
								$error_id = $usual_errors[$i]['id'];
								$error_message = $usual_errors[$i]['message'];
								$percentage = $usual_errors[$i]['total'] * 100 / $usual_errors['total'];
								$percentage = number_format($percentage, 2, ',', '.') . "%";
					?>
							<div class="top-block">
								<p class="top-block-number">
									<a href="./errors/<?= $error_id; ?>"><?=  $percentage; ?> </a>
								</p>
								<p class="top-block-explanation color-<?= $performance ?>">
									<a href="./errors/<?= $error_id; ?>"><?= $error_message; ?></a>
								</p>
							</div><!-- .top-block -->
					<?php
							endfor;
						else :
					?>
							<h3 class="top-3-title"><?= $text[$lang_sufix]['no_errors']; ?> </h3>
					<?php
						endif;
					?>
							<h3 class="subtitle"><?= $text[$lang_sufix]['about_cc_title']; ?></h3>
							<p><?= $text[$lang_sufix]['about_cc_p1']; ?></p>
							<p><?= $text[$lang_sufix]['about_cc_p2']; ?></p>
						</div><!-- .right-summary -->
						<div class="clearboth"></div>
					</section><!-- .content -->
				<?php
					else:
						if(!$role_student): 
				?>
						
					
					<p>Aún no has generado ningún reporte en COLMENA, por lo que no puedes ver ningún ranking.</p>
				

				<?php
						else:
				?>
					<p>El Usuario <?= $user_id ; ?> aún no ha generado ningún error, entonces no se puede establecer ninguna métrica para él.</p>
				
				<?php
						endif;

					endif;
				?>
				</section><!-- .accordion-block -->


				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['user_subject']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_user_subject']; ?>"></span></h2>
					<section class="content center">
				<?php 
					//si el usuario participo
					if ($user_general_participation):
						//preparo el array de todos los rankings
						$all_ranks = array();
						//recorro las asignaturas
						foreach($user_cc_subjects as $id_subject => $cc_subject): 								
							//obtengo un valor unico de CC para ese usuario 
							$user_average_cc_subject = ceil(array_sum($cc_subject) / count($cc_subject));

							//compruebo la participación en esa asignatura concreta
							$user_subject_participation = false;
							if(has_participated($user_id, $cc_subject)):
								$user_subject_participation = true;
							endif;

							//si participó en la asignatura
							if($user_subject_participation):								
								//obtenemos el cc de todas las sesiones para saber en cuales participó
								$user_cc_subject_sessions = get_cc_user_subject($user_id, $subjects_tables[$id_subject], $id_subject);
								print_r($cc_sessions);
								//obtengo los datos para esa asignatura globales del grupo
								$all_users_cc_subject_merged = merge_and_average($all_users_cc_subject[$id_subject]);
								//sacamos los usuarios de esa asignatura
								$subject_users = $all_users_cc_subject_average_rank[$id_subject];
								//los ordeno por la media de su CC 								
								ksort($subject_users);
								uasort($subject_users, 'cmp_nat');
								
								//saco indices y ubico al usuario
								$index = array_search($user_id, array_keys($subject_users));
								$interval = count($subject_users)/4;
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
							else:
								$performance_text = $text[$lang_sufix]['p_unknow'];
							endif;
							
					?>
						<article class="subject">
							<p class="title"><?= $subjects[$id_subject]['subject_name'.$lang_sufix] ?><br/><span class="year"><?= $subjects[$id_subject]['academic_year'] ?></span></p>
							<div class="clearboth"></div>
						<?php
							//si participó en la asignatura
							if($user_subject_participation):
								//CÁLCULO DE LA ASISTENCIA
								$asistencia = 0;
								foreach ($user_cc_subject_sessions as $id_session_temp => $session_temp) {
									$asistencia += ($session_temp[1]) == -1 ? 0 : 1;
								}
								
								if($role_admin):
							?>
								<section class="admin-stick">
									<a id="admin_notify_user_subject" title="Enviar reporte de asignatura al usuario" 
									data-subject="<?= $id_subject ?>" data-user="<?= $user_id ?>" data-url="notify-user-subject" class="admin-button"><i class="fa fa-envelope"></i></a>
								</section>								
							<?php
								endif;
						?>
							<div class="content-stats">
								<div class="performance-block">
									<p class="performance <?= strtolower($performance_class) ?> icon"><?= $icon ?></p>
									<p class="performance <?= strtolower($performance_class) ?>"><?= $performance_text ?></p>
									<div class="clearboth"></div>
									<p class="performance <?= strtolower($performance_class) ?>"><?= $text[$lang_sufix]['you_are']; ?> <?= $index + 1 ?> <?= $text[$lang_sufix]['of']; ?> <?= count($subject_users) ?></p>
									
								</div><!-- .performance-block -->
								<div class="total">
									<p class="cc-average-title"><?= $text[$lang_sufix]['colmena_coeficient_average']; ?> <?= $text[$lang_sufix]['in_subject']; ?> </p>
									<!--<p class="cc-average-number"><?= $user_average_cc_subject ?></p>-->
									<div class='progress-circle-container cc-average-number'>									
										<div class="radial-progress" data-score="<?= $user_average_cc_subject ?>">
											<div class="circle">
												<div class="mask full">
													<div class="fill"></div>
												</div>
												<div class="mask half">
													<div class="fill"></div>
													<div class="fill fix"></div>
												</div>
											</div>
											<div class="inset"><span class='big'><?= $user_average_cc_subject ?></span><span class='little'>/ 100</span></div>											
										</div>
									</div>
								</div>															
								<div class="total">
									<p class="cc-average-title"><?= $text[$lang_sufix]['total_participation']; ?></p>
									<p class="cc-average-number"><?= $asistencia ?><span class="max-rank">/<?= count($user_cc_subject_sessions) ?></span></p>
								</div>
								<p class="bottom-link"><a class="more" href="./users/<?= $user_id ?>/<?= $subject['id'] ?>"><?= $text[$lang_sufix]['see_more']; ?> &raquo;</a></p>
							</div><!-- .content-stats -->
							<div class="content-chart">	

								<div class="skillset secondary-skillset color-<?= $performance ?>"></div><!-- .skillset-->
								<div class="skillset-data">
							<?php 
								//preparamos el gráfico de ranking
								foreach ($family_names as $key => $value) :
							?>
									<div class="skillset-block">
										<p class="total-errors"><?= $cc_subject[$key]; ?></p>
										<p class="average-errors"><?= $all_users_cc_subject_merged[$key]; ?></p>
										<p class="legend"><?= strtoupper(substr(str_replace("é", "e", $value), 0,3)) . "." ?></p>	
											
									</div><!-- skillset-block -->
							<?php 
								endforeach; 
							?>
								</div><!-- .skillset-data -->
							</div><!-- .content-chart -->
						<?php 
							//si no participó
							else:
								if($role_student): 
						?>
								
							
							<p>Aún no has generado ningún reporte en COLMENA en <?= $subject['subject_name'.$lang_sufix] ?></p>
						

						<?php
								else:
						?>
							<p>El Usuario <?= $user_id ; ?> aún no ha participado en <?= $subject['subject_name'.$lang_sufix] ?></p>
						
						<?php
								endif;

							endif;
						?>
							<div class="clearboth"></div>
						</article><!-- .subject -->
				<?php 
						endforeach;
				?>
						<div class="legend">
						<?php 
							//pintamos la leyenda
							foreach ($family_names as $key => $value):
								$aux = explode(' ', $value);
								$name = $aux[0];
						?>
							<p><?= strtoupper(substr(str_replace("é", "e", $name), 0, 3)); ?> : <?= $name ; ?></p>
						<?php 
							endforeach;
						?>
							<div class="clearboth"></div>
						</div><!-- .legend -->
				<?php 
					//si no participó en general
					else:
						if($role_student): 
				?>
					<p>Aún no has generado ningún reporte en COLMENA.</p>
				
				<?php
						else:
				?>
					<p>El Usuario <?= $user_id ; ?> aún no ha participado en ninguna asignatura</p>
				
				<?php
						endif;

					endif;
				?>
						<div class="clearboth"></div>
					</section><!-- user-subjects .content --> 					
				</section>

				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['top_errors']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_subject_errors']; ?>"></span></h2>
				<?php 
					//si el usuario participo
					if ($user_general_participation):
				?>
					<section id="ajax-result" class="content">
						<input type='hidden' id="id_user" value="<?= $id ?>">
						<input type='hidden' id="subjects_tables" value="<?= implode('-', $subjects_tables) ?>">
						<?php
							$desc = '1';
							$sortby = 'total';
							include ('elements/table-users-template.php');
						?>
					</section><!-- .content -->
				<?php
					else:
						if($role_student): 
				?>
					
					<p>No tienes ningún error registrado en COLMENA.</p>

				<?php
						else:
				?>
					<p>El Usuario <?= $user_id ; ?> aún no ha generado ningún error o warning</p>
				
				<?php
						endif;

					endif;
				?>

					<div class="legend">
						<p><a href="./families/"><?= $text[$lang_sufix]['know_more_families']; ?></a></p>
						<div class="clearboth"></div>
					</div><!-- .legend -->	

				</section><!-- top errors -->
			
			<?php if(!$role_student): ?>
				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['similar_users']; ?><span class="info" data-info="<?= $text[$lang_sufix]['legend_similar_users']; ?>"></span></h2>
					<section class="content">
				<?php 				
					//recorro los usuarios
					foreach ($all_users as $u):
						$index_in_class = array_search($user_id, $u);						
						unset($u[$index_in_class]);
						$interval = intval(count($u)/4);
						$rank = intval($index_in_class/$interval) + 1;
						$subarray = array_slice($u, $interval*($rank - 1), $interval*$rank );
						
						if(!isset($similar_users))
							$similar_users = $subarray;
						else
							$similar_users = array_intersect($similar_users, $subarray);
					endforeach;
					
					shuffle($similar_users);
					$similar_users = array_slice($similar_users, 0, 4);
					
					if (!empty($similar_users)):
						foreach ($similar_users as $user_id) :
							$user = get_user($user_id);
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
								<div class="user-image"><?= $iniciales ?></div>
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
			<?php endif; ?>
			</section>
		</section>	
	</section> <!-- .wrapper -->
<?php 
		endif; 
	endif;
?>