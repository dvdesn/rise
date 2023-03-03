<?php
/**
 * Grace-Church Framework: templates and thumbs management
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('grace_church_templates_theme_setup')) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_templates_theme_setup' );
	function grace_church_templates_theme_setup() {

		// Add custom thumb sizes into media manager
		add_filter( 'image_size_names_choose', 'grace_church_show_thumb_sizes');
	}
}



/* Templates
-------------------------------------------------------------------------------- */

// Add template (layout name)
if (!function_exists('grace_church_add_template')) {
	function grace_church_add_template($tpl) {
		global $GRACE_CHURCH_GLOBALS;
		if (empty($tpl['mode']))						$tpl['mode'] = 'blog';
		if (empty($tpl['template']))					$tpl['template'] = $tpl['layout'];
		if (empty($tpl['need_content']))				$tpl['need_content'] = false;
		if (empty($tpl['need_terms']))					$tpl['need_terms'] = false;
		if (empty($tpl['need_columns']))				$tpl['need_columns'] = false;
		if (empty($tpl['need_isotope']))				$tpl['need_isotope'] = false;
		if (!isset($tpl['h_crop']) && isset($tpl['h']))	$tpl['h_crop'] = $tpl['h'];
		if (!isset($GRACE_CHURCH_GLOBALS['registered_templates'])) $GRACE_CHURCH_GLOBALS['registered_templates'] = array();
		$GRACE_CHURCH_GLOBALS['registered_templates'][$tpl['layout']] = $tpl;
		if (!empty($tpl['thumb_title']))
			grace_church_add_thumb_sizes( $tpl );
		else 
			$tpl['thumb_title'] = '';
	}
}

// Return template file name
if (!function_exists('grace_church_get_template_name')) {
	function grace_church_get_template_name($layout_name) {
		global $GRACE_CHURCH_GLOBALS;
		return $GRACE_CHURCH_GLOBALS['registered_templates'][$layout_name]['template'];
	}
}

// Return true, if template required content
if (!function_exists('grace_church_get_template_property')) {
	function grace_church_get_template_property($layout_name, $what) {
		global $GRACE_CHURCH_GLOBALS;
		return !empty($GRACE_CHURCH_GLOBALS['registered_templates'][$layout_name][$what]) ? $GRACE_CHURCH_GLOBALS['registered_templates'][$layout_name][$what] : '';
	}
}

// Return template output function name
if (!function_exists('grace_church_get_template_function_name')) {
	function grace_church_get_template_function_name($layout_name) {
		global $GRACE_CHURCH_GLOBALS;
		return 'grace_church_template_'.str_replace(array('-', '.'), '_', $GRACE_CHURCH_GLOBALS['registered_templates'][$layout_name]['template']).'_output';
	}
}


/* Thumbs
-------------------------------------------------------------------------------- */

// Add image dimensions with layout name
if (!function_exists('grace_church_add_thumb_sizes')) {
	function grace_church_add_thumb_sizes($sizes) {
		global $GRACE_CHURCH_GLOBALS;
		if (!isset($sizes['h_crop']))		$sizes['h_crop'] =  isset($sizes['h']) ? $sizes['h'] : null;
		if (empty($sizes['thumb_title']))	$sizes['thumb_title'] = grace_church_strtoproper($sizes['layout']);
		$thumb_slug = grace_church_get_slug($sizes['thumb_title']);
		if (empty($GRACE_CHURCH_GLOBALS['thumb_sizes'][$thumb_slug])) {
			if (empty($GRACE_CHURCH_GLOBALS['thumb_sizes'])) $GRACE_CHURCH_GLOBALS['thumb_sizes'] = array();
			$GRACE_CHURCH_GLOBALS['thumb_sizes'][$thumb_slug] = $sizes;
            add_image_size( 'grace_church-'.$thumb_slug, $sizes['w'], $sizes['h'], $sizes['h']!=null );
			if ($sizes['h']!=$sizes['h_crop']) {
                add_image_size( 'grace_church-'.$thumb_slug.'_crop', $sizes['w'], $sizes['h_crop'], true );
			}
		}
	}
}

// Return image dimensions
if (!function_exists('grace_church_get_thumb_sizes')) {
	function grace_church_get_thumb_sizes($opt) {
		$opt = array_merge(array(
			'layout' => 'excerpt'
		), $opt);
		global $GRACE_CHURCH_GLOBALS;
		$thumb_slug = empty($GRACE_CHURCH_GLOBALS['registered_templates'][$opt['layout']]['thumb_title']) ? '' : grace_church_get_slug($GRACE_CHURCH_GLOBALS['registered_templates'][$opt['layout']]['thumb_title']);
		$rez = $thumb_slug ? $GRACE_CHURCH_GLOBALS['thumb_sizes'][$thumb_slug] : array('w'=>null, 'h'=>null, 'h_crop'=>null);
		return $rez;
	}
}

// Show custom thumb sizes into media manager sizes list
if (!function_exists('grace_church_show_thumb_sizes')) {
	function grace_church_show_thumb_sizes( $sizes ) {
		global $GRACE_CHURCH_GLOBALS;
		$thumb_sizes = $GRACE_CHURCH_GLOBALS['thumb_sizes'];
		if (is_array($thumb_sizes) && count($thumb_sizes) > 0) {
			$rez = array();
			foreach ($thumb_sizes as $k=>$v)
				$rez[$k] = !empty($v['thumb_title']) ? $v['thumb_title'] : $k;
			$sizes = array_merge( $sizes, $rez);
		}
		return $sizes;
	}
}

// AJAX callback: Get attachment url
if ( !function_exists( 'grace_church_callback_get_attachment_url' ) ) {
	function grace_church_callback_get_attachment_url() {
        global $_REQUEST, $GRACE_CHURCH_GLOBALS;

        if ( !wp_verify_nonce( $_REQUEST['nonce'], $GRACE_CHURCH_GLOBALS['ajax_url'] ) )
			wp_die();
	
		$response = array('error'=>'');
		
		$id = (int) grace_church_get_value_gp('attachment_id');
		
		$response['data'] = wp_get_attachment_url($id);
		
		echo json_encode($response);
		wp_die();
	}
}
?>