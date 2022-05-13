<table class="center-table sortable" data-url="users-template">
	<tr>
		<th>#</th>
		<th data-sort="name" data-desc="<?= intval($desc) ?>" title="Sort by name" <?= $sortby == 'name' ? 'class="current"' : '' ?>><?= $text[$lang_sufix]['table_name']; ?></th>
		<th data-sort="message" data-desc="<?= intval($desc) ?>" title="Sort by description" <?= $sortby == 'message' ? 'class="current"' : '' ?>><?= $text[$lang_sufix]['table_message']; ?></th>
		<th data-sort="total" data-desc="<?= intval($desc) ?>" title="Sort by times" <?= $sortby == 'total' ? 'class="current"' : '' ?>><?= $text[$lang_sufix]['table_times']; ?></th>								
		<th data-sort="total" data-desc="<?= intval($desc) ?>" <?= $sortby == 'total' ? 'class="current"' : '' ?>><?= $text[$lang_sufix]['%aparitions']; ?></th>
		<th data-sort="gender" data-desc="<?= intval($desc) ?>" title="Sort by type" <?= $sortby == 'gender' ? 'class="current"' : '' ?>><?= $text[$lang_sufix]['table_type']; ?></th>
	</tr>
<?php 							
	$total_usual_errors = $usual_errors['total'];
	unset($usual_errors['total']);
	$position = 1;
	foreach ($usual_errors as $id => $value) :
		$percentage = $value['total'] * 100 / $total_usual_errors;
		$icon = $value['gender'] == 'WARNING' ? 'warning' : 'times-circle';
?>
	
	<tr class="wrapper-errors">
		<td><a href="./errors/<?= $value['id'] ?>"><?= $position++ ?></a></td>
		<td class="text"><a href="./errors/<?= $value['id'] ?>"><?= $value['name'] ?></a></td>
		<td class="text"><a href="./errors/<?= $value['id'] ?>"><?= $value['message'] ?></a></td>
		<td><a href="./errors/<?= $value['id'] ?>"><?= $value['total'] ?></a></td>
		<td><a href="./errors/<?= $value['id'] ?>"><?= number_format($percentage, 2, ',', '.') ?>%</a></td>
		<td class="<?= strtolower($value['gender']) ?>"><a href="./errors/<?= $value['id'] ?>"><i class="fa fa-<?= $icon ?> fa-lg"></i></a></td>
	</tr><!-- .wrapper-errors -->
	</a>
<?php
	endforeach;
?>
</table>