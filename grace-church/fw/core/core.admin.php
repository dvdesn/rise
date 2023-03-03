<?php
/**
 * Grace-Church Framework: Admin functions
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Admin actions and filters:
------------------------------------------------------------------------ */

if (is_admin()) {

	/* Theme setup section
	-------------------------------------------------------------------- */
	
	if ( !function_exists( 'grace_church_admin_theme_setup' ) ) {
		add_action( 'grace_church_action_before_init_theme', 'grace_church_admin_theme_setup', 11 );
		function grace_church_admin_theme_setup() {
			if ( is_admin() ) {
				add_action("admin_footer",			'grace_church_admin_prepare_scripts');
				add_action("admin_enqueue_scripts",	'grace_church_admin_load_scripts');
				add_action('tgmpa_register',		'grace_church_admin_register_plugins');

				// AJAX: Get terms for specified post type
				add_action('wp_ajax_grace_church_admin_change_post_type', 		'grace_church_callback_admin_change_post_type');
				add_action('wp_ajax_nopriv_grace_church_admin_change_post_type','grace_church_callback_admin_change_post_type');
			}
		}
	}
	
	// Load required styles and scripts for admin mode
	if ( !function_exists( 'grace_church_admin_load_scripts' ) ) {
		//Handler of add_action("admin_enqueue_scripts", 'grace_church_admin_load_scripts');
		function grace_church_admin_load_scripts() {
            if (grace_church_get_theme_option('debug_mode')=='yes') {
			    wp_enqueue_script( 'grace-church-debug-script', grace_church_get_file_url('js/core.debug.js'), array('jquery'), null, true );
            }
				wp_enqueue_style( 'grace-church-admin-style', grace_church_get_file_url('css/core.admin.css'), array(), null );
				wp_enqueue_script( 'grace-church-admin-script', grace_church_get_file_url('js/core.admin.js'), array('jquery'), null, true );
			if (grace_church_strpos(add_query_arg(array()), 'widgets.php')!==false) {
				wp_enqueue_style( 'fontello-style', grace_church_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null );
				wp_enqueue_style( 'fontello-animations-style', grace_church_get_file_url('css/fontello-admin/css/animation.css'), array(), null );
			}
		}
	}
	
	// Prepare required styles and scripts for admin mode
	if ( !function_exists( 'grace_church_admin_prepare_scripts' ) ) {
		//Handler of add_action("admin_head", 'grace_church_admin_prepare_scripts');
		function grace_church_admin_prepare_scripts() {
            $vars = grace_church_get_global('js_vars');
            if (empty($vars) || !is_array($vars)) $vars = array();
            $vars = array_merge($vars, array(
                'admin_mode' => true,
                'ajax_nonce' => wp_create_nonce(admin_url('admin-ajax.php')),
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_error' => esc_html__('Invalid server answer', 'grace-church'),
                'importer_error_msg' => esc_html__('Errors that occurred during the import process:', 'grace-church'),
                'msg_importer_full_alert' => esc_html__("ATTENTION!\n\nIn this case ALL THE OLD DATA WILL BE ERASED\nand YOU WILL GET A NEW SET OF POSTS, pages and menu items.", 'grace-church')
                    . "\n\n"
                    . esc_html__("It is strongly recommended only for new installations of WordPress\n(without posts, pages and any other data)!", 'grace-church')
                    . "\n\n"
                    . esc_html__("Press OK to continue or Cancel to return to a partial installation", 'grace-church'),
                'user_logged_in' => true
            ));
            wp_localize_script('grace-church-admin-script', 'GRACE_CHURCH_GLOBALS', apply_filters('grace_church_action_add_scripts_inline', $vars));
		}
	}
	
	// AJAX: Get terms for specified post type
	if ( !function_exists( 'grace_church_callback_admin_change_post_type' ) ) {
		//Handler of add_action('wp_ajax_grace_church_admin_change_post_type', 		'grace_church_callback_admin_change_post_type');
		//Handler of add_action('wp_ajax_nopriv_grace_church_admin_change_post_type',	'grace_church_callback_admin_change_post_type');
		function grace_church_callback_admin_change_post_type() {
            global $GRACE_CHURCH_GLOBALS;
                if ( !wp_verify_nonce( $_REQUEST['nonce'], $GRACE_CHURCH_GLOBALS['ajax_url'] ) )
				wp_die();
			$post_type = grace_church_get_value_gp('post_type');
			$terms = grace_church_get_list_terms(false, grace_church_get_taxonomy_categories_by_post_type($post_type));
			$terms = grace_church_array_merge(array(0 => esc_html__('- Select category -', 'grace-church')), $terms);
			$response = array(
				'error' => '',
				'data' => array(
					'ids' => array_keys($terms),
					'titles' => array_values($terms)
				)
			);
			echo json_encode($response);
			wp_die();
		}
	}

	// Return current post type in dashboard
	if ( !function_exists( 'grace_church_admin_get_current_post_type' ) ) {
		function grace_church_admin_get_current_post_type() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type )							//we have a post so we can just get the post type from that
				return $post->post_type;
			else if ( $typenow )										//check the global $typenow — set in admin.php
				return $typenow;
			else if ( $current_screen && $current_screen->post_type )	//check the global $current_screen object — set in sceen.php
				return $current_screen->post_type;
			else if ( isset( $_REQUEST['post_type'] ) )					//check the post_type querystring
				return sanitize_key( $_REQUEST['post_type'] );
			else if ( isset( $_REQUEST['post'] ) ) {					//lastly check the post id querystring
				$post = get_post( sanitize_key( $_REQUEST['post'] ) );
				return !empty($post->post_type) ? $post->post_type : '';
			} else														//we do not know the post type!
				return '';
		}
	}

    // Add admin menu pages
    if ( !function_exists( 'grace_church_admin_add_menu_item' ) ) {
        function grace_church_admin_add_menu_item($mode, $item, $pos='100') {
            static $shift = 0;
            if ($pos=='100') $pos .= '.'.$shift++;
            $fn = join('_', array('add', $mode, 'page'));
            if (empty($item['parent']))
                $fn($item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback']);
            else
                $fn($item['parent'], $item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback']);
        }
    }
	
	// Register optional plugins
	if ( !function_exists( 'grace_church_admin_register_plugins' ) ) {
		function grace_church_admin_register_plugins() {

			$plugins = apply_filters('grace_church_filter_required_plugins', array(
				array(
					'name' 		=> 'WPBakery PageBuilder',
					'slug' 		=> 'js_composer',
                    'version'   => '6.2',
					'source'	=> grace_church_get_file_dir('plugins/js_composer.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Revolution Slider',
					'slug' 		=> 'revslider',
                    'version'   => '6.2.12',
					'source'	=> grace_church_get_file_dir('plugins/revslider.zip'),
					'required' 	=> false
				),
				array(
                    'name' 		=> 'Tribe Events Calendar',
                    'slug' 		=> 'the-events-calendar',
                    'required' 	=> false
                ),
                array(
                    'name' 		=> 'Contact Form 7',
                    'slug' 		=> 'contact-form-7',
                    'required' 	=> false
                ),
                array(
                    'name' 		=> 'Essential Grid',
                    'slug' 		=> 'essential-grid',
                    'version'   => '2.3.6',
                    'source'	=> grace_church_get_file_dir('plugins/essential-grid.zip'),
                    'required' 	=> false
                ),
                array(
                    'name' 		=> 'Themerex Updater',
                    'slug' 		=> 'trx_updater',
                    'version'   => '1.4.1',
                    'source'	=> grace_church_get_file_dir('plugins/trx_updater.zip'),
                    'required' 	=> false
                ),
                array(
                    'name' 		=> 'PayPal Donation',
                    'slug' 		=> 'paypal-donations',
                    'version'   => '1.9.5',
                    'source'	=> grace_church_get_file_dir('plugins/paypal_donations.zip'),
                    'required' 	=> false
                ),
				array(
					'name' 		=> 'Instagram Widget by WPZOOM',
					'slug' 		=> 'instagram-widget-by-wpzoom',
					'required' 	=> false
				),
				array(
					'name' 		=> 'WordPress Social Login',
					'slug' 		=> 'wordpress-social-login',
					'required' 	=> false
				),
                array(
                    'name' 		=> 'WP GDPR Compliance',
                    'slug' 		=> 'wp-gdpr-compliance',
                    'required' 	=> false
                )
			));
            $config = array(
                'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
                'default_path' => '',                      // Default absolute path to bundled plugins.
                'menu'         => 'tgmpa-install-plugins', // Menu slug.
                'parent_slug'  => 'themes.php',            // Parent menu slug.
                'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
                'has_notices'  => true,                    // Show admin notices or not.
                'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
                'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => true,                    // Automatically activate plugins after installation or not.
                'message'      => ''                       // Message to output right before the plugins table.
            );

			tgmpa( $plugins, $config );
		}
	}

	require_once( grace_church_get_file_dir('lib/tgm/class-tgm-plugin-activation.php') );
}

?>