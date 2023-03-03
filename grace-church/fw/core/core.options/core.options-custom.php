<?php
/**
 * Grace-Church Framework: Theme options custom fields
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_options_custom_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_options_custom_theme_setup' );
	function grace_church_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'grace_church_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'grace_church_options_custom_load_scripts' ) ) {
	//Handler of add_action("admin_enqueue_scripts", 'grace_church_options_custom_load_scripts');
	function grace_church_options_custom_load_scripts() {
		wp_enqueue_script( 'grace-church-options-custom-script',	grace_church_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
function grace_church_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(grace_church_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'grace-church') : esc_html__( 'Choose Image', 'grace-church')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'grace-church') : esc_html__( 'Choose Image', 'grace-church')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="grace_church_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'grace-church') : esc_html__( 'Choose Image', 'grace-church')) . '</a>';
			break;
	}
	return apply_filters('grace_church_filter_show_custom_field', $output, $id, $field, $value);
}
?>