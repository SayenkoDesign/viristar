import $ from 'jquery';


export default {
	init() {


		const $siteHeader = $(".site-header");

		console.log('stickyHeader.js loaded');

		$(window).on('scroll wheel', function (e) {
			var scrollPosition = $(window).scrollTop();

			if (e.originalEvent.deltaY > 0) {
				// Scrolling down
				$siteHeader.removeClass('sticky');
			} else if (e.originalEvent.deltaY < 0) {
				// Scrolling up
				if (scrollPosition > 0) {
					$siteHeader.addClass('sticky');
				}
			}

			// Remove 'sticky' class when at the top of the page
			if (scrollPosition === 0) {
				$siteHeader.removeClass('sticky');
			}
		});

		let lastScrollTop = 0;

		$(window).on('scroll mousewheel DOMMouseScroll', function (e) {
			var scrollPosition = $(window).scrollTop();

			if (scrollPosition > lastScrollTop && !$siteHeader.hasClass('scrolling') && scrollPosition > 0) {
				// Scrolling down and class not already added
				$siteHeader.addClass('scrolling');
			} else if (scrollPosition < lastScrollTop && $siteHeader.hasClass('scrolling')) {
				// Scrolling up and class already added
				$siteHeader.removeClass('scrolling');
			}

			// Update last scroll position
			lastScrollTop = scrollPosition;
		});

	},
};


