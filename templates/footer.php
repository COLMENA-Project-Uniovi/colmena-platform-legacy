
	</div><!-- #wrapper-->
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
</body>
<script src="./js/chart/highcharts.js"></script>
<script src="http://code.highcharts.com/highcharts-more.js"></script>
	<script type="text/javascript">
		var base = '<?= $base ?>';
		subjectCharts();
		userCharts();
		multipleSessionsCharts();
	</script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-59817289-1', 'auto');
	  ga('send', 'pageview');

	</script>
</html>