<?php
/**
 * Grace-Church Framework: Theme specific actions
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_core_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_core_theme_setup', 11 );
	function grace_church_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');

        // Add user menu
		add_theme_support('nav-menus');

		// Editor custom stylesheet - for user
		add_editor_style(grace_church_get_file_url('css/editor-style.css'));
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'grace-church', grace_church_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */

			// Get theme calendar (instead standard WP calendar) to support Events
			add_filter( 'get_calendar',						'grace_church_get_calendar' );
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'grace_church_wp_title', 10, 2);
			}

			// Add main menu classes
			//Handler of add_filter('wp_nav_menu_objects', 			'grace_church_add_mainmenu_classes', 10, 2);
	
			// Prepare logo text
			add_filter('grace_church_filter_prepare_logo_text',	'grace_church_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'grace_church_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'grace_church_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'grace_church_callback_frontend_editor_delete');
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'grace_church_core_frontend_scripts');
			add_action('wp_footer',		 					'grace_church_core_frontend_scripts_inline', 9);
			add_filter('grace_church_action_add_scripts_inline','grace_church_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('grace_church_action_prepare_globals',	'grace_church_core_prepare_globals');

		}

		// Register theme specific nav menus
		grace_church_register_theme_menus();

		// Register theme specific sidebars
		grace_church_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function grace_church_core_init_theme() {
	global $GRACE_CHURCH_GLOBALS;
	if (!empty($GRACE_CHURCH_GLOBALS['theme_inited'])) return;
	$GRACE_CHURCH_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (grace_church_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	grace_church_load_custom_options();

	// Load skin
	$skin = grace_church_esc(grace_church_get_custom_option('theme_skin'));
	$GRACE_CHURCH_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(grace_church_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once( grace_church_get_file_dir('skins/'.($skin).'/skin.php') );
	}

	// Fire init theme actions (after skin and custom options are loaded)
	do_action('grace_church_action_init_theme');

	// Prepare theme core global variables
	do_action('grace_church_action_prepare_globals');

	// Fire after init theme actions
	do_action('grace_church_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'grace_church_core_prepare_globals' ) ) {
	function grace_church_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $GRACE_CHURCH_GLOBALS;
		
			// Logo text and slogan
			$GRACE_CHURCH_GLOBALS['logo_text'] = apply_filters('grace_church_filter_prepare_logo_text', grace_church_get_custom_option('logo_text'));
			if(!$GRACE_CHURCH_GLOBALS['logo_text'] || $GRACE_CHURCH_GLOBALS['logo_text'] == '') $GRACE_CHURCH_GLOBALS['logo_text'] = get_bloginfo('name');
			$slogan = grace_church_get_custom_option('logo_slogan');
			if (!$slogan || $slogan == '') $slogan = get_bloginfo ( 'description' );
			$GRACE_CHURCH_GLOBALS['logo_slogan'] = $slogan;
			
			// Logo image and icons from skin
			$logo_side   = grace_church_get_logo_icon('logo_side');
			$logo_fixed  = grace_church_get_logo_icon('logo_fixed');
			$logo_footer = grace_church_get_logo_icon('logo_footer');
			$GRACE_CHURCH_GLOBALS['logo']        = grace_church_get_logo_icon('logo');
			$GRACE_CHURCH_GLOBALS['logo_icon']   = grace_church_get_logo_icon('logo_icon');
			$GRACE_CHURCH_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $GRACE_CHURCH_GLOBALS['logo'];
			$GRACE_CHURCH_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $GRACE_CHURCH_GLOBALS['logo'];
			$GRACE_CHURCH_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $GRACE_CHURCH_GLOBALS['logo'];
	
			$shop_mode = '';
			if (grace_church_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = grace_church_get_value_gpc('grace_church_shop_mode');
			if (empty($shop_mode))
				$shop_mode = grace_church_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$GRACE_CHURCH_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'grace_church_get_logo_icon' ) ) {
	function grace_church_get_logo_icon($slug) {
		$logo_icon = grace_church_get_custom_option($slug);
		return $logo_icon;
	}
}


// Add menu locations
if ( !function_exists( 'grace_church_register_theme_menus' ) ) {
	function grace_church_register_theme_menus() {
		register_nav_menus(apply_filters('grace_church_filter_add_theme_menus', array(
			'menu_main'		=> esc_html__('Main Menu', 'grace-church'),
			'menu_user'		=> esc_html__('User Menu', 'grace-church'),
			'menu_footer'	=> esc_html__('Footer Menu', 'grace-church'),
			'menu_side'		=> esc_html__('Side Menu', 'grace-church')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'grace_church_register_theme_sidebars' ) ) {
    add_action('widgets_init', 'grace_church_register_theme_sidebars');
	function grace_church_register_theme_sidebars($sidebars=array()) {
		global $GRACE_CHURCH_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = grace_church_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'grace_church_filter_add_theme_sidebars', $sidebars );
        $registered = grace_church_get_global('registered_sidebars');
        if (!is_array($registered)) $registered = array();
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
                if (isset($registered[$id])) continue;
                $registered[$id] = $name;
				register_sidebar( array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h6 class="widget_title">',
					'after_title'   => '</h6>',
				) );
			}
		}
        grace_church_set_global('registered_sidebars', $registered);
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'grace_church_core_frontend_scripts' ) ) {
	function grace_church_core_frontend_scripts() {
		global $GRACE_CHURCH_GLOBALS;
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = grace_church_get_list_fonts(false);
		$theme_fonts = array();
		$custom_fonts = grace_church_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			foreach ($custom_fonts as $s=>$f) {
				if (!empty($f['font-family']) && !grace_church_is_inherit_option($f['font-family'])) $theme_fonts[$f['font-family']] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('grace_church_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		if (is_array($theme_fonts) && count($theme_fonts) > 0) {
            $google_fonts = '';
            foreach ($theme_fonts as $font => $v) {
                if (isset($fonts[$font])) {
                    $font_name = ($pos = grace_church_strpos($font, ' (')) !== false ? grace_church_substr($font, 0, $pos) : $font;
                    if (!empty($fonts[$font]['css'])) {
                        $css = $fonts[$font]['css'];
                        wp_enqueue_style('grace-church-font-' . str_replace(' ', '-', $font_name) . '-style', $css, array(), null);
                    } else {
                        $google_fonts .= ($google_fonts ? '|' : '') // %7C = |
                            . (!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name) . ':300,300italic,400,400italic,700,700italic');
                    }
                }
            }
            if ($google_fonts) {
                /*
                Translators: If there are characters in your language that are not supported
                by chosen font(s), translate this to 'off'. Do not translate into your own language.
                */
                $google_fonts_enabled = ('off' !== esc_html_x('on', 'Google fonts: on or off', 'grace-church'));
                if ($google_fonts_enabled) {
                    wp_enqueue_style('grace-church-font-google-fonts-style', add_query_arg('family', urlencode($google_fonts . '&subset=latin,latin-ext'), "//fonts.googleapis.com/css"), array(), null);
                }
            }
        }
		
		// Fontello styles must be loaded before main stylesheet
		wp_enqueue_style( 'fontello-style',  grace_church_get_file_url('css/fontello/css/fontello.css'),  array(), null);

		// Main stylesheet
		wp_enqueue_style( 'grac-church-main-style', get_stylesheet_uri(), array(), null );
		
		// Animations
		if (grace_church_get_theme_option('css_animation')=='yes')
			wp_enqueue_style( 'grace-church-animation-style',	grace_church_get_file_url('css/core.animation.css'), array(), null );

		// Theme skin stylesheet
		do_action('grace_church_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		grace_church_enqueue_custom_styles();

		// Responsive
		if (grace_church_get_theme_option('responsive_layouts') == 'yes') {
			$suffix = grace_church_param_is_off(grace_church_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
			wp_enqueue_style( 'grace-church-responsive-style', grace_church_get_file_url('css/responsive'.($suffix).'.css'), array(), null );
			do_action('grace_church_action_add_responsive');
			if (grace_church_get_custom_option('theme_skin')!='') {
				$css = apply_filters('grace_church_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'grace-church-responsive-style', $css );
			}
		}


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		// Load separate theme scripts
		wp_enqueue_script( 'superfish', grace_church_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
		if (grace_church_get_theme_option('menu_slider')=='yes') {
			wp_enqueue_script( 'slidemenu-script', grace_church_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
		}

		wp_enqueue_script( 'grace-church-core-utils-script', grace_church_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		wp_enqueue_script( 'grace-church-core-init-script', grace_church_get_file_url('js/core.init.js'), array('jquery'), null, true );

		// Media elements library	
		if (grace_church_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			global $wp_styles, $wp_scripts;
			$wp_scripts->done[]	= 'mediaelement';
			$wp_scripts->done[]	= 'wp-mediaelement';
			$wp_styles->done[]	= 'mediaelement';
			$wp_styles->done[]	= 'wp-mediaelement';
		}
		
		// Video background
		if (grace_church_get_custom_option('show_video_bg') == 'yes' && grace_church_get_custom_option('video_bg_youtube_code') != '') {
			wp_enqueue_script( 'video-bg-script', grace_church_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

			
		// Social share buttons
		if (is_singular() && !grace_church_get_global('blog_streampage') && grace_church_get_custom_option('show_share')!='hide') {
			wp_enqueue_script( 'social-share-script', grace_church_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !grace_church_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (grace_church_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(grace_church_get_file_dir('core/core.customizer/front.customizer.css')))
				wp_enqueue_style(  'grace-church-customizer-style',  grace_church_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(grace_church_get_file_dir('core/core.customizer/front.customizer.js')))
				wp_enqueue_script( 'grace-church-customizer-script', grace_church_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );
		}
		
		//Debug utils
		if (grace_church_get_theme_option('debug_mode')=='yes') {
			wp_enqueue_script( 'grace-church-core-debug-script', grace_church_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('grace_church_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'grace_church_enqueue_slider' ) ) {
	function grace_church_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			wp_enqueue_style( 'swiperslider-style', 				grace_church_get_file_url('js/swiper/swiper.css'), array(), null );
			wp_enqueue_script( 'swiperslider-script', 			grace_church_get_file_url('js/swiper/swiper.js'), array(), null, true );
		}
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'grace_church_enqueue_messages' ) ) {
	function grace_church_enqueue_messages() {
		wp_enqueue_style( 'grace-church-messages-style',		grace_church_get_file_url('js/core.messages/core.messages.css'), array(), null );
		wp_enqueue_script( 'grace-church-messages-script',	grace_church_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'grace_church_enqueue_portfolio' ) ) {
	function grace_church_enqueue_portfolio($hover='') {
		wp_enqueue_style( 'grace-church-portfolio-style',  grace_church_get_file_url('css/core.portfolio.css'), array(), null );
		if (grace_church_strpos($hover, 'effect_dir')!==false)
			wp_enqueue_script( 'hoverdir', grace_church_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'grace_church_enqueue_diagram' ) ) {
	function grace_church_enqueue_diagram($type='all') {
		if ($type=='all' || $type=='pie') wp_enqueue_script( 'diagram-chart-script',	grace_church_get_file_url('js/diagram/chart.min.js'), array(), null, true );
		if ($type=='all' || $type=='arc') wp_enqueue_script( 'diagram-raphael-script',	grace_church_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'grace_church_enqueue_popup' ) ) {
	function grace_church_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && grace_church_get_theme_option('popup_engine')=='pretty')) {
			wp_enqueue_style(  'prettyphoto-style',	grace_church_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			wp_enqueue_script( 'prettyphoto-script',	grace_church_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && grace_church_get_theme_option('popup_engine')=='magnific')) {
			wp_enqueue_style(  'magnific-style',	grace_church_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			wp_enqueue_script( 'magnific-script',grace_church_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && grace_church_get_theme_option('popup_engine')=='internal')) {
			grace_church_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'grace_church_core_frontend_scripts_inline' ) ) {
    //Handler of add_action('wp_footer', 'grace_church_core_frontend_scripts_inline', 9);
    function grace_church_core_frontend_scripts_inline() {
        $vars = grace_church_get_global('js_vars');
        if (empty($vars) || !is_array($vars)) $vars = array();
        wp_localize_script('grace-church-core-init-script', 'GRACE_CHURCH_GLOBALS', apply_filters('grace_church_action_add_scripts_inline', $vars));
    }
}

//  Add inline scripts in the footer
if (!function_exists('grace_church_core_add_scripts_inline')) {
	function grace_church_core_add_scripts_inline($vars = array()) {
        $msg = grace_church_get_system_message(true);
        if (!empty($msg['message'])) grace_church_enqueue_messages();

        // AJAX parameters
        $vars['ajax_url'] = esc_url(admin_url('admin-ajax.php'));
        $vars['ajax_nonce'] = esc_attr(wp_create_nonce(admin_url('admin-ajax.php')));

        // Site base url
        $vars['site_url'] = esc_url(get_site_url());

        // VC frontend edit mode
        $vars['vc_edit_mode'] = function_exists('grace_church_vc_is_frontend') && grace_church_vc_is_frontend();

        // Theme base font
        $vars['theme_font'] = grace_church_get_custom_font_settings('p', 'font-family');

        // Theme skin
        $vars['theme_skin'] = esc_attr(grace_church_get_custom_option('theme_skin'));
        $vars['theme_skin_color'] = grace_church_get_scheme_color('text_dark');
        $vars['theme_skin_bg_color'] = grace_church_get_scheme_color('bg_color');

        // Slider height
        $vars['slider_height'] = max(100, grace_church_get_custom_option('slider_height'));

        // System message
        $vars['system_message']    = $msg;

        // User logged in
        $vars['user_logged_in']    = is_user_logged_in();

        // Show table of content for the current page
        $vars['toc_menu'] = grace_church_get_custom_option('menu_toc');
        $vars['toc_menu_home'] = grace_church_get_custom_option('menu_toc')!='hide' && grace_church_get_custom_option('menu_toc_home')=='yes';
        $vars['toc_menu_top'] = grace_church_get_custom_option('menu_toc')!='hide' && grace_church_get_custom_option('menu_toc_top')=='yes';

        // Fix main menu
        $vars['menu_fixed'] = grace_church_get_theme_option('menu_attachment')=='fixed';

        // Use responsive version for main menu
        $vars['menu_relayout'] = grace_church_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) grace_church_get_theme_option('menu_relayout')) : 0;
        $vars['menu_responsive'] = grace_church_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) grace_church_get_theme_option('menu_responsive')) : 0;
        $vars['menu_slider'] = grace_church_get_theme_option('menu_slider')=='yes';

        // Menu cache is used
        $vars['menu_cache']    = grace_church_get_theme_option('use_menu_cache')=='yes';

        // Right panel demo timer
        $vars['demo_time'] = grace_church_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) grace_church_get_theme_option('customizer_demo')) : 0;

        // Video and Audio tag wrapper
        $vars['media_elements_enabled'] = grace_church_get_theme_option('use_mediaelement')=='yes';

        // Use AJAX search
        $vars['ajax_search_enabled'] = grace_church_get_theme_option('use_ajax_search')=='yes';
        $vars['ajax_search_min_length']    = min(3, grace_church_get_theme_option('ajax_search_min_length'));
        $vars['ajax_search_delay'] = min(200, max(1000, grace_church_get_theme_option('ajax_search_delay')));

        // AJAX posts counter
        $vars['use_ajax_views_counter'] = is_singular() && grace_church_get_theme_option('use_ajax_views_counter')=='yes';
        if (is_singular() && grace_church_get_theme_option('use_ajax_views_counter')=='yes') {
            $vars['post_id'] = (int) get_the_ID();
            $vars['views'] = (int) apply_filters('trx_utils_filter_get_post_views', 0, get_the_ID()) + 1;
        }

        // Use CSS animation
        $vars['css_animation'] = grace_church_get_theme_option('css_animation')=='yes';
        $vars['menu_animation_in'] = grace_church_get_theme_option('menu_animation_in');
        $vars['menu_animation_out'] = grace_church_get_theme_option('menu_animation_out');

        // Popup windows engine
        $vars['popup_engine'] = grace_church_get_theme_option('popup_engine');

        // E-mail mask
        $vars['email_mask'] = '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';

        // Messages max length
        $vars['contacts_maxlength']    = intval(grace_church_get_theme_option('message_maxlength_contacts'));
        $vars['comments_maxlength']    = intval(grace_church_get_theme_option('message_maxlength_comments'));

        // Remember visitors settings
        $vars['remember_visitors_settings']    = grace_church_get_theme_option('remember_visitors_settings')=='yes';

        // Internal vars - do not change it!
        // Flag for review mechanism
        $vars['admin_mode'] = false;
        // Max scale factor for the portfolio and other isotope elements before relayout
        $vars['isotope_resize_delta'] = 0.3;
        // jQuery object for the message box in the form
        $vars['error_message_box'] = null;
        // Waiting for the viewmore results
        $vars['viewmore_busy'] = false;
        $vars['video_resize_inited'] = false;
        $vars['top_panel_height'] = 0;

        return $vars;
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'grace_church_enqueue_custom_styles' ) ) {
	function grace_church_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';
		wp_enqueue_style( 'grace-church-custom-style', $custom_css ? $custom_css : grace_church_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'grace-church-custom-style', grace_church_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'grace_church_add_widget_number' ) ) {
	//Handler of add_filter('dynamic_sidebar_params', 'grace_church_add_widget_number', 10, 1);
	function grace_church_add_widget_number($prm) {
		global $GRACE_CHURCH_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = !empty($GRACE_CHURCH_GLOBALS['current_sidebar']) ? $GRACE_CHURCH_GLOBALS['current_sidebar'] : 'undefined';
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) grace_church_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'grace_church_wp_title' ) ) {
	// Handler of add_filter( 'wp_title', 'grace_church_wp_title', 10, 2 );
	function grace_church_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = grace_church_get_custom_option('logo_slogan');
			if (empty($site_description)) 
				$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'grace-church' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'grace_church_add_mainmenu_classes' ) ) {
	// Handler of add_filter('wp_nav_menu_objects', 'grace_church_add_mainmenu_classes', 10, 2);
	function grace_church_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && grace_church_get_theme_option('menu_colored')=='yes' && is_array($items) && count($items) > 0) {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = grace_church_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !grace_church_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Show content with the html layout (if not empty)
if ( !function_exists('grace_church_show_layout') ) {
    function grace_church_show_layout($str, $before='', $after='') {
        if ($str != '') {
            printf("%s%s%s", $before, $str, $after);
        }
    }
}


// Save post data from frontend editor
if ( !function_exists( 'grace_church_callback_frontend_editor_save' ) ) {
	function grace_church_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'grace_church_editor_nonce' ) )
			wp_die();

		$response = array('error'=>'');

		parse_str(grace_church_get_value_gp('data'), $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( grace_church_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = esc_html__('Post update error!', 'grace-church');
			} else {
				$response['error'] = esc_html__('Post update error!', 'grace-church');
			}
		} else
			$response['error'] = esc_html__('Post update denied!', 'grace-church');
		
		echo json_encode($response);
		wp_die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'grace_church_callback_frontend_editor_delete' ) ) {
	function grace_church_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'grace_church_editor_nonce' ) )
			wp_die();

		$response = array('error'=>'');
		
		$post_id = grace_church_get_value_gp('post_id');

		if ( grace_church_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = esc_html__('Post delete error!', 'grace-church');
			} else {
				$response['error'] = esc_html__('Post delete error!', 'grace-church');
			}
		} else
			$response['error'] = esc_html__('Post delete denied!', 'grace-church');

		echo json_encode($response);
		wp_die();
	}
}


// Prepare logo text
if ( !function_exists( 'grace_church_prepare_logo_text' ) ) {
	function grace_church_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>