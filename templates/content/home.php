<section class="wrapper">	
	<?php
		if($role_student):
	?>
		<h1 class="center-title"><?= $text[$lang_sufix]['home_slogan_student']; ?></h1>
	<?php
		else:
	?>
		<h1 class="center-title"><?= $text[$lang_sufix]['home_slogan_teacher']; ?></h1>
	<?php 	
		endif;
	?>	
			
	<section class="center-subtitle">
		<section class="content-center-subtitle">
			<?php
				if($role_student):
			?>
				<p><?= $text[$lang_sufix]['home_goal_student']; ?></p>
				<p><?= $text[$lang_sufix]['home_select_student']; ?></p>
			<?php
				else:
			?>
				<p><?= $text[$lang_sufix]['home_goal_teacher']; ?></p>
				<p><?= $text[$lang_sufix]['home_select_teacher']; ?></p>
			<?php
				endif;
			?>
			
		</section><!-- .content-center-subtitle -->
	</section><!-- .center-subtitle -->
			
	<section class="bloque-principal sin-sidebar">
		<section id="accordion" class="bloque-presentacion">

		<?php 
			if($role_admin):
				$subjects = get_asignaturas_por_curso_academico();
			elseif($role_teacher):
				$subjects = get_asignaturas_por_usuario($logged_user['id']);
			endif;

			$current_year = "";
			
			foreach($subjects as $subject):
				$subject_id = $subject['id'];
				$subject_name = $subject['subject_name' . $lang_sufix];
				$subject_year = $subject['academic_year'];
				$subject_table = $subject['table_name'];
		
				$usuarios_asignatura = get_usuarios_asignatura_family($subject_table);	
				$errors_in_all = get_errores_totales_asignatura($subject_id, $subject_table);
				$compilations_in_all = get_compilaciones_totales_asignatura($subject_id, $subject_table);
				$sessions = get_sesiones_asignatura($subject_id);
				$cc_subject = get_cc_subject($subject_table, $subject_id);
				$cc_average = ceil(array_sum($cc_subject)/count($cc_subject));
		?>
		
		<?php
			if($subject_year != $current_year):
				if($current_year != ""):
		?>
			<div class="clearboth">
			</section><!-- .content-asignaturas -->
			</section><!-- .accordion-block -->
		<?php			
				endif;
				$current_year = $subject_year;
		?>
			<section class="accordion-block">
				<h2 class="title"><i class="fa fa-calendar"></i> 
					<?= $subject_year; ?>
				</h2>
				<section class="content-asignaturas content">
		<?php
			endif;
		?>
					<a href="./subjects/<?= $subject_id; ?>">
						<section class="asignatura">
							<h3><?= $subject_name; ?></h3>

							
							<div class="resume-subject">
								<div class="column sessions">
									<p class="data"><i class="fa fa-book"></i> <?= number_format(count($sessions), "0", ",", $text[$lang_sufix]['point']); ?></p>
									<p><?= $text[$lang_sufix]['total_sessions_home']; ?></p>
								</div><!-- .sessions -->
								<div class="column  users">
									<p class="data"><i class="fa fa-users"></i> <?= number_format(count($usuarios_asignatura), "0", ",", $text[$lang_sufix]['point']); ?></p>
									<p><?= $text[$lang_sufix]['total_users_home']; ?></p>
								</div><!-- .users -->
								<!--<div class="column errors">
									<p class="data"><i class="fa fa-bar-chart"></i> <?= number_format($errors_in_all, "0", ",", $text[$lang_sufix]['point']); ?></p>
									<p><?= $text[$lang_sufix]['total_errors_home']; ?></p>
								</div><!-- .errors -->	
								<div class='column progress-circle-container'>
									<div class="radial-progress" data-score="<?= $cc_average ?>">
										<div class="circle">
											<div class="mask full">
												<div class="fill"></div>
											</div><!-- .mask.full -->
											<div class="mask half">
												<div class="fill"></div>
												<div class="fill fix"></div>
											</div><!-- .mask.half -->
										</div><!-- .circle -->
										<div class="inset"><span class='big'><?= $cc_average ?></span><span class='little'>/ 100</span></div>
									</div><!-- .radial-progress -->
									<p><?= $text[$lang_sufix]['colmena_coeficient']; ?></p>
									
								</div><!-- .progress-circle-container -->

							</div><!-- .resumen-subject -->

						</section><!-- asignatura -->
					</a>
				<?php endforeach; ?>
					<div class="clearboth"></div>
					</section><!-- .content-asignaturas -->
				</section><!-- .accordion-block -->
				<div class="clearboth"></div>
			</section><!-- .accordion -->			
		</section><!-- .bloque-principal -->		
	<div class="clearboth"></div>
</section> <!-- .wrapper -->