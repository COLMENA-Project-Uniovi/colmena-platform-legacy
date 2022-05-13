	<section class="wrapper">
		<h1 class="center-title "><i class="fa fa-plus"></i> <?= $text[$lang_sufix]['add_new_example']; ?> </h1> 

		<section class="bloque-principal sin-sidebar">			
			<section class="bloque-presentacion">
				<form  method = 'POST' action="./lib/examples/examples-add/">
					<input type="hidden" name="error_id" value="<?= $id; ?>"/>
					<input type="hidden" name="user-id" value="<?= $logged_user['id']; ?>"/>
					<h2><?= $text[$lang_sufix]['source_code']; ?></h2>
				
					<div class="column">
						<div class="block-form-add">
							<p><?= $text[$lang_sufix]['start_line_wrong']; ?></p>
							<input type="text" name="start-line-wrong" required="required"/>
						</div><!-- .block-form-add -->
						<div class="block-form-add">
							<p><?= $text[$lang_sufix]['end_line_wrong']; ?></p>
							<input type="text" name="end-line-wrong"  required="required"/>
						</div><!-- .block-form-add -->
						
						<div class="block-form-add">
							<p><?= $text[$lang_sufix]['wrong_usage']; ?></p>
							<textarea name="wrong-source-code" required="required"></textarea>
						</div><!-- .block-form-add -->
					</div><!-- .left-column -->
					<div class="column">
						<div class="block-form-add">
							<p><?= $text[$lang_sufix]['start_line_right']; ?></p>
							<input type="text" name="start-line-right"required="required"/>
						</div><!-- .block-form-add -->
						<div class="block-form-add">
							<p><?= $text[$lang_sufix]['end_line_right']; ?></p>
							<input type="text" name="end-line-right" required="required"/>
						</div><!-- .block-form-add -->

						<div class="block-form-add">
							<p><?= $text[$lang_sufix]['right_usage']; ?></p>
							<textarea name="right-source-code" required="required"></textarea>
						</div><!-- .block-form-add -->
					</div><!-- .column -->
					<div class="clearboth"></div>
					
					<div class="block-form-add">
						<h2><?= $text[$lang_sufix]['explanation']; ?></h2>
						<textarea class="long ckeditor" name="explanation"></textarea>
					</div><!-- .block-form-add -->

					<div class="block-form-add">
						<h2><?= $text[$lang_sufix]['solution']; ?></h2>
						<textarea class="long ckeditor" name="solution"></textarea>
					</div><!-- .block-form-add -->

					<div class="block-form-add">
						<h2><?= $text[$lang_sufix]['references']; ?></h2>
						<textarea class="long ckeditor" name="references"></textarea>
					</div><!-- .block-form-add -->
					<div class="block-form-add">
						<input type="submit" value="<?= $text[$lang_sufix]['add_new_example']; ?>"/>
					</div><!-- .block-form-add -->
					
				</form>
			</section><!-- .bloque-presentacion -->
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->
		<div class="clearboth"></div>
	</section> <!-- .wrapper -->