<?php
namespace App;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Element Base.
 *
 * An abstract class to register new Elementor elements. It extended the
 * `Controls_Stack` class to inherit its properties.
 *
 * This abstract class must be extended in order to register new elements.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Element_Base  extends Controls_Stack {
    
    /**
	 * Element render attributes.
	 *
	 * Holds all the render attributes of the element. Used to store data like
	 * the HTML class name and the class value, or HTML element ID name and value.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $_render_attributes = [];
    
    /**
	 * Element default data.
	 *
	 * Holds all the default data of the element.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $_default_data = [];

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
	private $_default_args = [];

	
	/**
	 * Get default arguments.
	 *
	 * Retrieve the element default arguments. Used to return all the default
	 * arguments or a specific default argument, if one is set.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $item Optional. Default is null.
	 *
	 * @return array Default argument(s).
	 */
	public function get_default_args( $item = null ) {
		return self::_get_items( $this->_default_args, $item );
	}

	/**
	 * Add render attribute.
	 *
	 * Used to add render attribute to specific HTML elements.
	 *
	 * Example usage:
	 *
	 * `$this->add_render_attribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
	 * `$this->add_render_attribute( 'widget', 'id', 'custom-widget-id' );
	 * `$this->add_render_attribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element   The HTML element.
	 * @param array|string $key       Optional. Attribute key. Default is null.
	 * @param array|string $value     Optional. Attribute value. Default is null.
	 * @param bool         $overwrite Optional. Whether to overwrite existing
	 *                                attribute. Default is false, not to overwrite.
	 *
	 * @return Element_Base Current instance of the element.
	 */
	public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		if ( is_array( $element ) ) {
			foreach ( $element as $element_key => $attributes ) {
				$this->add_render_attribute( $element_key, $attributes, null, $overwrite );
			}

			return $this;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $attribute_key => $attributes ) {
				$this->add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
			}

			return $this;
		}

		if ( empty( $this->_render_attributes[ $element ][ $key ] ) ) {
			$this->_render_attributes[ $element ][ $key ] = [];
		}

		settype( $value, 'array' );

		if ( $overwrite ) {
			$this->_render_attributes[ $element ][ $key ] = $value;
		} else {
			$this->_render_attributes[ $element ][ $key ] = array_merge( $this->_render_attributes[ $element ][ $key ], $value );
		}

		return $this;
	}

	/**
	 * Set render attribute.
	 *
	 * Used to set the value of the HTML element render attribute or to update
	 * an existing render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element The HTML element.
	 * @param array|string $key     Optional. Attribute key. Default is null.
	 * @param array|string $value   Optional. Attribute value. Default is null.
	 *
	 * @return Element_Base Current instance of the element.
	 */
	public function set_render_attribute( $element, $key = null, $value = null ) {
		return $this->add_render_attribute( $element, $key, $value, true );
	}

	/**
	 * Get render attribute string.
	 *
	 * Used to retrieve the value of the render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element The element.
	 *
	 * @return string Render attribute string, or an empty string if the attribute
	 *                is empty or not exist.
	 */
	public function get_render_attribute_string( $element ) {
		if ( empty( $this->_render_attributes[ $element ] ) ) {
			return '';
		}

		$render_attributes = $this->_render_attributes[ $element ];

		$attributes = [];

		foreach ( $render_attributes as $attribute_key => $attribute_values ) {
			$attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( implode( ' ', $attribute_values ) ) );
		}

		return implode( ' ', $attributes );
	}

	public function print_render_attribute_string( $element ) {
		echo $this->get_render_attribute_string( $element ); // XSS ok.
	}
    
    /**
	 * Get the element raw data.
	 *
	 * Retrieve the raw element data, including the id, type, settings, child
	 * elements and whether it is an inner element.
	 *
	 * The data with the HTML used always to display the data, but the Elementor
	 * editor uses the raw data without the HTML in order not to render the data
	 * again.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 *
	 * @return array Element raw data.
	 */
	public function get_raw_data() {
		$data = $this->get_data();

		return [
			'id' => $this->get_id(),
			'settings' => $data['settings'],
            'fields' => $data['fields'],
		];
	}

    
    /**
	 * Clear element
	 *
	 * Used to clear the element.
	 *
	 * @since 1.3.0
	 * @access public
	 */
    public function clear() {
        $this->_data = $this->get_default_data();
    }

	/**
	 * Add render attributes.
	 *
	 * Used to add render attributes to the element.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function _add_render_attributes() {
		$id = $this->get_id();
                
		//$this->add_render_attribute( 'wrapper', 'data-id', $id );	
        
        /*$this->add_render_attribute(
			'wrapper', 'class', [
				$this->get_name() . '-' . $id,
			]
		);	
        */
        
	}

    
	/**
	 * Element base constructor.
	 *
	 * Initializing the element base class using `$data` and `$args`.
	 *
	 * The `$data` parameter is required for a normal instance because of the
	 * way Elementor renders data when initializing elements.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array      $data Optional. Element data. Default is an empty array.
	 * @param array|null $args Optional. Element default arguments. Default is null.
	 **/
	public function __construct( array $data = [], array $args = null ) {
        if ( $args ) {
			$this->_default_args = $args;
		}
        
        parent::__construct( $data );
	}
}
