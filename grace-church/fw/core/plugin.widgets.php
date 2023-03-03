<?php
/**
 * Grace-Church Framework: Widgets detection
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Check if specified widget installed and activated
if (!function_exists('grace_church_widget_is_active')) {
	function grace_church_widget_is_active($slug) {
		if (!function_exists('is_plugin_inactive')) { 
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' ); 
		}
		return !is_plugin_inactive("{$slug}.php");
	}
}


// Check if Instagram widget installed and activated
if (!function_exists('grace_church_exists_instagram')) {
	function grace_church_exists_instagram($slug) {
		return grace_church_widget_is_active('wp-instagram-widget/wp-instagram-widget');
	}
}

?>