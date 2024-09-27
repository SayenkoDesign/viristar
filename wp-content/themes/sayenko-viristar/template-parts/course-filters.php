<div class="filters">

    <?php

	if(function_exists('facetwp_display')) {

		echo facetwp_display('facet', 'product_course_category');

		echo facetwp_display('facet', 'product_course');

		echo facetwp_display('facet', 'product_course_mode');
	?>
	<div class="facetwp-submit" style="display: none;"><button class="fwp-submit" onclick="FWP.refresh()"><i class="fa fa-search"></i> <span class="">Search</span></button></div>
	<?php
	}
	?>
</div>