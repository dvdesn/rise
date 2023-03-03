<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'grace_church_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_theme_setup', 1 );
	function grace_church_theme_setup() {

		// Register theme menus
		add_filter( 'grace_church_filter_add_theme_menus',		'grace_church_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'grace_church_filter_add_theme_sidebars',	'grace_church_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'grace_church_filter_importer_options',		'grace_church_set_importer_options' );

        add_filter( 'grace_church_filter_required_plugins',		'grace_church_add_required_plugins' );

        add_action('wp_head', 'grace_church_head_add_page_meta', 1);

        // Add theme specified classes into the body
        add_filter( 'body_class', 							'grace_church_body_classes' );

        // Gutenberg support
        add_theme_support( 'align-wide' );

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'grace_church_add_theme_menus' ) ) {
	//Handler of add_filter( 'grace_church_filter_add_theme_menus', 'grace_church_add_theme_menus' );
	function grace_church_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'grace_church_add_theme_sidebars' ) ) {
	//Handler of add_filter( 'grace_church_filter_add_theme_sidebars',	'grace_church_add_theme_sidebars' );
	function grace_church_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'grace-church' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'grace-church' )
			);
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'grace_church_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'grace_church_importer_set_options', 9 );
    function grace_church_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( GRACE_CHURCH_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = GRACE_CHURCH_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( grace_church_get_protocol().'://demofiles.ancorathemes.com/grace-church/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'essential-grid',
                'revslider',
                'js_composer',
                'the-events-calendar',
                'content_timeline',
                'contact-form-7',
                'paypal-donations'
            );

            $options['theme_slug'] = 'grace_church';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Grace Church Demo', 'grace-church' );
            $options['files']['default']['domain_dev'] = esc_url(grace_church_get_protocol().'://gracechurch.ancorathemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(grace_church_get_protocol().'://gracechurch.ancorathemes.com'); // Demo-site domain

        }
        return $options;
    }
}

if (!function_exists('grace_church_tribe_events_theme_setup')) {
    add_action( 'grace_church_action_before_init_theme', 'grace_church_tribe_events_theme_setup' );
    function grace_church_tribe_events_theme_setup() {
        if (grace_church_exists_tribe_events()) {

            // Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
            add_filter('grace_church_filter_get_blog_type',				'grace_church_tribe_events_get_blog_type', 9, 2);
            add_filter('grace_church_filter_get_blog_title',			'grace_church_tribe_events_get_blog_title', 9, 2);
            add_filter('grace_church_filter_get_current_taxonomy',		'grace_church_tribe_events_get_current_taxonomy', 9, 2);
            add_filter('grace_church_filter_is_taxonomy',				'grace_church_tribe_events_is_taxonomy', 9, 2);
            add_filter('grace_church_filter_get_stream_page_title',		'grace_church_tribe_events_get_stream_page_title', 9, 2);
            add_filter('grace_church_filter_get_stream_page_link',		'grace_church_tribe_events_get_stream_page_link', 9, 2);
            add_filter('grace_church_filter_get_stream_page_id',		'grace_church_tribe_events_get_stream_page_id', 9, 2);
            add_filter('grace_church_filter_get_period_links',			'grace_church_tribe_events_get_period_links', 9, 3);
            add_filter('grace_church_filter_detect_inheritance_key',	'grace_church_tribe_events_detect_inheritance_key', 9, 1);

            add_action( 'grace_church_action_add_styles',				'grace_church_tribe_events_frontend_scripts' );
            add_action( 'grace_church_action_add_styles',				'grace_church_time_line_frontend_scripts' );


            add_filter('grace_church_filter_list_post_types', 			'grace_church_tribe_events_list_post_types', 10, 1);

            // Advanced Calendar filters
            add_filter('grace_church_filter_calendar_get_month_link',		'grace_church_tribe_events_calendar_get_month_link', 9, 2);
            add_filter('grace_church_filter_calendar_get_prev_month',		'grace_church_tribe_events_calendar_get_prev_month', 9, 2);
            add_filter('grace_church_filter_calendar_get_next_month',		'grace_church_tribe_events_calendar_get_next_month', 9, 2);
            add_filter('grace_church_filter_calendar_get_curr_month_posts',	'grace_church_tribe_events_calendar_get_curr_month_posts', 9, 2);

            // Extra column for events lists
            if (grace_church_get_theme_option('show_overriden_posts')=='yes') {
                add_filter('manage_edit-'.Tribe__Events__Main::POSTTYPE.'_columns',			'grace_church_post_add_options_column', 9);
                add_filter('manage_'.Tribe__Events__Main::POSTTYPE.'_posts_custom_column',	'grace_church_post_fill_options_column', 9, 2);
            }
        }
    }
}

// Add page meta to the head
if (!function_exists('grace_church_head_add_page_meta')) {
    //Handler of add_action('wp_head', 'grace_church_head_add_page_meta', 1);
    function grace_church_head_add_page_meta() { ?>
        <meta charset="<?php bloginfo('charset'); ?>"/>
        <?php
        if (grace_church_get_theme_option('responsive_layouts') == 'yes') {
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?php
        }

        if (floatval(get_bloginfo('version')) < "4.1") {
            ?>
            <title><?php wp_title('|', true, 'right'); ?></title>
            <?php
        }
        ?>
        <link rel="profile" href="//gmpg.org/xfn/11"/>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
        <?php
        $time_line_style = grace_church_get_custom_option('time_line_style');
        if ($time_line_style == 'standard') {
            wp_enqueue_style('grace-church-time-line-standart-style');
        }
    }
}

// Enqueue Tribe Events custom styles
if ( !function_exists( 'grace_church_time_line_frontend_scripts' ) ) {
    function grace_church_time_line_frontend_scripts() {
        wp_register_style( 'grace-church-time-line-standart-style',  grace_church_get_file_url('css/time-line.css'), array(), null );
    }
}


// Add theme required plugins
if ( !function_exists( 'grace_church_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'grace_church_add_trx_utils' );
    function grace_church_add_trx_utils($enable=true) {
        return true;
    }
}

// Add theme required plugins
if ( !function_exists( 'grace_church_add_required_plugins' ) ) {
    //Handler of add_filter( 'grace_church_filter_required_plugins',		'grace_church_add_required_plugins' );
    function grace_church_add_required_plugins($plugins) {
        $plugins[] = array(
            'name' 		=> esc_html__('Grace-church Utilities', 'grace-church'),
            'version'	=> '3.3.1',					// Minimal required version
            'slug' 		=> 'trx_utils',
            'source'	=> grace_church_get_file_dir('plugins/trx_utils.zip'),
            'required' 	=> true
        );
        return $plugins;
    }
}

// Return text for the Privacy Policy checkbox
if ( ! function_exists('grace_church_get_privacy_text' ) ) {
    function grace_church_get_privacy_text() {
        $page = get_option( 'wp_page_for_privacy_policy' );
        $privacy_text = grace_church_get_theme_option( 'privacy_text' );
        return apply_filters( 'grace_church_filter_privacy_text', wp_kses_post(
                $privacy_text
                . ( ! empty( $page ) && ! empty( $privacy_text )
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf( __( 'For further details on handling user data, see our %s', 'grace-church' ),
                        '<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">'
                        . __( 'Privacy Policy', 'grace-church' )
                        . '</a>' )
                    : ''
                )
            )
        );
    }
}

// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'grace_church_trx_utils_privacy_text' ) ) {
    add_filter( 'trx_utils_filter_privacy_text', 'grace_church_trx_utils_privacy_text' );
    function grace_church_trx_utils_privacy_text( $text='' ) {
        return grace_church_get_privacy_text();
    }
}


/**
 * Fire the wp_body_open action.
 *
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action('wp_body_open');
    }
}

// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('grace_church_body_classes') ) {
    function grace_church_body_classes( $classes ) {

        $classes[] = 'grace_church_body';
        $classes[] = 'body_style_' . trim(grace_church_get_custom_option('body_style'));
        $classes[] = 'body_' . (grace_church_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
        $classes[] = 'theme_skin_' . esc_attr(grace_church_get_custom_option('theme_skin'));
        $classes[] = 'article_style_' . trim(grace_church_get_custom_option('article_style'));

        $blog_style = grace_church_get_custom_option(is_singular() && !grace_church_get_global('blog_streampage') ? 'single_style' : 'blog_style');
        $classes[] = 'layout_' . trim($blog_style);
        $classes[] = 'template_' . trim(grace_church_get_template_name($blog_style));

        $body_scheme = grace_church_get_custom_option('body_scheme');
        if (empty($body_scheme)  || grace_church_is_inherit_option($body_scheme)) $body_scheme = 'original';
        $classes[] = 'scheme_' . $body_scheme;

        $top_panel_position = grace_church_get_custom_option('top_panel_position');
        if (!grace_church_param_is_off($top_panel_position)) {
            $classes[] = 'top_panel_show';
            $classes[] = 'top_panel_' . trim($top_panel_position);
        } else
            $classes[] = 'top_panel_hide';
        $classes[] = grace_church_get_sidebar_class();

        if (grace_church_get_custom_option('show_video_bg')=='yes' && (grace_church_get_custom_option('video_bg_youtube_code')!='' || grace_church_get_custom_option('video_bg_url')!=''))
            $classes[] = 'video_bg_show';

        return $classes;
    }
}

/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
if (!isset($_POST['action']) || $_POST['action']!="heartbeat") {
	require_once( get_template_directory().'/fw/loader.php' );
}
?>