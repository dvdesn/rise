<?php

/* Theme setup section
-------------------------------------------------------------------- */


// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings
$GRACE_CHURCH_GLOBALS['settings'] = array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '', //'/*---LESS_SEPARATOR---*/',	// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'no',							    // no|internal|external - Generate map for .less files.
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

    'allow_profiler'    => false,                                // Allow to show theme profiler when 'debug mode' is on

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'images'								// images|bg - Use image as slide's content or as slide's background

);



// Default Theme Options
if ( !function_exists( 'grace_church_options_settings_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_options_settings_theme_setup', 2 );	// Priority 1 for add grace_church_filter handlers
	function grace_church_options_settings_theme_setup() {
		global $GRACE_CHURCH_GLOBALS;
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'grace_church_options_reset');

		// Settings 
		$socials_type = grace_church_get_theme_setting('socials_type');
				
		// Prepare arrays 
		$GRACE_CHURCH_GLOBALS['options_params'] = array(
			'list_fonts'		=> array('$grace_church_get_list_fonts' => ''),
			'list_fonts_styles'	=> array('$grace_church_get_list_fonts_styles' => ''),
			'list_socials' 		=> array('$grace_church_get_list_socials' => ''),
			'list_icons' 		=> array('$grace_church_get_list_icons' => ''),
			'list_posts_types' 	=> array('$grace_church_get_list_posts_types' => ''),
			'list_categories' 	=> array('$grace_church_get_list_categories' => ''),
			'list_menus'		=> array('$grace_church_get_list_menus' => ''),
			'list_sidebars'		=> array('$grace_church_get_list_sidebars' => ''),
			'list_positions' 	=> array('$grace_church_get_list_sidebars_positions' => ''),
			'list_skins'		=> array('$grace_church_get_list_skins' => ''),
			'list_color_schemes'=> array('$grace_church_get_list_color_schemes' => ''),
			'list_bg_tints'		=> array('$grace_church_get_list_bg_tints' => ''),
			'list_body_styles'	=> array('$grace_church_get_list_body_styles' => ''),
			'list_header_styles'=> array('$grace_church_get_list_templates_header' => ''),
			'list_blog_styles'	=> array('$grace_church_get_list_templates_blog' => ''),
			'list_single_styles'=> array('$grace_church_get_list_templates_single' => ''),
			'list_article_styles'=> array('$grace_church_get_list_article_styles' => ''),
			'list_animations_in' => array('$grace_church_get_list_animations_in' => ''),
			'list_animations_out'=> array('$grace_church_get_list_animations_out' => ''),
			'list_filters'		=> array('$grace_church_get_list_portfolio_filters' => ''),
			'list_hovers'		=> array('$grace_church_get_list_hovers' => ''),
			'list_hovers_dir'	=> array('$grace_church_get_list_hovers_directions' => ''),
			'list_sliders' 		=> array('$grace_church_get_list_sliders' => ''),
			'list_revo_sliders'	=> array('$grace_church_get_list_revo_sliders' => ''),
			'list_bg_image_positions' => array('$grace_church_get_list_bg_image_positions' => ''),
			'list_popups' 		=> array('$grace_church_get_list_popup_engines' => ''),
			'list_gmap_styles' 	=> array('$grace_church_get_list_googlemap_styles' => ''),
			'list_yes_no' 		=> array('$grace_church_get_list_yesno' => ''),
			'list_on_off' 		=> array('$grace_church_get_list_onoff' => ''),
			'list_show_hide' 	=> array('$grace_church_get_list_showhide' => ''),
			'list_sorting' 		=> array('$grace_church_get_list_sortings' => ''),
			'list_ordering' 	=> array('$grace_church_get_list_orderings' => ''),
			'list_locations' 	=> array('$grace_church_get_list_dedicated_locations' => '')
			);



		// Theme options array
		$GRACE_CHURCH_GLOBALS['options'] = apply_filters('faith_hope_filter_options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'grace-church'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'grace-church'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'grace-church'),
					"desc" => esc_html__('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'grace-church'),
					"desc" => wp_kses( __('Select body style:', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
								. ' <br>'
                                . wp_kses( __('<b>boxed</b> - if you want use background color and/or image', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
								. ',<br>'
                                .  wp_kses( __('<b>wide</b> - page fill whole window with centered content', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
								. (grace_church_get_theme_setting('allow_fullscreen')
									? ',<br>' . wp_kses( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
									: '')
								. (grace_church_get_theme_setting('allow_fullscreen')
									? ',<br>' . wp_kses( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
									: ''),
					"override" => "category,services_group,post,page",
					"std" => "wide",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'grace-church'),
					"desc" => esc_html__('Add paddings above and below the page content', 'grace-church'),
					"override" => "post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'grace-church'),
					"desc" => esc_html__('Select skin for the theme decoration', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'grace-church'),
					"desc" => esc_html__('Select predefined color scheme for the entire page', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'grace-church'),
					"desc" => esc_html__('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'grace-church'),
					"desc" => esc_html__('Color and image for the site background', 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'grace-church'),
					"desc" => esc_html__("Use custom color and/or image as the site background", 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'grace-church'),
					"desc" => esc_html__('Body background color',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'grace-church'),
					"desc" => esc_html__('Select theme background pattern (first case - without pattern)',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => grace_church_get_file_url('images/spacer.png'),
						1 => grace_church_get_file_url('images/bg/pattern_1.jpg'),
						2 => grace_church_get_file_url('images/bg/pattern_2.jpg'),
						3 => grace_church_get_file_url('images/bg/pattern_3.jpg'),
						4 => grace_church_get_file_url('images/bg/pattern_4.jpg'),
						5 => grace_church_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'grace-church'),
					"desc" => esc_html__('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'grace-church'),
					"desc" => esc_html__('Select theme background image (first case - without image)',  'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => grace_church_get_file_url('images/spacer.png'),
						1 => grace_church_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => grace_church_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => grace_church_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'grace-church'),
					"desc" => esc_html__('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'grace-church'),
					"desc" => esc_html__('Select custom image position',  'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'grace-church'),
					"desc" => esc_html__('Always load background images or only for boxed body style', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'grace-church'),
						'always' => esc_html__('Always', 'grace-church')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'grace-church'),
					"desc" => esc_html__('Parameters of the video, used as site background', 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'grace-church'),
					"desc" => esc_html__("Show video as the site background", 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'grace-church'),
					"desc" => esc_html__("Youtube code of video", 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'grace-church'),
					"desc" => esc_html__("URL to video-file (uploaded on your site)", 'grace-church'),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'grace-church'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'grace-church'),
															'update' => esc_html__( 'Select Video', 'grace-church')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'grace-church'),
					"desc" => esc_html__('Use overlay texture for the video background', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'grace-church'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'grace-church'),
					"desc" => esc_html__('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'grace-church'),
					"desc" => esc_html__('Select desired style of the page header', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "header_3",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_header_styles'],
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'grace-church'),
					"desc" => esc_html__('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'grace-church'),
					"desc" => esc_html__('Select position for the top panel with logo and main menu', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'grace-church'),
						'above' => esc_html__('Above slider', 'grace-church'),
						'below' => esc_html__('Below slider', 'grace-church'),
						'over'  => esc_html__('Over slider', 'grace-church')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'grace-church'),
					"desc" => esc_html__('Select predefined color scheme for the top panel', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'grace-church'),
					"desc" => esc_html__('Show post/page/category title', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'grace-church'),
					"desc" => esc_html__('Show path to current category (post, page)', 'grace-church'),
                    "dependency" => array(
                        'show_page_title' => array('yes')
                    ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'grace-church'),
					"desc" => esc_html__("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'grace-church'),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'grace-church'),
					"desc" => esc_html__('Select the Main menu style and position', 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'grace-church'),
					"desc" => esc_html__('Select main menu for the current page',  'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'grace-church'),
					"desc" => esc_html__('Attach main menu to top of window then page scroll down', 'grace-church'),
					"std" => "fixed",
					"options" => array(
						"fixed"=>__("Fix menu position", 'grace-church'),
						"none"=>__("Don't fix menu position", 'grace-church')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'grace-church'),
					"desc" => esc_html__('Use slider background for main menu items', 'grace-church'),
					"std" => "yes",
					"type" => "switch",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'grace-church'),
					"desc" => esc_html__('Select animation to show submenu ', 'grace-church'),
					"std" => "none",
					"type" => "select",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'grace-church'),
					"desc" => esc_html__('Select animation to hide submenu ', 'grace-church'),
					"std" => "bounceIn",
					"type" => "select",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'grace-church'),
					"desc" => esc_html__('Allow relayout main menu if window width less then this value', 'grace-church'),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'grace-church'),
					"desc" => esc_html__('Allow responsive version for the main menu if window width less then this value', 'grace-church'),
					"std" => 650,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'grace-church'),
					"desc" => esc_html__('Width for dropdown menus in main menu', 'grace-church'),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'grace-church'),
					"desc" => esc_html__("Select parts for the user's menu area", 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'grace-church'),
					"desc" => esc_html__('Show user menu area on top of page', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'grace-church'),
					"desc" => esc_html__('Select user menu for the current page',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'grace-church'),
					"desc" => esc_html__('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'grace-church'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login & Logout buttons', 'grace-church'),
					"desc" => esc_html__('Show Login and Logout buttons in the user menu area', 'grace-church'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_donate_button" => array(
					"title" => esc_html__('Show Donate button', 'grace-church'),
					"desc" => esc_html__('Show Donate button in the user menu area', 'grace-church'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'grace-church'),
					"desc" => esc_html__('Show bookmarks selector in the user menu', 'grace-church'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'grace-church'),
					"desc" => esc_html__('Show Social icons in the user menu area', 'grace-church'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		

		
		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'grace-church'),
					"desc" => esc_html__("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'grace-church'),
					"desc" => esc_html__('Show TOC for the current page', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'grace-church'),
						'fixed' => esc_html__('Fixed', 'grace-church'),
						'float' => esc_html__('Float', 'grace-church')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'grace-church'),
					"desc" => esc_html__('Automatically add "Home" item into table of contents - return to home page of the site', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'grace-church'),
					"desc" => esc_html__('Automatically add "To Top" item into table of contents - scroll to top of the page', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'grace-church'),
					"desc" => esc_html__("Select or upload logos for the site's header and select it position", 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'grace-church'),
					"desc" => esc_html__('Main logo image', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'grace-church'),
					"desc" => esc_html__('Logo image for the header (if menu is fixed after the page is scrolled)', 'grace-church'),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'grace-church'),
					"desc" => esc_html__('Logo text - display it after logo image', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => esc_html__('Logo slogan', 'grace-church'),
					"desc" => esc_html__('Logo slogan - display it under logo image (instead the site tagline)', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'grace-church'),
					"desc" => esc_html__('Height for the logo in the header area', 'grace-church'),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'grace-church'),
					"desc" => esc_html__('Top offset for the logo in the header area', 'grace-church'),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'grace-church'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'grace-church'),
					"desc" => esc_html__('Select parameters for main slider (you can override it in each category and page)', 'grace-church'),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'grace-church'),
					"desc" => esc_html__('Do you want to show slider on each page (post)', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'grace-church'),
					"desc" => esc_html__('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>__("Boxed", 'grace-church'),
						"fullwide"=>__("Fullwide", 'grace-church'),
						"fullscreen"=>__("Fullscreen", 'grace-church')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'grace-church'),
					"desc" => esc_html__("Slider height (in pixels) - only if slider display with fixed height.", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'grace-church'),
					"desc" => esc_html__('What engine use to show slider?', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "revo",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_alias" => array(
					"title" => esc_html__('Revolution Slider: Select slider',  'grace-church'),
					"desc" => esc_html__("Select slider to show (if engine=revo in the field above)", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('revo')
					),
					"std" => "",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_revo_sliders'],
					"type" => "select"),

        "show_additional_area" => array(
                    "title" => esc_html__('Additional area on the main slider', 'grace-church'),
                    "desc" => wp_kses( __('Do you want to show the last 10 Events on slider.', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br>'
                            . wp_kses( __('Only if there are events.', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
                    "override" => "category,services_group,page",
                    "dependency" => array(
                        'show_slider' => array('yes'),
                        'slider_engine' => array('revo')
                    ),
                    "std" => "yes",
                    "options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
                    "type" => "switch"),

		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'grace-church'),
					"desc" => esc_html__('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'grace-church')), $GRACE_CHURCH_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'grace-church'),
					"desc" => esc_html__("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'grace-church'),
					"desc" => esc_html__("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'grace-church'),
					"desc" => esc_html__('Select the desired ordering method for posts', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'grace-church'),
					"desc" => esc_html__("Interval (in ms) for slides change in slider", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'grace-church'),
					"desc" => esc_html__("Choose pagination style for the slider", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'grace-church'),
						'yes'  => esc_html__('Dots', 'grace-church'),
						'over' => esc_html__('Titles', 'grace-church')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'grace-church'),
					"desc" => esc_html__("Do you want to show post's title, reviews rating and description on slides in slider", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'grace-church'),
						'slide' => esc_html__('Slide', 'grace-church'),
						'fixed' => esc_html__('Fixed', 'grace-church')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'grace-church'),
					"desc" => esc_html__("Do you want to show post's category on slides in slider", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'grace-church'),
					"desc" => esc_html__("Do you want to show post's reviews rating on slides in slider", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'grace-church'),
					"desc" => esc_html__("How many characters show in the post's description in slider. 0 - no descriptions", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'grace-church'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'grace-church'),
					"desc" => esc_html__('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'grace-church'),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'grace-church'),
					"desc" => esc_html__('Manage custom sidebars. You can use it with each category (page, post) independently',  'grace-church'),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'grace-church'),
					"desc" => esc_html__('Show / Hide and select main sidebar', 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'grace-church'),
					"desc" => esc_html__('Select position for the main sidebar or hide it',  'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'grace-church'),
					"desc" => esc_html__('Select predefined color scheme for the main sidebar', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'grace-church'),
					"desc" => esc_html__('Select main sidebar content',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),

		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'grace-church'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),

        "info_footer_1_1" => array(
                    "title" => esc_html__("Call to action", 'grace-church'),
                    "desc" => esc_html__("Select call to action in the footer.", 'grace-church'),
                    "override" => "category,services_group,page,post",
                    "type" => "info"),

        "show_call_to_action" => array(
                    "title" => esc_html__('Show Call to action', 'grace-church'),
                    "desc" => esc_html__('Select Call to action for the footer or hide it', 'grace-church'),
                    "override" => "category,services_group,post,page",
                    "std" => "no",
                    "options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
                    "type" => "switch"),

        "call_to_action_scheme" => array(
                "title" => esc_html__("Color scheme", 'grace-church'),
                "desc" => esc_html__('Select predefined color scheme for the footer', 'grace-church'),
                "override" => "category,services_group,post,page",
                "dependency" => array(
                    'show_call_to_action' => array('yes')
                ),
                "std" => "original",
                "dir" => "horizontal",
                "options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
                "type" => "checklist"),

        "call_to_action_title" => array(
                    "title" => esc_html__('Title',  'grace-church'),
                    "desc" => esc_html__("Title for the block", 'grace-church'),
                    "override" => "category,services_group,page,post",
                    "dependency" => array(
                        'show_call_to_action' => array('yes')
                    ),
                    "std" => "",
                    "type" => "text"),

        "call_to_action_description" => array(
                    "title" => esc_html__('Description',  'grace-church'),
                    "desc" => esc_html__("Description for the block", 'grace-church'),
                    "override" => "category,services_group,page,post",
                    "dependency" => array(
                        'show_call_to_action' => array('yes')
                    ),
                    "std" => "",
                    "type" => "text"),

        "call_to_action_link" => array(
                    "title" => esc_html__('Link',  'grace-church'),
                    "desc" => esc_html__("Link URL for the button at the bottom of the block", 'grace-church'),
                    "override" => "category,services_group,page,post",
                    "dependency" => array(
                        'show_call_to_action' => array('yes')
                    ),
                    "std" => "",
                    "type" => "text"),

        "call_to_action_link_caption" => array(
                    "title" => esc_html__('Link caption',  'grace-church'),
                    "desc" => esc_html__("Caption for the button at the bottom of the block", 'grace-church'),
                    "override" => "category,services_group,page,post",
                    "dependency" => array(
                        'show_call_to_action' => array('yes')
                    ),
                    "std" => "",
                    "type" => "text"),

        "call_to_action_picture" => array(
                    "title" => esc_html__('Image before Title',  'grace-church'),
                    "desc" => esc_html__("Select or upload image or write URL from other site", 'grace-church'),
                    "override" => "category,services_group,page,post",
                    "dependency" => array(
                        'show_call_to_action' => array('yes')
                    ),
                    "std" => "",
                    "type" => "media"),


		"info_footer_1" => array(
					"title" => esc_html__("Footer sidebar", 'grace-church'),
					"desc" => esc_html__("Select components of the footer sidebar, set style and number columns", 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'grace-church'),
					"desc" => esc_html__('Select style for the footer sidebar or hide it', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'grace-church'),
					"desc" => esc_html__('Select predefined color scheme for the footer', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'grace-church'),
					"desc" => esc_html__('Select footer sidebar for the blog page',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'grace-church'),
					"desc" => esc_html__('Select columns number for the footer sidebar',  'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 4,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'grace-church'),
					"desc" => esc_html__('Select parameters for Testimonials in the Footer', 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'grace-church'),
					"desc" => esc_html__('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'grace-church'),
					"desc" => esc_html__('Select predefined color scheme for the testimonials area', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'grace-church'),
					"desc" => esc_html__('Number testimonials to show', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'grace-church'),
					"desc" => esc_html__('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'grace-church'),
					"desc" => esc_html__('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'grace-church'),
					"desc" => esc_html__('Select predefined color scheme for the twitter area', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'grace-church'),
					"desc" => esc_html__('Number twitter to show', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'grace-church'),
					"desc" => esc_html__('Select parameters for Google map (you can override it in each category and page)', 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'grace-church'),
					"desc" => esc_html__('Do you want to show Google map on each page (post)', 'grace-church'),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'grace-church'),
					"desc" => esc_html__("Map height (default - in pixels, allows any CSS units of measure)", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'grace-church'),
					"desc" => esc_html__("Enter address to show on map center", 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'grace-church'),
					"desc" => esc_html__("Enter coordinates (separated by comma) to show on map center (instead of address)", 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'grace-church'),
					"desc" => esc_html__("Enter title to show on map center", 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'grace-church'),
					"desc" => esc_html__("Enter description to show on map center", 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'grace-church'),
					"desc" => esc_html__("Enter desired initial zoom for Google map", 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'grace-church'),
					"desc" => esc_html__("Select style to show Google map", 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'greyscale',
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'grace-church'),
					"desc" => esc_html__("Select or upload png-image with Google map marker", 'grace-church'),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'grace-church'),
					"desc" => esc_html__("Show/Hide contacts area in the footer", 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'grace-church'),
					"desc" => esc_html__('Show contact information area in footer: site logo, contact info and contact form', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

        "contacts_scheme" => array(
                "title" => esc_html__("Color scheme", 'grace-church'),
                "desc" => esc_html__('Select predefined color scheme for the contacts area', 'grace-church'),
                "override" => "category,services_group,post,page",
                "dependency" => array(
                    'show_contacts_in_footer' => array('yes')
                ),
                "std" => "original",
                "dir" => "horizontal",
                "options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
                "type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'grace-church'),
					"desc" => esc_html__('Logo image in the footer (in the contacts area)', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),

        "show_footer_contacts_form" => array(
                "title" => esc_html__('Show Contacts Form below the area', 'grace-church'),
                "desc" => esc_html__('Show or hide the contact form under the contact information', 'grace-church'),
                "override" => "category,services_group,post,page",
                "dependency" => array(
                    'show_contacts_in_footer' => array('yes')
                ),
                "std" => "no",
                "options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
                "type" => "switch"),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'grace-church'),
					"desc" => esc_html__("Show/Hide copyright area in the footer", 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'grace-church'),
					"desc" => esc_html__('Show area with copyright information, footer menu and small social icons in footer', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "socials",
					"options" => array(
						'none' => esc_html__('Hide', 'grace-church'),
						'text' => esc_html__('Text', 'grace-church'),
						'menu' => esc_html__('Text and menu', 'grace-church'),
						'socials' => esc_html__('Text and Social icons', 'grace-church')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'grace-church'),
					"desc" => esc_html__('Select predefined color scheme for the copyright area', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'grace-church'),
					"desc" => esc_html__('Select footer menu for the current page',  'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'grace-church'),
					"desc" => esc_html__("Copyright text to show in footer area (bottom of site)", 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "Grace-Church &copy; {Y} All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'grace-church'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'grace-church'),
					"desc" => esc_html__('Animation parameters and responsive layouts for the small screens', 'grace-church'),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'grace-church'),
					"desc" => esc_html__('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'grace-church'),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'grace-church'),
					"desc" => esc_html__('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'grace-church'),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'grace-church'),
					"desc" => esc_html__('Do you want use extended animations effects on your site?', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'grace-church'),
					"desc" => esc_html__('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'grace-church'),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'grace-church'),
					"desc" => esc_html__('Do you want use responsive layouts on small screen or still use main layout?', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'grace-church'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'grace-church') ),
                "std"   => wp_kses_post( __( 'I agree that my submitted data is being collected and stored.', 'grace-church') ),
                "type"  => "text"
            ),


		'info_other_2_2' => array(
					"title" => esc_html__('Parameter for shortcode Time Line', 'grace-church'),
					"desc" => esc_html__('You can choose the behavior shortcode', 'grace-church'),
					"override" => "page",
					"type" => "info"
					),
        'time_line_style' => array(
                    "title" => wp_kses( __('Style shortcode',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                             . '<br />'
                             . wp_kses( __('Time Line',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
                    "desc" => wp_kses( __('Choose a "Standard" for displaying default.',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                              . '<br />'
                              . wp_kses( __('Or a "Customizable" style for shortcode to control through its configuration.',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
                    "override" => "page",
                    "std" => "standard",
                    "options" => array(
                        'standard'    => esc_html__('Standard', 'grace-church'),
                        'customizable' => esc_html__('Customizable', 'grace-church')
                    ),
                    "dir" => "horizontal",
                    "type" => "checklist"),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'grace-church'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'grace-church'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'grace-church'),
					"desc" => esc_html__('Select desired blog streampage parameters (you can override it in each category)', 'grace-church'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'grace-church'),
					"desc" => esc_html__('Select desired blog style', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'grace-church'),
					"desc" => esc_html__('Select desired hover style (only for Blog style = Portfolio)', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'grace-church'),
					"desc" => esc_html__('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'grace-church'),
					"desc" => esc_html__('Select article display method: boxed or stretch', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),

		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'grace-church'),
					"desc" => esc_html__('Select location for the dedicated content or featured image in the "excerpt" blog style', 'grace-church'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "center",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'grace-church'),
					"desc" => esc_html__('What taxonomy use for filter buttons', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'grace-church'),
					"desc" => esc_html__('Select the desired sorting method for posts', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'grace-church'),
					"desc" => esc_html__('Select the desired ordering method for posts', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'grace-church'),
					"desc" => esc_html__('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'grace-church'),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'grace-church'),
					"desc" => esc_html__('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'grace-church'),
					"desc" => esc_html__('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'grace-church'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'grace-church'),
					"desc" => esc_html__('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'grace-church'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'grace-church'),
					"desc" => esc_html__('Select desired style for single page', 'grace-church'),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),

        "icon" => array(
                "title" => esc_html__('Select post icon', 'grace-church'),
                "desc" => esc_html__('Select icon for output before post/category name in some layouts', 'grace-church'),
                "override" => "services_group,page,post",
                "std" => "",
                "options" => $GRACE_CHURCH_GLOBALS['options_params']['list_icons'],
                "style" => "select",
                "type" => "icons"
                ),

		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'grace-church'),
					"desc" => esc_html__("Show featured image (if selected) before post content on single pages", 'grace-church'),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'grace-church'),
					"desc" => esc_html__('Show area with post title on single pages', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'grace-church'),
					"desc" => esc_html__('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'grace-church'),
					"desc" => esc_html__('Show area with post info on single pages', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'grace-church'),
					"desc" => esc_html__('Show text before "Read more" tag on single pages', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'grace-church'),
					"desc" => esc_html__("Show post author information block on single post page", 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'grace-church'),
					"desc" => esc_html__("Show tags block on single post page", 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'grace-church'),
					"desc" => esc_html__("Show related posts block on single post page", 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'grace-church'),
					"desc" => esc_html__("How many related posts showed on single post page", 'grace-church'),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'grace-church'),
					"desc" => esc_html__("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'grace-church'),
					"desc" => esc_html__('Select the desired sorting method for related posts', 'grace-church'),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'grace-church'),
					"desc" => esc_html__('Select the desired ordering method for related posts', 'grace-church'),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'grace-church'),
					"desc" => esc_html__("Show comments block on single post page", 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'grace-church'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'grace-church'),
					"desc" => esc_html__('Select excluded categories, substitute parameters, etc.', 'grace-church'),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'grace-church'),
					"desc" => esc_html__('Select categories, which posts are exclude from blog page', 'grace-church'),
					"std" => "",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'grace-church'),
					"desc" => esc_html__('Select type of the pagination on blog streampages', 'grace-church'),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'grace-church'),
						'viewmore' => esc_html__('"View more" button', 'grace-church'),
						'infinite' => esc_html__('Infinite scroll', 'grace-church')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_pagination_style" => array(
					"title" => esc_html__('Blog pagination style', 'grace-church'),
					"desc" => esc_html__('Select pagination style for standard page numbers', 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_pagination' => array('pages')
					),
					"std" => "pages",
					"options" => array(
						'pages'  => esc_html__('Page numbers list', 'grace-church'),
						'slider' => esc_html__('Slider with page numbers', 'grace-church')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'grace-church'),
					"desc" => esc_html__('Select counters, displayed near the post title', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "comments",
					"options" => array(
						'views' => esc_html__('Views', 'grace-church'),
						'likes' => esc_html__('Likes', 'grace-church'),
						'rating' => esc_html__('Rating', 'grace-church'),
						'comments' => esc_html__('Comments', 'grace-church')
					),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'grace-church'),
					"desc" => esc_html__('What category display in announce block (over posts thumb) - original or nearest parental', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'grace-church'),
						'original' => esc_html__("Original post's category", 'grace-church')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'grace-church'),
					"desc" => esc_html__('Show post date after N days (before - show post age)', 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'grace-church'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'grace-church'),
					"desc" => esc_html__('Set up list of reviews criterias. You can override it in any category.', 'grace-church'),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'grace-church'),
					"desc" => esc_html__("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'grace-church'),
					"override" => "category,services_group,services_group",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'grace-church'),
					"desc" => esc_html__("Maximum level for reviews marks", 'grace-church'),
					"std" => "5",
					"options" => array(
						'5'=>__('5 stars', 'grace-church'),
						'10'=>__('10 stars', 'grace-church'),
						'100'=>__('100%', 'grace-church')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'grace-church'),
					"desc" => esc_html__("Show rating marks as text or as stars/progress bars.", 'grace-church'),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'grace-church'),
						'stars' => esc_html__('As stars or bars', 'grace-church')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'grace-church'),
					"desc" => esc_html__('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'grace-church'),
					"std" => esc_html__("bad,poor,normal,good,great", 'grace-church'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'grace-church'),
					"desc" => esc_html__("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'grace-church'),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'grace-church'),
						'users' => esc_html__('By visitors', 'grace-church')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'grace-church'),
					"desc" => esc_html__("Do you want hide second reviews tab in widgets and single posts?", 'grace-church'),
					"std" => "show",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'grace-church'),
					"desc" => esc_html__("What visitors can vote: all or only registered", 'grace-church'),
					"std" => "all",
					"options" => array(
						'all'=>__('All visitors', 'grace-church'),
						'registered'=>__('Only registered', 'grace-church')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'grace-church'),
					"desc" => esc_html__('Add default reviews criterias.',  'grace-church'),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'grace-church'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'grace-church'),
					"desc" => esc_html__('Set up parameters to show images, galleries, audio and video posts', 'grace-church'),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'grace-church'),
					"desc" => esc_html__('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'grace-church'),
					"std" => "2",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'grace-church'),
						"2" => esc_html__("Retina", 'grace-church')
					),
					"type" => "switch"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard WordPress gallery', 'grace-church'),
					"desc" => esc_html__('Substitute standard WordPress gallery with our slider on the single pages', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'grace-church'),
					"desc" => esc_html__('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'grace-church'),
					"desc" => esc_html__('Maximum images number from gallery into slider', 'grace-church'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'grace-church'),
					"desc" => esc_html__('Select engine to show popup windows with images and galleries', 'grace-church'),
					"std" => "pretty",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'grace-church'),
					"desc" => esc_html__('Substitute audio tag with source from soundcloud to embed player', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'grace-church'),
					"desc" => esc_html__('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'grace-church'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'grace-church'),
					"desc" => esc_html__('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'grace-church'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'grace-church'),
					"desc" => esc_html__("Social networks list for site footer and Social widget", 'grace-church'),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'grace-church'),
					"desc" => esc_html__('Select icon and write URL to your profile in desired social networks.',  'grace-church'),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $GRACE_CHURCH_GLOBALS['options_params']['list_socials'] : $GRACE_CHURCH_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'grace-church'),
					"desc" => wp_kses( __('Add button\'s code for each social share network.',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('In share url you can use next macro:',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                                        . '<br />'
                            . wp_kses( __('<b>{url}</b> - share post (page) URL,',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('<b>{title}</b> - post title,',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('<b>{image}</b> - post image,',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('<b>{descr}</b> - post description (if supported)',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('For example:',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em>',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
                    "override" => "category,services_group,page",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'grace-church'),
					"desc" => esc_html__("Show social share buttons block", 'grace-church'),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'grace-church'),
						'vertical'	=> esc_html__('Vertical', 'grace-church'),
						'horizontal'=> esc_html__('Horizontal', 'grace-church')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'grace-church'),
					"desc" => esc_html__("Show share counters after social buttons", 'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'grace-church'),
					"desc" => esc_html__('Caption for the block with social share buttons',  'grace-church'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'grace-church'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'grace-church'),
					"desc" => wp_kses( __('Select icon and write share URL for desired social networks.',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                              . '<br />'
                              . wp_kses( __('<b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $GRACE_CHURCH_GLOBALS['options_params']['list_socials'] : $GRACE_CHURCH_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'grace-church'),
					"desc" => wp_kses( __('Put to this section Twitter API 1.1 keys.',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                            . '<br />'
                            . wp_kses( __('You can take them after registration your application in <strong>https://apps.twitter.com/</strong>',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'grace-church'),
					"desc" => esc_html__('Your login (username) in Twitter',  'grace-church'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'grace-church'),
					"desc" => esc_html__('Twitter API Consumer key',  'grace-church'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'grace-church'),
					"desc" => esc_html__('Twitter API Consumer secret',  'grace-church'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'grace-church'),
					"desc" => esc_html__('Twitter API Token key',  'grace-church'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'grace-church'),
					"desc" => esc_html__('Twitter API Token secret',  'grace-church'),
					"divider" => false,
					"std" => "",
					"type" => "text"),



		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'grace-church'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'grace-church'),
					"desc" => esc_html__('Company address, phones and e-mail', 'grace-church'),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'grace-church'),
					"desc" => esc_html__('String with contact info in the left side of the site header', 'grace-church'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_open_hours" => array(
                    "title" => esc_html__('Open hours (part 1)', 'grace-church'),
					"desc" => esc_html__('String with open hours in the site header and footer contact', 'grace-church'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),

		"contact_open_hours_2" => array(
					"title" => esc_html__('Open hours (part2)', 'grace-church'),
					"desc" => esc_html__('String with open hours in the site header and footer contact', 'grace-church'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'grace-church'),
					"desc" => esc_html__('E-mail for send contact form and user registration data', 'grace-church'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address', 'grace-church'),
					"desc" => esc_html__('Company country, post code and city', 'grace-church'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'grace-church'),
					"desc" => esc_html__('Phone number', 'grace-church'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'grace-church'),
					"desc" => esc_html__('Fax number', 'grace-church'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'grace-church'),
					"desc" => esc_html__('Maximum length of the messages in the contact form shortcode and in the comments form', 'grace-church'),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'grace-church'),
					"desc" => esc_html__("Message's maxlength in the contact form shortcode", 'grace-church'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'grace-church'),
					"desc" => esc_html__("Message's maxlength in the comments form", 'grace-church'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'grace-church'),
					"desc" => esc_html__('What function you want to use for sending mail: the built-in WordPress or standard PHP function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'grace-church'),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'grace-church'),
					"desc" => esc_html__("What function you want to use for sending mail?", 'grace-church'),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'grace-church'),
						'mail' => esc_html__('PHP mail', 'grace-church')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'grace-church'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'grace-church'),
					"desc" => esc_html__('Enable/disable AJAX search and output settings for it', 'grace-church'),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'grace-church'),
					"desc" => esc_html__('Show search field in the top area and side menus', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'grace-church'),
					"desc" => esc_html__('Use incremental AJAX search for the search field in top of page', 'grace-church'),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'grace-church'),
					"desc" => esc_html__('The minimum length of the search string',  'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "hidden"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'grace-church'),
					"desc" => esc_html__('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "hidden"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'grace-church'),
					"desc" => esc_html__('Select post types, what will be include in search results. If not selected - use all types.', 'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "hidden"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__('Number of the posts to show in search results',  'grace-church'),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "hidden"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's thumbnail in the search results", 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's publish date in the search results", 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's author in the search results", 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'grace-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's counters (views, comments, likes) in the search results", 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'grace-church'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'grace-church'),
					"desc" => esc_html__('Basic theme functionality settings', 'grace-church'),
					"type" => "info"),

		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'grace-church'),
					"desc" => esc_html__('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'grace-church'),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'grace-church'),
					"desc" => esc_html__("Allow authors to edit their posts in frontend area)", 'grace-church'),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'grace-church'),
                    "desc" => wp_kses( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts".',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags'])
                        . '<br />'
                        . wp_kses( __('Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.',  'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'grace-church'),
					"desc" => esc_html__('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'grace-church'),
					"desc" => esc_html__('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'grace-church'),
					"desc" => wp_kses( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'grace-church'),
					"desc" => esc_html__('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'grace-church'),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'grace-church'),
					"desc" => esc_html__('Allow to use Grace-Church Emailer for mass-volume e-mail distribution and management of mailing lists in "Tools - Emailer"', 'grace-church'),
					"std" => "yes",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'grace-church'),
					"desc" => esc_html__('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'grace-church'),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'grace-church'),
					"desc" => wp_kses( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utility <b>yuicompressor-x.y.z.jar</b>', 'grace-church'), $GRACE_CHURCH_GLOBALS['allowed_tags']),
					"std" => "no",
					"options" => $GRACE_CHURCH_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

        "info_service_2" => array(
                "title" => esc_html__('API Keys', 'grace-church'),
                "desc" => wp_kses_data( __('API Keys for some Web services', 'grace-church') ),
                "type" => "info"),
        'api_google' => array(
                "title" => esc_html__('Google API Key', 'grace-church'),
                "desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'grace-church') ),
                "std" => "",
                "type" => "text"),

        "info_service_3" => array(
                "title" => esc_html__('Login and Register', 'grace-church'),
                "desc" => wp_kses_data( __('Settings for the users login and registration', 'grace-church') ),
                "type" => "info"),

        "social_login" => array(
                "title" => esc_html__('Social Login code',  'grace-church'),
                "desc" => wp_kses_data( __('Specify shortcode from your Social Login Plugin or any HTML/JS code to make Social Login section',  'grace-church') ),
                "std" => "[wordpress_social_login]",
                "type" => "textarea"),
            )
		);
	}
}


// Update all temporary vars (start with $grace_church_) in the Theme Options with actual lists
if ( !function_exists( 'grace_church_options_settings_theme_setup2' ) ) {
	add_action( 'grace_church_action_after_init_theme', 'grace_church_options_settings_theme_setup2', 1 );
	function grace_church_options_settings_theme_setup2() {
		if (grace_church_options_is_used()) {
			global $GRACE_CHURCH_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			if (count($GRACE_CHURCH_GLOBALS['options']) > 0) {
				foreach ($GRACE_CHURCH_GLOBALS['options'] as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (grace_church_substr($k1, 0, 14) == '$grace_church_' || grace_church_substr($v1, 0, 14) == '$grace_church_') {
								$list_func = grace_church_substr(grace_church_substr($k1, 0, 14) == '$grace_church_' ? $k1 : $v1, 1);
								unset($GRACE_CHURCH_GLOBALS['options'][$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$GRACE_CHURCH_GLOBALS['options'][$k]['options'] = grace_church_array_merge($GRACE_CHURCH_GLOBALS['options'][$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$GRACE_CHURCH_GLOBALS['options'][$k]['options'] = $lists[$list_func] = grace_church_array_merge($GRACE_CHURCH_GLOBALS['options'][$k]['options'], $list_func == 'grace_church_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		echo sprintf( esc_html__('Wrong function name %s in the theme options array', 'grace-church'), $list_func);
								}
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'grace_church_options_reset' ) ) {
	function grace_church_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(grace_church_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'grace_church_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s","grace_church_%"
                    )
                );
				// Add Templates Options
				if (file_exists(grace_church_get_file_dir('demo/templates_options.txt'))) {
					$theme_options_txt = grace_church_fgc(grace_church_get_file_dir('demo/templates_options.txt'));
					$data = unserialize( $theme_options_txt );
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = grace_church_replace_uploads_url(grace_church_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>