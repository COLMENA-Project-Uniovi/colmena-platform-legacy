<?php
		require_once("../config/config.php");		
		require_once($root_path."lib/functions/lang-functions.php");
		require_once($root_path."lib/functions/general-functions.php");
		require_once($root_path."lib/functions/errores-functions.php");
		$sortby = $_POST['sortby'];	
		$id = $_POST['id'];
		$desc = $_POST['desc'];
		$subject_table = $_POST['subjects_tables'];
		$usual_errors = get_most_usual_error_for_user($id, array($subject_table), $sortby, $desc);
?>	
			<input type='hidden' id="id_user" value="<?= $id ?>">
			<input type='hidden' id="subjects_tables" value="<?= $subject_table ?>">		
				<?php include ($root_path.'templates/content/elements/table-user-in-subject-template.php'); ?>	

<script type="text/javascript">
sortTables();
</script>						
						
					