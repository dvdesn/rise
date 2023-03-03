<?php
/**
 * Grace-Church Framework: strings manipulations
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'GRACE_CHURCH_MULTIBYTE' ) ) define( 'GRACE_CHURCH_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('grace_church_strlen')) {
	function grace_church_strlen($text) {
		return GRACE_CHURCH_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('grace_church_strpos')) {
	function grace_church_strpos($text, $char, $from=0) {
		return GRACE_CHURCH_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('grace_church_strrpos')) {
	function grace_church_strrpos($text, $char, $from=0) {
		return GRACE_CHURCH_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('grace_church_substr')) {
	function grace_church_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = grace_church_strlen($text)-$from;
		}
		return GRACE_CHURCH_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('grace_church_strtolower')) {
	function grace_church_strtolower($text) {
		return GRACE_CHURCH_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('grace_church_strtoupper')) {
	function grace_church_strtoupper($text) {
		return GRACE_CHURCH_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('grace_church_strtoproper')) {
	function grace_church_strtoproper($text) {
		$rez = ''; $last = ' ';
		for ($i=0; $i<grace_church_strlen($text); $i++) {
			$ch = grace_church_substr($text, $i, 1);
			$rez .= grace_church_strpos(' .,:;?!()[]{}+=', $last)!==false ? grace_church_strtoupper($ch) : grace_church_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('grace_church_strrepeat')) {
	function grace_church_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('grace_church_strshort')) {
	function grace_church_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0)
			return '';
		if ($maxlength < 1 || $maxlength >= grace_church_strlen($str))
			return strip_tags($str);
		$str = grace_church_substr(strip_tags($str), 0, $maxlength - grace_church_strlen($add));
		$ch = grace_church_substr($str, $maxlength - grace_church_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = grace_church_strlen($str) - 1; $i > 0; $i--)
				if (grace_church_substr($str, $i, 1) == ' ') break;
			$str = trim(grace_church_substr($str, 0, $i));
		}
		if (!empty($str) && grace_church_strpos(',.:;-', grace_church_substr($str, -1))!==false) $str = grace_church_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('grace_church_strclear')) {
	function grace_church_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (grace_church_substr($text, 0, grace_church_strlen($open))==$open) {
					$pos = grace_church_strpos($text, '>');
					if ($pos!==false) $text = grace_church_substr($text, $pos+1);
				}
				if (grace_church_substr($text, -grace_church_strlen($close))==$close) $text = grace_church_substr($text, 0, grace_church_strlen($text) - grace_church_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('grace_church_get_slug')) {
	function grace_church_get_slug($title) {
		return grace_church_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('grace_church_strmacros')) {
	function grace_church_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}
?>