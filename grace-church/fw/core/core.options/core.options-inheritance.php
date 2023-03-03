<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'grace_church_add_theme_inheritance' ) ) {
	function grace_church_add_theme_inheritance($options, $append=true) {
		global $GRACE_CHURCH_GLOBALS;
		if (!isset($GRACE_CHURCH_GLOBALS["inheritance"])) $GRACE_CHURCH_GLOBALS["inheritance"] = array();
		$GRACE_CHURCH_GLOBALS['inheritance'] = $append
			? grace_church_array_merge($GRACE_CHURCH_GLOBALS['inheritance'], $options)
			: grace_church_array_merge($options, $GRACE_CHURCH_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'grace_church_get_theme_inheritance' ) ) {
	function grace_church_get_theme_inheritance($key = '') {
		global $GRACE_CHURCH_GLOBALS;
		return $key ? $GRACE_CHURCH_GLOBALS['inheritance'][$key] : $GRACE_CHURCH_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'grace_church_detect_inheritance_key' ) ) {
	function grace_church_detect_inheritance_key() {
		static $inheritance_key = '';
		if (!empty($inheritance_key)) return $inheritance_key;
		$inheritance_key = apply_filters('grace_church_filter_detect_inheritance_key', '');
		return $inheritance_key;
	}
}


// Return key for override parameter
if ( !function_exists( 'grace_church_get_override_key' ) ) {
	function grace_church_get_override_key($value, $by) {
		$key = '';
		$inheritance = grace_church_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'grace_church_get_taxonomy_categories_by_post_type' ) ) {
	function grace_church_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = grace_church_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'grace_church_get_taxonomy_tags_by_post_type' ) ) {
	function grace_church_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = grace_church_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>