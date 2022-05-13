<?php
	require_once("../config/config.php");		
	require_once($root_path."lib/functions/lang-functions.php");
	require_once($root_path."lib/functions/general-functions.php");
	require_once($root_path."lib/functions/usuarios-functions.php");
	$id_subject = $_POST['data']['subject'];
	$users = get_all_notified_users($id_subject);
?>	
		
		<div class="admin-inner">
			<div class="admin-close">
				<i class="fa fa-close fa-2x"></i>
			</div>
			<div id="selector" class="admin-users-list">
				<h3>Notificar a <?= count($users) ?> usuarios</h3>
				<div class="admin-wrapper-button">
					<div class='button select-all'><a>Seleccionar todos</a></div>
					<div class='button select-no-notified'><a>Seleccionar solo los no notificados</a></div>
				</div>
			<?php
				foreach ($users as $u) :
					$notificado = ($u['notified']) ? 'notified' : '';
			?>
				<div class="selectable <?= $notificado ?>" data-user="<?= $u['id'] ?>">
					<h4><?= $u['name'] . ' ' . $u['surname'] ?></h4>
					<p><?= $u['id'] ?></p>
					<?php
						if($u['notified'])
							echo '<i class="fa fa-check stick green"></i>';
					?>
				</div>
			<?php
				endforeach;
			?>					
					
			</div>

			<div class='submit-button send-notifications' data-url="send-notifications"><a>Notificar</a></div>


		</div>
						
					