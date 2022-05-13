	<section class="wrapper">

	<?php 

		$error_id = $id; 
		$error = get_error ($error_id);
		$nombre_error = $error['name'];
		$mensaje_error = $error['message'];
		$descripcion_error = $error['problem_reason'];
		$references = $error['reference'];
		$gender = strtolower($error['gender']);
		$icon = $gender == 'warning' ? 'warning' : 'times-circle';

		if ($role_admin) :
			$error_data = get_data_error($error_id);
		elseif ($role_teacher) :
			$error_data = get_data_error($error_id, $logged_user['id']);						
		else :
			$error_data = get_data_error($error_id, $logged_user['id'], true);
		endif;
		$error_examples = get_ejemplos_error($error_id);
		
	?>
		<section class="profile-error">
			<h1 class="center-title">
				<span class="<?= $gender ?>"><i class="fa fa-<?= $icon ?> fa-lg"></i><?= $gender ?></span>
				<?= $mensaje_error; ?>
			</h1>
			<section class="params">
				<ul>
					<li><i class="fa fa-tasks"></i> <?= $text[$lang_sufix]['times_that_appear']; ?>: <?= number_format($error_data['total'], "0", ",", $text[$lang_sufix]['point']); ?></li>
			<?php		
				if(!$role_student):
			?>
					<li><i class="fa fa-users"></i> <?= $text[$lang_sufix]['users_who_have']; ?>: <?= number_format($error_data['num_users'], "0", ",", $text[$lang_sufix]['point']); ?></li>
					<li><i class="fa fa-book"></i> <?= $text[$lang_sufix]['subjects_where_appear']; ?>: <?= number_format($error_data['num_subjects'], "0", ",", $text[$lang_sufix]['point']); ?></li>
			<?php
				else:
			?>
					<li><i class="fa fa-tasks"></i> <?= $text[$lang_sufix]['times_that_appear_user']; ?>: <?= number_format($error_data['total_user'], "0", ",", $text[$lang_sufix]['point']); ?></li>
			<?php
				endif;
			?>
				</ul>
			</section><!-- .params -->
	<?php 
		if(!empty($descripcion_error)):
	?>
			<section class="center-subtitle">
		
		<?php 
			if($role_admin || $role_teacher) : 
		?>
				<a class="edit-block" href="./errors/edit/<?= $error_id; ?>"><i class="fa fa-pencil"></i> <?= $text[$lang_sufix]['edit']; ?></a>
		<?php 
			endif;
		?>
				<section class="description-subtitle">
					<?= $descripcion_error; ?>	
				</section><!-- .description-subtitle -->

		<?php 
			if($references != ""):
		?>
				<section class="references-subtitle">
					<?= $references; ?>
				</section><!-- .references-subtitle -->
		<?php 
			endif;
		?>
				
			</section><!-- .center-subtitle -->
	<?php 
		endif;
	?>

		</section><!-- .profile-error -->
	
		<section class="bloque-principal sin-sidebar">			
			<section id="accordion" class="bloque-presentacion">
		<?php 
			if ($role_teacher || $role_student) :
				$examples_error_title = $text[$lang_sufix]['custom_examples_errors_title'];
				if ($role_teacher) :
					$examples_error_title = $text[$lang_sufix]['custom_examples_errors_title_teacher'];
					$examples = get_examples_error_user($error_id, $logged_user['id'], 3, true);
				else :
					$examples = get_examples_error_user($error_id, $logged_user['id'], 3);
				endif;
		?>

				
				<section class="accordion-block examples-error">
					<h2 class="title"><?= $examples_error_title ?></h2>
					<section class="content">
			<?php 
				if(!empty($examples)):
					foreach ($examples as $key => $example_subject) :
			?>
					<div class="wrapper-subject">					
						<div class="example-subject">
							<h3><?= $example_subject['subject_name'.$lang_sufix] ?><br/>
							<?= $example_subject['academic_year'] ?></h3>
						</div><!-- .example-subject -->
						<div class="example-errors">
						<?php foreach ($example_subject['examples'] as $example) : ?>
							<p><?= $example['custom_message'] ?> : <?= $example['total'] ?></p>
						<?php endforeach; ?>
						</div><!-- .example-errors -->
					</div><!-- .wrapper-subject -->
				<?php
					endforeach;
				else:
				?>
					<h2><?= $text[$lang_sufix]['ejemplos-no-registrados'] ?></h2>
		<?php 
				endif;
		?>
					</section>
				</section><!-- .accordion-block -->
		<?php
			endif; 
		?>

		<?php 
			if(!empty($error_examples)) : 
		?>
				<section class="accordion-block examples-error">
					<h2 class="title"><?= $text[$lang_sufix]['examples_errors_title']; ?></h2>
				<?php 
					if($role_admin || $role_teacher || $role_student):
				?>
					<p><a  class="operations-title" href="./errors/add-example/<?= $error_id; ?>"><i class="fa fa-plus"></i> <?= $text[$lang_sufix]['add_new_example']; ?> </a><p>
				<?php 
					endif;
				?>
							
				<?php 
					foreach ($error_examples as $error_example) :
						$start_line_wrong = $error_example['start_line_wrong'];
						$end_line_wrong = $error_example['end_line_wrong'];
						$source_code_wrong = $error_example['source_code_wrong'];
						$start_line_right = $error_example['start_line_right'];
						$end_line_right = $error_example['end_line_right'];
						$source_code_right = $error_example['source_code_right'];
						$explanation = $error_example['explanation'];
						$complete_wrong_message = $error_example['complete_wrong_message'];
						$solution = $error_example['solution'];
						$references = $error_example['reference'];

						$lines_wrong = array();
						for($i= $start_line_wrong; $i<=$end_line_wrong; $i++):
					  		array_push($lines_wrong, $i);
						endfor;
						$lines_wrong = implode(",", $lines_wrong);

						$lines_right = array();
						for($i= $start_line_right; $i<=$end_line_right; $i++):
					  		array_push($lines_right, $i);
						endfor;
						$lines_right = implode(",", $lines_right);
				?>
					<section class="example-error">
				<?php 
					if($role_admin || $role_teacher) : 
				?>
						<a class="edit-block" href="./errors/edit-example/<?= $error_example['id']; ?>"><i class="fa fa-pencil"></i> <?= $text[$lang_sufix]['edit']; ?></a>
				<?php 
					endif;
				?>
						<section class="block-codes">
							<section class="container-block-codes">
								<section class="block-example-error wrong-usage block-code">
									<h3><?= $text[$lang_sufix]['wrong_usage']; ?></h3>
									<pre class="brush: java; highlight: [<?= $lines_wrong; ?>];"><?= $source_code_wrong; ?>
									</pre><!-- .code-wrong -->
								</section><!-- .block-example-error -->
						
								<section class="block-example-error right-usage block-code">
									<h3><?= $text[$lang_sufix]['right_usage']; ?></h3>
									
									<pre class="brush: java; highlight: [<?= $lines_right; ?>];"><?= $source_code_right; ?>
									</pre><!-- .code-right -->
								</section><!-- .block-example-error -->
							</section><!-- .container-block-codes -->
						</section><!-- .block-codes -->
						<section class="block-example-info">
					<?php
						if($complete_wrong_message != ""):
					?>
							<section class="block-example-error ">
								<h3><?= $text[$lang_sufix]['complete_wrong_message']; ?></h3>
								<section class="explanation-example">
									<?= $complete_wrong_message; ?>
								</section><!-- .explanation-example -->
							</section><!-- .block-example-error -->
					<?php 
						endif;
					?>


					<?php
						if($solution != ""):
					?>
							<section class="block-example-error ">
								<h3><?= $text[$lang_sufix]['solution']; ?></h3>
								<section class="explanation-example">
									<?= $solution; ?>
								</section><!-- .explanation-example -->
							</section><!-- .block-example-error -->
					<?php 
						endif;
					?>

					<?php
						if($explanation != ""): ?>
							<section class="block-example-error">
								<h3><?= $text[$lang_sufix]['explanation']; ?></h3>
								<section class="explanation-example">
									<?= $explanation; ?>
								</section><!-- .explanation-example -->
							</section><!-- .block-example-error -->
					<?php 
						endif;
					?>

					<?php
						if($references != ""):
					?>
						<section class="block-example-error last">
							<h3><?= $text[$lang_sufix]['references']; ?></h3>
							<section class="explanation-example">
								<?= $references; ?>
							</section><!-- .explanation-example -->
						</section><!-- .block-example-error -->
					<?php
						endif;
					?>
					</section><!-- .block-example-info -->
				</section><!-- .example-error -->
				<?php
					endforeach;
				?>
			</section><!-- .examples-error -->
			<?php
				endif;
			?>
		</section><!-- .bloque-presentacion -->
		<div class="clearboth"></div>
	</section><!-- .bloque-principal -->
		
	<div class="clearboth"></div>
</section> <!-- .wrapper -->