<?php

/**
 * Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

use \App\ACF_Block;

$block = new ACF_Block($block, $content, $is_preview);

/* $block->add_render_attribute(
'block', 'class', [
'alignfull',
]
);
 */

// Placeholder
if ($is_preview) {
    printf('<div class="acf-block-placeholder"><h3>%s</h3><p>Click to Edit</p></div>', $block->get_title());
    return;
}

$show_filters = get_field('filters');
if (!empty($show_filters)) {
    $show_filters = strtolower(get_field('filters'));
}

$terms = get_field('terms');
$post_ids = get_field('posts');

$filters = '';

$filters_id = wp_unique_id('filters-');

// Open the block
echo $block->before_render();

$args = array(
    'post_type' => 'faq',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'fields' => 'ids',
);

if (!empty($terms)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'topic', // taxonomy name
            'field' => 'term_id', // term_id, slug or name
            'terms' => $terms, // term id, term slug or term name
        ),
    );

    foreach ($terms as $id) {
        $term = get_term($id, 'topic');
        if ($term->count) {
            $filters .= sprintf(
                '<li data-filter=".%s"><span class="filter">%s</span></li>',
                esc_attr($term->slug),
                esc_attr($term->name)
            );
        }
    }

    $filters = sprintf('<ul class="filter-button-group" id="%s"><li class="active" data-filter="*"><span class="filter">All</span></li>%s</ul>', $filters_id, $filters);
}

if (!empty($post_ids)) {
    $args['orderby'] = 'post__in';
    $args['post__in'] = $post_ids;
    $args['posts_per_page'] = count($post_ids);
}

// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()): ?>

	<?php
if ('yes' == $show_filters && !empty($filters)) {
    echo $filters;
}

$items = [];
?>

		<?php
while ($loop->have_posts()): $loop->the_post();

    $term_classnames = '';
    $term_list = wp_get_post_terms(get_the_ID(), 'topic');
    foreach ($term_list as $t) {
        $term_classnames .= ' ' . $t->slug;
    }

    $items[] = [
        'title' => get_the_title(),
        'content' => apply_filters('the_content', get_the_content()),
        'term' => $term_classnames,
    ];

endwhile;
endif;
wp_reset_postdata();

$accordion = new \App\Accordion($items, ['accordion_header_size' => 'h3']);

$accordion->render();

if (!$is_preview):

    if ('yes' == $show_filters && !empty($filters)):
    ?>
			<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
		<?php
endif;
?>
	<script>
		(function(document, window, $) {

			<?php
if ('yes' == $show_filters && !empty($filters)):
?>
				Isotope.Item.prototype._create = function() {
				// assign id, used for original-order sorting
				this.id = this.layout.itemGUID++;
				// transition objects
				this._transn = {
					ingProperties: {},
					clean: {},
					onEnd: {}
				};
				this.sortData = {};
				};

				Isotope.Item.prototype.layoutPosition = function() {
				this.emitEvent( 'layout', [ this ] );
				};

				Isotope.prototype.arrange = function( opts ) {
				// set any options pass
				this.option( opts );
				this._getIsInstant();
				// just filter
				this.filteredItems = this._filter( this.items );
				// flag for initalized
				this._isLayoutInited = true;
				};

				// layout mode that does not position items
				Isotope.LayoutMode.create('none');


				$('#<?php echo $accordion->get_id(); ?>').isotope({
					itemSelector: '.accordion-item',
					transitionDuration: 0,
					layoutMode: 'none'
				});

				// filter items on button click
				$('#<?php echo $filters_id; ?>').on('click', 'li', function() {
					var filterValue = $(this).attr('data-filter');
					$('#<?php echo $accordion->get_id(); ?>').isotope({
						filter: filterValue
					});
					$('#<?php echo $filters_id; ?> li').removeClass('active');
					$(this).addClass('active');
				});
			<?php
endif;
?>

		}(document, window, jQuery));
	</script>
<?php
endif;
// close the block
echo $block->after_render();
?>
