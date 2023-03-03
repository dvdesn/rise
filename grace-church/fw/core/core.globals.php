<?php
/**
 * Grace-Church Framework: global variables storage
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('grace_church_get_global')) {
	function grace_church_get_global($var_name) {
		global $GRACE_CHURCH_GLOBALS;
		return isset($GRACE_CHURCH_GLOBALS[$var_name]) ? $GRACE_CHURCH_GLOBALS[$var_name] : '';
	}
}

// Set global variable
if (!function_exists('grace_church_set_global')) {
	function grace_church_set_global($var_name, $value) {
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('grace_church_inc_global')) {
	function grace_church_inc_global($var_name, $value=1) {
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('grace_church_concat_global')) {
	function grace_church_concat_global($var_name, $value) {
		global $GRACE_CHURCH_GLOBALS;
        if (empty($GRACE_CHURCH_GLOBALS[$var_name])) $GRACE_CHURCH_GLOBALS[$var_name] = '';
		$GRACE_CHURCH_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('grace_church_get_global_array')) {
	function grace_church_get_global_array($var_name, $key) {
		global $GRACE_CHURCH_GLOBALS;
		return isset($GRACE_CHURCH_GLOBALS[$var_name][$key]) ? $GRACE_CHURCH_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('grace_church_set_global_array')) {
	function grace_church_set_global_array($var_name, $key, $value) {
		global $GRACE_CHURCH_GLOBALS;
		if (!isset($GRACE_CHURCH_GLOBALS[$var_name])) $GRACE_CHURCH_GLOBALS[$var_name] = array();
		$GRACE_CHURCH_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('grace_church_inc_global_array')) {
	function grace_church_inc_global_array($var_name, $key, $value=1) {
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('grace_church_concat_global_array')) {
	function grace_church_concat_global_array($var_name, $key, $value) {
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS[$var_name][$key] .= $value;
	}
}

// Check if theme variable is set
if (!function_exists('grace_church_storage_isset')) {
    function grace_church_storage_isset($var_name, $key='', $key2='') {
        global $GRACE_CHURCH_GLOBALS;
        if (!empty($key) && !empty($key2))
            return isset($GRACE_CHURCH_GLOBALS[$var_name][$key][$key2]);
        else if (!empty($key))
            return isset($GRACE_CHURCH_GLOBALS[$var_name][$key]);
        else
            return isset($GRACE_CHURCH_GLOBALS[$var_name]);
    }
}

// Merge two-dim array element
if (!function_exists('grace_church_storage_merge_array')) {
    function grace_church_storage_merge_array($var_name, $key, $arr) {
        global $GRACE_CHURCH_GLOBALS;
        if (!isset($GRACE_CHURCH_GLOBALS[$var_name])) $GRACE_CHURCH_GLOBALS[$var_name] = array();
        if (!isset($GRACE_CHURCH_GLOBALS[$var_name][$key])) $GRACE_CHURCH_GLOBALS[$var_name][$key] = array();
        $GRACE_CHURCH_GLOBALS[$var_name][$key] = array_merge($GRACE_CHURCH_GLOBALS[$var_name][$key], $arr);
    }
}
?>