<?php 
	if(isset($id) && $id != '') : 
		include ($root_path."templates/content/errors-template.php"); 	
	else:
?>
<section class="wrapper">
		
		<section class="bloque-principal sin-sidebar">			
			<section class="bloque-presentacion">				
			</section><!-- .bloque-presentacion -->
			<script type="text/javascript">
				loadIsotopeErrors();
			</script>
			<div class="clearboth"></div>
		</section><!-- .bloque-principal -->
		<div class="clearboth"></div>
	</section> <!-- .wrapper -->
<?php endif; ?>