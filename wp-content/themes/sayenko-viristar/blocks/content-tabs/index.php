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
    echo $block->before_render();

    $items = sd_parse_block_content($content);

    if(empty($items)) {
        echo $block->after_render();
        return;
    }

    $tabs = new \App\Tabs($items);

    $tabs->add_render_attribute('tabs', 'aria-hidden', 'true');

	$tabs->add_render_attribute('tabs-list', 'class', 'nav-tabs--count-'.count($items));

    $tabs->render();

    // Accordion
	$accordion = new \App\Accordion($items);

	$accordion->render();
    
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const tabList = document.getElementById('<?php echo $tabs->get_id(); ?>-tabs-list');
    const tabs = tabList.querySelectorAll('.nav-link');

    // Set CSS variable --nav-tab-count to the number of tabs
    // tabList.style.setProperty('--nav-tab-count', tabs.length);

    // Create the underline element
    const underline = document.createElement('li');
    underline.className = 'tab-underline';
    underline.style.position = 'absolute'; // Make sure the underline is absolutely positioned
    underline.style.transition = 'transform 0.3s ease'; // Add a transition for smooth movement
    tabList.appendChild(underline);

    // Set initial width and position of the underline
    const setUnderlinePosition = (tab) => {
        underline.style.width = `${tab.offsetWidth}px`;
        underline.style.transform = `translate3d(${tab.offsetLeft}px, 0px, 0px)`;
    };

    // Set initial position based on the active tab
    const activeTab = tabList.querySelector('.nav-link.active');
    if (activeTab) {
        setUnderlinePosition(activeTab);
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            setUnderlinePosition(tab);
        });
    });

    // Adjust the underline position on window resize
    window.addEventListener('resize', function() {
        const activeTab = tabList.querySelector('.nav-link.active');
        if (activeTab) {
            setUnderlinePosition(activeTab);
        }
    });
});

</script>
<?php
    // close the block
    echo $block->after_render();
endif;