import Splide from '@splidejs/splide';

window.splides = {};



// Main function for initializing all Splide sliders
const initializePage = () => {
    var elms = document.getElementsByClassName('splide');

    const initializeSplide = (element) => {
        const id = element.id;

		if(!id) {
			console.log('No ID found for Splide element: ' + element.getAttribute('class'));
		}
        
        if (!window.splides[id]) {
            const splide = new Splide(element);

            splide.mount();
            window.splides[id] = splide;
        } else {
            window.splides[id].refresh();
        }
    };

    // Initialize all Splide instances
    for (let elm of elms) {
        initializeSplide(elm);
    }


};

// Initialize everything when the DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    initializePage();
});


// Debugging function to log all existing Splide instances

/* window.addEventListener('load', function () {
	function logExistingSplides() {
        console.log('Existing Splide instances:');
        
        if (typeof window.splides !== 'object' || window.splides === null) {
            console.log('No window.splides object found');
            return;
        }
    
        Object.keys(window.splides).forEach((key, index) => {
            const splide = window.splides[key];
            console.log(`${index + 1}. ID: ${key}`);
            console.log(`   - Element:`, splide.root);
            console.log(`   - Options:`, splide.options);
            console.log(`   - Is Mounted:`, splide.state.is(Splide.STATES.MOUNTED));
            console.log(`   - Current Index:`, splide.index);
            console.log(`   - Number of Slides:`, splide.length);
            console.log('-------------------');
        });
    }

	// Give some time for all Splides to initialize
	setTimeout(logExistingSplides, 1000);
}); */