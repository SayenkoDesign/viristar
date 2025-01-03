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

echo $block->before_render();

$rows = get_field('links');

if (empty($rows)) {

    return;
}

$links = '';

foreach ($rows as $row) {
    $title = $row['title'];
    $id = sanitize_title($title);
    $links .= sprintf('<li class="section-link-list-item"><a href="#%s" class="section-link">%s</a></li>', $id, $title);
}

// Button?

$button = get_field('button') ?: [];
$button['classes'] = 'section-link gb-button';
$button = _s_get_acf_button($button);
if (!empty($button)) {
    $links .= sprintf('<li class="button-wrapper">%s</li>', $button);
}

echo _s_format_string($links, 'ul', ['class' => 'section-links']);
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sectionLinks = document.querySelectorAll('.section-link');
    const headerOffset = 100; // Adjust as needed

    // Helper function to set the active class
    function setActiveClass(activeLink) {
        sectionLinks.forEach(link => link.classList.remove('active'));
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }

    // Handle scroll events
    function handleScroll() {
        const scrollPosition = window.scrollY + headerOffset;
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

        // Clear active state if no section is matched
        if (!activeFound) {
            setActiveClass(null);
        }
    }

    // Add scroll listener
    window.addEventListener('scroll', handleScroll);

    // Initial call to set active link
    handleScroll();
});

					</script>
					<?php

echo $block->after_render();