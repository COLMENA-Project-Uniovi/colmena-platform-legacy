	<nav>
		<ul id="menu-navegacion">
			<?php if ($role_teacher || $role_admin):

				

			 ?>

			<?php $current = $_SERVER['REQUEST_URI'] == '/colmena/' ? 'class="current"':'' ; ?>
			<li <?= $current ?>><a href="./"><?= $text[$lang_sufix]['menu_home']; ?></a></li>
			<?php $current = strstr($_SERVER['REQUEST_URI'],'subjects') ? 'current':'' ; ?>
			<li class="desplegable  <?= $current ?>"><a><?= $text[$lang_sufix]['menu_subjects']; ?></a>
				<ul class="submenu">
					<?php

						if($role_admin){
							$subjects = get_asignaturas_por_curso_academico();
						}else if($role_teacher){
							$subjects = get_asignaturas_por_usuario($logged_user['id']);
						}	
						foreach($subjects as $subject){
						
						$subject_name = $subject['subject_name' . $lang_sufix];
						$subject_id = $subject['id'];
						$subject_year = $subject['academic_year'];
						
					?>
					
					<li><a href="./subjects/<?= $subject_id; ?>"><?= $subject_name; ?> (<?= $subject_year;?>)</a></li>
					
					
					<?php } ?>
					
				</ul><!-- .submenu -->
			</li>
			<?php $current = strstr($_SERVER['REQUEST_URI'],'users') ? 'class="current"':'' ; ?>
			<li <?= $current ?>><a href="./users/"><?= $text[$lang_sufix]['menu_users']; ?></a></li>
			<?php $current = strstr($_SERVER['REQUEST_URI'],'families') ? 'current':'' ; ?>
			<li class="desplegable  <?= $current ?>"><a href="./families/"><?= $text[$lang_sufix]['menu_families']; ?></a>
				<ul class="submenu">
					<?php

						$familias = get_familias();
						foreach($familias as $familia){
						
						$nombre_familia = $familia['name'. $lang_sufix];
						$id_familia = $familia['id'];
					?>	
					
					<li><a href="./families/<?= $id_familia; ?>"><?= $nombre_familia; ?></a></li>
					
					
					<?php } ?>
					
				</ul><!-- .submenu -->
			</li>
			
			<?php elseif($role_student):?>
			
			<?php $current = strstr($_SERVER['REQUEST_URI'],'users') ? 'class="current"':'' ; ?>
			<li <?= $current ?>><a href="./users/<?= $logged_user['id']; ?>"><?= $text[$lang_sufix]['menu_profile']; ?></a></li>			
			<?php $current = (strstr($_SERVER['REQUEST_URI'],'families') || strstr($_SERVER['REQUEST_URI'],'errors')) ? 'current':'' ; ?>
			<li class="desplegable <?= $current ?>" ><a href="./families/"><?= $text[$lang_sufix]['menu_families']; ?></a>
				<ul class="submenu">
					<?php

						$familias = get_familias();
						foreach($familias as $familia){
						
						$nombre_familia = $familia['name'. $lang_sufix];
						$id_familia = $familia['id'];
					?>	
					
					<li><a href="./families/<?= $id_familia; ?>"><?= $nombre_familia; ?></a></li>
					
					
					<?php } ?>
					
				</ul><!-- .submenu -->
			</li>
			
			<?php endif; ?>
			
			<section class="login">
				<p><?= $text[$lang_sufix]['menu_welcome']; ?> <?= $logged_user['id']; ?> (<a href="../login/logout/"><?= $text[$lang_sufix]['menu_logout']; ?></a>)</p>
			</section>
			<div class="clearboth"></div>
		</ul><!-- #menu-navegacion -->
	</nav>