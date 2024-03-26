import $ from 'jquery';

import '@accessible360/accessible-slick';


document.addEventListener('DOMContentLoaded', function () {

	$('.slick').slick();
	$('.slick-cloned').find('a').removeAttr('data-fancybox'); // remove duplicate fancbox

	$('.modal').on('shown.bs.modal', function (e) {

		let $modal = $(e.currentTarget);

		//console.log(e.target.id);

		var $anchor = $(e.relatedTarget);

		let slideIndex = $anchor.parents('.grid__item').index();
		
		$('.slick', '#' + e.target.id ).slick('setPosition'); // needed to trigger width 0 issue in modal
		$('.slick', '#' + e.target.id ).slick( 'slickGoTo', parseInt(slideIndex) );
		$('.slick .slide').matchHeight();
		$('.slick', '#' + e.target.id ).slick( 'slickSetOption', 'speed', 500, false ); // set speed after so we remove layout shift
		
		$('.modal-body', '#' + e.target.id ).addClass('show');
	})

}, false);