<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Check if Ess. Grid installed and activated
if ( !function_exists( 'grace_church_exists_essgrids' ) ) {
	function grace_church_exists_essgrids() {
		return defined('EG_PLUGIN_PATH');
	}
}
?>