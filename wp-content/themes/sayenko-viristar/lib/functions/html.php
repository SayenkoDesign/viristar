<?php

function safe_email($email = ''  ){
	
	if( !$email ) {
		$email = get_option('admin_email');	
	}
	
	if ( ! is_email( $email ) )
		return;

	return '<a href="mailto:'.antispambot($email).'">'.antispambot($email).'</a>';
}




/**
 * Unordered List
 *
 * Generates an HTML unordered list from an single or multi-dimensional array.
 *
 * @access	public
 * @param	array
 * @param	mixed
 * @return	string
 */
if ( ! function_exists('ul'))
{
	function ul($list, $attributes = '')
	{
		return _list('ul', $list, $attributes);
	}
}


// ------------------------------------------------------------------------

if (!function_exists('_list')) {
    function _list($list, $type = 'ul', $attributes = '', $depth = 0)
    {
        if (!is_array($list)) {
            return $list;
        }

        $indent = str_repeat(" ", $depth);
        $attr_string = '';

        if (is_array($attributes)) {
            $attr_string = ' ' . implode(' ', array_map(
                function ($key, $val) {
                    return $key . '="' . esc_attr($val) . '"';
                },
                array_keys($attributes),
                $attributes
            ));
        } elseif (is_string($attributes) && strlen($attributes) > 0) {
            $attr_string = ' ' . $attributes;
        }

        $out = $indent . "<{$type}{$attr_string}>\n";

        foreach ($list as $key => $val) {
            $out .= $indent . "  <li>";
            if (is_array($val)) {
                $out .= $key . "\n" . _list($val, $type, '', $depth + 4) . $indent . "  ";
            } else {
                $out .= $val;
            }
            $out .= "</li>\n";
        }

        $out .= $indent . "</{$type}>\n";

        return $out;
    }
}

if (!function_exists('ul')) {
    function ul($list, $attributes = '')
    {
        return _list($list, 'ul', $attributes);
    }
}