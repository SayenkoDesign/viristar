import $ from 'jquery';

import cssHasPseudo from 'css-has-pseudo/browser';
cssHasPseudo(document);

import general from './modules/general';
import course from './modules/course';
import footer from './modules/footer';
import stickyHeader from './modules/sticky-header';
//import blog from './modules/blog';

// Bootsrap
// import ScrollSpy from 'bootstrap/js/dist/scrollspy';
import tabs from 'bootstrap/js/dist/tab';
import modal from 'bootstrap/js/dist/modal';
import OffCanvas from 'bootstrap/js/dist/offcanvas';
import collapse from 'bootstrap/js/dist/collapse';


document.addEventListener('DOMContentLoaded', function () {
	general.init();
	course.init();
	footer.init();
	stickyHeader.init();
	updateScrollbarWidth();
}
);


function updateScrollbarWidth() {
	// Calculate the scrollbar width
	const getScrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

	console.log(`${getScrollbarWidth}px`);

	// Set the scrollbar width as a CSS variable on the body
	document.documentElement.style.setProperty("--scrollbarWidth", `${getScrollbarWidth}px`);

	// Optionally, if you need to directly use or modify the --viewportWidth variable in JS,
	// you could also set it here. Otherwise, it's manageable purely in CSS as shown in your description.
}

// Run the function on window resize
window.addEventListener('resize', updateScrollbarWidth);

/* document.addEventListener('DOMContentLoaded', function () {
	['focus', 'click', 'touchstart', 'mousedown', 'pointerdown'].forEach(function (eventType) {
		document.body.addEventListener(eventType, function (e) {
			if (e.target.matches('.mobile-menu-search-form .search-field') ||
				e.target.matches('.mobile-menu-search-form .search-form') ||
				e.target.closest('.mobile-menu-search-form')) {
				e.stopPropagation();
				e.stopImmediatePropagation();
			}
		}, true);
	});
}); */

// fix bug having search in mobile menu
document.addEventListener('DOMContentLoaded', function () {
	// Store the original offsetParent getter
	const originalOffsetParent = Object.getOwnPropertyDescriptor(HTMLElement.prototype, 'offsetParent');

	// Override offsetParent for menu toggle elements
	Object.defineProperty(HTMLElement.prototype, 'offsetParent', {
		get: function () {
			// If search is focused, make menu toggle appear visible
			if (this.classList.contains('menu-toggle') &&
				document.activeElement.matches('.mobile-menu-search-form .search-field')) {
				return document.body; // Return non-null to prevent menu close
			}
			// Otherwise use original behavior
			return originalOffsetParent.get.call(this);
		}
	});
});