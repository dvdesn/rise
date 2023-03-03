<?php
/**
 * Grace-Church Framework
 *
 * @package grace_church
 * @since grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'GRACE_CHURCH_FW_DIR' ) )		define( 'GRACE_CHURCH_FW_DIR', '/fw/' );
if ( ! defined( 'GRACE_CHURCH_THEME_PATH' ) ) define( 'GRACE_CHURCH_THEME_PATH', 	trailingslashit( get_template_directory() ) );

// Theme timing
if ( ! defined( 'GRACE_CHURCH_START_TIME' ) )	define( 'GRACE_CHURCH_START_TIME', microtime());			// Framework start time
if ( ! defined( 'GRACE_CHURCH_START_MEMORY' ) )	define( 'GRACE_CHURCH_START_MEMORY', memory_get_usage());	// Memory usage before core loading

// Global variables storage
global $GRACE_CHURCH_GLOBALS;
$GRACE_CHURCH_GLOBALS = array(
    'page_template'	=> '',
    'allowed_tags'	=> array(		// Allowed tags list (with attributes) in translations
        'b' => array(),
        'strong' => array(),
        'i' => array(),
        'em' => array(),
        'u' => array(),
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'id' => array(),
            'class' => array()
        ),
        'span' => array(
            'id' => array(),
            'class' => array()
        )
    )
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'grace_church_loader_theme_setup' ) ) {
    add_action( 'after_setup_theme', 'grace_church_loader_theme_setup', 20 );
    function grace_church_loader_theme_setup() {

        // Init admin url and nonce
        global $GRACE_CHURCH_GLOBALS;
        $GRACE_CHURCH_GLOBALS['admin_url']	= get_admin_url();
        $GRACE_CHURCH_GLOBALS['admin_nonce']= wp_create_nonce(get_admin_url());
        $GRACE_CHURCH_GLOBALS['ajax_url']	= admin_url('admin-ajax.php');
        $GRACE_CHURCH_GLOBALS['ajax_nonce']	= wp_create_nonce(admin_url('admin-ajax.php'));

        // Before init theme
        do_action('grace_church_action_before_init_theme');

        // Load current values for main theme options
        grace_church_load_main_options();

        // Theme core init - only for admin side. In frontend it called from header.php
        if ( is_admin() ) {
            grace_church_core_init_theme();
        }
    }
}


/* Include core parts
------------------------------------------------------------------------ */

// Manual load important libraries before load all rest files
// core.strings must be first - we use grace_church_str...() in the grace_church_get_file_dir()
require_once (file_exists(get_stylesheet_directory().(GRACE_CHURCH_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(GRACE_CHURCH_FW_DIR).'core/core.strings.php' ;
// core.files must be first - we use grace_church_get_file_dir() to include all rest parts
require_once (file_exists(get_stylesheet_directory().(GRACE_CHURCH_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(GRACE_CHURCH_FW_DIR).'core/core.files.php';

require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.admin.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.arrays.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.date.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.debug.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.globals.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.html.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.http.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.ini.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.less.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.lists.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.media.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.messages.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.templates.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.theme.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.users.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.wp.php');

require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.customizer/core.customizer.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/core.options/core.options.php');

require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/plugin.essgrids.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/plugin.revslider.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/plugin.tribe-events.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/plugin.visual-composer.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/plugin.widgets.php');

require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.attachment.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.clients.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.post.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.post_type.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.services.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.taxonomy.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.team.php');
require_once grace_church_get_file_dir(GRACE_CHURCH_FW_DIR . 'core/type.testimonials.php');

// Include custom theme files
require_once grace_church_get_file_dir('includes/theme.options.php');

// Include theme templates
require_once grace_church_get_file_dir('templates/404.php');
require_once grace_church_get_file_dir('templates/attachment.php');
require_once grace_church_get_file_dir('templates/excerpt.php');
require_once grace_church_get_file_dir('templates/masonry.php');
require_once grace_church_get_file_dir('templates/no-articles.php');
require_once grace_church_get_file_dir('templates/no-search.php');
require_once grace_church_get_file_dir('templates/portfolio.php');
require_once grace_church_get_file_dir('templates/related.php');
require_once grace_church_get_file_dir('templates/single-portfolio.php');
require_once grace_church_get_file_dir('templates/single-standard.php');
require_once grace_church_get_file_dir('templates/single-team.php');

require_once grace_church_get_file_dir('templates/headers/header_1.php');
require_once grace_church_get_file_dir('templates/headers/header_2.php');
require_once grace_church_get_file_dir('templates/headers/header_3.php');
require_once grace_church_get_file_dir('templates/headers/header_4.php');
require_once grace_church_get_file_dir('templates/headers/header_5.php');
require_once grace_church_get_file_dir('templates/headers/header_6.php');
require_once grace_church_get_file_dir('templates/headers/header_7.php');
require_once grace_church_get_file_dir('templates/trx_clients/clients-1.php');
require_once grace_church_get_file_dir('templates/trx_clients/clients-2.php');
require_once grace_church_get_file_dir('templates/trx_blogger/accordion.php');
require_once grace_church_get_file_dir('templates/trx_blogger/date.php');
require_once grace_church_get_file_dir('templates/trx_blogger/list.php');
require_once grace_church_get_file_dir('templates/trx_blogger/plain.php');
require_once grace_church_get_file_dir('templates/trx_services/services-1.php');
require_once grace_church_get_file_dir('templates/trx_services/services-2.php');
require_once grace_church_get_file_dir('templates/trx_services/services-3.php');
require_once grace_church_get_file_dir('templates/trx_services/services-4.php');
require_once grace_church_get_file_dir('templates/trx_team/team-1.php');
require_once grace_church_get_file_dir('templates/trx_team/team-2.php');
require_once grace_church_get_file_dir('templates/trx_team/team-3.php');
require_once grace_church_get_file_dir('templates/trx_team/team-4.php');
require_once grace_church_get_file_dir('templates/trx_testimonials/testimonials-1.php');
require_once grace_church_get_file_dir('templates/trx_testimonials/testimonials-2.php');
require_once grace_church_get_file_dir('templates/trx_testimonials/testimonials-3.php');
require_once grace_church_get_file_dir('templates/trx_testimonials/testimonials-4.php');
?>