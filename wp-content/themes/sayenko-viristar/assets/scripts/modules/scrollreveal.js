import $ from 'jquery';
import ScrollReveal from 'scrollreveal';

export default {
	init() {
		"use strict";

		ScrollReveal({ mobile: false, cleanup: true });

		(function () {

			// GeneratePress Blocks
			$('[data-animation]').each(function () {
				console.log($(this));

				var settings = {
					'origin': 'bottom',
					'distance': '100%',
					'duration': 500,
					'delay': 200,
					'opacity': 0,
					'scale': 1,
					'reset': false
				}

				if ($(this).data('origin')) {
					settings.origin = $(this).data('origin');
				}

				if ($(this).data('distance')) {
					settings.distance = $(this).data('distance');
				}

				if ($(this).data('duration')) {
					settings.duration = $(this).data('duration');
				}

				if ($(this).data('delay')) {
					settings.delay = $(this).data('delay');
				}

				if ($(this).data('opacity')) {
					settings.scale = $(this).data('opacity');
				}

				if ($(this).data('scale')) {
					settings.scale = $(this).data('scale');
				}

				console.log(settings);

				ScrollReveal().reveal($(this), settings);
			});


			ScrollReveal().reveal('.is-desktop .gb-container-animate', {
				delay: 400,
				distance: '100%',
				interval: 200
			});



		})();





	},
};
