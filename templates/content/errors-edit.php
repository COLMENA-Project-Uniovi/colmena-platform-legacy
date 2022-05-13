<?php 
	$error = get_error ($id);
	$descripcion_error = $error['problem_reason'];
	$references = $error['reference'];

?>
	<section class="wrapper">
		
		<section class="bloque-principal sin-sidebar">			
			<section class="bloque-presentacion">
				<h1 class="center-title "><?= $text[$lang_sufix]['edit']; ?> <?= $id; ?> | '<?= $error['message']; ?>'</h1> 

				<form  method = 'POST' action="./lib/errors/error-edit/">
					<input type="hidden" name="error_id" value="<?= $id; ?>"/>
					<input type="hidden" name="user-id" value="<?= $logged_user['id']; ?>"/>
					
					<div class="clearboth"></div>
					<div class="block-form-add">
						<h3><?= $text[$lang_sufix]['message']; ?></h3>
						<textarea class="long ckeditor" name="problem_reason"><?= $descripcion_error; ?></textarea>
					</div><!-- .block-form-add -->
					<div class="block-form-add">
						<h3><?= $text[$lang_sufix]['concepts']; ?></h3>
						<textarea class="long ckeditor" name="references"><?= $references; ?></textarea>
					</div><!-- .block-form-add -->
					<div class="block-form-add">
						<a href="errors/<?= $id; ?>" class="button" onclick="return confirm('<?= $text[$lang_sufix]['confirm-cancel'] ?>');"><?= $text[$lang_sufix]['cancel']; ?></a>
						<input type="submit" value="<?= $text[$lang_sufix]['save']; ?>"/>
					</div><!-- .block-form-add -->
					
				</form>
			</section><!-- .bloque-presentacion -->
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->
		<div class="clearboth"></div>
	</section> <!-- .wrapper -->