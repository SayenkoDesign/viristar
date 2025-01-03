<?php

/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

function sd_child_theme_setup()
{

    //* Child theme (do not remove)
    define('CHILD_THEME_VERSION', '1.0');

    define('THEME_DIR', get_stylesheet_directory());
    define('THEME_URL', get_stylesheet_directory_uri());
    define('THEME_DIST', THEME_URL . '/dist');

    define('THEME_LANG', THEME_URL . '/languages');
    define('THEME_LIB', THEME_URL . '/lib');
    define('THEME_STYLES', THEME_DIST . '/styles');
    define('THEME_IMG', THEME_DIST . '/images');
    define('THEME_SCRIPTS', THEME_DIST . '/scripts');
    define('THEME_ICONS', THEME_URL . '/assets/icons');

    // -- Responsive embeds
    add_theme_support('responsive-embeds');

    add_theme_support('custom-spacing');

    add_theme_support('editor-styles');
    add_editor_style('dist/styles/editor-style.css');

    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'sd_child_theme_setup', 15);

/**
 * Explicitly load theme files
 */
array_map(function ($file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', ''), $file), E_USER_ERROR);
    }

    require_once $filepath;
}, [
    // Classes
    'lib/classes/class-controls-stack.php',
    'lib/classes/class-element-base.php',
    'lib/classes/class-tabs.php',
    'lib/classes/class-accordion.php',
    'lib/classes/class-nav-sections.php',
    'lib/classes/class-acf-blocks.php',
    'lib/classes/class-acf-block.php',

    // Core
    'lib/core/admin.php',
    'lib/core/attributes.php',
    'lib/core/markup.php',
    'lib/core/format.php',
    'lib/core/media.php',
    'lib/core/scripts.php',
    'lib/core/assets.php',
    'lib/core/icon.php',
    'lib/core/theme.php',
    'lib/core/menu.php',
    'lib/core/template-tags.php',

    // GeneratePress
    'lib/generatepress/defaults.php',
    'lib/generatepress/colors.php',
    'lib/generatepress/icons.php',
    'lib/generatepress/hooks.php',
    'lib/generatepress/mobile-header.php',

    // Woocommerce
    'lib/woocommerce/hooks.php',
    'lib/woocommerce/disable-add-to-cart.php',

    // Post Types
    'lib/post-types/init.php',

    // ACF + ACF Blocks
    'lib/functions/blog.php',
    'lib/functions/post.php',

    // Theme
    'lib/functions/string.php',
    'lib/functions/html.php',
    'lib/functions/acf.php',
    'lib/functions/gravity-forms.php',
    'lib/functions/redirects.php',
    'lib/functions/blocks.php',
    'lib/functions/menu.php',
    'lib/functions/theme.php',
    'lib/functions/facetwp.php',
    'lib/functions/clients.php',
    'lib/functions/course.php',
    'lib/functions/mega-menu.php',
    'lib/functions/testimonial.php',
    'lib/functions/modal.php',
    'lib/functions/woocommerce.php',
    'lib/functions/woostamper.php',
    'lib/functions/product.php',

    'lib/classes/class-course.php',
    'lib/classes/class-course-grid.php',
    'lib/classes/class-course-finder.php',
    'lib/functions/relevanssi.php',

    // Keep this off unless being used. This allows us to grab some testimonial and client data and process it
    //'lib/functions/tools.php',

]);

/* add_shortcode ('get_user_certificate_moodle', 'get_user_certificate_moodle');
function get_user_certificate_moodle() {
global $current_user;
$ch = curl_init();
$headers = array(
'Accept: application/json',
'Content-Type: application/json',
);
$email = 'stephanie@viristar.com';
$email = urlencode($email);
$moodleURL = get_field('moodle_url', 'option');
$token = get_field('access_token', 'option');
$url =$moodleURL."/webservice/rest/server.php?wstoken=".$token."&wsfunction=mod_customcert2_get_user_course_certificates&email=".$email."&moodlewsrestformat=json";

var_dump($url);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Timeout in seconds
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$data = json_decode($response);

curl_close($ch);

var_dump($data);

} */
