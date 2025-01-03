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
    $current_time = time();

    // Define the base date/stock conditions that we'll reuse
    $stock_and_date_conditions = array(
        'relation' => 'OR',
        array(
            'key' => '_stock_status',
            'value' => 'instock',
            'compare' => '=',
        ),
        array(
            'key' => 'start_date_unix_timestamp',
            'value' => $current_time,
            'compare' => '>=',
            'type' => 'NUMERIC',
        ),
    );

    // Set up the base query args
    $args = array(
        "post_type" => "product",
        "post_status" => 'publish',
        'posts_per_page' => 250,
        'meta_query' => $stock_and_date_conditions,
        'orderby' => 'meta_value_num',
        'meta_key' => 'start_date_unix_timestamp',
        'order' => 'ASC',
    );

    if (!empty($course)) {
        $args['meta_query'] = array(
            'relation' => 'AND',
            array(
                'key' => 'course',
                'value' => $course,
                'compare' => '=',
            ),
            $stock_and_date_conditions,
        );
    }
    // Create the WP_Query with the merged args
    $loop = new \WP_Query($args);

    $rows = [];

    if ($loop->have_posts()):
        while ($loop->have_posts()): $loop->the_post();
            $rows[get_the_ID()] = get_the_title();
        endwhile;
    endif;
    wp_reset_postdata();

    return $rows;
}

// First, modify your populate_cohort function to use post IDs as values
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

        $submitted_value = rgpost('input_' . $field_id);

        $choices = array();
        foreach ($rows as $id => $title) {
            $choices[] = array(
                'text' => $title,
                'value' => $id, // Use post ID as value
                'isSelected' => ($submitted_value == $id),
            );
        }

        $field->placeholder = 'Select a Cohort';
        $field->choices = $choices;
    }

    return $form;
}

// Convert ID to title before saving entry
add_filter('gform_save_field_value_8_33', 'convert_cohort_id_to_title', 10, 4);
function convert_cohort_id_to_title($value, $entry, $field, $form)
{
    if (is_numeric($value)) {
        $post = get_post($value);
        if ($post) {
            return $post->post_title;
        }
    }
    return $value;
}
