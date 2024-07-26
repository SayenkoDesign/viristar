<?php
// Global Colors

add_filter( 'option_generate_settings', function( $settings ) {
    $settings['global_colors'] = [

        [
            'name' => __( 'Base', 'generatepress' ),
            'slug' => 'base',
            'color' => '#0B2543',
        ],
        [
            'name' => __( 'Primary', 'generatepress' ),
            'slug' => 'primary',
            'color' => '#003C71',
        ],

        [
            'name' => __( 'Secondary', 'generatepress' ),
            'slug' => 'secondary',
            'color' => '#87A7CE',
        ],
        [
            'name' => __( 'Secondary Alt', 'generatepress' ),
            'slug' => 'secondary-alt',
            'color' => '#69AFE0',
        ],
        [
            'name' => __( 'Action', 'generatepress' ),
            'slug' => 'action',
            'color' => '#0A8543',
        ],
        [
            'name' => __( 'Accent', 'generatepress' ),
            'slug' => 'accent',
            'color' => '#09763C',
        ],
        [
            'name' => __( 'Accent Alt', 'generatepress' ),
            'slug' => 'accent-alt',
            'color' => '#7A98BC',
        ],
        [
            'name' => __( 'Muted Blue', 'generatepress' ),
            'slug' => 'muted-blue',
            'color' => '#E7EDF3',
        ],
        
        [
            'name' => __( 'Off White', 'generatepress' ),
            'slug' => 'off-white',
            'color' => '#FDFCFA',
        ],
        
        [
            'name' => __( 'White', 'generatepress' ),
            'slug' => 'white',
            'color' => '#ffffff',
        ],

       
    ];

    return $settings;
} );
