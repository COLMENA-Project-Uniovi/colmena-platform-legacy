<?php 
	if(isset($ida) && $ida != ''): 
		include ($root_private_area_path."templates/content/user-in-subject-template.php");
	else:
		if($role_student && $logged_user['id'] != $id):
			http_response_code(403);
			include($root_private_area_path."templates/content/403.php");
		else :
			$user = get_user($id);

			$user_id = $id;	
			// obtenemos las asignaturas en función del usuario logueado											
			if($role_admin || $role_student){
				$subjects = get_asignaturas_por_usuario($user_id);
			}else if($role_teacher){
				$subjects = get_asignaturas_usuario_profesor($user_id, $logged_user['id']);
			}

			//cc del usuario
			$user_cc = get_cc_user($user_id, true, true);
			//hago una media para tener un valor de todas las familias agrupado
			$user_cc_average = ceil(array_sum($user_cc) / count($user_cc));
			
			//nombres de las tablas de las asignaturas
			$subjects_tables = array();
			//arrays para calcular los CC
			$all_users = array();
			$all_users_cc_subject = array();
			$all_users_cc_general = array();
			$all_users_cc_general_average_rank = array();

			//ranking				
			$rank = 0;

			//recorro las asignaturas
			foreach ($subjects as $key => $subject) {
				//asigno nombre de asignatura
				$subjects_tables[$subject['id']] = $subject['table_name'];
				//obtengo los usuarios de una asignatura
				$users_in_subject = get_subject_users($subject['table_name']);
				//los asigno
				$all_users[$subject['id']] = $users_in_subject;

				//recorro estos usuarios
				foreach ($users_in_subject as $key => $user_id_temp) {
					//le calculo su CC
					$user_subject_cc = get_cc_user($user_id_temp, true, true);

					//los añado en un array separado por asignatura
					$all_users_cc_subject[$subject['id']][$user_id_temp] = $user_subject_cc;
					//los añado a un array sin distincion de asignatura
					$all_users_cc_general[$user_id_temp] = $user_subject_cc;

					//genero una media por asignatura
					$all_users_cc_subject_average_rank[$subject['id']][$user_id_temp] = ceil(array_sum($user_subject_cc) / count($user_subject_cc));
					//genero una media sin distinción de asignatura
					$all_users_cc_general_average_rank[$user_id_temp] = ceil(array_sum($user_subject_cc) / count($user_subject_cc));
				}
			}

			//en la que no tiene distincion, junto para conseguir un unico array
			$all_users_cc_general = merge_and_average($all_users_cc_general);
			//ordeno el array general del ranking
			arsort($all_users_cc_general_average_rank);

			//obtengo el indice, el intervalo y la posicion para sacar el rendimiento
			$index_in_class = array_search($user_id, array_keys($all_users_cc_general_average_rank));
			$interval = count($all_users_cc_general_average_rank)/4;
			$rank += intval($index_in_class/$interval) + 1;
			$performance = intval($rank / count($subjects));

			switch ($performance) {
				case '1':
					$performance_string = $text[$lang_sufix]['p_excelent'];	
					break;
				case '2':
					$performance_string = $text[$lang_sufix]['p_good'];
					break;
				case '3':
					$performance_string = $text[$lang_sufix]['p_regular'];
					break;
				case '4':
					$performance_string = $text[$lang_sufix]['p_low'];
					break;
				default:
					$performance_string = $text[$lang_sufix]['p_unknow'];
					break;
			}
			
			//saco los errores frecuentes de este usuario
			$usual_errors = get_most_usual_error_for_user($user_id, $subjects_tables);
			
	?>

	<section class="wrapper">		
		<h1 class="center-title"><?= ($user['name'] != '') ? strtoupper($user['name']) . ' ' . strtoupper($user['surname']) . '<br/>' : ''; ?><?= strtoupper($id); ?></h1> 
		<section class="bloque-principal sin-sidebar">					
			<section id="accordion" class="bloque-presentacion">
				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['user_summary']; ?></h2>				
					<section class="content">
						<p class="total-ranking color-<?= $performance ?>"><?= $performance_string ?></p>

						<div class="left-summary">
							<h3 class="subtitle">Coeficiente colmena medio: <?= $user_cc_average; ?></h3>
							
							<div id="skillset" class="main-skillset color-<?= $performance ?>"></div><!-- .skillset-->
							<div class="skillset-data main-skillset-data">
						<?php foreach ($family_names as $key => $value) :?>
								<div class="skillset-block">
									<p class="total-errors"><?= $user_cc[$key]; ?></p>
									<p class="average-errors"><?= $all_users_cc_general[$key]; ?></p>
									<p class="family-name"><?= $value; ?></p>	
								</div><!-- skillset-block -->
						<?php endforeach; ?>
							</div><!-- .skillset-data -->
							<script type="text/javascript">

								var skillset = $(".main-skillset");
								var skillsetObject = [];
								
								var skillsetData = $(".skillset-data.main-skillset-data");
								skillsetData.children(".skillset-block").each(function(){
									var skillSetBlock = $(this);
									var skillBlockArray = 
									{
										'headline' : skillSetBlock.find(".family-name").html() + " (" + skillSetBlock.find(".total-errors").html() + "/100)" ,
										'value' : skillSetBlock.find(".total-errors").html(),
										'average' : skillSetBlock.find(".average-errors").html(),
										'length' : 100
									}
									skillsetObject.push(skillBlockArray);
								});
								
								var m_object = skillsetObject;

								$("#skillset.main-skillset").skillset({

									object:skillsetObject,
									duration:40

								});

								object = "";
							</script>
						</div><!-- .left-summary -->
						<div class="right-summary">
							
					<?php
						if(!empty($usual_errors[0]['total'])):
					?>	

							<h3 class="subtitle"><?= $text[$lang_sufix]['top_3_errors']; ?></h3>
					<?php
							$number_errors = 3;
							if(count($usual_errors) < $number_errors)
								$number_errors = count($usual_errors);
							for($i = 0; $i < $number_errors; $i++):
								$error_id = $usual_errors[$i]['id'];
								$error_message = $usual_errors[$i]['message'];
								$percentage = $usual_errors[$i]['total'] * 100 / $usual_errors['total'];
								$percentage = number_format($percentage, 2, ',', '.');
								$percentage .= "%";
					?>
							<div class="top-3-block">
								<p class="error-times"><a href="./errors/<?= $error_id; ?>"><?=  $percentage; ?> </a></p>
								<p class="error-message color-<?= $performance ?>"><a href="./errors/<?= $error_id; ?>"><?= $error_message; ?></a></p>
							</div><!-- .top-3-block -->
					<?php
							endfor;
					?>

					<?php
						else :
					?>
							<h3 class="top-3-title"><?= $text[$lang_sufix]['no_errors']; ?> </h3>
					<?php
						endif;
					?>
							<h3 class="subtitle">Sobre el coeficiente colmena</h3>
							<p>El coeficiente colmena es un coeficiente que se calcula a partir de los errores que un usuario comete durante la programación. Se consideran todas las sesiones de las asignaturas donde haya participado desde que se registró en el sistema.</p>
							<p>Sus valores van de 0 hasta 100. Cuanto más próximo a 100 esté, menos errores se habrán detectado de esa familia. Si se aproxima mucho a 0, significa que el usuario tiene muchos errores de esa familia.</p>


						</div><!-- .right-summary -->
						<div class="clearboth"></div>
					</section><!-- .content -->
				</section><!-- .accordion-block -->






				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['user_subject']; ?></h2>
					<section class="content center">
					<?php 
						
						$all_ranks = array();
						$user_cc_subject = get_cc_user($user_id, true, false);
						foreach($subjects as $asignatura): 	
							$user_cc_subject_current = $user_cc_subject[$asignatura['id']];
							$user_average_cc_subject = ceil(array_sum($user_cc_subject_current) / count($user_cc_subject_current));
							$all_users_cc_subject_merged = merge_and_average($all_users_cc_subject[$asignatura['id']]);

							//sacamos los usuarios de esa asignatura
							$subject_users = $all_users_cc_subject_average_rank[$asignatura['id']];
							arsort($subject_users);
				
							// Ordear e imprimir el array resultante
							$index = array_search($user_id, array_keys($subject_users));

							$interval = intval(count($subject_users)/4);

							if($index < $interval * 4){
								if($index == 3){
									$performance = $text[$lang_sufix]['p_numberone'];
									$icon = '<i class="fa fa-trophy"></i>';
								}elseif($index == 2){
									$performance = $text[$lang_sufix]['p_numbertwo'];
									$icon = '<i class="fa fa-trophy"></i>';
								}elseif($index == 1){
									$performance = $text[$lang_sufix]['p_numberthree'];
									$icon = '<i class="fa fa-trophy"></i>';
								}else{
									$performance = $text[$lang_sufix]['p_excelent'];
									$icon = '<i class="fa fa-smile-o"></i>';
								}

								$performance_class = "excellent";
									
								
							}elseif($index < $interval*3){
								$performance = $text[$lang_sufix]['p_good'];
								$performance_class = "good";
								$icon = '<i class="fa fa-smile-o"></i>';
							}elseif($index < $interval*2){
								$performance = $text[$lang_sufix]['p_regular'];
								$performance_class = "regular";
								$icon = '<i class="fa fa-meh-o"></i>';
							}elseif ($index < $interval){
								$performance = $text[$lang_sufix]['p_low'];
								$performance_class = "low";
								$icon = '<i class="fa fa-frown-o"></i>';
							}
					?>
						<article class="subject">
							<p class="title"><?= $asignatura['subject_name'.$lang_sufix] ?><br/><span class="year"><?= $asignatura['academic_year'] ?></span></p>
							<div class="clearboth"></div>
							<div class="content-stats">
								<div class="performance-block">
									<p class="performance <?= strtolower($performance_class) ?> icon"><?= $icon ?></p>
									<p class="performance <?= strtolower($performance_class) ?>"><?= $performance ?></p>
									<div class="clearboth"></div>
									<p class="performance <?= strtolower($performance_class) ?>"><?= $text[$lang_sufix]['you_are']; ?> <?= $index + 1 ?> <?= $text[$lang_sufix]['of']; ?> <?= count($subject_users) ?></p>
									
								</div><!-- .performance-block -->
								<p class="total"><?= $user_average_cc_subject ?> <!--<?= $text[$lang_sufix]['total_errors_in_subject']; ?>--> COEFICIENTE COLMENA</p>							
								<p class="bottom-link"><a class="more" href="./users/<?= $user_id ?>/<?= $asignatura['id'] ?>"><?= $text[$lang_sufix]['see_more']; ?> &raquo;</a></p>
							</div><!-- .content-stats -->
							<div class="content-chart">	

								<div id="skillset" class="subject-skillset color-<?= strtolower($performance_class) ?>"></div><!-- .skillset-->
									<div class="skillset-data">
								<?php 
									foreach ($family_names as $key => $value) :
								?>
										<div class="skillset-block">
											<p class="total-errors"><?= $user_cc_subject_current[$key]; ?></p>
											<p class="average-errors"><?= $all_users_cc_subject_merged[$key]; ?></p>
											<p class="family-name"><?= $value; ?></p>	
										</div><!-- skillset-block -->
								<?php 
									endforeach; 
								?>
									</div><!-- .skillset-data -->
									</div><!-- .content-chart -->
							<div class="clearboth"></div>
						</article>
					<?php endforeach;?>
						<script type="text/javascript">

							$("#skillset.subject-skillset").each(function(){
								var s_skillset = $(this);
								
								var s_object = "";
								var s_skillsetObject = [];
								var s_skillBlockArray = [];

								var s_skillsetData = s_skillset.next(".skillset-data");
								console.log(s_skillsetData.html());

								s_skillsetData.children(".skillset-block").each(function(){
									var s_skillSetBlock = $(this);
									var s_skillBlockArray = 
									{
										'headline' : s_skillSetBlock.find(".family-name").html() + " (" + s_skillSetBlock.find(".total-errors").html() + "/100)" ,
										'value' : s_skillSetBlock.find(".total-errors").html(),
										'average' : s_skillSetBlock.find(".average-errors").html(),
										'length' : 100
									}
									s_skillsetObject.push(s_skillBlockArray);
								});

								
								s_object = s_skillsetObject;
								
								s_skillset.skillset({

									object:s_skillsetObject,
									duration:0

								});
							});
						</script>

						<div class="legend">
							<?php foreach ($family_names as $key => $value) {
									$aux = explode(' ', $value);
									$name = $aux[0];
							?>
								<p><?= strtoupper(substr(str_replace("é", "e", $name), 0, 3)); ?> : <?= $name ; ?></p>
							<?php }?>
							<div class="clearboth"></div>
						</div><!-- .legend -->
						<div class="clearboth"></div>
					</section><!-- user-subjects .content --> 					
				</section>

				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['top_errors']; ?></h2>
					<section id="ajax-result" class="content">
						<input type='hidden' id="id_user" value="<?= $id ?>">
						<input type='hidden' id="subjects_tables" value="<?= implode('-', $subjects_tables) ?>">
						<?php
							$desc = '1';
							$sortby = 'total';
							include ('elements/table-users-template.php');
						?>
					</section><!-- .content -->
				</section><!-- top errors -->
			<?php if(!$role_student): ?>
				<section class="accordion-block">
					<h2 class="title"><?= $text[$lang_sufix]['similar_users']; ?></h2>
					<section class="content">
					<?php 

						//$similar_users = array();
						//while (!isset($similar_users || !empty($similar_users) && ) {
							foreach ($all_users as $u) {
								$index_in_class = array_search($user_id, $u);
								unset($u[$index_in_class]);
								$interval = intval(count($u)/4);
								$rank = intval($index_in_class/$interval) + 1;
								$subarray = array_slice($u, $interval*($rank - 1), $interval*$rank );
								
								
								if(!isset($similar_users))
									$similar_users = $subarray;
								else
									$similar_users = array_intersect($similar_users, $subarray);
							}
						//}

						shuffle($similar_users);
						$similar_users = array_slice($similar_users, 0, 4);
						if (!empty($similar_users)):
							foreach ($similar_users as $user_id) :
								$user = get_user($user_id);
								if($user['name'] != ''){
									$format = formatString($user['name']);
									$format2 = formatString($user['surname']);
									$iniciales = $format[0].$format2[0];
								}
								else
									$iniciales = $user['id'][0];
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
<script type="text/javascript">
sortMostUsualErrors();
</script>