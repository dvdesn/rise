<?php
/* The GDPR Framework support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('grace_church_gdpr_compliance_theme_setup')) {
    add_action( 'grace_church_action_before_init_theme', 'grace_church_gdpr_compliance_theme_setup', 1 );
    function grace_church_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'grace_church_filter_required_plugins', 'grace_church_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'grace_church_exists_gdpr_compliance' ) ) {
    function grace_church_exists_gdpr_compliance() {
        return defined( 'WP_GDPR_C_SLUG' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'grace_church_gdpr_compliance_required_plugins' ) ) {
    //Handler of add_filter('grace_church_filter_required_plugins',    'grace_church_gdpr_compliance_required_plugins');
    function grace_church_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('gdpr-compliance', (array)grace_church_get_global('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('WP GDPR Compliance', 'grace-church'),
                'slug'         => 'wp-gdpr-compliance',
                'required'     => false
            );
        return $list;
    }
}