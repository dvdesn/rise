<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Check if RevSlider installed and activated
if ( !function_exists( 'grace_church_exists_revslider' ) ) {
	function grace_church_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}
?>