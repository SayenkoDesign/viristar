<?php
// Gravity Forms

add_filter('gform_submit_button', 'input_to_button', 10, 2);
function input_to_button($button, $form)
{
    $dom = new DOMDocument();
    $dom->loadHTML($button);
    $input = $dom->getElementsByTagName('input')->item(0);
    $new_button = $dom->createElement('button');
    $new_button->appendChild($dom->createTextNode($input->getAttribute('value')));
    $input->removeAttribute('value');
    foreach ($input->attributes as $attribute) {
        $new_button->setAttribute($attribute->name, $attribute->value);
    }
    $input->parentNode->replaceChild($new_button, $input);

    $new_button_text = sprintf('<span>%s</span>', $form['button']['text']);

    $button = $dom->saveHtml($new_button);
    $button = str_replace($form['button']['text'], $new_button_text, $button);

    // Add a class
    $processor = new WP_HTML_Tag_Processor($button);
    $processor->next_tag('button');
    $processor->add_class('gb-button');

    return $processor->get_updated_html();

}

// Gravity Forms
function _s_get_dropdown_cohorts($course = '')
{
    $today = date("Y-m-d H:i:s");

    // Set up the base query args
    $args = array(
        "post_type" => "product",
        "post_status" => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'start_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => "AND",
            array(
                'key' => 'end_date',
                'value' => $today,
                'compare' => '>=',
                'type' => 'DATETIME',
            ),
        ),
    );

    if (!empty($course)) {
        $args['meta_query'] = array(
            'relation' => 'AND',
            array(
                'key' => 'course',
                'value' => $course,
                'compare' => '=',
            ),
            // The existing 'start_date' condition from the function will be added to this
        );
    }

    // Create the WP_Query with the merged args
    $loop = new \WP_Query($args);

    $rows = [];

    if ($loop->have_posts()):
        while ($loop->have_posts()): $loop->the_post();
            $rows[get_the_title()] = get_the_title();
        endwhile;
    endif;
    wp_reset_postdata();

    return $rows;
}

// Add pre_render filter to handle validation state
add_filter('gform_pre_render_8', 'populate_cohort');
add_filter('gform_pre_validation_8', 'populate_cohort');
add_filter('gform_pre_submission_filter_8', 'populate_cohort');
add_filter('gform_admin_pre_render_8', 'populate_cohort');

function populate_cohort($form)
{
    if (is_admin()) {
        return $form;
    }

    foreach ($form['fields'] as &$field) {
        $field_id = 33;
        if ($field->id != $field_id) {
            continue;
        }

        $rows = _s_get_dropdown_cohorts(601);
        if (empty($rows)) {
            return $form;
        }

        $choices = array();
        foreach ($rows as $id => $title) {
            $choices[] = array('text' => $title, 'value' => $title);
        }

        $field->placeholder = 'Select a Cohort';
        $field->choices = $choices;
    }

    return $form;
}
/**
 * Gravity Wiz // Gravity Forms // Unrequire Required Fields for Testing
 *
 * When bugs pop up on your forms, it can be really annoying to have to fill out all the required fields for every test
 * submission. This snippet saves you that hassle by unrequiring all required fields so you don't have to fill them out.
 *
 * @version 1.0
 * @author David Smith <david@gravitywiz.com>
 * @license GPL-2.0+
 * @link https://gravitywiz.com/speed-up-gravity-forms-testing-unrequire-required-fields/
 * @copyright 2013 Gravity Wiz
 */

class GWUnrequire
{

    public $_args = null;

    public function __construct($args = array())
    {

        $this->_args = wp_parse_args($args, array(
            'admins_only' => true,
            'require_query_param' => true,
        ));

        add_filter('gform_pre_validation', array($this, 'unrequire_fields'));

    }

    public function unrequire_fields($form)
    {

        if ($this->_args['admins_only'] && !current_user_can('activate_plugins')) {
            return $form;
        }

        if ($this->_args['require_query_param'] && !isset($_GET['gwunrequire'])) {
            return $form;
        }

        foreach ($form['fields'] as &$field) {
            $field['isRequired'] = false;
        }

        return $form;
    }

}

# Basic Usage
# requires that the user be logged in as an administrator and that a 'gwunrequire' parameter be added to the query string
# http://youurl.com/your-form-page/?gwunrequire=1

new GWUnrequire();
