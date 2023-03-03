<?php
/**
Template Name: Single post
 */
get_header(); 

global $GRACE_CHURCH_GLOBALS;
$single_style = !empty($GRACE_CHURCH_GLOBALS['single_style']) ? $GRACE_CHURCH_GLOBALS['single_style'] : grace_church_get_custom_option('single_style');

while ( have_posts() ) { the_post();

	// Move grace_church_set_post_views to the javascript - counter will work under cache system
	if (grace_church_get_custom_option('use_ajax_views_counter')=='no') {
        do_action('trx_utils_filter_set_post_views', get_the_ID());
	}

	grace_church_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !grace_church_param_is_off(grace_church_get_custom_option('show_sidebar_main')),
			'content' => grace_church_get_template_property($single_style, 'need_content'),
			'terms_list' => grace_church_get_template_property($single_style, 'need_terms')
		)
	);

}

get_footer();
?>