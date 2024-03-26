import $ from 'jquery';

export default {
	init() {

	if(!$('body').hasClass('single-white_paper')) { 
		return;
	}
	
	
	function removeDataAttributes(target) {
		var $target = $(target);
		
		// Loop through data attributes.
		$.each($target.data(), function (key) {
			// Because each key is in camelCase,
			// we need to convert it to kabob-case and store it in attr.
			var attr = 'data-' + key.replace(/([A-Z])/g, '-$1').toLowerCase(); 
			// Remove the attribute.
			$target.removeAttr(attr);
		});
	};

		
	/*
     * Create cookies
     */
		function createCookie(name,value,days) {
			var expires = "";
			if (days) {
				var date = new Date();
				date.setTime(date.getTime() + (days*24*60*60*1000));
				expires = "; expires=" + date.toUTCString();
			}
			document.cookie = name + "=" + value + expires + "; path=/";
		}
		
		/*
		 * Get cookie
		*/
		
		function getCookie(name) {
			var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
			return v ? v[2] : null;
		}
		
	
		
		// if "gatedPdf" cookie exists
		var gatedPdf = getCookie('gatedPdf');
		
		if(gatedPdf == 1 || gatedPdf == "1"){

			$('.download-pdf').attr("href", $('.download-pdf').attr('data-url') ); // Set herf value
			$('.download-pdf').attr("target", "_blank");
			//removeDataAttributes('.gated-pdf');
			//$('.download-pdf').removeClass('gated-pdf');
		} else {
			$('#download > .acf-block').removeClass('hide');
		}
		
		
		/*
		 * After ajax form submission, redirect visitor to link, and set cookie so that all future visits will have no modal form
		*/
		
		$(document).on('gform_confirmation_loaded', function(e, formId){
			
			if(formId != 3) {  // Download form
				return;
			}

			if($('.gated-pdf').length == 0) {
				return;
			}


			createCookie('gatedPdf',1,7);

			$('.download-pdf').attr("href", $('.download-pdf').attr('data-url') ); // Set herf value
			$('.download-pdf').attr("target", "_blank");
			
			//removeDataAttributes('.gated-pdf');
			//$('.download-pdf').removeClass('gated-pdf');
			
			//window.open($('.download-pdf').attr("href"),"_blank");
			
		});


	},
};
