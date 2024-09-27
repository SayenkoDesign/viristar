import $ from 'jquery';

export default {
	init() {

		// Function to set a cookie
		function setCookie(name, value, days) {
			let expires = "";
			if (days) {
				const date = new Date();
				date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				expires = "; expires=" + date.toUTCString();
			}
			document.cookie = name + "=" + (value || "")  + expires + "; path=/";
		}

		// Function to get a cookie
		function getCookie(name) {
			const nameEQ = name + "=";
			const ca = document.cookie.split(';');
			for(let i = 0; i < ca.length; i++) {
				let c = ca[i];
				while (c.charAt(0) == ' ') c = c.substring(1, c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
			}
			return null;
		}

		// Select the parent wrapper
		const courseViewWrap = document.querySelector('.course-view-wrap');

		if (!courseViewWrap) {
			return;
		}

		// Select the spans inside the wrapper
		const listViewSpan = courseViewWrap.querySelector('.course-view__list');
		const gridViewSpan = courseViewWrap.querySelector('.course-view__grid');

		// Select the parent element to toggle classes on
		const coursesList = courseViewWrap.closest('.courses');

		// Function to set view
		function setView(view) {
			if (view === 'list') {
				listViewSpan.classList.add('active');
				gridViewSpan.classList.remove('active');
				coursesList.classList.add('courses--table');
				coursesList.classList.remove('courses--grid');
			} else {
				gridViewSpan.classList.add('active');
				listViewSpan.classList.remove('active');
				coursesList.classList.add('courses--grid');
				coursesList.classList.remove('courses--table');
			}
			setCookie('courseView', view, 30); // Set cookie for 30 days
		}

		// Check cookie and set initial view
		const savedView = getCookie('courseView');
		if (savedView) {
			setView(savedView);
		} else {
			setView('grid'); // Default view if no cookie is set
		}

		listViewSpan.addEventListener('click', function () {
			setView('list');
		});

		gridViewSpan.addEventListener('click', function () {
			setView('grid');
		});


	},
};
