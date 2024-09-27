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
class Accordion extends Element_Base
{

	/**
	 * Element default arguments.
	 *
	 * Holds all the default arguments of the element. Used to store additional
	 * data. For example WordPress widgets use this to store widget names.
	 *
	 * @access private
	 *
	 * @var array
	 */

	private $_id;

	private $_default_args = [
		'accordion_header_size' => 'h3',
		'open' => false
	];

	private $items = [];


	public function __construct($items = [], array $args = null)
	{
		if(empty($items )) {
			return;
		}

		if ($args) {
			$this->_default_args = wp_parse_args( $args, $this->_default_args );
		}

		$this->items = $items;

		$this->_id = wp_unique_id('accordion-');
	}


	/**
	 * Get section name.
	 *
	 * Retrieve the section name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Section name.
	 */
	public function get_name()
	{
		return 'accordion';
	}


	public function get_id() {
		return $this->_id;
	}


	/**
	 * Add section render attributes.
	 *
	 * Used to add render attributes to the section element.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function _add_render_attributes()
	{

		parent::_add_render_attributes();
	}

	public function render()
	{
		if (empty($this->items)) {
			return;
		}

		$items = $this->items;

		$count = 0;

		$this->add_render_attribute('accordion', [
			'id' => $this->get_id(),
			'class' => ['accordion'],
		], null, true);

?>
		<div <?php $this->print_render_attribute_string('accordion'); ?>>

			<?php

			

			foreach ($items as $item) :

				$count++;

				$accordion_item_classes = ['accordion-item'];

				// For FAQ filters
				if( ! empty( $item['term'] ) ) {
					$accordion_item_classes[] = $item['term'];
				}

				$this->add_render_attribute('accordion-item', [
					'class' => $accordion_item_classes
				], null, true);

				$this->add_render_attribute('accordion-header', [
					'id' => sprintf('%s-heading-%d', $this->get_id(), $count),
					'class' => ['accordion-header']
				], null, true);

				$button = (1 === $count && true == $this->_default_args['open']) ? ['accordion-button'] : ['accordion-button', 'collapsed'];

				$this->add_render_attribute('accordion-button', [
					'class' => $button,
					'type' => 'button',
					'data-bs-toggle' => 'collapse',
					'data-bs-target' => sprintf('#%s-content-%d', $this->get_id(), $count),
					'aria-controls' => sprintf('%s-content-%d', $this->get_id(), $count),
					'aria-expanded' => (1 === $count && true == $this->_default_args['open']) ? 'true' : 'false',
				], null, true);

				$this->add_render_attribute('accordion-collapse', [
					'class' => ['accordion-collapse', 'collapse', (1 === $count && true == $this->_default_args['open']) ? ' show' : ''],
					'id' => sprintf('%s-content-%d', $this->get_id(), $count),
					'data-bs-parent' => sprintf('#%s', $this->get_id()),
					'aria-labelledby' => sprintf('%s-heading-%d', $this->get_id(), $count)
				], null, true);

			?>
				<div <?php $this->print_render_attribute_string('accordion-item'); ?>>

					<<?php echo $this->_default_args['accordion_header_size'];?> <?php $this->print_render_attribute_string('accordion-header'); ?>>
						<button <?php $this->print_render_attribute_string('accordion-button'); ?>>
							<?php echo $item['title']; ?>
						</button>
					</<?php echo $this->_default_args['accordion_header_size'];?>>
					<div <?php $this->print_render_attribute_string('accordion-collapse'); ?>>
						<div class="accordion-body"><?php echo $item['content']; ?></div>
					</div>
				</div>
			<?php


			endforeach;

			?>

		</div>
<?php
	}
}
