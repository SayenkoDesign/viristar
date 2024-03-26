<?php
namespace App;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Block Class
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

class ACF_Block {
    
	const BLOCKS_DIR = 'blocks';
    
    /**
	 * Block data.
	 *
	 * Holds all the default block attributes
	 *
	 * @access private
	 *
	 * @var array
	 */
    private $block = [];

	private $content;

	private $is_preview;

	private $post_id;

	private $context;
    
    
    private $html_tag = 'section';  
	
    
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
    
        
    public function __construct( $block = [], $content = '', $is_preview = false, $post_id = false, $context = [] ) {
                        
        if ( ! empty( $block ) ) {
			$this->block = $block;   
		}

		$this->content = $content; 

		$this->is_preview = $is_preview; 

		$this->post_id = $post_id; 

		$this->context = $context; 
                        
	}
    
    /**
	 * Get block name.
	 *
	 * Retrieve the block name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Block name.
	 */
	public function get_name() {
        
        if( empty( $this->block['name'] ) ) {
            return false;
        }
        
        return sanitize_title_with_dashes( str_replace( 'acf/', '', $this->block['name'] ) ); 
	}


	 /**
	 * Get block class.
	 *
	 * Retrieve the block class.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Block class.
	 */
	public function get_class_name() {
        
        if( empty( $this->block['name'] ) ) {
            return false;
        }
        
        return 'wp-block-' . sanitize_title_with_dashes( str_replace( '/', '-', $this->block['name'] ) ); 
	}


	/**
	 * Get blocks URL.
	 *
	 * Retrieve the block URL.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string URL.
	 */
	public function get_url()  {
        
		return trailingslashit( get_stylesheet_directory_uri() . '/' . self::BLOCKS_DIR ) . $this->get_name();
        
	}
    
    
    /**
	 * Get readable block name.
	 *
	 * Retrieve the block name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Block name.
	 */
	public function get_readable_name() {
        return ucwords( str_replace( '-', ' ', $this->get_name() ) );
	}
    
    
    
    
    /**
	 * Get block title.
	 *
	 * Retrieve the block title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Block title.
	 */
	public function get_title() {
        return $this->block['title']; 
	}

	/**
	 * Is Block Preview
	 *
	 * Retrieve the preview status
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean Block Is Preview.
	 */
	public function is_preview() {
        return $this->is_preview; 
	}


	/**
	 * Is Block Empty
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean Block Is Empty.
	 */
	public function is_empty() {
        return empty( $this->content ) ? true : false;  
	}
    
    
    /**
	 * Get block Description.
	 *
	 * Retrieve the block title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Block title.
	 */
	public function get_description() {
        return $this->block['description']; 
	}
    
    
    /**
	 * Get block ID.
	 *
	 * Retrieve the block generic ID.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return string The ID.
	 */
	public function get_id() {
       return !empty($this->block['anchor']) ? $this->block['anchor'] : $this->block['id']; 
	}
    
            
    /**
	 * Before block rendering.
	 *
	 * Used to add stuff before the block block.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function before_render() {  
		
		if(!$this->is_preview()) {
			$this->set_render_attribute('id', $this->get_id() );
		}
		
		$attributes = $this->get_render_attribute('block') ?? [];
                                        
        return sprintf( '<%s %s>', 
                        esc_html( $this->get_html_tag() ), 
                        get_block_wrapper_attributes( $attributes ),
                        );
    }
    

	/**
	 * After block rendering.
	 *
	 * Used to add stuff after the block block.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {
        return sprintf( '</%s>', esc_html( $this->get_html_tag() ) );
	}
    


    

	/**
	 * Set HTML tag.
	 *
	 * Set block HTML tag.
	 *
	 * @since 1.5.3
	 * @access private
	 *
	 * @return string Block HTML tag.
	 */
	public function set_html_tag( $tag = 'section' ) {
	
		if( $tag ) {
            $this->html_tag = $tag;
        }

	}
    
    
    /**
	 * Get HTML tag.
	 *
	 * Retrieve the block HTML tag.
	 *
	 * @since 1.5.3
	 * @access private
	 *
	 * @return string Block HTML tag.
	 */
	public function get_html_tag() {
	
		return $this->html_tag;
	}


	/**
	 * Add render attribute.
	 *
	 * Used to add render attribute to specific HTML elements.
	 *
	 * Example usage:
	 *
	 * `$this->add_render_attribute( 'block', 'class', 'custom-widget-block-class' );`
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
	 */
	private function _add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		if ( is_array( $element ) ) {
			foreach ( $element as $element_key => $attributes ) {
				$this->_add_render_attribute( $element_key, $attributes, null, $overwrite );
			}

			return $this;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $attribute_key => $attributes ) {
				$this->_add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
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
	 * Add render attribute.
	 *
	 * Used to set the value of the HTML element render attribute
	 * an existing render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element The HTML element.
	 * @param array|string $key     Optional. Attribute key. Default is null.
	 * @param array|string $value   Optional. Attribute value. Default is null.
	 *
	 */
	public function add_render_attribute( $key = null, $value = null, $overwrite = false ) {
		return $this->_add_render_attribute( 'block', $key, $value, $overwrite );
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
	 */
	public function set_render_attribute( $key = null, $value = null ) {
		return $this->_add_render_attribute( 'block', $key, $value, true );
	}

	/**
	 * Get render attributes.
	 *
	 * Used to retrieve the value of the render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element The element.
	 *
	 * @return array Render attribute array, or an empty string 
	 * 
	 */
	public function get_render_attribute( $element ) {
		
		if ( empty( $this->_render_attributes[ $element ] ) ) {
			return '';
		}

		$render_attributes = $this->_render_attributes[ $element ];

		$attributes = [];

		foreach ( $render_attributes as $attribute_key => $attribute_values ) {
			$attributes[$attribute_key] = esc_attr( implode( ' ', $attribute_values ) );
		}

		return $attributes;
	}

	public function print_render_attribute_string( $element ) {
		echo $this->get_render_attribute_string( $element ); // XSS ok.
	}

            
}
