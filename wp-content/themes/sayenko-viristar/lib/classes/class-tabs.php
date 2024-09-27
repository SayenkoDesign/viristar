<?php

namespace App;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Tabs
 *
 * @since 1.0.0
 */
class Tabs
{
    private $id;
    private $_render_attributes = [];
    private $items = [];

    public function __construct(array $items = [], array $args = [])
    {
        $default = [
            'id' => wp_unique_id('tabs-')
        ];

        $args = wp_parse_args($args, $default);
        $this->id = $args['id'];
        $this->items = $items;
    }

    public function get_name()
    {
        return 'tabs';
    }

	public function get_id()
    {
        return $this->id;
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

    public function render()
    {
        if (empty($this->items)) {
            return;
        }

        $this->add_render_attribute('tabs', 'class', 'tabs');
        $this->add_render_attribute('tabs-list', [
            'id' => sprintf('%s-tabs-list', $this->id),
            'class' => ['nav', 'nav-tabs'],
            'role' => 'tablist',
        ]);

        ?>
        <div <?php echo $this->get_render_attribute_string('tabs'); ?>>
            <ul <?php echo $this->get_render_attribute_string('tabs-list'); ?>>
            <?php
            $count = 0;
            foreach ($this->items as $item) :
                $count++;
                $this->add_render_attribute('nav-item-button', [
                    'id' => sprintf('%s-%d', $this->id, $count),
                    'class' => ['nav-link', $count === 1 ? 'active' : ''],
                    'aria-selected' => $count === 1 ? 'true' : 'false',
                    'data-bs-toggle' => 'tab',
                    'data-bs-target' => sprintf('#%s-content-%d', $this->id, $count),
                    'type' => 'button',
                    'role' => 'tab',
                    'aria-controls' => sprintf('%s-content-%d', $this->id, $count)
                ], null, true );
                ?>
                <li class="nav-item" role="presentation">
                    <button <?php echo $this->get_render_attribute_string('nav-item-button'); ?>>
                        <?php echo esc_html($item['title']); ?>
                    </button>
                </li>
            <?php endforeach; ?>
            </ul>

            <div class="tab-content" id="<?php echo esc_attr($this->id . '-content'); ?>">
            <?php
            $count = 0;
            foreach ($this->items as $item) :
                $count++;
                $this->add_render_attribute('tab-pane', [
                    'id' => sprintf('%s-content-%d', $this->id, $count),
                    'class' => ['tab-pane', 'fade', $count === 1 ? 'show active' : ''],
                    'aria-selected' => $count === 1 ? 'true' : 'false',
                    'role' => 'tabpanel',
                    'aria-labelledby' => sprintf('%s-%d', $this->id, $count)
                ], null, true );
                ?>
                <div <?php echo $this->get_render_attribute_string('tab-pane'); ?>>
                    <?php echo $item['content']; ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
