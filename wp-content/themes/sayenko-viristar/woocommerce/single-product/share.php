<?php
/**
 * Single Product Share
 *
 * Sharing plugins can hook into here or you can add your own code directly.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/share.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'woocommerce_share', function() {

	global $post;

	$course = get_field('course', $post->ID);
    if(empty($course)) {
        return;
    }
	?>
	<div class="social-share">
	<h4>Share this course </h4>
	<div class="a2a_kit a2a_kit_size_32">
	<a class="a2a_button_facebook"><svg fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg"><path d="m9.23896 16v-7.29782h2.44854l.3674-2.84493h-2.81594v-1.81607c0-.82342.22771-1.38457 1.40984-1.38457l1.5052-.00062v-2.544606c-.2603-.0338277-1.1538-.111384-2.19383-.111384-2.17168 0-3.65845 1.32557-3.65845 3.75942v2.09783h-2.45602v2.84493h2.45602v7.29782z" fill="currentColor"/></svg></a>
		<a class="a2a_button_x"><svg fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg"><path d="m9.38625 6.87226 5.68255-6.605536h-1.3466l-4.93415 5.735496-3.94091-5.735496h-4.545382l5.959442 8.673086-5.959442 6.92689h1.346662l5.21062-6.05685 4.16186 6.05685h4.5454l-6.18039-8.99444zm-1.84445 2.14396-.60381-.86364-4.80434-6.87211h2.06839l3.87716 5.54601.60382.86364 5.03988 7.20898h-2.0684l-4.1127-5.88255z" fill="currentColor"/></svg></a>
		<a class="a2a_button_linkedin"><svg fill="none" height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="currentColor"><path d="m18.4735 18.74v-5.8405c0-2.8704-.6179-5.06307-3.9667-5.06307-1.6146 0-2.691.87706-3.1296 1.71426h-.0398v-1.45513h-3.16943v10.64444h3.30893v-5.2824c0-1.3953.2591-2.7308 1.9734-2.7308 1.6943 0 1.7143 1.5747 1.7143 2.8106v5.1826h3.3089z"/><path d="m2.78516 8.09558h3.30893v10.64442h-3.30893z"/><path d="m4.43997 2.79333c-1.05647 0-1.9136.85714-1.9136 1.91361 0 1.05646.85713 1.93353 1.9136 1.93353 1.05646 0 1.9136-.87707 1.9136-1.93353 0-1.05647-.85714-1.91361-1.9136-1.91361z"/></g></svg></a>
		<a class="a2a_button_email"><svg fill="none" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><g fill="currentColor"><path d="m11.3265 9.29937c-.16 0-.32-.06093-.4419-.18312-.97464-.97469-2.56058-.975-3.53558 0-.24375.24406-.64.24406-.88375 0-.24406-.24406-.24406-.63969 0-.88375 1.46219-1.4625 3.84123-1.46219 5.30313 0 .2441.24406.2441.63969 0 .88375-.1219.12219-.2822.18312-.4419.18312z"/><path d="m10.8844 12.8647c-.9603 0-1.92061-.3656-2.65155-1.0965-.24406-.2441-.24406-.6397 0-.8838.24375-.2441.64-.2441.88375 0 .9747.9747 2.5606.975 3.5356 0 .2438-.2441.64-.2441.8838 0 .244.2441.244.6397 0 .8838-.7313.7309-1.6916 1.0965-2.6516 1.0965z"/><path d="m13.0937 11.9509c-.16 0-.32-.0609-.4419-.1831-.2441-.244-.2441-.6397 0-.8837l3.4912-3.49128c.2438-.24406.64-.24406.8838 0 .2441.24407.2441.63969 0 .88376l-3.4913 3.49122c-.1218.1222-.2818.1831-.4418.1831z"/><path d="m7.83488 17.2097c-.16 0-.32-.0609-.44187-.1831-.24406-.2441-.24406-.6397 0-.8838l1.95156-1.9515c.24375-.2441.64-.2441.88373 0 .2441.244.2441.6397 0 .8837l-1.95154 1.9516c-.12188.1222-.28188.1831-.44188.1831z"/><path d="m3.41594 12.7903c-.16 0-.32-.0609-.44188-.1831-.24406-.2441-.24406-.6397 0-.8838l3.49125-3.49091c.24406-.24407.64-.24407.88375 0 .24406.24406.24406.63968 0 .88375l-3.49125 3.49096c-.12219.1218-.28219.1831-.44187.1831z"/><path d="m10.2138 5.9922c-.16 0-.3197-.06094-.44189-.18281-.24406-.24406-.24406-.63969 0-.88406l1.95159-1.95188c.244-.24406.6397-.24406.884 0 .2441.24406.2441.63969 0 .88406l-1.9515 1.95188c-.1222.12156-.2825.18281-.4422.18281z"/><path d="m5.62519 18.1047c-.96656 0-1.93312-.3594-2.65156-1.0781-1.4375-1.4375-1.4375-3.866 0-5.3035.24375-.244.64-.244.88375 0 .24406.2441.24406.6397 0 .8838-.95812.9581-.95812 2.5775 0 3.5356.95813.9581 2.5775.9581 3.53563 0 .24375-.244.64-.244.88375 0 .24406.2441.24406.6397 0 .8838-.71844.719-1.685 1.0784-2.65157 1.0784z"/><path d="m16.5853 8.4597c-.16 0-.32-.06094-.4419-.18313-.244-.24406-.244-.63968 0-.88375.9582-.95812.9582-2.5775 0-3.53562-.9581-.95813-2.5775-.95813-3.5356 0-.2437.24406-.64.24406-.8837 0-.2441-.24406-.2441-.63969 0-.88375 1.4375-1.4375 3.8659-1.4375 5.3034 0s1.4375 3.86594 0 5.30344c-.1222.12187-.2822.18281-.4422.18281z"/></g></svg></a>
		</div>
    </div>  
    <script async src="https://static.addtoany.com/menu/page.js"></script>
	<?php
});

do_action( 'woocommerce_share' ); // Sharing plugins can hook into here.

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
