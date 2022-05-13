<?php
		require_once("../config/config.php");		
		require_once($root_path."lib/functions/lang-functions.php");
		require_once($root_path."lib/functions/general-functions.php");
		require_once($root_path."lib/functions/errores-functions.php");
		$sortby = $_POST['sortby'];	
		$id = $_POST['id'];
		$desc = $_POST['desc'];
		$aux_st = $_POST['subjects_tables'];
		$subjects_tables = explode('-', $aux_st);
		$usual_errors = get_most_usual_error_for_user($id, $subjects_tables, $sortby, $desc);
?>	
			<input type='hidden' id="id_user" value="<?= $id ?>">
			<input type='hidden' id="subjects_tables" value="<?= $aux_st ?>">		
			<?php include ($root_path.'templates/content/elements/table-users-template.php'); ?>

<script type="text/javascript">
sortTables();
</script>						
						
					