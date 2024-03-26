<?php
// Case Studies


add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

add_action('generate_before_main_content', function() {
	if(!is_post_type_archive('case_study') && !is_tax('solution')) {
		return;
	}

	if( is_tax('solution') ) {
		$post_type_labels = get_post_type_labels(get_post_type_object('case_study'));
		$plural_name = $post_type_labels->name;

		printf('<div class="page-header-category"><h3>%s</h3></div>', $plural_name); 
	}

	/* add_filter( 'get_the_archive_title', function($title) {
		if( is_tax('solution') ) {
			$post_type_labels = get_post_type_labels(get_post_type_object('case_study'));
			$plural_name = $post_type_labels->name;

			$title = sprintf('<div>%s<h3>%s</h3></div>', $title, $plural_name); 
		  }
		  return $title;
	}); */
	
	add_action('generate_after_archive_title', function() {
		$args = array(
			'show_option_none' => __( 'Select a Solution', '_s' ),
			'show_count'       => 0,
			'taxonomy'		   => 'solution',
			'orderby'          => 'name',
			'hierarchical'     => 1,
			'name' 			   => 'solution',
			'hide_if_empty'    => false,
			'class'            => '',
			'echo'             => 0,
			'value_field'      => 'slug'
		);
		
		if( is_tax('solution') ) {
			
			// is this a parent?
			$args['selected'] = get_queried_object()->slug;
			
		} 
		
		
		$url = home_url( '/' );
		
		
		$categories = wp_dropdown_categories( $args );
	
		$filters = sprintf( '<form id="solution-select" class="solution-select" action="%s" method="get">
			   %s
			 </form>',  
			esc_url( $url ),
			$categories
		  );     
	
		  
		
		echo $filters;
	});
	
	add_action('generate_before_loop', function(){
		?><div class="facetwp-template"><?php
	});
	
	add_action('generate_after_loop', function(){
	?></div><?php
	}, 1);
	
	add_action( 'generate_before_content', function() {
		echo '<div class="post-content">';
	}, 12 );
	
	add_filter( 'generate_inside_featured_image_output', function($output) {
	return '<svg fill="none"  viewBox="0 0 386 38" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m386 0h-386v13c0-5.52283 4.47754-10 10-10h178.929c2.202 0 4.343.72729 6.091 2.06885l25.653 19.70105c1.747 1.3417 3.889 2.0688 6.091 2.0688h95.802l53.603.9081c5.456.0924 9.831 4.5418 9.831 9.9986z" fill="#fff" fill-rule="evenodd"/></svg>
	' . $output;
	} );
	
	
	add_filter( 'generate_svg_icon', function( $output, $icon ) {
	if ( 'categories' === $icon ) {
		return false;
	}
	
	return $output;
	}, 15, 2 );
	
	add_filter( 'generate_term_separator', function() {
	return '';
	} );
	
	
	add_action( 'generate_after_content', function() {
		echo '</div>';
	});
	
	
	
	add_action('wp_footer', function() {
	?>
	<script>
	var dropdown = document.getElementById("solution");
	function onCatChange() {
		if ( dropdown.options[dropdown.selectedIndex].value != -1 ) {
			location.href = "<?php echo esc_url( home_url( '/' ) ); ?>?solution="+dropdown.options[dropdown.selectedIndex].value;
		} else {
			location.href = "<?php echo esc_url( get_post_type_archive_link('case_study') ); ?>"
		}
	}
	dropdown.onchange = onCatChange;
	</script>
	<?php
	});
});
