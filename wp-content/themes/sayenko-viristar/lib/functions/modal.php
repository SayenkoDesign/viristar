<?php

// Modal

add_action('wp_footer', 'add_modals_to_footer');
function add_modals_to_footer()
{

    $args = array(
        'post_type' => 'modal',
        'posts_per_page' => 100,
        'post_status' => 'publish',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );

    $loop = new WP_Query($args);

    if ($loop->have_posts()):
        while ($loop->have_posts()): $loop->the_post();
            modal_content(get_the_ID());

        endwhile;
    endif;

    wp_reset_postdata();

}

function modal_content($post_id = null)
{

    if (!$post_id) {
        return;
    }

    if (get_post_field('post_content', $post_id)):
    ?>
			<div class="modal" id="modal-<?php echo $post_id; ?>" tabindex="-1" aria-labelledby="TimeModal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h1 id="TimeModal" class="visually-hidden">Modal</h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">

						<div class="modal-text">

						<?php
the_content();
    ?>
						</div>
						</div>
					</div>
				</div>

			</div>
		<?php
endif;
}

function _s_menu_item_fancybox($item_output, $item)
{
    if (!empty($item->object) && 'modal' === $item->object) {
        $slug = sanitize_title_with_dashes($item->title);
        $post_id = $item->object_id;

        return sprintf('<a data-bs-toggle="modal" data-bs-target="#modal-%s" href="#">%s</a>', $post_id, $item->title);
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el', '_s_menu_item_fancybox', 10, 2);
