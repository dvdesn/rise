<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'grace_church_shortcodes_is_used' ) ) {
	function grace_church_shortcodes_is_used() {
		return grace_church_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| grace_church_vc_is_frontend();															// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'grace_church_shortcodes_width' ) ) {
	function grace_church_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", "trx_utils"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'grace_church_shortcodes_height' ) ) {
	function grace_church_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", "trx_utils"),
			"desc" => esc_html__("Width (in pixels or percent) and height (only in pixels) of element", "trx_utils"),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_shortcodes_settings_theme_setup' ) ) {
//	if ( grace_church_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'grace_church_action_before_init_theme', 'grace_church_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'grace_church_action_after_init_theme', 'grace_church_shortcodes_settings_theme_setup' );
	function grace_church_shortcodes_settings_theme_setup() {
		if (grace_church_shortcodes_is_used()) {
			global $GRACE_CHURCH_GLOBALS;

			// Prepare arrays 
			$GRACE_CHURCH_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", "trx_utils"),
					"desc" => esc_html__("ID for current element", "trx_utils"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", "trx_utils"),
					"desc" => esc_html__("CSS class for current element (optional)", "trx_utils"),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", "trx_utils"),
					"desc" => esc_html__("Any additional CSS rules (if need)", "trx_utils"),
					"value" => "",
					"type" => "text"
				),
			
				// Margins params
				'top' => array(
					"title" => esc_html__("Top margin", "trx_utils"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				'bottom' => array(
					"title" => esc_html__("Bottom margin", "trx_utils"),
					"value" => "",
					"type" => "text"
				),
			
				'left' => array(
					"title" => esc_html__("Left margin", "trx_utils"),
					"value" => "",
					"type" => "text"
				),
			
				'right' => array(
					"title" => esc_html__("Right margin", "trx_utils"),
					"desc" => esc_html__("Margins around list (in pixels).", "trx_utils"),
					"value" => "",
					"type" => "text"
				),
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'trx_utils'),
					'ol'	=> esc_html__('Ordered', 'trx_utils'),
					'iconed'=> esc_html__('Iconed', 'trx_utils')
				),
				'yes_no'	=> grace_church_get_list_yesno(),
				'on_off'	=> grace_church_get_list_onoff(),
				'dir' 		=> grace_church_get_list_directions(),
				'align'		=> grace_church_get_list_alignments(),
				'float'		=> grace_church_get_list_floats(),
				'show_hide'	=> grace_church_get_list_showhide(),
				'sorting' 	=> grace_church_get_list_sortings(),
				'ordering' 	=> grace_church_get_list_orderings(),
				'shapes'	=> grace_church_get_list_shapes(),
				'sizes'		=> grace_church_get_list_sizes(),
				'sliders'	=> grace_church_get_list_sliders(),
				'revo_sliders' => grace_church_get_list_revo_sliders(),
                'categories'=> is_admin() && grace_church_get_value_gp('action')=='vc_edit_form' && substr(grace_church_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
                    ? grace_church_get_list_terms(false, grace_church_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
                    : grace_church_get_list_categories(),
				'columns'	=> grace_church_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), grace_church_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), grace_church_get_list_icons()),
				'locations'	=> grace_church_get_list_dedicated_locations(),
				'filters'	=> grace_church_get_list_portfolio_filters(),
				'formats'	=> grace_church_get_list_post_formats_filters(),
				'hovers'	=> grace_church_get_list_hovers(true),
				'hovers_dir'=> grace_church_get_list_hovers_directions(true),
				'schemes'	=> grace_church_get_list_color_schemes(true),
				'animations'=> grace_church_get_list_animations_in(),
				'blogger_styles'	=> grace_church_get_list_templates_blogger(),
				'posts_types'		=> grace_church_get_list_posts_types(),
				'googlemap_styles'	=> grace_church_get_list_googlemap_styles(),
				'field_types'		=> grace_church_get_list_field_types(),
				'label_positions'	=> grace_church_get_list_label_positions()
			);

			$GRACE_CHURCH_GLOBALS['sc_params']['animation'] = array(
				"title" => esc_html__("Animation",  'trx_utils'),
				"desc" => esc_html__('Select animation while object enter in the visible area of page',  'trx_utils'),
				"value" => "none",
				"type" => "select",
				"options" => $GRACE_CHURCH_GLOBALS['sc_params']['animations']
			);
	
			// Shortcodes list
			//------------------------------------------------------------------
			$GRACE_CHURCH_GLOBALS['shortcodes'] = array(
			
				// Accordion
				"trx_accordion" => array(
					"title" => esc_html__("Accordion", "trx_utils"),
					"desc" => esc_html__("Accordion items", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Accordion style", "trx_utils"),
							"desc" => esc_html__("Select style for display accordion", "trx_utils"),
							"value" => 1,
							"options" => grace_church_get_list_styles(1, 2),
							"type" => "radio"
						),
						"counter" => array(
							"title" => esc_html__("Counter", "trx_utils"),
							"desc" => esc_html__("Display counter before each accordion title", "trx_utils"),
							"value" => "off",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['on_off']
						),
						"initial" => array(
							"title" => esc_html__("Initially opened item", "trx_utils"),
							"desc" => esc_html__("Number of initially opened item", "trx_utils"),
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'trx_utils'),
							"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'trx_utils'),
							"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_accordion_item",
						"title" => esc_html__("Item", "trx_utils"),
						"desc" => esc_html__("Accordion item", "trx_utils"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Accordion item title", "trx_utils"),
								"desc" => esc_html__("Title for current accordion item", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'trx_utils'),
								"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'trx_utils'),
								"value" => "",
								"type" => "icons",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'trx_utils'),
								"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'trx_utils'),
								"value" => "",
								"type" => "icons",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Accordion item content", "trx_utils"),
								"desc" => esc_html__("Current accordion item content", "trx_utils"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Anchor
				"trx_anchor" => array(
					"title" => esc_html__("Anchor", "trx_utils"),
					"desc" => esc_html__("Insert anchor for the TOC (table of content)", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__("Anchor's icon",  'trx_utils'),
							"desc" => esc_html__('Select icon for the anchor from Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"title" => array(
							"title" => esc_html__("Short title", "trx_utils"),
							"desc" => esc_html__("Short title of the anchor (for the table of content)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Long description", "trx_utils"),
							"desc" => esc_html__("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"url" => array(
							"title" => esc_html__("External URL", "trx_utils"),
							"desc" => esc_html__("External URL for this TOC item", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"separator" => array(
							"title" => esc_html__("Add separator", "trx_utils"),
							"desc" => esc_html__("Add separator under item in the TOC", "trx_utils"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id']
					)
				),
			
			
				// Audio
				"trx_audio" => array(
					"title" => esc_html__("Audio", "trx_utils"),
					"desc" => esc_html__("Insert audio player", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for audio file", "trx_utils"),
							"desc" => esc_html__("URL for audio file", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose audio', 'trx_utils'),
								'action' => 'media_upload',
								'type' => 'audio',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose audio file', 'trx_utils'),
									'update' => esc_html__('Select audio file', 'trx_utils')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"image" => array(
							"title" => esc_html__("Cover image", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for audio cover", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Title of the audio file", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"author" => array(
							"title" => esc_html__("Author", "trx_utils"),
							"desc" => esc_html__("Author of the audio file", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => esc_html__("Type", "trx_utils"),
							"desc" => esc_html__("Select type of display", "trx_utils"),
							"value" => array(),
                            "options" => array(
                                'default' => esc_html__('Normal', 'trx_utils'),
                                'minimal' => esc_html__('Minimal', 'trx_utils')
                            ),
							"type" => "checklist"
						),
						"controls" => array(
							"title" => esc_html__("Show controls", "trx_utils"),
							"desc" => esc_html__("Show controls in audio player", "trx_utils"),
							"divider" => true,
							"size" => "medium",
							"value" => "show",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['show_hide']
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay audio", "trx_utils"),
							"desc" => esc_html__("Autoplay audio on page load", "trx_utils"),
							"value" => "off",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", "trx_utils"),
							"desc" => esc_html__("Select block alignment", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
                        "inverse_color_player" => array(
                            "title" => esc_html__("Inverse color", "trx_utils"),
                            "desc" => wp_kses( __("Change color to light (for dark background)", "trx_utils"), $GRACE_CHURCH_GLOBALS['allowed_tags'] ),
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Block
				"trx_block" => array(
					"title" => esc_html__("Block container", "trx_utils"),
					"desc" => esc_html__("Container for any block ([section] analog - to enable nesting)", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", "trx_utils"),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", "trx_utils"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", "trx_utils"),
							"desc" => esc_html__("Select block alignment", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", "trx_utils"),
							"desc" => esc_html__("Select width for columns emulation", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", "trx_utils"),
							"desc" => esc_html__("Use pan effect to show section content", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "trx_utils"),
							"desc" => esc_html__("Use scroller to show section content", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll direction", "trx_utils"),
							"desc" => esc_html__("Scroll direction (if Use scroller = yes)", "trx_utils"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", "trx_utils"),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", "trx_utils"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "trx_utils"),
							"desc" => esc_html__("Select color scheme for this block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => esc_html__("Fore color", "trx_utils"),
							"desc" => esc_html__("Any color for objects in this section", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "trx_utils"),
							"desc" => esc_html__("Any background color for this section", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "trx_utils"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "trx_utils"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "trx_utils"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "trx_utils"),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "trx_utils"),
							"desc" => esc_html__("Font weight of the text", "trx_utils"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'trx_utils'),
								'300' => esc_html__('Light (300)', 'trx_utils'),
								'400' => esc_html__('Normal (400)', 'trx_utils'),
								'700' => esc_html__('Bold (700)', 'trx_utils')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "trx_utils"),
							"desc" => esc_html__("Content for section container", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Blogger
				"trx_blogger" => array(
					"title" => esc_html__("Blogger", "trx_utils"),
					"desc" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Title for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "trx_utils"),
							"desc" => esc_html__("Subtitle for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "trx_utils"),
							"desc" => esc_html__("Short description for the block", "trx_utils"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Posts output style", "trx_utils"),
							"desc" => esc_html__("Select desired style for posts output", "trx_utils"),
							"value" => "regular",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['blogger_styles']
						),
                        "show_button" => array(
                            "title" => esc_html__("Show Button", "trx_utils"),
//                            "desc" => esc_html__("", "trx_utils"),
                            "dependency" => array(
                                'style' => 'list'
                            ),
                            "value" => "no",
                            "type" => "switch",
                            "size" => "medium",
                            "options" => array(
                                'yes'   => esc_html__('Show', 'trx_utils') ,
                                'no'    => esc_html__('Hide', 'trx_utils')
                            ),
                        ),
						"filters" => array(
							"title" => esc_html__("Show filters", "trx_utils"),
							"desc" => esc_html__("Use post's tags or categories as filter buttons", "trx_utils"),
							"value" => "no",
							"dir" => "horizontal",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['filters']
						),
						"hover" => array(
							"title" => esc_html__("Hover effect", "trx_utils"),
							"desc" => esc_html__("Select hover effect (only if style=Portfolio)", "trx_utils"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','short','colored')
							),
							"value" => "",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['hovers']
						),
						"hover_dir" => array(
							"title" => esc_html__("Hover direction", "trx_utils"),
							"desc" => esc_html__("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "trx_utils"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','short','colored'),
								'hover' => array('square','circle')
							),
							"value" => "left_to_right",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['hovers_dir']
						),
						"dir" => array(
							"title" => esc_html__("Posts direction", "trx_utils"),
							"desc" => esc_html__("Display posts in horizontal or vertical direction", "trx_utils"),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['dir']
						),
						"post_type" => array(
							"title" => esc_html__("Post type", "trx_utils"),
							"desc" => esc_html__("Select post type to show", "trx_utils"),
							"value" => "post",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['posts_types']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "trx_utils"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories list", "trx_utils"),
							"desc" => esc_html__("Select the desired categories. If not selected - show posts from any category or from IDs list", "trx_utils"),
							"dependency" => array(
								'ids' => array('is_empty'),
								'post_type' => array('refresh')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $GRACE_CHURCH_GLOBALS['sc_params']['categories'])
						),
						"count" => array(
							"title" => esc_html__("Total posts to show", "trx_utils"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns number", "trx_utils"),
							"desc" => esc_html__("How many columns used to show posts? If empty or 0 - equal to posts number", "trx_utils"),
							"dependency" => array(
								'dir' => array('horizontal')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "trx_utils"),
							"desc" => esc_html__("Skip posts before select next part.", "trx_utils"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "trx_utils"),
							"desc" => esc_html__("Select desired posts sorting method", "trx_utils"),
							"value" => "date",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "trx_utils"),
							"desc" => esc_html__("Select desired posts order", "trx_utils"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['ordering']
						),
						"only" => array(
							"title" => esc_html__("Select posts only", "trx_utils"),
							"desc" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", "trx_utils"),
							"value" => "no",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['formats']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "trx_utils"),
							"desc" => esc_html__("Use scroller to show all posts", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Show slider controls", "trx_utils"),
							"desc" => esc_html__("Show arrows to control scroll slider", "trx_utils"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"location" => array(
							"title" => esc_html__("Dedicated content location", "trx_utils"),
							"desc" => esc_html__("Select position for dedicated content (only for style=excerpt)", "trx_utils"),
							"divider" => true,
							"dependency" => array(
								'style' => array('excerpt')
							),
							"value" => "default",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['locations']
						),
						"rating" => array(
							"title" => esc_html__("Show rating stars", "trx_utils"),
							"desc" => esc_html__("Show rating stars under post's header", "trx_utils"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"info" => array(
							"title" => esc_html__("Show post info block", "trx_utils"),
							"desc" => esc_html__("Show post info block (author, date, tags, etc.)", "trx_utils"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"links" => array(
							"title" => esc_html__("Allow links on the post", "trx_utils"),
							"desc" => esc_html__("Allow links on the post from each blogger item", "trx_utils"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"descr" => array(
							"title" => esc_html__("Description length", "trx_utils"),
							"desc" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", "trx_utils"),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"readmore" => array(
							"title" => esc_html__("More link text", "trx_utils"),
							"desc" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "trx_utils"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "trx_utils"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Br
				"trx_br" => array(
					"title" => esc_html__("Break", "trx_utils"),
					"desc" => esc_html__("Line break with clear floating (if need)", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"clear" => 	array(
							"title" => esc_html__("Clear floating", "trx_utils"),
							"desc" => esc_html__("Clear floating (if need)", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => array(
								'none' => esc_html__('None', 'trx_utils'),
								'left' => esc_html__('Left', 'trx_utils'),
								'right' => esc_html__('Right', 'trx_utils'),
								'both' => esc_html__('Both', 'trx_utils')
							)
						)
					)
				),
			
			
			
			
				// Button
				"trx_button" => array(
					"title" => esc_html__("Button", "trx_utils"),
					"desc" => esc_html__("Button with link", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Caption", "trx_utils"),
							"desc" => esc_html__("Button caption", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => esc_html__("Button's shape", "trx_utils"),
							"desc" => esc_html__("Select button's shape", "trx_utils"),
							"value" => "square",
							"size" => "medium",
							"options" => array(
								'square' => esc_html__('Square', 'trx_utils'),
								'round' => esc_html__('Round', 'trx_utils')
							),
							"type" => "switch"
						), 
						"style" => array(
							"title" => esc_html__("Button's style", "trx_utils"),
							"desc" => esc_html__("Select button's style", "trx_utils"),
							"value" => "default",
							"dir" => "horizontal",
							"options" => array(
								'filled' => esc_html__('Filled', 'trx_utils'),
								'border' => esc_html__('Border', 'trx_utils')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => esc_html__("Button's size", "trx_utils"),
							"desc" => esc_html__("Select button's size", "trx_utils"),
							"value" => "small",
							"dir" => "horizontal",
							"options" => array(
								'small' => esc_html__('Small', 'trx_utils'),
								'medium' => esc_html__('Medium', 'trx_utils'),
								'large' => esc_html__('Large', 'trx_utils')
							),
							"type" => "checklist"
						), 
						"icon" => array(
							"title" => esc_html__("Button's icon",  'trx_utils'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Button's text color", "trx_utils"),
							"desc" => esc_html__("Any color for button's caption", "trx_utils"),
							"std" => "",
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Button's backcolor", "trx_utils"),
							"desc" => esc_html__("Any color for button's background", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => esc_html__("Button's alignment", "trx_utils"),
							"desc" => esc_html__("Align button to left, center or right", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", "trx_utils"),
							"desc" => esc_html__("URL for link on button click", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"target" => array(
							"title" => esc_html__("Link target", "trx_utils"),
							"desc" => esc_html__("Target for link on button click", "trx_utils"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"popup" => array(
							"title" => esc_html__("Open link in popup", "trx_utils"),
							"desc" => esc_html__("Open link target in popup window", "trx_utils"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						), 
						"rel" => array(
							"title" => esc_html__("Rel attribute", "trx_utils"),
							"desc" => esc_html__("Rel attribute for button's link (if need)", "trx_utils"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),




				// Call to Action block
				"trx_call_to_action" => array(
					"title" => esc_html__("Call to action", "trx_utils"),
					"desc" => esc_html__("Insert call to action block in your page (post)", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Title for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "trx_utils"),
							"desc" => esc_html__("Subtitle for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "trx_utils"),
							"desc" => esc_html__("Short description for the block", "trx_utils"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Style", "trx_utils"),
							"desc" => esc_html__("Select style to display block", "trx_utils"),
							"value" => "1",
							"type" => "checklist",
							"options" => grace_church_get_list_styles(1, 2)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Alignment elements in the block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"accent" => array(
							"title" => esc_html__("Accented", "trx_utils"),
							"desc" => esc_html__("Fill entire block with Accent1 color from current color scheme", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "trx_utils"),
							"desc" => esc_html__("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"image" => array(
							"title" => esc_html__("Image", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site to include image into this block", "trx_utils"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"video" => array(
							"title" => esc_html__("URL for video file", "trx_utils"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site to include video into this block", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose video', 'trx_utils'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose video file', 'trx_utils'),
									'update' => esc_html__('Select video file', 'trx_utils')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"link" => array(
							"title" => esc_html__("Button URL", "trx_utils"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "trx_utils"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"link2" => array(
							"title" => esc_html__("Button 2 URL", "trx_utils"),
							"desc" => esc_html__("Link URL for the second button at the bottom of the block", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link2_caption" => array(
							"title" => esc_html__("Button 2 caption", "trx_utils"),
							"desc" => esc_html__("Caption for the second button at the bottom of the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Chat
				"trx_chat" => array(
					"title" => esc_html__("Chat", "trx_utils"),
					"desc" => esc_html__("Chat message", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Item title", "trx_utils"),
							"desc" => esc_html__("Chat item title", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"photo" => array(
							"title" => esc_html__("Item photo", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"link" => array(
							"title" => esc_html__("Item link", "trx_utils"),
							"desc" => esc_html__("Chat item link", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Chat item content", "trx_utils"),
							"desc" => esc_html__("Current chat item content", "trx_utils"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Columns
				"trx_columns" => array(
					"title" => esc_html__("Columns", "trx_utils"),
					"desc" => esc_html__("Insert up to 5 columns in your page (post)", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"fluid" => array(
							"title" => esc_html__("Fluid columns", "trx_utils"),
							"desc" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "trx_utils"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						), 
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_column_item",
						"title" => esc_html__("Column", "trx_utils"),
						"desc" => esc_html__("Column item", "trx_utils"),
						"container" => true,
						"params" => array(
							"span" => array(
								"title" => esc_html__("Merge columns", "trx_utils"),
								"desc" => esc_html__("Count merged columns from current", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"align" => array(
								"title" => esc_html__("Alignment", "trx_utils"),
								"desc" => esc_html__("Alignment text in the column", "trx_utils"),
								"value" => "",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
							),
							"color" => array(
								"title" => esc_html__("Fore color", "trx_utils"),
								"desc" => esc_html__("Any color for objects in this column", "trx_utils"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", "trx_utils"),
								"desc" => esc_html__("Any background color for this column", "trx_utils"),
								"value" => "",
								"type" => "color"
							),
							"bg_image" => array(
								"title" => esc_html__("URL for background image file", "trx_utils"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the background", "trx_utils"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Column item content", "trx_utils"),
								"desc" => esc_html__("Current column item content", "trx_utils"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Contact form
				"trx_contact_form" => array(
					"title" => esc_html__("Contact form", "trx_utils"),
					"desc" => esc_html__("Insert contact form", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Title for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "trx_utils"),
							"desc" => esc_html__("Subtitle for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "trx_utils"),
							"desc" => esc_html__("Short description for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Style", "trx_utils"),
							"desc" => esc_html__("Select style of the contact form", "trx_utils"),
							"value" => 1,
							"options" => grace_church_get_list_styles(1, 2),
							"type" => "checklist"
						), 
						"scheme" => array(
							"title" => esc_html__("Color scheme", "trx_utils"),
							"desc" => esc_html__("Select color scheme for this block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "trx_utils"),
							"desc" => esc_html__("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "trx_utils"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						), 
						"action" => array(
							"title" => esc_html__("Action", "trx_utils"),
							"desc" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", "trx_utils"),
							"desc" => esc_html__("Select form alignment", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"width" => grace_church_shortcodes_width(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_form_item",
						"title" => esc_html__("Field", "trx_utils"),
						"desc" => esc_html__("Custom field", "trx_utils"),
						"container" => false,
						"params" => array(
							"type" => array(
								"title" => esc_html__("Type", "trx_utils"),
								"desc" => esc_html__("Type of the custom field", "trx_utils"),
								"value" => "text",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['field_types']
							), 
							"name" => array(
								"title" => esc_html__("Name", "trx_utils"),
								"desc" => esc_html__("Name of the custom field", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Default value", "trx_utils"),
								"desc" => esc_html__("Default value of the custom field", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"options" => array(
								"title" => esc_html__("Options", "trx_utils"),
								"desc" => esc_html__("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "trx_utils"),
								"dependency" => array(
									'type' => array('radio', 'checkbox', 'select')
								),
								"value" => "",
								"type" => "text"
							),
							"label" => array(
								"title" => esc_html__("Label", "trx_utils"),
								"desc" => esc_html__("Label for the custom field", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"label_position" => array(
								"title" => esc_html__("Label position", "trx_utils"),
								"desc" => esc_html__("Label position relative to the field", "trx_utils"),
								"value" => "top",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['label_positions']
							), 
							"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
							"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
							"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
							"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Content block on fullscreen page
				"trx_content" => array(
					"title" => esc_html__("Content block", "trx_utils"),
					"desc" => esc_html__("Container for main content block with desired class and style (use it only on fullscreen pages)", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"scheme" => array(
							"title" => esc_html__("Color scheme", "trx_utils"),
							"desc" => esc_html__("Select color scheme for this block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "trx_utils"),
							"desc" => esc_html__("Content for section container", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Countdown
				"trx_countdown" => array(
					"title" => esc_html__("Countdown", "trx_utils"),
					"desc" => esc_html__("Insert countdown object", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"date" => array(
							"title" => esc_html__("Date", "trx_utils"),
							"desc" => esc_html__("Upcoming date (format: yyyy-mm-dd)", "trx_utils"),
							"value" => "",
							"format" => "yy-mm-dd",
							"type" => "date"
						),
						"time" => array(
							"title" => esc_html__("Time", "trx_utils"),
							"desc" => esc_html__("Upcoming time (format: HH:mm:ss)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Style", "trx_utils"),
							"desc" => esc_html__("Countdown style", "trx_utils"),
							"value" => "1",
							"type" => "checklist",
							"options" => grace_church_get_list_styles(1, 2)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Align counter to left, center or right", "trx_utils"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						), 
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Dropcaps
				"trx_dropcaps" => array(
					"title" => esc_html__("Dropcaps", "trx_utils"),
					"desc" => esc_html__("Make first letter as dropcaps", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "trx_utils"),
							"desc" => esc_html__("Dropcaps style", "trx_utils"),
							"value" => "1",
							"type" => "checklist",
							"options" => grace_church_get_list_styles(1, 4)
						),
						"_content_" => array(
							"title" => esc_html__("Paragraph content", "trx_utils"),
							"desc" => esc_html__("Paragraph with dropcaps content", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Emailer
				"trx_emailer" => array(
					"title" => esc_html__("E-mail collector", "trx_utils"),
					"desc" => esc_html__("Collect the e-mail address into specified group", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"group" => array(
							"title" => esc_html__("Group", "trx_utils"),
							"desc" => esc_html__("The name of group to collect e-mail address", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"open" => array(
							"title" => esc_html__("Open", "trx_utils"),
							"desc" => esc_html__("Initially open the input field on show object", "trx_utils"),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Align object to left, center or right", "trx_utils"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						), 
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Gap
				"trx_gap" => array(
					"title" => esc_html__("Gap", "trx_utils"),
					"desc" => esc_html__("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Gap content", "trx_utils"),
							"desc" => esc_html__("Gap inner content", "trx_utils"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						)
					)
				),
			
			
			
			
			
				// Google map
				"trx_googlemap" => array(
					"title" => esc_html__("Google map", "trx_utils"),
					"desc" => esc_html__("Insert Google map with specified markers", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"zoom" => array(
							"title" => esc_html__("Zoom", "trx_utils"),
							"desc" => esc_html__("Map zoom factor", "trx_utils"),
							"divider" => true,
							"value" => 16,
							"min" => 1,
							"max" => 20,
							"type" => "spinner"
						),
						"style" => array(
							"title" => esc_html__("Map style", "trx_utils"),
							"desc" => esc_html__("Select map style", "trx_utils"),
							"value" => "default",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['googlemap_styles']
						),
						"width" => grace_church_shortcodes_width('100%'),
						"height" => grace_church_shortcodes_height(240),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_googlemap_marker",
						"title" => esc_html__("Google map marker", "trx_utils"),
						"desc" => esc_html__("Google map marker", "trx_utils"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"address" => array(
								"title" => esc_html__("Address", "trx_utils"),
								"desc" => esc_html__("Address of this marker", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"latlng" => array(
								"title" => esc_html__("Latitude and Longtitude", "trx_utils"),
								"desc" => esc_html__("Comma separated marker's coorditanes (instead Address)", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"point" => array(
								"title" => esc_html__("URL for marker image file", "trx_utils"),
								"desc" => esc_html__("Select or upload image or write URL from other site for this marker. If empty - use default marker", "trx_utils"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"title" => array(
								"title" => esc_html__("Title", "trx_utils"),
								"desc" => esc_html__("Title for this marker", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "trx_utils"),
								"desc" => esc_html__("Description for this marker", "trx_utils"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id']
						)
					)
				),
			
			
			
				// Hide or show any block
				"trx_hide" => array(
					"title" => esc_html__("Hide/Show any block", "trx_utils"),
					"desc" => esc_html__("Hide or Show any block with desired CSS-selector", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"selector" => array(
							"title" => esc_html__("Selector", "trx_utils"),
							"desc" => esc_html__("Any block's CSS-selector", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"hide" => array(
							"title" => esc_html__("Hide or Show", "trx_utils"),
							"desc" => esc_html__("New state for the block: hide or show", "trx_utils"),
							"value" => "yes",
							"size" => "small",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						)
					)
				),
			
			
			
				// Highlght text
				"trx_highlight" => array(
					"title" => esc_html__("Highlight text", "trx_utils"),
					"desc" => esc_html__("Highlight text with selected color, background color and other styles", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"type" => array(
							"title" => esc_html__("Type", "trx_utils"),
							"desc" => esc_html__("Highlight type", "trx_utils"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								0 => esc_html__('Custom', 'trx_utils'),
								1 => esc_html__('Type 1', 'trx_utils'),
								2 => esc_html__('Type 2', 'trx_utils'),
								3 => esc_html__('Type 3', 'trx_utils')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", "trx_utils"),
							"desc" => esc_html__("Color for the highlighted text", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "trx_utils"),
							"desc" => esc_html__("Background color for the highlighted text", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "trx_utils"),
							"desc" => esc_html__("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Highlighting content", "trx_utils"),
							"desc" => esc_html__("Content for highlight", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Icon
				"trx_icon" => array(
					"title" => esc_html__("Icon", "trx_utils"),
					"desc" => esc_html__("Insert icon", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__('Icon',  'trx_utils'),
							"desc" => esc_html__('Select font icon from the Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Icon's color", "trx_utils"),
							"desc" => esc_html__("Icon's color", "trx_utils"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "color"
						),
						"bg_shape" => array(
							"title" => esc_html__("Background shape", "trx_utils"),
							"desc" => esc_html__("Shape of the icon background", "trx_utils"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "none",
							"type" => "radio",
							"options" => array(
								'none' => esc_html__('None', 'trx_utils'),
								'round' => esc_html__('Round', 'trx_utils'),
								'square' => esc_html__('Square', 'trx_utils')
							)
						),
						"bg_color" => array(
							"title" => esc_html__("Icon's background color", "trx_utils"),
							"desc" => esc_html__("Icon's background color", "trx_utils"),
							"dependency" => array(
								'icon' => array('not_empty'),
								'background' => array('round','square')
							),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "trx_utils"),
							"desc" => esc_html__("Icon's font size", "trx_utils"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "spinner",
							"min" => 8,
							"max" => 240
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "trx_utils"),
							"desc" => esc_html__("Icon font weight", "trx_utils"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'trx_utils'),
								'300' => esc_html__('Light (300)', 'trx_utils'),
								'400' => esc_html__('Normal (400)', 'trx_utils'),
								'700' => esc_html__('Bold (700)', 'trx_utils')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Icon text alignment", "trx_utils"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", "trx_utils"),
							"desc" => esc_html__("Link URL from this icon (if not empty)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Image
				"trx_image" => array(
					"title" => esc_html__("Image", "trx_utils"),
					"desc" => esc_html__("Insert image into your post (page)", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for image file", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
							)
						),
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Image title (if need)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon before title",  'trx_utils'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => esc_html__("Float image", "trx_utils"),
							"desc" => esc_html__("Float image to left or right side", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['float']
						), 
						"shape" => array(
							"title" => esc_html__("Image Shape", "trx_utils"),
							"desc" => esc_html__("Shape of the image: square (rectangle) or round", "trx_utils"),
							"value" => "square",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"square" => esc_html__('Square', 'trx_utils'),
								"round" => esc_html__('Round', 'trx_utils')
							)
						), 
						"link" => array(
							"title" => esc_html__("Link", "trx_utils"),
							"desc" => esc_html__("The link URL from the image", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Infobox
				"trx_infobox" => array(
					"title" => esc_html__("Infobox", "trx_utils"),
					"desc" => esc_html__("Insert infobox into your post (page)", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "trx_utils"),
							"desc" => esc_html__("Infobox style", "trx_utils"),
							"value" => "regular",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'regular' => esc_html__('Regular', 'trx_utils'),
								'info' => esc_html__('Info', 'trx_utils'),
								'success' => esc_html__('Success', 'trx_utils'),
								'error' => esc_html__('Error', 'trx_utils')
							)
						),
						"closeable" => array(
							"title" => esc_html__("Closeable box", "trx_utils"),
							"desc" => esc_html__("Create closeable box (with close button)", "trx_utils"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"icon" => array(
							"title" => esc_html__("Custom icon",  'trx_utils'),
							"desc" => esc_html__('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Text color", "trx_utils"),
							"desc" => esc_html__("Any color for text and headers", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "trx_utils"),
							"desc" => esc_html__("Any background color for this infobox", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"_content_" => array(
							"title" => esc_html__("Infobox content", "trx_utils"),
							"desc" => esc_html__("Content for infobox", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Line
				"trx_line" => array(
					"title" => esc_html__("Line", "trx_utils"),
					"desc" => esc_html__("Insert Line into your post (page)", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "trx_utils"),
							"desc" => esc_html__("Line style", "trx_utils"),
							"value" => "solid",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'solid' => esc_html__('Solid', 'trx_utils'),
								'dashed' => esc_html__('Dashed', 'trx_utils'),
								'dotted' => esc_html__('Dotted', 'trx_utils'),
								'double' => esc_html__('Double', 'trx_utils')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", "trx_utils"),
							"desc" => esc_html__("Line color", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// List
				"trx_list" => array(
					"title" => esc_html__("List", "trx_utils"),
					"desc" => esc_html__("List items with specific bullets", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Bullet's style", "trx_utils"),
							"desc" => esc_html__("Bullet's style for each list item", "trx_utils"),
							"value" => "ul",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['list_styles']
						), 
						"color" => array(
							"title" => esc_html__("Color", "trx_utils"),
							"desc" => esc_html__("List items color", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('List icon',  'trx_utils'),
							"desc" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)",  'trx_utils'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"icon_color" => array(
							"title" => esc_html__("Icon color", "trx_utils"),
							"desc" => esc_html__("List icons color", "trx_utils"),
							"value" => "",
							"dependency" => array(
								'style' => array('iconed')
							),
							"type" => "color"
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_list_item",
						"title" => esc_html__("Item", "trx_utils"),
						"desc" => esc_html__("List item with specific bullet", "trx_utils"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"_content_" => array(
								"title" => esc_html__("List item content", "trx_utils"),
								"desc" => esc_html__("Current list item content", "trx_utils"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"title" => array(
								"title" => esc_html__("List item title", "trx_utils"),
								"desc" => esc_html__("Current list item title (show it as tooltip)", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"color" => array(
								"title" => esc_html__("Color", "trx_utils"),
								"desc" => esc_html__("Text color for this item", "trx_utils"),
								"value" => "",
								"type" => "color"
							),
							"icon" => array(
								"title" => esc_html__('List icon',  'trx_utils'),
								"desc" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)",  'trx_utils'),
								"value" => "",
								"type" => "icons",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
							),
							"icon_color" => array(
								"title" => esc_html__("Icon color", "trx_utils"),
								"desc" => esc_html__("Icon color for this item", "trx_utils"),
								"value" => "",
								"type" => "color"
							),
							"link" => array(
								"title" => esc_html__("Link URL", "trx_utils"),
								"desc" => esc_html__("Link URL for the current list item", "trx_utils"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"target" => array(
								"title" => esc_html__("Link target", "trx_utils"),
								"desc" => esc_html__("Link target for the current list item", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
				// Number
				"trx_number" => array(
					"title" => esc_html__("Number", "trx_utils"),
					"desc" => esc_html__("Insert number or any word as set separate characters", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"value" => array(
							"title" => esc_html__("Value", "trx_utils"),
							"desc" => esc_html__("Number or any word", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", "trx_utils"),
							"desc" => esc_html__("Select block alignment", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Parallax
				"trx_parallax" => array(
					"title" => esc_html__("Parallax", "trx_utils"),
					"desc" => esc_html__("Create the parallax container (with asinc background image)", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"gap" => array(
							"title" => esc_html__("Create gap", "trx_utils"),
							"desc" => esc_html__("Create gap around parallax container", "trx_utils"),
							"value" => "no",
							"size" => "small",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						), 
						"dir" => array(
							"title" => esc_html__("Dir", "trx_utils"),
							"desc" => esc_html__("Scroll direction for the parallax background", "trx_utils"),
							"value" => "up",
							"size" => "medium",
							"options" => array(
								'up' => esc_html__('Up', 'trx_utils'),
								'down' => esc_html__('Down', 'trx_utils')
							),
							"type" => "switch"
						), 
						"speed" => array(
							"title" => esc_html__("Speed", "trx_utils"),
							"desc" => esc_html__("Image motion speed (from 0.0 to 1.0)", "trx_utils"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0.3",
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "trx_utils"),
							"desc" => esc_html__("Select color scheme for this block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => esc_html__("Text color", "trx_utils"),
							"desc" => esc_html__("Select color for text object inside parallax block", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "trx_utils"),
							"desc" => esc_html__("Select color for parallax background", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the parallax background", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image_x" => array(
							"title" => esc_html__("Image X position", "trx_utils"),
							"desc" => esc_html__("Image horizontal position (as background of the parallax block) - in percent", "trx_utils"),
							"min" => "0",
							"max" => "100",
							"value" => "50",
							"type" => "spinner"
						),
						"bg_video" => array(
							"title" => esc_html__("Video background", "trx_utils"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site to show it as parallax background", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose video', 'trx_utils'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose video file', 'trx_utils'),
									'update' => esc_html__('Select video file', 'trx_utils')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"bg_video_ratio" => array(
							"title" => esc_html__("Video ratio", "trx_utils"),
							"desc" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "trx_utils"),
							"value" => "16:9",
							"type" => "text"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "trx_utils"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "trx_utils"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "trx_utils"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"_content_" => array(
							"title" => esc_html__("Content", "trx_utils"),
							"desc" => esc_html__("Content for the parallax container", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Popup
				"trx_popup" => array(
					"title" => esc_html__("Popup window", "trx_utils"),
					"desc" => esc_html__("Container for any html-block with desired class and style for popup window", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Container content", "trx_utils"),
							"desc" => esc_html__("Content for section container", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Price
				"trx_price" => array(
					"title" => esc_html__("Price", "trx_utils"),
					"desc" => esc_html__("Insert price with decoration", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"money" => array(
							"title" => esc_html__("Money", "trx_utils"),
							"desc" => esc_html__("Money value (dot or comma separated)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => esc_html__("Currency", "trx_utils"),
							"desc" => esc_html__("Currency character", "trx_utils"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => esc_html__("Period", "trx_utils"),
							"desc" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Align price to left or right side", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['float']
						), 
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Price block
				"trx_price_block" => array(
					"title" => esc_html__("Price block", "trx_utils"),
					"desc" => esc_html__("Insert price block with title, price and description", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Block title", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Link URL", "trx_utils"),
							"desc" => esc_html__("URL for link from button (at bottom of the block)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"link_text" => array(
							"title" => esc_html__("Link text", "trx_utils"),
							"desc" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon",  'trx_utils'),
							"desc" => esc_html__('Select icon from Fontello icons set (placed before/instead price)',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"money" => array(
							"title" => esc_html__("Money", "trx_utils"),
							"desc" => esc_html__("Money value (dot or comma separated)", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => esc_html__("Currency", "trx_utils"),
							"desc" => esc_html__("Currency character", "trx_utils"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => esc_html__("Period", "trx_utils"),
							"desc" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "trx_utils"),
							"desc" => esc_html__("Select color scheme for this block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Align price to left or right side", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['float']
						), 
						"_content_" => array(
							"title" => esc_html__("Description", "trx_utils"),
							"desc" => esc_html__("Description for this price block", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Quote
				"trx_quote" => array(
					"title" => esc_html__("Quote", "trx_utils"),
					"desc" => esc_html__("Quote text", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"cite" => array(
							"title" => esc_html__("Quote cite", "trx_utils"),
							"desc" => esc_html__("URL for quote cite", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"title" => array(
							"title" => esc_html__("Title (author)", "trx_utils"),
							"desc" => esc_html__("Quote title (author name)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Style Quote", "trx_utils"),
							"desc" => esc_html__("Select a transparent background if you want to write a quote on the image", "trx_utils"),
							"value" => "",
							"options" => array(
                                ""              => esc_html__('Default', 'trx_utils'),
                                "transparent"   => esc_html__('Transparent', 'trx_utils')
                            ),
							"type" => "checklist"
						),
                    	"_content_" => array(
							"title" => esc_html__("Quote content", "trx_utils"),
							"desc" => esc_html__("Quote content", "trx_utils"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
                        "bg_color" => array(
                            "title" => esc_html__("Background color", "trx_utils"),
                            "desc" => esc_html__("Any background color for this section", "trx_utils"),
                            "dependency" => array(
                                'style' => array('transparent')
                            ),
                            "value" => "",
                            "type" => "color"
                        ),
                        "bg_image" => array(
                            "title" => esc_html__("Background image URL", "trx_utils"),
                            "desc" => esc_html__("Select or upload image or write URL from other site for the background", "trx_utils"),
                            "dependency" => array(
                                'style' => array('transparent')
                            ),
                            "readonly" => false,
                            "value" => "",
                            "type" => "media"
                        ),
                        "bg_overlay" => array(
                            "title" => esc_html__("Overlay", "trx_utils"),
                            "desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
                            "dependency" => array(
                                'style' => array('transparent')
                            ),
                            "min" => "0",
                            "max" => "1",
                            "step" => "0.1",
                            "value" => "0",
                            "type" => "spinner"
                        ),
						"width" => grace_church_shortcodes_width(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Reviews
				"trx_reviews" => array(
					"title" => esc_html__("Reviews", "trx_utils"),
					"desc" => esc_html__("Insert reviews block in the single post", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Align counter to left, center or right", "trx_utils"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						), 
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Search
				"trx_search" => array(
					"title" => esc_html__("Search", "trx_utils"),
					"desc" => esc_html__("Show search form", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "trx_utils"),
							"desc" => esc_html__("Select style to display search field", "trx_utils"),
							"value" => "regular",
							"options" => array(
								"regular" => esc_html__('Regular', 'trx_utils'),
								"rounded" => esc_html__('Rounded', 'trx_utils')
							),
							"type" => "checklist"
						),
						"state" => array(
							"title" => esc_html__("State", "trx_utils"),
							"desc" => esc_html__("Select search field initial state", "trx_utils"),
							"value" => "fixed",
							"options" => array(
								"fixed"  => esc_html__('Fixed',  'trx_utils'),
								"opened" => esc_html__('Opened', 'trx_utils'),
								"closed" => esc_html__('Closed', 'trx_utils')
							),
							"type" => "checklist"
						),
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Title (placeholder) for the search field", "trx_utils"),
							"value" => esc_html__("Search &hellip;", 'trx_utils'),
							"type" => "text"
						),
						"ajax" => array(
							"title" => esc_html__("AJAX", "trx_utils"),
							"desc" => esc_html__("Search via AJAX or reload page", "trx_utils"),
							"value" => "yes",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Section
				"trx_section" => array(
					"title" => esc_html__("Section container", "trx_utils"),
					"desc" => esc_html__("Container for any block with desired class and style", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", "trx_utils"),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", "trx_utils"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", "trx_utils"),
							"desc" => esc_html__("Select block alignment", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", "trx_utils"),
							"desc" => esc_html__("Select width for columns emulation", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", "trx_utils"),
							"desc" => esc_html__("Use pan effect to show section content", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "trx_utils"),
							"desc" => esc_html__("Use scroller to show section content", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll and Pan direction", "trx_utils"),
							"desc" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "trx_utils"),
							"dependency" => array(
								'pan' => array('yes'),
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", "trx_utils"),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", "trx_utils"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "trx_utils"),
							"desc" => esc_html__("Select color scheme for this block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => esc_html__("Fore color", "trx_utils"),
							"desc" => esc_html__("Any color for objects in this section", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "trx_utils"),
							"desc" => esc_html__("Any background color for this section", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "trx_utils"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "trx_utils"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "trx_utils"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "trx_utils"),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "trx_utils"),
							"desc" => esc_html__("Font weight of the text", "trx_utils"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'trx_utils'),
								'300' => esc_html__('Light (300)', 'trx_utils'),
								'400' => esc_html__('Normal (400)', 'trx_utils'),
								'700' => esc_html__('Bold (700)', 'trx_utils')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "trx_utils"),
							"desc" => esc_html__("Content for section container", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Skills
				"trx_skills" => array(
					"title" => esc_html__("Skills", "trx_utils"),
					"desc" => esc_html__("Insert skills diagramm in your page (post)", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"max_value" => array(
							"title" => esc_html__("Max value", "trx_utils"),
							"desc" => esc_html__("Max value for skills items", "trx_utils"),
							"value" => 100,
							"min" => 1,
							"type" => "spinner"
						),
						"type" => array(
							"title" => esc_html__("Skills type", "trx_utils"),
							"desc" => esc_html__("Select type of skills block", "trx_utils"),
							"value" => "bar",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'bar' => esc_html__('Bar', 'trx_utils'),
								'pie' => esc_html__('Pie chart', 'trx_utils'),
								'counter' => esc_html__('Counter', 'trx_utils'),
								'arc' => esc_html__('Arc', 'trx_utils')
							)
						), 
						"layout" => array(
							"title" => esc_html__("Skills layout", "trx_utils"),
							"desc" => esc_html__("Select layout of skills block", "trx_utils"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "rows",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'rows' => esc_html__('Rows', 'trx_utils'),
								'columns' => esc_html__('Columns', 'trx_utils')
							)
						),
						"dir" => array(
							"title" => esc_html__("Direction", "trx_utils"),
							"desc" => esc_html__("Select direction of skills block", "trx_utils"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "horizontal",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['dir']
						), 
						"style" => array(
							"title" => esc_html__("Counters style", "trx_utils"),
							"desc" => esc_html__("Select style of skills items (only for type=counter)", "trx_utils"),
							"dependency" => array(
								'type' => array('counter')
							),
							"value" => 1,
							"options" => grace_church_get_list_styles(1, 4),
							"type" => "checklist"
						), 
						// "columns" - autodetect, not set manual
						"color" => array(
							"title" => esc_html__("Skills items color", "trx_utils"),
							"desc" => esc_html__("Color for all skills items", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "trx_utils"),
							"desc" => esc_html__("Background color for all skills items (only for type=pie)", "trx_utils"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"border_color" => array(
							"title" => esc_html__("Border color", "trx_utils"),
							"desc" => esc_html__("Border color for all skills items (only for type=pie)", "trx_utils"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => esc_html__("Align skills block", "trx_utils"),
							"desc" => esc_html__("Align skills block to left or right side", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['float']
						), 
						"arc_caption" => array(
							"title" => esc_html__("Arc Caption", "trx_utils"),
							"desc" => esc_html__("Arc caption - text in the center of the diagram", "trx_utils"),
							"dependency" => array(
								'type' => array('arc')
							),
							"value" => "",
							"type" => "text"
						),
						"pie_compact" => array(
							"title" => esc_html__("Pie compact", "trx_utils"),
							"desc" => esc_html__("Show all skills in one diagram or as separate diagrams", "trx_utils"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"pie_cutout" => array(
							"title" => esc_html__("Pie cutout", "trx_utils"),
							"desc" => esc_html__("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "trx_utils"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => 0,
							"min" => 0,
							"max" => 99,
							"type" => "spinner"
						),
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Title for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "trx_utils"),
							"desc" => esc_html__("Subtitle for the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "trx_utils"),
							"desc" => esc_html__("Short description for the block", "trx_utils"),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "trx_utils"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "trx_utils"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_skills_item",
						"title" => esc_html__("Skill", "trx_utils"),
						"desc" => esc_html__("Skills item", "trx_utils"),
						"container" => false,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", "trx_utils"),
								"desc" => esc_html__("Current skills item title", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Value", "trx_utils"),
								"desc" => esc_html__("Current skills level", "trx_utils"),
								"value" => 50,
								"min" => 0,
								"step" => 1,
								"type" => "spinner"
							),
							"color" => array(
								"title" => esc_html__("Color", "trx_utils"),
								"desc" => esc_html__("Current skills item color", "trx_utils"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", "trx_utils"),
								"desc" => esc_html__("Current skills item background color (only for type=pie)", "trx_utils"),
								"value" => "",
								"type" => "color"
							),
							"border_color" => array(
								"title" => esc_html__("Border color", "trx_utils"),
								"desc" => esc_html__("Current skills item border color (only for type=pie)", "trx_utils"),
								"value" => "",
								"type" => "color"
							),
							"style" => array(
								"title" => esc_html__("Counter style", "trx_utils"),
								"desc" => esc_html__("Select style for the current skills item (only for type=counter)", "trx_utils"),
								"value" => 1,
								"options" => grace_church_get_list_styles(1, 4),
								"type" => "checklist"
							), 
							"icon" => array(
								"title" => esc_html__("Counter icon",  'trx_utils'),
								"desc" => esc_html__('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'trx_utils'),
								"value" => "",
								"type" => "icons",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Slider
				"trx_slider" => array(
					"title" => esc_html__("Slider", "trx_utils"),
					"desc" => esc_html__("Insert slider into your post (page)", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array_merge(array(
						"engine" => array(
							"title" => esc_html__("Slider engine", "trx_utils"),
							"desc" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "trx_utils"),
							"value" => "swiper",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['sliders']
						),
						"align" => array(
							"title" => esc_html__("Float slider", "trx_utils"),
							"desc" => esc_html__("Float slider to left or right side", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['float']
						),
						"custom" => array(
							"title" => esc_html__("Custom slides", "trx_utils"),
							"desc" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						)
						),
						grace_church_exists_revslider() ? array(
						"alias" => array(
							"title" => esc_html__("Revolution slider alias", "trx_utils"),
							"desc" => esc_html__("Select Revolution slider to display", "trx_utils"),
							"dependency" => array(
								'engine' => array('revo')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['revo_sliders']
						)) : array(), array(
						"cat" => array(
							"title" => esc_html__("Swiper: Category list", "trx_utils"),
							"desc" => esc_html__("Select category to show post's images. If empty - select posts from any category or from IDs list", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $GRACE_CHURCH_GLOBALS['sc_params']['categories'])
						),
						"count" => array(
							"title" => esc_html__("Swiper: Number of posts", "trx_utils"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Swiper: Offset before select posts", "trx_utils"),
							"desc" => esc_html__("Skip posts before select next part.", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Swiper: Post order by", "trx_utils"),
							"desc" => esc_html__("Select desired posts sorting method", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "date",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Swiper: Post order", "trx_utils"),
							"desc" => esc_html__("Select desired posts order", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Swiper: Post IDs list", "trx_utils"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => esc_html__("Swiper: Show slider controls", "trx_utils"),
							"desc" => esc_html__("Show arrows inside slider", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"pagination" => array(
							"title" => esc_html__("Swiper: Show slider pagination", "trx_utils"),
							"desc" => esc_html__("Show bullets for switch slides", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								'no'   => esc_html__('None', 'trx_utils'),
								'yes'  => esc_html__('Dots', 'trx_utils'),
								'full' => esc_html__('Side Titles', 'trx_utils'),
								'over' => esc_html__('Over Titles', 'trx_utils')
							)
						),
						"titles" => array(
							"title" => esc_html__("Swiper: Show titles section", "trx_utils"),
							"desc" => esc_html__("Show section with post's title and short post's description", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								"no"    => esc_html__('Not show', 'trx_utils'),
								"slide" => esc_html__('Show/Hide info', 'trx_utils'),
								"fixed" => esc_html__('Fixed info', 'trx_utils')
							)
						),
						"descriptions" => array(
							"title" => esc_html__("Swiper: Post descriptions", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"desc" => esc_html__("Show post's excerpt max length (characters)", "trx_utils"),
							"value" => 0,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"links" => array(
							"title" => esc_html__("Swiper: Post's title as link", "trx_utils"),
							"desc" => esc_html__("Make links from post's titles", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"crop" => array(
							"title" => esc_html__("Swiper: Crop images", "trx_utils"),
							"desc" => esc_html__("Crop images in each slide or live it unchanged", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"autoheight" => array(
							"title" => esc_html__("Swiper: Autoheight", "trx_utils"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"slides_per_view" => array(
							"title" => esc_html__("Swiper: Slides per view", "trx_utils"),
							"desc" => esc_html__("Slides per view showed in this slider", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slides_space" => array(
							"title" => esc_html__("Swiper: Space between slides", "trx_utils"),
							"desc" => esc_html__("Size of space (in px) between slides", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Swiper: Slides change interval", "trx_utils"),
							"desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 5000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)),
					"children" => array(
						"name" => "trx_slider_item",
						"title" => esc_html__("Slide", "trx_utils"),
						"desc" => esc_html__("Slider item", "trx_utils"),
						"container" => false,
						"params" => array(
							"src" => array(
								"title" => esc_html__("URL (source) for image file", "trx_utils"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the current slide", "trx_utils"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Socials
				"trx_socials" => array(
					"title" => esc_html__("Social icons", "trx_utils"),
					"desc" => esc_html__("List of social icons (with hovers)", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"type" => array(
							"title" => esc_html__("Icon's type", "trx_utils"),
							"desc" => esc_html__("Type of the icons - images or font icons", "trx_utils"),
							"value" => grace_church_get_theme_setting('socials_type'),
							"options" => array(
								'icons' => esc_html__('Icons', 'trx_utils'),
								'images' => esc_html__('Images', 'trx_utils')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => esc_html__("Icon's size", "trx_utils"),
							"desc" => esc_html__("Size of the icons", "trx_utils"),
							"value" => "small",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['sizes'],
							"type" => "checklist"
						), 
						"shape" => array(
							"title" => esc_html__("Icon's shape", "trx_utils"),
							"desc" => esc_html__("Shape of the icons", "trx_utils"),
							"value" => "square",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['shapes'],
							"type" => "checklist"
						), 
						"socials" => array(
							"title" => esc_html__("Manual socials list", "trx_utils"),
							"desc" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => esc_html__("Custom socials", "trx_utils"),
							"desc" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_social_item",
						"title" => esc_html__("Custom social item", "trx_utils"),
						"desc" => esc_html__("Custom social item: name, profile url and icon url", "trx_utils"),
						"decorate" => false,
						"container" => false,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Social name", "trx_utils"),
								"desc" => esc_html__("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"url" => array(
								"title" => esc_html__("Your profile URL", "trx_utils"),
								"desc" => esc_html__("URL of your profile in specified social network", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("URL (source) for icon file", "trx_utils"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the current social icon", "trx_utils"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							)
						)
					)
				),
			
			
			
			
				// Table
				"trx_table" => array(
					"title" => esc_html__("Table", "trx_utils"),
					"desc" => esc_html__("Insert a table into post (page). ", "trx_utils"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Content alignment", "trx_utils"),
							"desc" => esc_html__("Select alignment for each table cell", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"_content_" => array(
							"title" => esc_html__("Table content", "trx_utils"),
							"desc" => esc_html__("Content, created with any table-generator", "trx_utils"),
							"divider" => true,
							"rows" => 8,
							"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
							"type" => "textarea"
						),
						"width" => grace_church_shortcodes_width(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Tabs
				"trx_tabs" => array(
					"title" => esc_html__("Tabs", "trx_utils"),
					"desc" => esc_html__("Insert tabs in your page (post)", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Tabs style", "trx_utils"),
							"desc" => esc_html__("Select style for tabs items", "trx_utils"),
							"value" => 1,
							"options" => grace_church_get_list_styles(1, 2),
							"type" => "radio"
						),
						"initial" => array(
							"title" => esc_html__("Initially opened tab", "trx_utils"),
							"desc" => esc_html__("Number of initially opened tab", "trx_utils"),
							"divider" => true,
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "trx_utils"),
							"desc" => esc_html__("Use scroller to show tab content (height parameter required)", "trx_utils"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_tab",
						"title" => esc_html__("Tab", "trx_utils"),
						"desc" => esc_html__("Tab item", "trx_utils"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Tab title", "trx_utils"),
								"desc" => esc_html__("Current tab title", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Tab content", "trx_utils"),
								"desc" => esc_html__("Current tab content", "trx_utils"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			


				
			
			
				// Title
				"trx_title" => array(
					"title" => esc_html__("Title", "trx_utils"),
					"desc" => esc_html__("Create header tag (1-6 level) with many styles", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Title content", "trx_utils"),
							"desc" => esc_html__("Title content", "trx_utils"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"type" => array(
							"title" => esc_html__("Title type", "trx_utils"),
							"desc" => esc_html__("Title type (header level)", "trx_utils"),
							"divider" => true,
							"value" => "1",
							"type" => "select",
							"options" => array(
								'1' => esc_html__('Header 1', 'trx_utils'),
								'2' => esc_html__('Header 2', 'trx_utils'),
								'3' => esc_html__('Header 3', 'trx_utils'),
								'4' => esc_html__('Header 4', 'trx_utils'),
								'5' => esc_html__('Header 5', 'trx_utils'),
								'6' => esc_html__('Header 6', 'trx_utils'),
							)
						),
						"style" => array(
							"title" => esc_html__("Title style", "trx_utils"),
							"desc" => esc_html__("Title style", "trx_utils"),
							"value" => "regular",
							"type" => "select",
							"options" => array(
								'regular' => esc_html__('Regular', 'trx_utils'),
								'underline' => esc_html__('Underline', 'trx_utils'),
								'divider' => esc_html__('Divider', 'trx_utils'),
								'iconed' => esc_html__('With icon (image)', 'trx_utils')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Title text alignment", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						), 
						"font_size" => array(
							"title" => esc_html__("Font_size", "trx_utils"),
							"desc" => esc_html__("Custom font size. If empty - use theme default", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "trx_utils"),
							"desc" => esc_html__("Custom font weight. If empty or inherit - use theme default", "trx_utils"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'inherit' => esc_html__('Default', 'trx_utils'),
								'100' => esc_html__('Thin (100)', 'trx_utils'),
								'300' => esc_html__('Light (300)', 'trx_utils'),
								'400' => esc_html__('Normal (400)', 'trx_utils'),
								'600' => esc_html__('Semibold (600)', 'trx_utils'),
								'700' => esc_html__('Bold (700)', 'trx_utils'),
								'900' => esc_html__('Black (900)', 'trx_utils')
							)
						),
						"color" => array(
							"title" => esc_html__("Title color", "trx_utils"),
							"desc" => esc_html__("Select color for the title", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('Title font icon',  'trx_utils'),
							"desc" => esc_html__("Select font icon for the title from Fontello icons set (if style=iconed)",  'trx_utils'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"image" => array(
							"title" => esc_html__('or image icon',  'trx_utils'),
							"desc" => esc_html__("Select image icon for the title instead icon above (if style=iconed)",  'trx_utils'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "images",
							"size" => "small",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['images']
						),
						"picture" => array(
							"title" => esc_html__('or URL for image file', "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "trx_utils"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"image_size" => array(
							"title" => esc_html__('Image (picture) size', "trx_utils"),
							"desc" => esc_html__("Select image (picture) size (if style='iconed')", "trx_utils"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								'small' => esc_html__('Small', 'trx_utils'),
								'medium' => esc_html__('Medium', 'trx_utils'),
								'large' => esc_html__('Large', 'trx_utils')
							)
						),
						"position" => array(
							"title" => esc_html__('Icon (image) position', "trx_utils"),
							"desc" => esc_html__("Select icon (image) position (if style=iconed)", "trx_utils"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "left",
							"type" => "checklist",
							"options" => array(
								'top' => esc_html__('Top', 'trx_utils'),
								'left' => esc_html__('Left', 'trx_utils')
							)
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Toggles
				"trx_toggles" => array(
					"title" => esc_html__("Toggles", "trx_utils"),
					"desc" => esc_html__("Toggles items", "trx_utils"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Toggles style", "trx_utils"),
							"desc" => esc_html__("Select style for display toggles", "trx_utils"),
							"value" => 1,
							"options" => grace_church_get_list_styles(1, 2),
							"type" => "radio"
						),
						"counter" => array(
							"title" => esc_html__("Counter", "trx_utils"),
							"desc" => esc_html__("Display counter before each toggles title", "trx_utils"),
							"value" => "off",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['on_off']
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'trx_utils'),
							"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'trx_utils'),
							"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'trx_utils'),
							"value" => "",
							"type" => "icons",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
						),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_toggles_item",
						"title" => esc_html__("Toggles item", "trx_utils"),
						"desc" => esc_html__("Toggles item", "trx_utils"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Toggles item title", "trx_utils"),
								"desc" => esc_html__("Title for current toggles item", "trx_utils"),
								"value" => "",
								"type" => "text"
							),
							"open" => array(
								"title" => esc_html__("Open on show", "trx_utils"),
								"desc" => esc_html__("Open current toggles item on show", "trx_utils"),
								"value" => "no",
								"type" => "switch",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
							),
							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'trx_utils'),
								"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'trx_utils'),
								"value" => "",
								"type" => "icons",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'trx_utils'),
								"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'trx_utils'),
								"value" => "",
								"type" => "icons",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Toggles item content", "trx_utils"),
								"desc" => esc_html__("Current toggles item content", "trx_utils"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
							"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
							"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Tooltip
				"trx_tooltip" => array(
					"title" => esc_html__("Tooltip", "trx_utils"),
					"desc" => esc_html__("Create tooltip for selected text", "trx_utils"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "trx_utils"),
							"desc" => esc_html__("Tooltip title (required)", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Tipped content", "trx_utils"),
							"desc" => esc_html__("Highlighted content with tooltip", "trx_utils"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Twitter
				"trx_twitter" => array(
					"title" => esc_html__("Twitter", "trx_utils"),
					"desc" => esc_html__("Insert twitter feed into post (page)", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"user" => array(
							"title" => esc_html__("Twitter Username", "trx_utils"),
							"desc" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"consumer_key" => array(
							"title" => esc_html__("Consumer Key", "trx_utils"),
							"desc" => esc_html__("Consumer Key from the twitter account", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"consumer_secret" => array(
							"title" => esc_html__("Consumer Secret", "trx_utils"),
							"desc" => esc_html__("Consumer Secret from the twitter account", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"token_key" => array(
							"title" => esc_html__("Token Key", "trx_utils"),
							"desc" => esc_html__("Token Key from the twitter account", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"token_secret" => array(
							"title" => esc_html__("Token Secret", "trx_utils"),
							"desc" => esc_html__("Token Secret from the twitter account", "trx_utils"),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => esc_html__("Tweets number", "trx_utils"),
							"desc" => esc_html__("Tweets number to show", "trx_utils"),
							"divider" => true,
							"value" => 3,
							"max" => 20,
							"min" => 1,
							"type" => "spinner"
						),
						"controls" => array(
							"title" => esc_html__("Show arrows", "trx_utils"),
							"desc" => esc_html__("Show control buttons", "trx_utils"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => esc_html__("Tweets change interval", "trx_utils"),
							"desc" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "trx_utils"),
							"desc" => esc_html__("Alignment of the tweets block", "trx_utils"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "trx_utils"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "trx_utils"),
							"desc" => esc_html__("Select color scheme for this block", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "trx_utils"),
							"desc" => esc_html__("Any background color for this section", "trx_utils"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "trx_utils"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "trx_utils"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "trx_utils"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "trx_utils"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Video
				"trx_video" => array(
					"title" => esc_html__("Video", "trx_utils"),
					"desc" => esc_html__("Insert video player", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for video file", "trx_utils"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose video', 'trx_utils'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose video file', 'trx_utils'),
									'update' => esc_html__('Select video file', 'trx_utils')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"ratio" => array(
							"title" => esc_html__("Ratio", "trx_utils"),
							"desc" => esc_html__("Ratio of the video", "trx_utils"),
							"value" => "16:9",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"16:9" => esc_html__("16:9", 'trx_utils'),
								"4:3" => esc_html__("4:3", 'trx_utils')
							)
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay video", "trx_utils"),
							"desc" => esc_html__("Autoplay video on page load", "trx_utils"),
							"value" => "off",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", "trx_utils"),
							"desc" => esc_html__("Select block alignment", "trx_utils"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"image" => array(
							"title" => esc_html__("Cover image", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for video preview", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "trx_utils"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", "trx_utils"),
							"desc" => esc_html__("Top offset (padding) inside background image to video block (in percent). For example: 3%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", "trx_utils"),
							"desc" => esc_html__("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", "trx_utils"),
							"desc" => esc_html__("Left offset (padding) inside background image to video block (in percent). For example: 20%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", "trx_utils"),
							"desc" => esc_html__("Right offset (padding) inside background image to video block (in percent). For example: 12%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Zoom
				"trx_zoom" => array(
					"title" => esc_html__("Zoom", "trx_utils"),
					"desc" => esc_html__("Insert the image with zoom/lens effect", "trx_utils"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"effect" => array(
							"title" => esc_html__("Effect", "trx_utils"),
							"desc" => esc_html__("Select effect to display overlapping image", "trx_utils"),
							"value" => "lens",
							"size" => "medium",
							"type" => "switch",
							"options" => array(
								"lens" => esc_html__('Lens', 'trx_utils'),
								"zoom" => esc_html__('Zoom', 'trx_utils')
							)
						),
						"url" => array(
							"title" => esc_html__("Main image", "trx_utils"),
							"desc" => esc_html__("Select or upload main image", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"over" => array(
							"title" => esc_html__("Overlaping image", "trx_utils"),
							"desc" => esc_html__("Select or upload overlaping image", "trx_utils"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"align" => array(
							"title" => esc_html__("Float zoom", "trx_utils"),
							"desc" => esc_html__("Float zoom to left or right side", "trx_utils"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['float']
						), 
						"bg_image" => array(
							"title" => esc_html__("Background image", "trx_utils"),
							"desc" => esc_html__("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", "trx_utils"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", "trx_utils"),
							"desc" => esc_html__("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", "trx_utils"),
							"desc" => esc_html__("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", "trx_utils"),
							"desc" => esc_html__("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", "trx_utils"),
							"desc" => esc_html__("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", "trx_utils"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => grace_church_shortcodes_width(),
						"height" => grace_church_shortcodes_height(),
						"top" => $GRACE_CHURCH_GLOBALS['sc_params']['top'],
						"bottom" => $GRACE_CHURCH_GLOBALS['sc_params']['bottom'],
						"left" => $GRACE_CHURCH_GLOBALS['sc_params']['left'],
						"right" => $GRACE_CHURCH_GLOBALS['sc_params']['right'],
						"id" => $GRACE_CHURCH_GLOBALS['sc_params']['id'],
						"class" => $GRACE_CHURCH_GLOBALS['sc_params']['class'],
						"animation" => $GRACE_CHURCH_GLOBALS['sc_params']['animation'],
						"css" => $GRACE_CHURCH_GLOBALS['sc_params']['css']
					)
				)
			);
			
			do_action('grace_church_action_shortcodes_list');

		}
	}
}
?>