<div class="filters-wrapper">
<div class="filters">

    <?php

if (function_exists('facetwp_display')) {

    echo facetwp_display('facet', 'product_course');

    //echo facetwp_display('facet', 'product_course_mode_select');
    ?>
	<div class="facetwp-submit" style="display: none;"><button class="fwp-submit" onclick="FWP.refresh()"><i class="fa fa-search"></i> <span class="">Search</span></button></div>
	<?php
}
?>
</div>
</div>