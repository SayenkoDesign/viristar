<?php

namespace App;

/**
 * Class Course
 * 
 * Represents a course associated with a product in a WordPress environment.
 */
class Course {
    /** @var int The ID of the associated product */
    private int $product_id;

    /** @var int|null The ID of the course, null if not set */
    private ?int $course_id;
    
    /**
     * Course constructor.
     * 
     * @param int $product_id The ID of the product associated with this course.
     */
    public function __construct(int $product_id = 0) {
        $this->product_id = $product_id;
        $this->course_id = $this->set_course_id();
    }

    /**
     * Sets the course ID based on the product's 'course' custom field.
     * 
     * @return int|null The course ID if set, null otherwise.
     */
    private function set_course_id(): ?int {
        $course_id = get_field('course', $this->product_id);
        return $course_id ? (int)$course_id : null;
    }

    /**
     * Gets the title of the course.
     * 
     * @return string The course title or a default message if no course is set.
     */
    public function get_title(): string {
        if (!$this->course_id) {
            return false;
        }
        return \get_the_title($this->course_id) ?: false;
    }
	

	public function get_course_id() {	
		return $this->course_id;
	}
    
    /**
     * Gets a specific property of the course's primary category.
     * 
     * @param string $value The property of the term object to return.
     * @return string|false The requested term property, or false if not found.
     */
    public function get_category(string $value = 'name'): string|false {
        if (!$this->course_id) {
            return false;
        }

        $term = $this->get_primary_term('course_category', $this->course_id);
    
        if ($term && isset($term->{$value})) {
            return $term->{$value};
        }

        return false;
    }

    /**
     * Gets the primary term for a given taxonomy and post.
     * 
     * Attempts to get the primary term set by Yoast SEO if available,
     * otherwise returns the first term found.
     * 
     * @param string $taxonomy The taxonomy slug.
     * @param int $post_id The post ID.
     * @return \WP_Term|false The primary term object, or false if not found.
     */
    private function get_primary_term(string $taxonomy = 'category', int $post_id = 0): \WP_Term|false {
        if (!$taxonomy || !$post_id) {
            return false;
        }

        // Check if Yoast SEO is active and has set a primary term
        if (class_exists('WPSEO_Primary_Term')) {
            $wpseo_primary_term = new \WPSEO_Primary_Term($taxonomy, $post_id);
            $primary_term_id = $wpseo_primary_term->get_primary_term();
            
            if ($primary_term_id) {
                return \get_term($primary_term_id);
            }
        }

        // If no primary term is set, get all the terms and return the first one
        $terms = \get_the_terms($post_id, $taxonomy);
        
        if (!$terms || \is_wp_error($terms)) {
            return false;
        }
        
        return $terms[0];
    }
}