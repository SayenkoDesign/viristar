<?php

namespace App;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Nav Sections
 *
 * Creates a navigation bar with jump links to content sections
 * Skips navigation items for empty content sections
 *
 * @since 1.0.0
 */
class Nav_Sections
{
    private $id;
    private $_render_attributes = [];
    private $items = [];
    private $after_nav_container = '';

    public function __construct(array $items = [], array $args = [], $after_nav_container = '')
    {
        $default = [
            'id' => wp_unique_id('nav-'),
        ];

        $args = wp_parse_args($args, $default);
        $this->id = $args['id'];
        $this->items = $items;
        $this->after_nav_container = $after_nav_container;
    }

    public function get_name()
    {
        return 'nav-sections';
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_after_nav_container($content)
    {
        $this->after_nav_container = $content;
        return $this;
    }

    public function add_render_attribute($element, $key = null, $value = null, $overwrite = false)
    {
        if (!isset($this->_render_attributes[$element])) {
            $this->_render_attributes[$element] = [];
        }

        if (is_array($key)) {
            foreach ($key as $attribute_key => $attribute_values) {
                $this->add_render_attribute($element, $attribute_key, $attribute_values, $overwrite);
            }
            return;
        }

        if (is_array($value)) {
            $value = implode(' ', $value);
        }

        if ($overwrite || !isset($this->_render_attributes[$element][$key])) {
            $this->_render_attributes[$element][$key] = $value;
        } else {
            $this->_render_attributes[$element][$key] .= ' ' . $value;
        }
    }

    public function get_render_attribute_string($element)
    {
        if (isset($this->_render_attributes[$element])) {
            $render_attributes = [];

            foreach ($this->_render_attributes[$element] as $attribute_key => $attribute_values) {
                $render_attributes[] = sprintf('%s="%s"', $attribute_key, esc_attr($attribute_values));
            }

            return implode(' ', $render_attributes);
        }

        return '';
    }

    /**
     * Check if content is empty after trimming whitespace
     *
     * @param string $content The content to check
     * @return bool True if content is empty, false otherwise
     */
    private function is_content_empty($content)
    {
        return empty(trim($content));
    }

    public function render()
    {
        // Filter out items with empty content
        $valid_items = array_filter($this->items, function ($item) {
            return !$this->is_content_empty($item['content']);
        });

        if (empty($valid_items)) {
            return;
        }

        $this->add_render_attribute('nav-container', 'class', 'navigation-links');
        $this->add_render_attribute('nav-list', [
            'id' => sprintf('%s-list', $this->id),
            'class' => 'nav-links',
            'role' => 'navigation',
            'aria-label' => 'Page Navigation',
        ]);

        ?>
        <div <?php echo $this->get_render_attribute_string('nav-container'); ?>>
            <?php
if (!empty($this->after_nav_container)) {
            echo $this->after_nav_container;
        }
        ?>
            <ul <?php echo $this->get_render_attribute_string('nav-list'); ?>>
            <?php
$count = 0;
        foreach ($valid_items as $item):
            $count++;
            $section_id = sanitize_title($item['title']);
            $this->add_render_attribute('nav-item-link', [
                'href' => '#' . $section_id,
                'class' => 'nav-link',
            ], null, true);
            ?>
	                <li class="nav-item">
	                    <a <?php echo $this->get_render_attribute_string('nav-item-link'); ?>>
	                        <?php echo esc_html($item['title']); ?>
	                    </a>
	                </li>
	            <?php endforeach;?>
            </ul>
        </div>

        <div class="content-sections">
            <?php
foreach ($valid_items as $item):
            $section_id = sanitize_title($item['title']);
            $this->add_render_attribute('content-section', [
                'id' => $section_id,
                'class' => 'content-section',
            ], null, true);
            ?>
	                <section <?php echo $this->get_render_attribute_string('content-section'); ?>>
	                    <?php echo $item['content']; ?>
	                </section>
	            <?php endforeach;?>
        </div>
        <?php
}
}