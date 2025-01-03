<?php
// Set the .grid-container to be the --content-width

add_action('wp_head', function () {
    ?>
	<script>
	document.addEventListener("DOMContentLoaded", function() {

		// Set the width as a custom property
		document.documentElement.style.setProperty('--container-width', '<?php echo generate_get_option('container_width') ?>px');

		// get the header height
		document.documentElement.style.setProperty('--site-header-height', '');

		updateHeaderHeight;

	});

	function updateHeaderHeight() {
		// Example: calculate the height of an element with the ID 'site-header'
		var newHeight = document.querySelector('.site-header').offsetHeight + 'px'; // Convert height to string with 'px'

		// Get the current value of the CSS variable
		var currentHeight = getComputedStyle(document.documentElement).getPropertyValue('--site-header-height');

		// Update the CSS variable if the value has changed
		if (newHeight !== currentHeight) {
			document.documentElement.style.setProperty('--site-header-height', newHeight);
			// console.log('Header height updated to: ', newHeight);
		}
	}

	// Listen for the 'resize' event to update the height on window resize
	window.addEventListener('resize', updateHeaderHeight);






	</script>
	<?php
}, 5);