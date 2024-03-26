<?php
// Gravity Forms

add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
function input_to_button( $button, $form ) {
    $dom = new DOMDocument();
    $dom->loadHTML( $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $new_button = $dom->createElement( 'button' );
    $new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
    $input->removeAttribute( 'value' );
    foreach( $input->attributes as $attribute ) {
        $new_button->setAttribute( $attribute->name, $attribute->value );
    }
    $input->parentNode->replaceChild( $new_button, $input );
    
    $new_button_text = sprintf( '<span>%s</span>', $form['button']['text'] );
 
    $button =  $dom->saveHtml( $new_button );
    $button = str_replace( $form['button']['text'], $new_button_text, $button );

    // Add a class
    $processor = new WP_HTML_Tag_Processor( $button );
    $processor->next_tag( 'button' );
    $processor->add_class( 'gb-button' );

    return $processor->get_updated_html();
    
}
