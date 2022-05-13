<div class='column progress-circle-container'>
	<div class="radial-progress" data-score="<?= $cc_average ?>">
		<div class="circle">
			<div class="mask full">
				<div class="fill"></div>
			</div><!-- .mask.full -->
			<div class="mask half">
				<div class="fill"></div>
				<div class="fill fix"></div>
			</div><!-- .mask.half -->
		</div><!-- .circle -->
		<div class="inset"><span class='big'><?= $cc_average ?></span><span class='little'>/ 100</span></div>
	</div><!-- .radial-progress -->
	<p><?= $text[$lang_sufix]['colmena_coeficient']; ?></p>
</div><!-- .progress-circle-container -->