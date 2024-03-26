import $ from 'jquery';

export default {
	init() {
        "use strict";
        
		if(!$('body').hasClass('home')) { 
           return;
        }
	},
};
