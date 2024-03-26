<?php
function sk_query_offset($query)
{

	// Before anything else, make sure this is the right query...
	if (!($query->is_home() && $query->is_main_query())) {
		return;
	}

	// First, define your desired offset...
	$offset = 1;

	// Next, determine how many posts per page you want (we'll use WordPress's settings)
	$ppp = get_option('posts_per_page');

	// Next, detect and handle pagination...
	if ($query->is_paged) {

		// Manually determine page query offset (offset + current page (minus one) x posts per page)
		$page_offset = $offset + (($query->query_vars['paged'] - 1) * $ppp);

		// Apply adjust page offset
		$query->set('offset', $page_offset);
	} else {

		// This is the first page. Set a different number for posts per page
		$query->set('posts_per_page', $offset + $ppp);
	}

	/* $query->set('ignore_sticky_posts', true);
	$query->set('post__not_in', get_option("sticky_posts")); */
}

add_action('pre_get_posts', 'sk_query_offset', 1);


function sk_adjust_offset_pagination($found_posts, $query)
{

	// Define our offset again...
	$offset = 1;

	// Ensure we're modifying the right query object...
	if ($query->is_home() && $query->is_main_query()) {
		// Reduce WordPress's found_posts count by the offset...
		return $found_posts - $offset;
	}
	return $found_posts;
}

add_filter('found_posts', 'sk_adjust_offset_pagination', 1, 2);


/**
 * Get the primary term of a post, by taxonomy.
 * If Yoast Primary Term is used, return it,
 * otherwise fallback to the first term.
 *
 * @version  1.1.0
 *
 * @link     https://gist.github.com/JiveDig/5d1518f370b1605ae9c753f564b20b7f
 * @link     https://gist.github.com/jawinn/1b44bf4e62e114dc341cd7d7cd8dce4c
 * @author   Mike Hemberger @JiveDig.
 *
 * @param    string  $taxonomy  The taxonomy to get the primary term from.
 * @param    int     $post_id   The post ID to check.
 *
 * @return   WP_Term|bool  The term object or false if no terms.
 */
function _s_get_primary_term( $taxonomy = 'category', $post_id = false ) {
	// Bail if no taxonomy.
	if ( ! $taxonomy ) {
		return false;
	}
	// If no post ID, set it.
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	// If checking for WPSEO.
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		// Get the primary term.
		$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
		$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
		// If we have one, return it.
		if ( $wpseo_primary_term ) {
			return get_term( $wpseo_primary_term );
		}
	}
	// We don't have a primary, so let's get all the terms.
	$terms = get_the_terms( $post_id, $taxonomy );
    
	// Bail if no terms.
	if ( ! $terms || is_wp_error( $terms ) ) {
		return false;
	}
    
	// Return the first term.
	return $terms[0];
}


add_filter('wp_dropdown_cats', function($output) {
	return sprintf('<div class="select-wrapper">%s</div>', $output );
});

add_action('generate_before_main_content', function() {
	if(!is_home() && !is_category()) {
		return;
	}

	add_filter('get_the_archive_title', function( $title ) {
	
		if(is_home()) {
			$title =  get_the_title(get_option('page_for_posts'));
		}
		
		return $title;
	});
	
	add_action('generate_after_archive_title', function() {
		$args = array(
			'show_option_none' => __( 'Select a Category', '_s' ),
			'show_count'       => 0,
			'orderby'          => 'name',
			'hierarchical'     => 1,
			'hide_if_empty'    => false,
			'class'            => '',
			'echo'             => 0,
		);
		
		if( is_category() ) {
			
			// is this a parent?
			$category = get_category( get_query_var( 'cat' ) );
			$args['selected'] =  $category->cat_ID;                            
		} 
		
		
		$url = home_url( '/' );
		
		
		$categories = wp_dropdown_categories( $args );
	
		$filters = sprintf( '<form id="category-select" class="category-select" action="%s" method="get">
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


	add_filter( 'generate_next_link_text', function() {
		$next = _s_get_icon(
			[
				'icon'	=> 'next',
				'group'	=> 'theme',
				'class'	=> 'next-icon',
				'width' => 94,
				'height'=> 94,
				'label'	=> false,
			]
		);
		return sprintf( '<span class="screen-reader-text" title="%1$s">%1$s</span>%2$s', esc_attr__( 'Next', 'generatepress' ), $next );
	} );
	
	add_filter( 'generate_previous_link_text', function() {
		$previous = _s_get_icon(
			[
				'icon'	=> 'previous',
				'group'	=> 'theme',
				'class'	=> 'previous-icon',
				'width' => 94,
				'height'=> 94,
				'label'	=> false,
			]
		);
		return sprintf( '<span class="screen-reader-text" title="%1$s">%1$s</span>%2$s', esc_attr__( 'Previous', 'generatepress' ), $previous );
	} );
	
	
	
	
	add_action('wp_footer', function() {

		$next = _s_get_icon(
			[
				'icon'	=> 'next',
				'group'	=> 'theme',
				'class'	=> 'next-icon',
				'width' => 94,
				'height'=> 94,
				'label'	=> false,
			]
		);

		$previous = _s_get_icon(
			[
				'icon'	=> 'previous',
				'group'	=> 'theme',
				'class'	=> 'previous-icon',
				'width' => 94,
				'height'=> 94,
				'label'	=> false,
			]
		);
	?>
	<script>
		(function (document, window, $) {

			const nav_links = $('.nav-links');
			const prev = '<a class="prev disabled page-numbers" aria-hidden="true"><span class="screen-reader-text" title="Previous">Previous</span><?php echo $previous;?></a>';
			const next = '<a class="next disabled page-numbers" aria-hidden="true"><span class="screen-reader-text" title="Next">Next</span><?php echo $next;?></a>';
			$('body:not(.paged) .nav-links').prepend(prev);

			if(nav_links.length > 0 && nav_links.find('.next').length == 0) {
				$('.nav-links').append(next);
			}

			if (document.getElementById("cat")) {
				var dropdown = document.getElementById("cat");
				function onCatChange() {
					if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
						location.href = "<?php echo esc_url( home_url( '/' ) ); ?>?cat="+dropdown.options[dropdown.selectedIndex].value;
					} else {
						location.href = "<?php echo esc_url( get_permalink(get_option('page_for_posts')) ); ?>"
					}
				}
				dropdown.onchange = onCatChange;
			}

		}(document, window, jQuery));

	
	</script>
	<?php
	});
});



/* add_filter( 'next_post_link', function( $output, $format, $link, $post ) {

    if ( ! $post ) {
    return '';
  }

  return sprintf(
      '<div class="nav-next"><span class="next"><a href="%1$s" title="%2$s">Next %3$s</a></span></div>',
        get_permalink( $post ),
        $post->post_title,
        generate_get_svg_icon( 'arrow-right' )
  );
}, 10, 4 );

add_filter( 'previous_post_link', function( $output, $format, $link, $post ) {
    if ( ! $post ) {
    return '';
  }

  return sprintf(
      '<div class="nav-previous"><span class="prev"><a href="%1$s" title="%2$s">%3$s Previous</a></span></div>',
        get_permalink( $post ),
        $post->post_title,
        generate_get_svg_icon( 'arrow-left' )
  );
}, 10, 4 );
 */



 /*
function check_for_featured_posts() {
    // Define the query arguments
    $args = array(
        'post_type'      => 'post', // or 'page' or custom post type
        'post_status'    => 'publish',
        'posts_per_page' => 1, // we only need to find at least one
        'meta_query'     => array(
            array(
                'key'   => 'is_featured',
                'value' => 'true',
                'compare' => '=',
            ),
        ),
    );

    // Perform the query
    $query = new WP_Query($args);

    // Check if any posts were found
    if ($query->have_posts()) {
        return true; // Yes, there are featured posts
    } else {
        return false; // No, there are no featured posts
    }
}

// Example usage
if (check_for_featured_posts()) {
    echo "There are featured posts.";
} else {
    echo "There are no featured posts.";
}

 */