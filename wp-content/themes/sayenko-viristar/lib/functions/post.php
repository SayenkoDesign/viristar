<?php
// Post

add_action('generate_before_main_content', function () {
	if (!is_singular('post')) {
		return;
	}

	add_action('generate_after_entry_content', function () {

		get_template_part('template-parts/post', 'share');
	});


	add_action('generate_after_main_content', 'sd_related_posts', 12);
	

	function sd_related_posts()
	{
		get_template_part('template-parts/related-posts');
	}
});


add_filter('next_post_link', function ($output, $format, $link, $post) {

	$icon = '<svg fill="none" height="26" viewBox="0 0 14 26" width="14" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m.435189 25.5523c.282596.2931.669681.4477 1.041521.4477.37183 0 .75817-.1546 1.04058-.4477l11.05141-11.4695c.2824-.2931.4313-.679.4313-1.0808 0-.401-.1489-.7869-.4313-1.081l-11.05141-11.469315c-.58025-.602247-1.50185-.602247-2.082101 0-.580252.602245-.580252 1.558775 0 2.161025l10.009811 10.38929-10.009811 10.3893c-.565007.6022-.565007 1.5746 0 2.161z" fill="currentColor" fill-rule="evenodd"/></svg>';

	if (!$post) {
		return '';
	}

	return sprintf(
		'<div class="nav-next"><span class="next"><a href="%1$s" title="%2$s"><span>Next</span> <div class="gp-icon">%3$s</div></a></span></div>',
		get_permalink($post),
		$post->post_title,
		$icon
	);
}, 10, 4);

add_filter('previous_post_link', function ($output, $format, $link, $post) {

	$icon = '<svg fill="none" height="26" viewBox="0 0 14 26" width="14" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m13.5648 25.5523c-.2826.2931-.6697.4477-1.0415.4477s-.7582-.1546-1.0406-.4477l-11.051368-11.4695c-.282411-.2931-.431332-.679-.431332-1.0808 0-.401.148921-.7869.431332-1.081l11.051368-11.469315c.5803-.602247 1.5019-.602247 2.0821 0 .5803.602245.5803 1.558775 0 2.161025l-10.00985 10.38929 10.00985 10.3893c.565.6022.565 1.5746 0 2.161z" fill="currentColor" fill-rule="evenodd"/></svg>';

	if (!$post) {
		return '';
	}

	return sprintf(
		'<div class="nav-previous"><span class="prev"><a href="%1$s" title="%2$s"><div class="gp-icon">%3$s</div> <span>Previous</span></a></span></div>',
		get_permalink($post),
		$post->post_title,
		$icon
	);
}, 10, 4);
