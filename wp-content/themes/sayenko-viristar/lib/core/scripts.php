<?php
// Scripts

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('generate-child');
    wp_enqueue_style('child-style', THEME_STYLES . '/style.css', []);
}, 5); // priority 5 to load after parent theme and before generate blocks custom styles

add_action('wp_enqueue_scripts', function () {

    wp_register_script(
        'project',
        get_url('scripts/project.js'),
        array(
            'jquery',
            //'vendor',
        ),
        null,
        false
    );

    //wp_add_inline_script( 'project', file_get_contents( get_path( 'scripts/manifest.js' ) ), 'before' );

    wp_enqueue_script('project');

});
