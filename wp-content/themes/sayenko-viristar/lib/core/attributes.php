<?php

/**
 * Add data attributes to an HTML element
 *
 * @param array $attr
 * @return string
 */
function parse_data_attributes($attr = [])
{

	if (empty($attr)) {
		return false;
	}

	$attr = array_map('esc_attr', $attr);

	$html = '';

	foreach ($attr as $name => $value) {
		$html .= " $name=" . '"' . $value . '"';
	}

	return $html;
}


function _parse_data_attribute( $data, $combine = '=', $sep = ' ' ) {
	if( is_array( $data ) ) {
		$t = array();
		foreach( $data as $k => $v ) {
			if( !empty( $v ) ) {
				$t[] = sprintf('"%s"%s"%s"', $k, $combine, $v);
			}
		}
		
		return implode( $sep, $t );
	}
	else {
		return $data;	
	}
}