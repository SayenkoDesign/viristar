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

$default_blocks = array(
    
);

if($is_preview) :
?>
<InnerBlocks
		template="<?php echo esc_attr( wp_json_encode( $default_blocks ) ); ?>"
        className="tab-container"
    />
<?php
else:

    $add_to_cart = '';

    if(is_product()) {



        $product_id = get_the_ID(); // Assumes you're in the product loop
        $product = wc_get_product($product_id);

        if ($product && $product instanceof WC_Product) {

            $external_product_url = get_field('external_product_url', $product_id);
            $external_product_url_description = get_field('external_product_url_description', $product_id);

            if($product->is_in_stock()) {
                if(! empty($external_product_url)) {
                    $add_to_cart = sprintf('<div class="cart external"><a href="%s" class="gb-button" target="_blank">Enroll Now</a></div>', $external_product_url);
                    // $add_to_cart .= _s_format_string( $external_product_url_description, 'p', ['class' => 'external-product-url-description'] );
                    $add_to_cart = _s_format_string( $add_to_cart, 'div' );
            
                } else {
                    ob_start();
                    do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
                    //woocommerce_template_single_add_to_cart();
                    $add_to_cart = ob_get_clean();
                }
            }
        }

    }

    echo $block->before_render();

    $items = sd_parse_block_content($content);

    if(empty($items)) {
        echo $block->after_render();
        return;
    }

    if(! empty($add_to_cart)) {
        printf('<div class="tab-add-to-cart"><div>%s</div></div>', $add_to_cart );
    }

    $tabs = new \App\Tabs($items);

    $tabs->add_render_attribute('tabs', 'aria-hidden', 'true');

    $tabs->render();

    // Accordion
	$accordion = new \App\Accordion($items);

	$accordion->render();
    

    // close the block
    echo $block->after_render();
endif;