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

$block->set_render_attribute('class', 'alignfull');

$default_blocks = array();

if ($is_preview):
?>
	<InnerBlocks
		template="<?php echo esc_attr(wp_json_encode($default_blocks)); ?>"
		className="tab-container" />
<?php
else:

    $add_to_cart = _s_get_template_part(sprintf('blocks/%s/components', $block->get_name()), 'add-to-cart', null, true);

    $items = sd_parse_block_content($content);

    if (empty($items)) {
        return;
    }

    echo $block->before_render();

    if (!empty($add_to_cart)) {
        $add_to_cart = sprintf('<div class="tab-add-to-cart"><div>%s</div></div>', $add_to_cart);
    }

    $sections = new \App\Nav_Sections($items);

    $sections->add_render_attribute('tabs', 'aria-hidden', 'true');

    $sections->set_after_nav_container($add_to_cart);

    $sections->render();

    // Accordion
    $accordion = new \App\Accordion($items);

    $accordion->render();

    ?>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const sectionLinks = document.querySelectorAll('.nav-link');
					let clickedLink = null;

					function setActiveClass(activeLink) {
						sectionLinks.forEach(link => link.classList.remove('active'));
						if (activeLink) {
							activeLink.classList.add('active');
						}
					}

					function handleScroll() {
						// If this scroll was triggered by a click, use the clicked link
						if (clickedLink) {
							setActiveClass(clickedLink);
							clickedLink = null;
							return;
						}

						const scrollPosition = window.scrollY;
						let activeFound = false;

						sectionLinks.forEach(link => {
							const targetId = link.getAttribute('href');
							if (!targetId?.startsWith('#')) return;

							const targetElement = document.querySelector(targetId);
							if (!targetElement) return;

							const sectionTop = targetElement.offsetTop;
							const sectionBottom = sectionTop + targetElement.offsetHeight;

							if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
								setActiveClass(link);
								activeFound = true;
							}
						});

						if (!activeFound) {
							setActiveClass(null);
						}
					}

					sectionLinks.forEach(link => {
						link.addEventListener('click', (e) => {
							clickedLink = link;
							setActiveClass(link);
						});
					});

					window.addEventListener('scroll', handleScroll);
					handleScroll();
				});
			</script>

		<?php

    // close the block
    echo $block->after_render();
endif;
