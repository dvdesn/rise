<?php
if (is_admin()
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once( trx_utils_get_file_dir('shortcodes/shortcodes_vc_classes.php') );
}

// Width and height params
if ( !function_exists( 'grace_church_vc_width' ) ) {
	function grace_church_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", "trx_utils"),
			"description" => esc_html__("Width (in pixels or percent) of the current element", "trx_utils"),
			"group" => esc_html__('Size &amp; Margins', 'trx_utils'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'grace_church_vc_height' ) ) {
	function grace_church_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", "trx_utils"),
			"description" => esc_html__("Height (only in pixels) of the current element", "trx_utils"),
			"group" => esc_html__('Size &amp; Margins', 'trx_utils'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'grace_church_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'grace_church_shortcodes_vc_scripts_admin' );
	function grace_church_shortcodes_vc_scripts_admin() {
		// Include CSS 
		wp_enqueue_style ( 'shortcodes-vc-style', trx_utils_get_file_url('shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		wp_enqueue_script( 'shortcodes-vc-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'grace_church_shortcodes_vc_scripts_front' ) ) {
	function grace_church_shortcodes_vc_scripts_front() {
		if (grace_church_vc_is_frontend()) {
			// Include CSS 
			wp_enqueue_style ( 'shortcodes-vc-style', trx_utils_get_file_url('shortcodes/shortcodes_vc_front.css'), array(), null );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'grace_church_shortcodes_vc_add_init_script' ) ) {
	//add_filter('grace_church_shortcode_output', 'grace_church_shortcodes_vc_add_init_script', 10, 4);
	function grace_church_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (grace_church_strpos($output, 'grace_church_vc_init_shortcodes')===false) {
				$id = "grace_church_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							grace_church_init_post_formats();
							grace_church_init_shortcodes(jQuery("body").eq(0));
							grace_church_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_shortcodes_vc_theme_setup' ) ) {
	//if ( grace_church_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'grace_church_action_before_init_theme', 'grace_church_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'grace_church_action_after_init_theme', 'grace_church_shortcodes_vc_theme_setup' );
	function grace_church_shortcodes_vc_theme_setup() {
	
		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( trx_utils_get_folder_dir('shortcodes/vc_shortcodes' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "trx_utils"),
					"description" => esc_html__("Select color scheme for this block", "trx_utils"),
					"group" => esc_html__('Color scheme', 'trx_utils'),
					"class" => "",
					"value" => array_flip(grace_church_get_list_color_schemes(true)),
					"type" => "dropdown"
		));

		if (grace_church_shortcodes_is_used()) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'grace_church_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'grace_church_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('grace_church_shortcode_output', 'grace_church_shortcodes_vc_add_init_script', 10, 4);

/*
			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_tweetmeme");
			vc_remove_element("vc_googleplus");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
			vc_remove_element("vc_carousel");
			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
			vc_remove_element("vc_separator");
			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
			vc_remove_element("vc_images_carousel");
*/

			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $GRACE_CHURCH_GLOBALS;
			
			$GRACE_CHURCH_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => esc_html__("Grace-Church shortcodes", "trx_utils"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", "trx_utils"),
					"description" => esc_html__("ID for current element", "trx_utils"),
					"group" => esc_html__('ID &amp; Class', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", "trx_utils"),
					"description" => esc_html__("CSS class for current element", "trx_utils"),
					"group" => esc_html__('ID &amp; Class', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", "trx_utils"),
					"description" => esc_html__("Select animation while object enter in the visible area of page", "trx_utils"),
					"group" => esc_html__('ID &amp; Class', 'trx_utils'),
					"class" => "",
					"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", "trx_utils"),
					"description" => esc_html__("Any additional CSS rules (if need)", "trx_utils"),
					"group" => esc_html__('ID &amp; Class', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", "trx_utils"),
					"description" => esc_html__("Top margin (in pixels).", "trx_utils"),
					"group" => esc_html__('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", "trx_utils"),
					"description" => esc_html__("Bottom margin (in pixels).", "trx_utils"),
					"group" => esc_html__('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", "trx_utils"),
					"description" => esc_html__("Left margin (in pixels).", "trx_utils"),
					"group" => esc_html__('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", "trx_utils"),
					"description" => esc_html__("Right margin (in pixels).", "trx_utils"),
					"group" => esc_html__('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => esc_html__("Accordion", "trx_utils"),
				"description" => esc_html__("Accordion items", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Accordion style", "trx_utils"),
						"description" => esc_html__("Select style for display accordion", "trx_utils"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(grace_church_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => esc_html__("Counter", "trx_utils"),
						"description" => esc_html__("Display counter before each accordion title", "trx_utils"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened item", "trx_utils"),
						"description" => esc_html__("Number of initially opened item", "trx_utils"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "trx_utils"),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "trx_utils"),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'trx_utils' ) . '"][/trx_accordion_item]
					[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'trx_utils' ) . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "trx_utils").'">'.__("Add item", "trx_utils").'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => esc_html__("Accordion item", "trx_utils"),
				"description" => esc_html__("Inner accordion item", "trx_utils"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for current accordion item", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "trx_utils"),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "trx_utils"),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends GRACE_CHURCH_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends GRACE_CHURCH_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => esc_html__("Anchor", "trx_utils"),
				"description" => esc_html__("Insert anchor for the TOC (table of content)", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Anchor's icon", "trx_utils"),
						"description" => esc_html__("Select icon for the anchor from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Short title", "trx_utils"),
						"description" => esc_html__("Short title of the anchor (for the table of content)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Long description", "trx_utils"),
						"description" => esc_html__("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("External URL", "trx_utils"),
						"description" => esc_html__("External URL for this TOC item", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => esc_html__("Add separator", "trx_utils"),
						"description" => esc_html__("Add separator under item in the TOC", "trx_utils"),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => esc_html__("Audio", "trx_utils"),
				"description" => esc_html__("Insert audio player", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for audio file", "trx_utils"),
						"description" => esc_html__("Put here URL for audio file", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for audio cover", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title of the audio file", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", "trx_utils"),
						"description" => esc_html__("Author of the audio file", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", "trx_utils"),
						"description" => esc_html__("Select type of display", "trx_utils"),
						"class" => "",
                        "value" => array(
                            esc_html__('Normal', 'trx_utils') => 'default',
                            esc_html__('Minimal', 'trx_utils') => 'minimal'
                        ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", "trx_utils"),
						"description" => esc_html__("Show/hide controls", "trx_utils"),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay", "trx_utils"),
						"description" => esc_html__("Autoplay audio on page load", "trx_utils"),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Select block alignment", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
                    array(
                        "param_name" => "inverse_color_player",
                        "heading" => esc_html__("Inverse color", "trx_utils"),
                        "description" => wp_kses( __("Change color to light (for dark background)", "trx_utils"), $GRACE_CHURCH_GLOBALS['allowed_tags'] ),
                        "class" => "",
                        "std" => 'no',
                        "value" => array(esc_html__('Inverse color', 'trx_utils') => 'yes'),
                        "type" => "checkbox"
                    ),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => esc_html__("Block container", "trx_utils"),
				"description" => esc_html__("Container for any block ([section] analog - to enable nesting)", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", "trx_utils"),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array( esc_html__('Use as dedicated content', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Select block alignment", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", "trx_utils"),
						"description" => esc_html__("Select width for columns emulation", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", "trx_utils"),
						"description" => esc_html__("Use pan effect to show section content", "trx_utils"),
						"group" => esc_html__('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array( esc_html__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "trx_utils"),
						"description" => esc_html__("Use scroller to show section content", "trx_utils"),
						"group" => esc_html__('Scroll', 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array( esc_html__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll direction", "trx_utils"),
						"description" => esc_html__("Scroll direction (if Use scroller = yes)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_utils'),
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", "trx_utils"),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_utils'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array( esc_html__('Show scroll controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "trx_utils"),
						"description" => esc_html__("Select color scheme for this block", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "trx_utils"),
						"description" => esc_html__("Any color for objects in this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Any background color for this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "trx_utils"),
						"description" => esc_html__("Select background image from library for this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "trx_utils"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "trx_utils"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "trx_utils"),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "trx_utils"),
						"description" => esc_html__("Font weight of the text", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Bold (700)', 'trx_utils') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "trx_utils"),
						"description" => esc_html__("Content for section container", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => esc_html__("Blogger", "trx_utils"),
				"description" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Output style", "trx_utils"),
						"description" => esc_html__("Select desired style for posts output", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "show_button",
						"heading" => esc_html__("Show/Hide Button", "trx_utils"),
//						"description" => esc_html__("Show/Hide Button", "trx_utils"),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => 'list'
                        ),
                        "std" => "no",
                        "value" => array(
                            esc_html__('Show Button "Read more"', 'trx_utils') => 'yes' ),
                        "type" => "checkbox"
					),
					array(
						"param_name" => "filters",
						"heading" => esc_html__("Show filters", "trx_utils"),
						"description" => esc_html__("Use post's tags or categories as filter buttons", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover",
						"heading" => esc_html__("Hover effect", "trx_utils"),
						"description" => esc_html__("Select hover effect (only if style=Portfolio)", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['hovers']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover_dir",
						"heading" => esc_html__("Hover direction", "trx_utils"),
						"description" => esc_html__("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['hovers_dir']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => esc_html__("Dedicated content location", "trx_utils"),
						"description" => esc_html__("Select position for dedicated content (only for style=excerpt)", "trx_utils"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Posts direction", "trx_utils"),
						"description" => esc_html__("Display posts in horizontal or vertical direction", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"std" => "horizontal",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "rating",
						"heading" => esc_html__("Show rating stars", "trx_utils"),
						"description" => esc_html__("Show rating stars under post's header", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						"class" => "",
						"value" => array( esc_html__('Show rating', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => esc_html__("Show post info block", "trx_utils"),
						"description" => esc_html__("Show post info block (author, date, tags, etc.)", "trx_utils"),
						"class" => "",
						"std" => 'yes',
						"value" => array( esc_html__('Show info', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => esc_html__("Description length", "trx_utils"),
						"description" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Allow links to the post", "trx_utils"),
						"description" => esc_html__("Allow links to the post from each blogger item", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						"class" => "",
						"std" => 'yes',
						"value" => array( esc_html__('Allow links', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("More link text", "trx_utils"),
						"description" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for the block", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "trx_utils"),
						"description" => esc_html__("Subtitle for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "trx_utils"),
						"description" => esc_html__("Description for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "trx_utils"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "trx_utils"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", "trx_utils"),
						"description" => esc_html__("Select post type to show", "trx_utils"),
						"group" => esc_html__('Query', 'trx_utils'),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", "trx_utils"),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
						"group" => esc_html__('Query', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", "trx_utils"),
						"description" => esc_html__("Select category. If empty - show posts from any category or from IDs list", "trx_utils"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'trx_utils'),
						"class" => "",
						"value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $GRACE_CHURCH_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Total posts to show", "trx_utils"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => esc_html__('Query', 'trx_utils'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns number", "trx_utils"),
						"description" => esc_html__("How many columns used to display posts?", "trx_utils"),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"group" => esc_html__('Query', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "trx_utils"),
						"description" => esc_html__("Skip posts before select next part.", "trx_utils"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'trx_utils'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post order by", "trx_utils"),
						"description" => esc_html__("Select desired posts sorting method", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Query', 'trx_utils'),
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "trx_utils"),
						"description" => esc_html__("Select desired posts order", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Query', 'trx_utils'),
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => esc_html__("Select posts only", "trx_utils"),
						"description" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Query', 'trx_utils'),
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "trx_utils"),
						"description" => esc_html__("Use scroller to show all posts", "trx_utils"),
						"group" => esc_html__('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array( esc_html__('Use scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show slider controls", "trx_utils"),
						"description" => esc_html__("Show arrows to control scroll slider", "trx_utils"),
						"group" => esc_html__('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array( esc_html__('Show controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => esc_html__("Line break", "trx_utils"),
				"description" => esc_html__("Line break or Clear Floating", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => esc_html__("Clear floating", "trx_utils"),
						"description" => esc_html__("Select clear side (if need)", "trx_utils"),
						"class" => "",
						"value" => "",
						"value" => array(
							__('None', 'trx_utils') => 'none',
							__('Left', 'trx_utils') => 'left',
							__('Right', 'trx_utils') => 'right',
							__('Both', 'trx_utils') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => esc_html__("Button", "trx_utils"),
				"description" => esc_html__("Button with link", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Caption", "trx_utils"),
						"description" => esc_html__("Button caption", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Button's shape", "trx_utils"),
						"description" => esc_html__("Select button's shape", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Square', 'trx_utils') => 'square',
							__('Round', 'trx_utils') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Button's style", "trx_utils"),
						"description" => esc_html__("Select button's style", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Filled', 'trx_utils') => 'filled',
							__('Border', 'trx_utils') => 'border'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => esc_html__("Button's size", "trx_utils"),
						"description" => esc_html__("Select button's size", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Small', 'trx_utils') => 'small',
							__('Medium', 'trx_utils') => 'medium',
							__('Large', 'trx_utils') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Button's icon", "trx_utils"),
						"description" => esc_html__("Select icon for the title from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Button's text color", "trx_utils"),
						"description" => esc_html__("Any color for button's caption", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Button's backcolor", "trx_utils"),
						"description" => esc_html__("Any color for button's background", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Button's alignment", "trx_utils"),
						"description" => esc_html__("Align button to left, center or right", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "trx_utils"),
						"description" => esc_html__("URL for the link on button click", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Link', 'trx_utils'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", "trx_utils"),
						"description" => esc_html__("Target for the link on button click", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Link', 'trx_utils'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => esc_html__("Open link in popup", "trx_utils"),
						"description" => esc_html__("Open link target in popup window", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Link', 'trx_utils'),
						"value" => array( esc_html__('Open in popup', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => esc_html__("Rel attribute", "trx_utils"),
						"description" => esc_html__("Rel attribute for the button's link (if need", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Link', 'trx_utils'),
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Call to Action block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_call_to_action",
				"name" => esc_html__("Call to Action", "trx_utils"),
				"description" => esc_html__("Insert call to action block in your page (post)", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_call_to_action',
				"class" => "trx_sc_collection trx_sc_call_to_action",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Block's style", "trx_utils"),
						"description" => esc_html__("Select style to display this block", "trx_utils"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(grace_church_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Select block alignment", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "accent",
						"heading" => esc_html__("Accent", "trx_utils"),
						"description" => esc_html__("Fill entire block with Accent1 color from current color scheme", "trx_utils"),
						"class" => "",
						"value" => array("Fill with Accent1 color" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "trx_utils"),
						"description" => esc_html__("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "trx_utils"),
						"class" => "",
						"value" => array("Custom content" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", "trx_utils"),
						"description" => esc_html__("Image to display inside block", "trx_utils"),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "video",
						"heading" => esc_html__("URL for video file", "trx_utils"),
						"description" => esc_html__("Paste URL for video file to display inside block", "trx_utils"),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for the block", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "trx_utils"),
						"description" => esc_html__("Subtitle for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "trx_utils"),
						"description" => esc_html__("Description for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "trx_utils"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "trx_utils"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2",
						"heading" => esc_html__("Button 2 URL", "trx_utils"),
						"description" => esc_html__("Link URL for the second button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2_caption",
						"heading" => esc_html__("Button 2 caption", "trx_utils"),
						"description" => esc_html__("Caption for the second button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Call_To_Action extends GRACE_CHURCH_VC_ShortCodeCollection {}


			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => esc_html__("Chat", "trx_utils"),
				"description" => esc_html__("Chat message", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Item title", "trx_utils"),
						"description" => esc_html__("Title for current chat item", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Item photo", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "trx_utils"),
						"description" => esc_html__("URL for the link on chat title click", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Chat item content", "trx_utils"),
						"description" => esc_html__("Current chat item content", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends GRACE_CHURCH_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => esc_html__("Columns", "trx_utils"),
				"description" => esc_html__("Insert columns with margins", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => esc_html__("Columns count", "trx_utils"),
						"description" => esc_html__("Number of the columns in the container.", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => esc_html__("Fluid columns", "trx_utils"),
						"description" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Fluid columns', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => esc_html__("Column", "trx_utils"),
				"description" => esc_html__("Column item", "trx_utils"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => esc_html__("Merge columns", "trx_utils"),
						"description" => esc_html__("Count merged columns from current", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Alignment text in the column", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "trx_utils"),
						"description" => esc_html__("Any color for objects in this column", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Any background color for this column", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("URL for background image file", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for the background", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Column's content", "trx_utils"),
						"description" => esc_html__("Content of the current column", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends GRACE_CHURCH_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_contact_form",
				"name" => esc_html__("Contact form", "trx_utils"),
				"description" => esc_html__("Insert contact form", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_contact_form',
				"class" => "trx_sc_collection trx_sc_contact_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_form_item'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "trx_utils"),
						"description" => esc_html__("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Create custom form', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "trx_utils"),
						"description" => esc_html__("Select style of the contact form", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(grace_church_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "trx_utils"),
						"description" => esc_html__("Select color scheme for this block", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "action",
						"heading" => esc_html__("Action", "trx_utils"),
						"description" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Select form alignment", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for the block", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "trx_utils"),
						"description" => esc_html__("Subtitle for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "trx_utils"),
						"description" => esc_html__("Description for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => esc_html__("Form item (custom field)", "trx_utils"),
				"description" => esc_html__("Custom field for the contact form", "trx_utils"),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", "trx_utils"),
						"description" => esc_html__("Select type of the custom field", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", "trx_utils"),
						"description" => esc_html__("Name of the custom field", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Default value", "trx_utils"),
						"description" => esc_html__("Default value of the custom field", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "options",
						"heading" => esc_html__("Options", "trx_utils"),
						"description" => esc_html__("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "trx_utils"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('radio','checkbox','select')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => esc_html__("Label", "trx_utils"),
						"description" => esc_html__("Label for the custom field", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => esc_html__("Label position", "trx_utils"),
						"description" => esc_html__("Label position relative to the field", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Contact_Form extends GRACE_CHURCH_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends GRACE_CHURCH_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => esc_html__("Content block", "trx_utils"),
				"description" => esc_html__("Container for main content block (use it only on fullscreen pages)", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "trx_utils"),
						"description" => esc_html__("Select color scheme for this block", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "trx_utils"),
						"description" => esc_html__("Content for section container", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => esc_html__("Countdown", "trx_utils"),
				"description" => esc_html__("Insert countdown object", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => esc_html__("Date", "trx_utils"),
						"description" => esc_html__("Upcoming date (format: yyyy-mm-dd)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => esc_html__("Time", "trx_utils"),
						"description" => esc_html__("Upcoming time (format: HH:mm:ss)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "trx_utils"),
						"description" => esc_html__("Countdown style", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(grace_church_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Align counter to left, center or right", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => esc_html__("Dropcaps", "trx_utils"),
				"description" => esc_html__("Make first letter of the text as dropcaps", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_single trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "trx_utils"),
						"description" => esc_html__("Dropcaps style", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(grace_church_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Paragraph text", "trx_utils"),
						"description" => esc_html__("Paragraph with dropcaps content", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => esc_html__("E-mail collector", "trx_utils"),
				"description" => esc_html__("Collect e-mails into specified group", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => esc_html__("Group", "trx_utils"),
						"description" => esc_html__("The name of group to collect e-mail address", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Opened", "trx_utils"),
						"description" => esc_html__("Initially open the input field on show object", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Initially opened', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Align field to left, center or right", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => esc_html__("Gap", "trx_utils"),
				"description" => esc_html__("Insert gap (fullwidth area) in the post content", "trx_utils"),
				"category" => esc_html__('Structure', 'trx_utils'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Gap content", "trx_utils"),
						"description" => esc_html__("Gap inner content", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					)
					*/
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => esc_html__("Google map", "trx_utils"),
				"description" => esc_html__("Insert Google map with desired address or coordinates", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_collection trx_sc_googlemap",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_googlemap_marker'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "zoom",
						"heading" => esc_html__("Zoom", "trx_utils"),
						"description" => esc_html__("Map zoom factor", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "trx_utils"),
						"description" => esc_html__("Map custom style", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width('100%'),
					grace_church_vc_height(240),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			vc_map( array(
				"base" => "trx_googlemap_marker",
				"name" => esc_html__("Googlemap marker", "trx_utils"),
				"description" => esc_html__("Insert new marker into Google map", "trx_utils"),
				"class" => "trx_sc_collection trx_sc_googlemap_marker",
				'icon' => 'icon_trx_googlemap_marker',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => esc_html__("Address", "trx_utils"),
						"description" => esc_html__("Address of this marker", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => esc_html__("Latitude and Longtitude", "trx_utils"),
						"description" => esc_html__("Comma separated marker's coorditanes (instead Address)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for this marker", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "point",
						"heading" => esc_html__("URL for marker image file", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for this marker. If empty - use default marker", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends GRACE_CHURCH_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Googlemap_Marker extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			
			
			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => esc_html__("Highlight text", "trx_utils"),
				"description" => esc_html__("Highlight text with selected color, background color and other styles", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_single trx_sc_highlight",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", "trx_utils"),
						"description" => esc_html__("Highlight type", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Custom', 'trx_utils') => 0,
								__('Type 1', 'trx_utils') => 1,
								__('Type 2', 'trx_utils') => 2,
								__('Type 3', 'trx_utils') => 3
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "trx_utils"),
						"description" => esc_html__("Color for the highlighted text", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Background color for the highlighted text", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "trx_utils"),
						"description" => esc_html__("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Highlight text", "trx_utils"),
						"description" => esc_html__("Content for highlight", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => esc_html__("Icon", "trx_utils"),
				"description" => esc_html__("Insert the icon", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "trx_utils"),
						"description" => esc_html__("Select icon class from Fontello icons set", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "trx_utils"),
						"description" => esc_html__("Icon's color", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Background color for the icon", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => esc_html__("Background shape", "trx_utils"),
						"description" => esc_html__("Shape of the icon background", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('None', 'trx_utils') => 'none',
							__('Round', 'trx_utils') => 'round',
							__('Square', 'trx_utils') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "trx_utils"),
						"description" => esc_html__("Icon's font size", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "trx_utils"),
						"description" => esc_html__("Icon's font weight", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Bold (700)', 'trx_utils') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Icon's alignment", "trx_utils"),
						"description" => esc_html__("Align icon to left, center or right", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "trx_utils"),
						"description" => esc_html__("Link URL from this icon (if not empty)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => esc_html__("Image", "trx_utils"),
				"description" => esc_html__("Insert image", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("Select image", "trx_utils"),
						"description" => esc_html__("Select image from library", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Image alignment", "trx_utils"),
						"description" => esc_html__("Align image to left or right side", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => esc_html__("Image shape", "trx_utils"),
						"description" => esc_html__("Shape of the image: square or round", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Square', 'trx_utils') => 'square',
							__('Round', 'trx_utils') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Image's title", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title's icon", "trx_utils"),
						"description" => esc_html__("Select icon for the title from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "trx_utils"),
						"description" => esc_html__("The link URL from the image", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => esc_html__("Infobox", "trx_utils"),
				"description" => esc_html__("Box with info or error message", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "trx_utils"),
						"description" => esc_html__("Infobox style", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Regular', 'trx_utils') => 'regular',
								__('Info', 'trx_utils') => 'info',
								__('Success', 'trx_utils') => 'success',
								__('Error', 'trx_utils') => 'error',
								__('Result', 'trx_utils') => 'result'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => esc_html__("Closeable", "trx_utils"),
						"description" => esc_html__("Create closeable box (with close button)", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Close button', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Custom icon", "trx_utils"),
						"description" => esc_html__("Select icon for the infobox from Fontello icons set. If empty - use default icon", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "trx_utils"),
						"description" => esc_html__("Any color for the text and headers", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Any background color for this infobox", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Message text", "trx_utils"),
						"description" => esc_html__("Message for the infobox", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends GRACE_CHURCH_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => esc_html__("Line", "trx_utils"),
				"description" => esc_html__("Insert line (delimiter)", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "trx_utils"),
						"description" => esc_html__("Line style", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Solid', 'trx_utils') => 'solid',
								__('Dashed', 'trx_utils') => 'dashed',
								__('Dotted', 'trx_utils') => 'dotted',
								__('Double', 'trx_utils') => 'double',
								__('Shadow', 'trx_utils') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Line color", "trx_utils"),
						"description" => esc_html__("Line color", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => esc_html__("List", "trx_utils"),
				"description" => esc_html__("List items with specific bullets", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Bullet's style", "trx_utils"),
						"description" => esc_html__("Bullet's style for each list item", "trx_utils"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "trx_utils"),
						"description" => esc_html__("List items color", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List icon", "trx_utils"),
						"description" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", "trx_utils"),
						"description" => esc_html__("List icons color", "trx_utils"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_list_item]' . esc_html__( 'Item 1', 'trx_utils' ) . '[/trx_list_item]
					[trx_list_item]' . esc_html__( 'Item 2', 'trx_utils' ) . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => esc_html__("List item", "trx_utils"),
				"description" => esc_html__("List item with specific bullet", "trx_utils"),
				"class" => "trx_sc_single trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("List item title", "trx_utils"),
						"description" => esc_html__("Title for the current list item (show it as tooltip)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "trx_utils"),
						"description" => esc_html__("Link URL for the current list item", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Link', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", "trx_utils"),
						"description" => esc_html__("Link target for the current list item", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Link', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "trx_utils"),
						"description" => esc_html__("Text color for this item", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List item icon", "trx_utils"),
						"description" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", "trx_utils"),
						"description" => esc_html__("Icon color for this item", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("List item text", "trx_utils"),
						"description" => esc_html__("Current list item content", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_List extends GRACE_CHURCH_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => esc_html__("Number", "trx_utils"),
				"description" => esc_html__("Insert number or any word as set of separated characters", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", "trx_utils"),
						"description" => esc_html__("Number or any word to separate", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Select block alignment", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends GRACE_CHURCH_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => esc_html__("Parallax", "trx_utils"),
				"description" => esc_html__("Create the parallax container (with asinc background image)", "trx_utils"),
				"category" => esc_html__('Structure', 'trx_utils'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => esc_html__("Create gap", "trx_utils"),
						"description" => esc_html__("Create gap around parallax container (not need in fullscreen pages)", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Create gap', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", "trx_utils"),
						"description" => esc_html__("Scroll direction for the parallax background", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Up', 'trx_utils') => 'up',
								__('Down', 'trx_utils') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => esc_html__("Speed", "trx_utils"),
						"description" => esc_html__("Parallax background motion speed (from 0.0 to 1.0)", "trx_utils"),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "trx_utils"),
						"description" => esc_html__("Select color scheme for this block", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "trx_utils"),
						"description" => esc_html__("Select color for text object inside parallax block", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Backgroud color", "trx_utils"),
						"description" => esc_html__("Select color for parallax background", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for the parallax background", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => esc_html__("Image X position", "trx_utils"),
						"description" => esc_html__("Parallax background X position (in percents)", "trx_utils"),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => esc_html__("Video background", "trx_utils"),
						"description" => esc_html__("Paste URL for video file to show it as parallax background", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => esc_html__("Video ratio", "trx_utils"),
						"description" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "trx_utils"),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "trx_utils"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "trx_utils"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Content", "trx_utils"),
						"description" => esc_html__("Content for the parallax container", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => esc_html__("Popup window", "trx_utils"),
				"description" => esc_html__("Container for any html-block with desired class and style for popup window", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "trx_utils"),
						"description" => esc_html__("Content for popup container", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Price
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price",
				"name" => esc_html__("Price", "trx_utils"),
				"description" => esc_html__("Insert price with decoration", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_price',
				"class" => "trx_sc_single trx_sc_price",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "money",
						"heading" => esc_html__("Money", "trx_utils"),
						"description" => esc_html__("Money value (dot or comma separated)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency symbol", "trx_utils"),
						"description" => esc_html__("Currency character", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => esc_html__("Period", "trx_utils"),
						"description" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Align price to left or right side", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Price extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Price block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price_block",
				"name" => esc_html__("Price block", "trx_utils"),
				"description" => esc_html__("Insert price block with title, price and description", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_price_block',
				"class" => "trx_sc_single trx_sc_price_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Block title", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "trx_utils"),
						"description" => esc_html__("URL for link from button (at bottom of the block)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => esc_html__("Link text", "trx_utils"),
						"description" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "trx_utils"),
						"description" => esc_html__("Select icon from Fontello icons set (placed before/instead price)", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "money",
						"heading" => esc_html__("Money", "trx_utils"),
						"description" => esc_html__("Money value (dot or comma separated)", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Money', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency symbol", "trx_utils"),
						"description" => esc_html__("Currency character", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Money', 'trx_utils'),
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => esc_html__("Period", "trx_utils"),
						"description" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Money', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "trx_utils"),
						"description" => esc_html__("Select color scheme for this block", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Align price to left or right side", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Description", "trx_utils"),
						"description" => esc_html__("Description for this price block", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_PriceBlock extends GRACE_CHURCH_VC_ShortCodeSingle {}

			
			
			
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => esc_html__("Quote", "trx_utils"),
				"description" => esc_html__("Quote text", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_single trx_sc_quote",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "cite",
						"heading" => esc_html__("Quote cite", "trx_utils"),
						"description" => esc_html__("URL for the quote cite link", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title (author)", "trx_utils"),
						"description" => esc_html__("Quote title (author name)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
                    array(
                        "param_name" => "style",
                        "heading" => esc_html__("Style Quote", "trx_utils"),
                        "description" => esc_html__("Select a transparent background if you want to write a quote on the image", "trx_utils"),
                        "value" => array(
                            esc_html__('Default', 'trx_utils') => "",
                            esc_html__('Transparent', 'trx_utils') => "transparent"
                        ),
                        "type" => "dropdown"
                    ),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Quote content", "trx_utils"),
						"description" => esc_html__("Quote content", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),

                    array(
                        "param_name" => "bg_color",
                        "heading" => esc_html__("Background color", "trx_utils"),
                        "description" => esc_html__("Any background color for this section", "trx_utils"),
                        "group" => esc_html__('Colors and Images', 'trx_utils'),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('transparent')
                        ),
                        "class" => "",
                        "value" => "",
                        "type" => "colorpicker"
                    ),
                    array(
                        "param_name" => "bg_image",
                        "heading" => esc_html__("Background image URL", "trx_utils"),
                        "description" => esc_html__("Select background image from library for this section", "trx_utils"),
                        "group" => esc_html__('Colors and Images', 'trx_utils'),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('transparent')
                        ),
                        "class" => "",
                        "value" => "",
                        "type" => "attach_image"
                    ),
                    array(
                        "param_name" => "bg_overlay",
                        "heading" => esc_html__("Overlay", "trx_utils"),
                        "description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
                        "group" => esc_html__('Colors and Images', 'trx_utils'),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('transparent')
                        ),
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),

					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => esc_html__("Reviews", "trx_utils"),
				"description" => esc_html__("Insert reviews block in the single post", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Align counter to left, center or right", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => esc_html__("Search form", "trx_utils"),
				"description" => esc_html__("Insert search form", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "trx_utils"),
						"description" => esc_html__("Select style to display search field", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Regular', 'trx_utils') => "regular",
							__('Flat', 'trx_utils') => "flat"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "state",
						"heading" => esc_html__("State", "trx_utils"),
						"description" => esc_html__("Select search field initial state", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Fixed', 'trx_utils')  => "fixed",
							__('Opened', 'trx_utils') => "opened",
							__('Closed', 'trx_utils') => "closed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title (placeholder) for the search field", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => esc_html__("Search &hellip;", 'trx_utils'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => esc_html__("AJAX", "trx_utils"),
						"description" => esc_html__("Search via AJAX or reload page", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Use AJAX search', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => esc_html__("Section container", "trx_utils"),
				"description" => esc_html__("Container for any block ([block] analog - to enable nesting)", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", "trx_utils"),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array( esc_html__('Use as dedicated content', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Select block alignment", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", "trx_utils"),
						"description" => esc_html__("Select width for columns emulation", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", "trx_utils"),
						"description" => esc_html__("Use pan effect to show section content", "trx_utils"),
						"group" => esc_html__('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array( esc_html__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "trx_utils"),
						"description" => esc_html__("Use scroller to show section content", "trx_utils"),
						"group" => esc_html__('Scroll', 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array( esc_html__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll and Pan direction", "trx_utils"),
						"description" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_utils'),
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", "trx_utils"),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_utils'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array( esc_html__('Show scroll controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "trx_utils"),
						"description" => esc_html__("Select color scheme for this block", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "trx_utils"),
						"description" => esc_html__("Any color for objects in this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Any background color for this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "trx_utils"),
						"description" => esc_html__("Select background image from library for this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "trx_utils"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "trx_utils"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "trx_utils"),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "trx_utils"),
						"description" => esc_html__("Font weight of the text", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Bold (700)', 'trx_utils') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "trx_utils"),
						"description" => esc_html__("Content for section container", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends GRACE_CHURCH_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => esc_html__("Skills", "trx_utils"),
				"description" => esc_html__("Insert skills diagramm", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => esc_html__("Max value", "trx_utils"),
						"description" => esc_html__("Max value for skills items", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Skills type", "trx_utils"),
						"description" => esc_html__("Select type of skills block", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Bar', 'trx_utils') => 'bar',
							__('Pie chart', 'trx_utils') => 'pie',
							__('Counter', 'trx_utils') => 'counter',
							__('Arc', 'trx_utils') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => esc_html__("Skills layout", "trx_utils"),
						"description" => esc_html__("Select layout of skills block", "trx_utils"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie')
						),
						"class" => "",
						"value" => array(
							__('Rows', 'trx_utils') => 'rows',
							__('Columns', 'trx_utils') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", "trx_utils"),
						"description" => esc_html__("Select direction of skills block", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Counters style", "trx_utils"),
						"description" => esc_html__("Select style of skills items (only for type=counter)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(grace_church_get_list_styles(1, 4)),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns count", "trx_utils"),
						"description" => esc_html__("Skills columns count (required)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "trx_utils"),
						"description" => esc_html__("Color for all skills items", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Background color for all skills items (only for type=pie)", "trx_utils"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", "trx_utils"),
						"description" => esc_html__("Border color for all skills items (only for type=pie)", "trx_utils"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Align skills block to left or right side", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "arc_caption",
						"heading" => esc_html__("Arc caption", "trx_utils"),
						"description" => esc_html__("Arc caption - text in the center of the diagram", "trx_utils"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "pie_compact",
						"heading" => esc_html__("Pie compact", "trx_utils"),
						"description" => esc_html__("Show all skills in one diagram or as separate diagrams", "trx_utils"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => array( esc_html__('Show all skills in one diagram', 'trx_utils') => 'on'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pie_cutout",
						"heading" => esc_html__("Pie cutout", "trx_utils"),
						"description" => esc_html__("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "trx_utils"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for the block", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "trx_utils"),
						"description" => esc_html__("Subtitle for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "trx_utils"),
						"description" => esc_html__("Description for the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "trx_utils"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "trx_utils"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "trx_utils"),
						"group" => esc_html__('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => esc_html__("Skill", "trx_utils"),
				"description" => esc_html__("Skills item", "trx_utils"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for the current skills item", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", "trx_utils"),
						"description" => esc_html__("Value for the current skills item", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "trx_utils"),
						"description" => esc_html__("Color for current skills item", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Background color for current skills item (only for type=pie)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", "trx_utils"),
						"description" => esc_html__("Border color for current skills item (only for type=pie)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Counter style", "trx_utils"),
						"description" => esc_html__("Select style for the current skills item (only for type=counter)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(grace_church_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Counter icon", "trx_utils"),
						"description" => esc_html__("Select icon from Fontello icons set, placed before counter (only for type=counter)", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends GRACE_CHURCH_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => esc_html__("Slider", "trx_utils"),
				"description" => esc_html__("Insert slider", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => esc_html__("Engine", "trx_utils"),
						"description" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Float slider", "trx_utils"),
						"description" => esc_html__("Float slider to left or right side", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom slides", "trx_utils"),
						"description" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Custom slides', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					)
					),
					grace_church_exists_revslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => esc_html__("Revolution slider alias", "trx_utils"),
						"description" => esc_html__("Select Revolution slider to display", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo')
						),
						"value" => array_flip(grace_church_array_merge(array('none' => esc_html__('- Select slider -', 'trx_utils')), $GRACE_CHURCH_GLOBALS['sc_params']['revo_sliders'])),
						"type" => "dropdown"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", "trx_utils"),
						"description" => esc_html__("Select category. If empty - show posts from any category or from IDs list", "trx_utils"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $GRACE_CHURCH_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Swiper: Number of posts", "trx_utils"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Swiper: Offset before select posts", "trx_utils"),
						"description" => esc_html__("Skip posts before select next part.", "trx_utils"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Swiper: Post sorting", "trx_utils"),
						"description" => esc_html__("Select desired posts sorting method", "trx_utils"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Swiper: Post order", "trx_utils"),
						"description" => esc_html__("Select desired posts order", "trx_utils"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Swiper: Post IDs list", "trx_utils"),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Swiper: Show slider controls", "trx_utils"),
						"description" => esc_html__("Show arrows inside slider", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
                        "std" => "no",
						"value" => array( esc_html__('Show controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => esc_html__("Swiper: Show slider pagination", "trx_utils"),
						"description" => esc_html__("Show bullets or titles to switch slides", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"std" => "no",
						"value" => array(
								__('None', 'trx_utils') => 'no',
								__('Dots', 'trx_utils') => 'yes',
								__('Side Titles', 'trx_utils') => 'full',
								__('Over Titles', 'trx_utils') => 'over'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => esc_html__("Swiper: Show titles section", "trx_utils"),
						"description" => esc_html__("Show section with post's title and short post's description", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Not show', 'trx_utils') => "no",
								__('Show/Hide info', 'trx_utils') => "slide",
								__('Fixed info', 'trx_utils') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => esc_html__("Swiper: Post descriptions", "trx_utils"),
						"description" => esc_html__("Show post's excerpt max length (characters)", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Swiper: Post's title as link", "trx_utils"),
						"description" => esc_html__("Make links from post's titles", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array( esc_html__('Titles as a links', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => esc_html__("Swiper: Crop images", "trx_utils"),
						"description" => esc_html__("Crop images in each slide or live it unchanged", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array( esc_html__('Crop images', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Swiper: Autoheight", "trx_utils"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array( esc_html__('Autoheight', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "slides_per_view",
						"heading" => esc_html__("Swiper: Slides per view", "trx_utils"),
						"description" => esc_html__("Slides per view showed in this slider", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Swiper: Space between slides", "trx_utils"),
						"description" => esc_html__("Size of space (in px) between slides", "trx_utils"),
						"admin_label" => true,
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Swiper: Slides change interval", "trx_utils"),
						"description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
						"group" => esc_html__('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => esc_html__("Slide", "trx_utils"),
				"description" => esc_html__("Slider item - single slide", "trx_utils"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => esc_html__("URL (source) for image file", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for the current slide", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends GRACE_CHURCH_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => esc_html__("Social icons", "trx_utils"),
				"description" => esc_html__("Custom social icons", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", "trx_utils"),
						"description" => esc_html__("Type of the icons - images or font icons", "trx_utils"),
						"class" => "",
						"std" => grace_church_get_theme_setting('socials_type'),
						"value" => array(
							__('Icons', 'trx_utils') => 'icons',
							__('Images', 'trx_utils') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => esc_html__("Icon's size", "trx_utils"),
						"description" => esc_html__("Size of the icons", "trx_utils"),
						"class" => "",
						"std" => "small",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['sizes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => esc_html__("Icon's shape", "trx_utils"),
						"description" => esc_html__("Shape of the icons", "trx_utils"),
						"class" => "",
						"std" => "square",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['shapes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Manual socials list", "trx_utils"),
						"description" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom socials", "trx_utils"),
						"description" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", "trx_utils"),
						"class" => "",
						"value" => array( esc_html__('Custom socials', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => esc_html__("Custom social item", "trx_utils"),
				"description" => esc_html__("Custom social item: name, profile url and icon url", "trx_utils"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Social name", "trx_utils"),
						"description" => esc_html__("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("Your profile URL", "trx_utils"),
						"description" => esc_html__("URL of your profile in specified social network", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("URL (source) for icon file", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for the current social icon", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends GRACE_CHURCH_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends GRACE_CHURCH_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => esc_html__("Table", "trx_utils"),
				"description" => esc_html__("Insert a table", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Cells content alignment", "trx_utils"),
						"description" => esc_html__("Select alignment for each table cell", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Table content", "trx_utils"),
						"description" => esc_html__("Content, created with any table-generator", "trx_utils"),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						"type" => "textarea_html"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends GRACE_CHURCH_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => esc_html__("Tabs", "trx_utils"),
				"description" => esc_html__("Tabs", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Tabs style", "trx_utils"),
						"description" => esc_html__("Select style of tabs items", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(grace_church_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened tab", "trx_utils"),
						"description" => esc_html__("Number of initially opened tab", "trx_utils"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Scroller", "trx_utils"),
						"description" => esc_html__("Use scroller to show tab content (height parameter required)", "trx_utils"),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_tab title="' . esc_html__( 'Tab 1', 'trx_utils' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . esc_html__( 'Tab 2', 'trx_utils' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => esc_html__("Tab item", "trx_utils"),
				"description" => esc_html__("Single tab item", "trx_utils"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Tab title", "trx_utils"),
						"description" => esc_html__("Title for current tab", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "tab_id",
						"heading" => esc_html__("Tab ID", "trx_utils"),
						"description" => esc_html__("ID for current tab (required). Please, start it from letter.", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends GRACE_CHURCH_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends GRACE_CHURCH_VC_ShortCodeTab {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => esc_html__("Title", "trx_utils"),
				"description" => esc_html__("Create header tag (1-6 level) with many styles", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Title content", "trx_utils"),
						"description" => esc_html__("Title content", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Title type", "trx_utils"),
						"description" => esc_html__("Title type (header level)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Header 1', 'trx_utils') => '1',
							__('Header 2', 'trx_utils') => '2',
							__('Header 3', 'trx_utils') => '3',
							__('Header 4', 'trx_utils') => '4',
							__('Header 5', 'trx_utils') => '5',
							__('Header 6', 'trx_utils') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Title style", "trx_utils"),
						"description" => esc_html__("Title style: only text (regular) or with icon/image (iconed)", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Regular', 'trx_utils') => 'regular',
							__('Underline', 'trx_utils') => 'underline',
							__('Divider', 'trx_utils') => 'divider',
							__('With icon (image)', 'trx_utils') => 'iconed'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Title text alignment", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "trx_utils"),
						"description" => esc_html__("Custom font size. If empty - use theme default", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "trx_utils"),
						"description" => esc_html__("Custom font weight. If empty or inherit - use theme default", "trx_utils"),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Semibold (600)', 'trx_utils') => '600',
							__('Bold (700)', 'trx_utils') => '700',
							__('Black (900)', 'trx_utils') => '900'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Title color", "trx_utils"),
						"description" => esc_html__("Select color for the title", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title font icon", "trx_utils"),
						"description" => esc_html__("Select font icon for the title from Fontello icons set (if style=iconed)", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'trx_utils'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("or image icon", "trx_utils"),
						"description" => esc_html__("Select image icon for the title instead icon above (if style=iconed)", "trx_utils"),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'trx_utils'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => esc_html__("or select uploaded image", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "trx_utils"),
						"group" => esc_html__('Icon &amp; Image', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => esc_html__("Image (picture) size", "trx_utils"),
						"description" => esc_html__("Select image (picture) size (if style=iconed)", "trx_utils"),
						"group" => esc_html__('Icon &amp; Image', 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Small', 'trx_utils') => 'small',
							__('Medium', 'trx_utils') => 'medium',
							__('Large', 'trx_utils') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Icon (image) position", "trx_utils"),
						"description" => esc_html__("Select icon (image) position (if style=iconed)", "trx_utils"),
						"group" => esc_html__('Icon &amp; Image', 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Top', 'trx_utils') => 'top',
							__('Left', 'trx_utils') => 'left'
						),
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => esc_html__("Toggles", "trx_utils"),
				"description" => esc_html__("Toggles items", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Toggles style", "trx_utils"),
						"description" => esc_html__("Select style for display toggles", "trx_utils"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(grace_church_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => esc_html__("Counter", "trx_utils"),
						"description" => esc_html__("Display counter before each toggles title", "trx_utils"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "trx_utils"),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "trx_utils"),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'trx_utils' ) . '"][/trx_toggles_item]
					[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'trx_utils' ) . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "trx_utils").'">'.__("Add item", "trx_utils").'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => esc_html__("Toggles item", "trx_utils"),
				"description" => esc_html__("Single toggles item", "trx_utils"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "trx_utils"),
						"description" => esc_html__("Title for current toggles item", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Open on show", "trx_utils"),
						"description" => esc_html__("Open current toggle item on show", "trx_utils"),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "trx_utils"),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "trx_utils"),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", "trx_utils"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends GRACE_CHURCH_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends GRACE_CHURCH_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => esc_html__("Twitter", "trx_utils"),
				"description" => esc_html__("Insert twitter feed into post (page)", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Twitter Username", "trx_utils"),
						"description" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => esc_html__("Consumer Key", "trx_utils"),
						"description" => esc_html__("Consumer Key from the twitter account", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => esc_html__("Consumer Secret", "trx_utils"),
						"description" => esc_html__("Consumer Secret from the twitter account", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => esc_html__("Token Key", "trx_utils"),
						"description" => esc_html__("Token Key from the twitter account", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => esc_html__("Token Secret", "trx_utils"),
						"description" => esc_html__("Token Secret from the twitter account", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Tweets number", "trx_utils"),
						"description" => esc_html__("Number tweets to show", "trx_utils"),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show arrows", "trx_utils"),
						"description" => esc_html__("Show control buttons", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Tweets change interval", "trx_utils"),
						"description" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Alignment of the tweets block", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "trx_utils"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "trx_utils"),
						"description" => esc_html__("Select color scheme for this block", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "trx_utils"),
						"description" => esc_html__("Any background color for this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "trx_utils"),
						"description" => esc_html__("Select background image from library for this section", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "trx_utils"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "trx_utils"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "trx_utils"),
						"group" => esc_html__('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => esc_html__("Video", "trx_utils"),
				"description" => esc_html__("Insert video player", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for video file", "trx_utils"),
						"description" => esc_html__("Paste URL for video file", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => esc_html__("Ratio", "trx_utils"),
						"description" => esc_html__("Select ratio for display video", "trx_utils"),
						"class" => "",
						"value" => array(
							__('16:9', 'trx_utils') => "16:9",
							__('4:3', 'trx_utils') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay video", "trx_utils"),
						"description" => esc_html__("Autoplay video on page load", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Select block alignment", "trx_utils"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for video preview", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", "trx_utils"),
						"description" => esc_html__("Top offset (padding) from background image to video block (in percent). For example: 3%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", "trx_utils"),
						"description" => esc_html__("Bottom offset (padding) from background image to video block (in percent). For example: 3%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", "trx_utils"),
						"description" => esc_html__("Left offset (padding) from background image to video block (in percent). For example: 20%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", "trx_utils"),
						"description" => esc_html__("Right offset (padding) from background image to video block (in percent). For example: 12%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends GRACE_CHURCH_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => esc_html__("Zoom", "trx_utils"),
				"description" => esc_html__("Insert the image with zoom/lens effect", "trx_utils"),
				"category" => esc_html__('Content', 'trx_utils'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => esc_html__("Effect", "trx_utils"),
						"description" => esc_html__("Select effect to display overlapping image", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"std" => "zoom",
						"value" => array(
							__('Lens', 'trx_utils') => 'lens',
							__('Zoom', 'trx_utils') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("Main image", "trx_utils"),
						"description" => esc_html__("Select or upload main image", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => esc_html__("Overlaping image", "trx_utils"),
						"description" => esc_html__("Select or upload overlaping image", "trx_utils"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "trx_utils"),
						"description" => esc_html__("Float zoom to left or right side", "trx_utils"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "trx_utils"),
						"description" => esc_html__("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", "trx_utils"),
						"description" => esc_html__("Top offset (padding) from background image to zoom block (in percent). For example: 3%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", "trx_utils"),
						"description" => esc_html__("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", "trx_utils"),
						"description" => esc_html__("Left offset (padding) from background image to zoom block (in percent). For example: 20%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", "trx_utils"),
						"description" => esc_html__("Right offset (padding) from background image to zoom block (in percent). For example: 12%", "trx_utils"),
						"group" => esc_html__('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css'],
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends GRACE_CHURCH_VC_ShortCodeSingle {}
			

			do_action('grace_church_action_shortcodes_list_vc');


		}
	}
}
?>