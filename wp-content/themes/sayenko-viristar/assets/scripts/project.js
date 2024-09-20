import $ from 'jquery';

import cssHasPseudo from 'css-has-pseudo/browser';
cssHasPseudo(document);

import general from './modules/general';
import course from './modules/course';
import footer from './modules/footer';
//import blog from './modules/blog';

// Bootsrap
import tabs from 'bootstrap/js/dist/tab';
import modal from 'bootstrap/js/dist/modal';
import collapse from 'bootstrap/js/dist/collapse';


document.addEventListener('DOMContentLoaded', function () {
	general.init();
	course.init();
	footer.init();
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