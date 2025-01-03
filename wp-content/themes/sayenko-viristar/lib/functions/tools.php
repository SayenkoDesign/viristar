<?php

class TestimonialClientManager
{
    private $custom_domain = '';

    /**
     * Set custom domain for URLs
     *
     * @param string $domain Custom domain URL
     * @return self
     */
    public function set_domain(string $domain): self
    {
        $this->custom_domain = $domain ?: home_url();
        return $this;
    }

    /**
     * Get testimonials from a specific category
     *
     * @param string $category Testimonial category slug
     * @return array Testimonials data with logo IDs as keys
     */
    public function get_testimonials(string $category): array
    {
        $testimonials = [];

        $args = [
            'post_type' => 'testimonial',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'logo',
                    'compare' => 'EXISTS',
                ],
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'testimonial_category',
                    'field' => 'slug',
                    'terms' => $category,
                ],
            ],
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $logo_id = get_field('logo', $post_id);

                $testimonials[$logo_id] = [
                    'title' => get_the_title(),
                    'permalink' => str_replace(home_url(), $this->custom_domain, get_permalink()),
                    'category' => $category,
                ];
            }
            wp_reset_postdata();
        }

        return $testimonials;
    }

    /**
     * Find clients matching the testimonial logos
     *
     * @param array $testimonials Testimonials array from get_testimonials()
     * @return array Matched clients data
     */
    public function find_matching_clients(array $testimonials): array
    {
        $logo_ids = array_keys($testimonials);

        if (empty($logo_ids)) {
            return [];
        }

        $args = [
            'post_type' => 'client',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_thumbnail_id',
                    'value' => $logo_ids,
                    'compare' => 'IN',
                ],
            ],
        ];

        $query = new WP_Query($args);
        $matched_clients = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $thumbnail_id = get_post_thumbnail_id($post_id);

                if (isset($testimonials[$thumbnail_id])) {
                    $matched_clients[] = [
                        'post_id' => $post_id,
                        'testimonial' => $testimonials[$thumbnail_id],
                        'client' => [
                            'title' => get_the_title(),
                            'permalink' => str_replace(home_url(), $this->custom_domain, get_permalink()),
                            'current_category' => wp_get_post_terms($post_id, 'client_category', ['fields' => 'names']),
                        ],
                    ];
                }
            }
            wp_reset_postdata();
        }

        return $matched_clients;
    }

    /**
     * Get preview of category changes
     *
     * @param array $matched_clients Results from find_matching_clients()
     * @param string $new_category Category to be set
     * @return array Preview of changes
     */
    public function preview_category_changes(array $matched_clients, string $new_category): array
    {
        return array_map(function ($client) use ($new_category) {
            return [
                'client_title' => $client['client']['title'],
                'current_categories' => $client['client']['current_category'],
                'new_category' => $new_category,
                'post_id' => $client['post_id'],
            ];
        }, $matched_clients);
    }

    /**
     * Update client categories
     *
     * @param array $matched_clients Results from find_matching_clients()
     * @param string $category Category to set
     * @return array Results of the update operation
     */
    public function update_client_categories(array $matched_clients, string $category): array
    {
        if (!term_exists($category, 'client_category')) {
            wp_insert_term($category, 'client_category');
        }

        $term = get_term_by('slug', $category, 'client_category');
        $results = [];

        if ($term) {
            foreach ($matched_clients as $client) {
                $result = wp_set_post_terms($client['post_id'], $term->term_id, 'client_category', false);
                $results[] = [
                    'client_title' => $client['client']['title'],
                    'post_id' => $client['post_id'],
                    'success' => !is_wp_error($result),
                    'new_category' => $category,
                ];
            }
        }

        return $results;
    }

    /**
     * Generate CSV file from matched clients
     *
     * @param array $matched_clients Results from find_matching_clients()
     * @param string $filename Optional custom filename
     * @return string CSV file URL
     */
    public function generate_csv(array $matched_clients, string $filename = 'testimonials_clients.csv'): string
    {
        $file_path = wp_upload_dir()['basedir'] . '/' . $filename;

        $file = fopen($file_path, 'w');
        fputcsv($file, ['Client Title', 'Client Permalink', 'Current Categories', 'Testimonial Title', 'Testimonial Permalink', 'Testimonial Category']);

        foreach ($matched_clients as $data) {
            fputcsv($file, [
                $data['client']['title'],
                $data['client']['permalink'],
                implode(', ', $data['client']['current_category']),
                $data['testimonial']['title'],
                $data['testimonial']['permalink'],
                $data['testimonial']['category'],
            ]);
        }

        fclose($file);

        return wp_upload_dir()['baseurl'] . '/' . $filename;
    }

    /**
     * Generate HTML display of matched clients
     *
     * @param array $matched_clients Results from find_matching_clients()
     * @return string HTML output
     */
    public function generate_html(array $matched_clients): string
    {
        if (empty($matched_clients)) {
            return 'No matching clients found.';
        }

        $output = '<div class="matched-clients">';
        foreach ($matched_clients as $data) {
            $output .= '<div class="client-match">';
            $output .= '<h3>Client: ' . esc_html($data['client']['title']) . '</h3>';
            $output .= '<p>Client URL: <a href="' . esc_url($data['client']['permalink']) . '">' . esc_url($data['client']['permalink']) . '</a></p>';
            $output .= '<p>Current Categories: ' . esc_html(implode(', ', $data['client']['current_category'])) . '</p>';
            $output .= '<p>Related Testimonial: ' . esc_html($data['testimonial']['title']) . '</p>';
            $output .= '<p>Testimonial URL: <a href="' . esc_url($data['testimonial']['permalink']) . '">' . esc_url($data['testimonial']['permalink']) . '</a></p>';
            $output .= '</div>';
        }
        $output .= '</div>';

        return $output;
    }
}

add_action('generate_after_footer', function () {

    return false;

    // Check for tools=run query parameter
    if (!isset($_GET['tools']) || $_GET['tools'] !== 'run') {
        return;
    }

    if (!is_user_logged_in()) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    // Basic usage - find and display matches
    $manager = new TestimonialClientManager();
    //$manager->set_domain('https://viristar.wpenginepowered.com');

    $testimonials = $manager->get_testimonials('rmop-course');

    $matched_clients = $manager->find_matching_clients($testimonials);

    echo $manager->generate_html($matched_clients);

    // Preview changes before updating
    $preview = $manager->preview_category_changes($matched_clients, 'rmop');
    print_r($preview);

    // Update categories
    $manager->update_client_categories($matched_clients, 'rmop');

    // Generate CSV
    //$csv_url = $manager->generate_csv($matched_clients);
}, 0);
