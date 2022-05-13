<?php 
	if(isset($id) && $id != ''): 
		include ($root_path."templates/content/users-template.php");
	else:
		// si es un estudiante, no tiene permiso y redireccionamos a error 403
		if($role_student):
			http_response_code(403);
			include($root_path."templates/content/403.php");
		else:
?>
	<section class="wrapper">		
		<section class="bloque-principal sin-sidebar all-users">			
		<?php
			// obtenemos los usuarios y asingaturas en función del rol del usuario
			if($role_admin){
		 		$users = get_all_users();		 		
		 		$subjects = get_all_subjects();
			}
			elseif($role_teacher){
				$users = get_all_users_by_teacher($logged_user['id']);			
				$subjects = get_asignaturas_por_usuario($logged_user['id']);				
			}

			// para cada asignatura obtenemos los usuarios con su cc
			foreach ($subjects as $key => $value) {				
				$ranking_users = get_cc_users_family_in_subject($value['table_name'], $key);
				$ranking_users = $ranking_users['active_users'];
				// Ordenamos array resultante de mejor a peor
				natsort($ranking_users);
				uasort($ranking_users, 'cmp_average');	
				$subjects[$key]['users'] = array_keys($ranking_users);
			}

			// para cada usuario, imprimiremos sus datos
			foreach ($users as $user) :
				$subjects_names = '';
				$class = '';
				$extra = '';
				$total_rank = 0;
				// por cada asignatura preparamos los datos
				foreach ($user['subjects'] as $key => $value) {					
					// obtenemos su índice en la asignatura
					$index = array_search($user['id'], $subjects[$value]['users']);
					$participacion = '';
					// si ha participado en la asignatura
					if($index !== false):
						$subjects_names .= $subjects[$value]['subject_name'.$lang_sufix];
						$class .= ' subject-'.$subjects[$value]['id'];						
						$interval = intval(count($subjects[$value]['users'])/4);	
						if($index < $interval)
							$rank = 1;
						elseif($index < $interval*2)
							$rank = 2;
						elseif($index < $interval*3)
							$rank = 3;
						elseif ($index < $interval*4)
							$rank = 4;
						$total_rank += $rank;
						$extra .= "<input type='hidden' name='subject-".$subjects[$value]['id']."' value='". ($index + 1) ."' />";
						$extra .= "<input type='hidden' name='subject-".$subjects[$value]['id']."-rank' value='$rank' />";
					else:
						$participacion = 'no-participa';
						$class .= ' subject-'.$subjects[$value]['id'];
						$extra = "<input type='hidden' name='subject-".$subjects[$value]['id']."' value='0' />";
						$extra .= "<input type='hidden' name='subject-".$subjects[$value]['id']."-rank' value='0' />";
					endif;
				}
				
				// formateamos su nombre para mostrarlo bonito
				if ($user['name'] != '') {
					$format = formatString($user['name']);
					$format2 = formatString($user['surname']);					
					$iniciales = $format[0].$format2[0];
				} else
					$iniciales = $user['id'][0];

				// adecuamos los datos para la búsqueda
				$for_search = formatString($user['id'].$user['name'].$user['surname']);
				$for_search = str_replace(' ', '', strtolower($for_search));
		?>
			<a href="./users/<?= $user['id'] ?>" class="<?= $class ?> user <?= $for_search?>">
				<div class="content-user <?= $participacion ?>">
					<div class="user-image color-<?= intval($total_rank/count($user['subjects'])) ?>"><?= $iniciales ?></div>
					<div class="user-info">
						<h2><?= isset($user['name']) ? ucwords(strtolower($user['name'])) . '<br/>' .ucwords(strtolower($user['surname'])).  " " .ucwords(strtolower($user['surname2'])) : '' ?> (<?= $user['id'] ?>)</h2>												
						<!--<?= $subjects_names ?>-->
						<?= $extra ?>				
					</div>
				</div>
			</a>
		<?php				
			endforeach;
		?>		
		<div class="droppable top">
		</div><!-- .droppable -->
		<div class="droppable bottom">
		</div><!-- .droppable -->
		<section id="accordion" class="subjects-selector" draggable="true">

			<p class="selector-info title">Asignaturas disponibles</p>
			<div class="content-selectors content">
			<?php foreach ($subjects as $key => $value) :?>
				<section id="subject-<?= $key ?>" class="selector">		
				<a href="./subjects/<?= $key; ?>">
				
					<section class="bg">
						<input type="hidden" name="total-users" value="<?= count($value['users']); ?>">
						<p><?= $value['subject_name'.$lang_sufix] ?></p>
						<p class="year"><?= $value['academic_year'] ?></p>
						<div class="extra">
						</div>			
					</section>
				</a>
				</section>
				
			<?php endforeach; ?>
			</div>			
		</section>
			
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->		
	</section> <!-- .wrapper -->
	<div class="search">
		<i class="fa fa-search fa-2x title"></i>
		<div class="content-selectors content">
			<input type="text" id="search-input" />
		</div>
	</div><!-- .search -->
	<?php endif; //if is a student ?>
<?php endif; //if is set id ?>	
<script>
$('.all-users .user').hover(function(){	
	var array = $(this).attr('class').split(' ');
	for(var i in array){		
		var value = array[i];
		if(value !== '' && value !== 'user'){
			var extra = $(this).find('input[name=' + value + ']').val();
			var rank = parseInt($(this).find('input[name= ' + value + '-rank]').val());	
			var total_users = $('.subjects-selector .selector#'+value).find('input[name=total-users]').val();
			$('.subjects-selector .selector#'+value).addClass('active').addClass('rank-' + rank);
			var string = '';
			switch(rank){
				case 1:
				case 2:
					string = '<i class="fa fa-2x fa-smile-o"></i>';	
					break;
				case 3:
					string = '<i class="fa fa-2x fa-meh-o"></i>';
					break;
				case 4:
					string = '<i class="fa fa-2x fa-frown-o"></i>';
					break;
			}
			if(rank == 0)
				$('.subjects-selector .selector#'+value + ' .extra').html('No participa');
			else
				$('.subjects-selector .selector#'+value + ' .extra').html(string + '<br/>' + extra + ' de ' + total_users);

		}
	}
}, function(){
		$('.subjects-selector .selector').attr('class','selector');	
		$('.subjects-selector .selector .extra').html('');			
});
$('.subjects-selector .selector').hover(function(){
	var id = $(this).attr('id');
	$('.all-users .user').not('.'+id).find('.content-user').addClass('not-selected');
 }, function(){
 	$('.all-users .user .content-user').removeClass('not-selected');
 });
</script>