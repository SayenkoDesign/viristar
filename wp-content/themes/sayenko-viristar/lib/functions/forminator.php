<?php
// Forminator

add_filter( 'forminator_enqueue_form_styles', function( $styles ){
	$form_ids = [5937];
	foreach( $form_ids as $form_id ){
		wp_dequeue_style( "forminator-module-css-{$form_id}" );
	}
	return $styles;
}, 999 );