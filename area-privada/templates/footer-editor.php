	<div class="espacio-intermedio"></div>
	
	<footer>
		<section id="content-footer">
			<section class="columna-izquierda-footer">
				<p class="titulo-footer"><?= $text[$lang_sufix]['colmena_project']; ?>
				<?= $text[$lang_sufix]['university']; ?></p>
				<p class="titulo-footer">Carlos Fernández Medina | Julia Vallina García | Juan Ramón Pérez Pérez</p>
				
			</section><!-- .columna-izquierda-footer -->
			<div class="clearboth"></div>
		</section><!-- #content-footer -->
	</footer>
	</div><!-- #wrapper-->
</body>
<script type="text/javascript">
	<!--
	var editor1 = CKEDITOR.replace( 'cktextarea1' );
	-->
</script>
<script src="./js/chart/highcharts.js"></script>
	<script type="text/javascript">
		var base = '<?= $base ?>';
		subjectCharts();
		userCharts();
	</script>
	
</html>