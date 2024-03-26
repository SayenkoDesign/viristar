import $ from 'jquery';

import cssHasPseudo from 'css-has-pseudo/browser';
cssHasPseudo(document);

// jquery match height NMP
//import jqueryMatchHeight from 'jquery-match-height';

import general from './modules/general';

//import blog from './modules/blog';


document.addEventListener('DOMContentLoaded', function () {
	general.init();
	//blog.init();

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