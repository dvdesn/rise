<?php
/**
 * Grace-Church Framework: Services post type settings
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Theme init
if (!function_exists('grace_church_services_theme_setup')) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_services_theme_setup' );
	function grace_church_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('grace_church_filter_get_blog_type',			'grace_church_services_get_blog_type', 9, 2);
		add_filter('grace_church_filter_get_blog_title',		'grace_church_services_get_blog_title', 9, 2);
		add_filter('grace_church_filter_get_current_taxonomy',	'grace_church_services_get_current_taxonomy', 9, 2);
		add_filter('grace_church_filter_is_taxonomy',			'grace_church_services_is_taxonomy', 9, 2);
		add_filter('grace_church_filter_get_stream_page_title',	'grace_church_services_get_stream_page_title', 9, 2);
		add_filter('grace_church_filter_get_stream_page_link',	'grace_church_services_get_stream_page_link', 9, 2);
		add_filter('grace_church_filter_get_stream_page_id',	'grace_church_services_get_stream_page_id', 9, 2);
		add_filter('grace_church_filter_query_add_filters',		'grace_church_services_query_add_filters', 9, 2);
		add_filter('grace_church_filter_detect_inheritance_key','grace_church_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (grace_church_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'grace_church_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'grace_church_post_fill_options_column', 9, 2);
		}

		// Add shortcodes [trx_services] and [trx_services_item]
		add_action('grace_church_action_shortcodes_list',		'grace_church_services_reg_shortcodes');
		add_action('grace_church_action_shortcodes_list_vc',	'grace_church_services_reg_shortcodes_vc');
		
		if (function_exists('grace_church_require_data')) {
			// Prepare type "Team"
			grace_church_require_data( 'post_type', 'services', array(
				'label'               => esc_html__( 'Service item', 'grace-church' ),
				'description'         => esc_html__( 'Service Description', 'grace-church' ),
				'labels'              => array(
					'name'                => _x( 'Services', 'Post Type General Name', 'grace-church' ),
					'singular_name'       => _x( 'Service item', 'Post Type Singular Name', 'grace-church' ),
					'menu_name'           => esc_html__( 'Servicios', 'grace-church' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'grace-church' ),
					'all_items'           => esc_html__( 'All Services', 'grace-church' ),
					'view_item'           => esc_html__( 'View Item', 'grace-church' ),
					'add_new_item'        => esc_html__( 'Add New Service', 'grace-church' ),
					'add_new'             => esc_html__( 'Add New', 'grace-church' ),
					'edit_item'           => esc_html__( 'Edit Item', 'grace-church' ),
					'update_item'         => esc_html__( 'Update Item', 'grace-church' ),
					'search_items'        => esc_html__( 'Search Item', 'grace-church' ),
					'not_found'           => esc_html__( 'Not found', 'grace-church' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'grace-church' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-info',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 25,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
				)
			);
			
			// Prepare taxonomy for team
			grace_church_require_data( 'taxonomy', 'services_group', array(
				'post_type'			=> array( 'services' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Services Group', 'taxonomy general name', 'grace-church' ),
					'singular_name'     => _x( 'Group', 'taxonomy singular name', 'grace-church' ),
					'search_items'      => esc_html__( 'Search Groups', 'grace-church' ),
					'all_items'         => esc_html__( 'All Groups', 'grace-church' ),
					'parent_item'       => esc_html__( 'Parent Group', 'grace-church' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'grace-church' ),
					'edit_item'         => esc_html__( 'Edit Group', 'grace-church' ),
					'update_item'       => esc_html__( 'Update Group', 'grace-church' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'grace-church' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'grace-church' ),
					'menu_name'         => esc_html__( 'Services Group', 'grace-church' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'services_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'grace_church_services_settings_theme_setup2' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_services_settings_theme_setup2', 3 );
	function grace_church_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		grace_church_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-services',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'page'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'grace_church_is_services_page' ) ) {
	function grace_church_is_services_page() {
		return get_query_var('post_type')=='services' || is_tax('services_group') || (is_page() && grace_church_get_template_page_id('blog-services')==get_the_ID());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'grace_church_services_detect_inheritance_key' ) ) {
	//Handler of add_filter('grace_church_filter_detect_inheritance_key',	'grace_church_services_detect_inheritance_key', 9, 1);
	function grace_church_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return grace_church_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'grace_church_services_get_blog_type' ) ) {
	//Handler of add_filter('grace_church_filter_get_blog_type',	'grace_church_services_get_blog_type', 9, 2);
	function grace_church_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'grace_church_services_get_blog_title' ) ) {
	//Handler of add_filter('grace_church_filter_get_blog_title',	'grace_church_services_get_blog_title', 9, 2);
	function grace_church_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( grace_church_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = grace_church_get_post_title();
			} else {
				$title = esc_html__('All services', 'grace-church');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'grace_church_services_get_stream_page_title' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_title',	'grace_church_services_get_stream_page_title', 9, 2);
	function grace_church_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (grace_church_strpos($page, 'services')!==false) {
			if (($page_id = grace_church_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = grace_church_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'grace-church');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'grace_church_services_get_stream_page_id' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_id',	'grace_church_services_get_stream_page_id', 9, 2);
	function grace_church_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (grace_church_strpos($page, 'services')!==false) $id = grace_church_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'grace_church_services_get_stream_page_link' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_link',	'grace_church_services_get_stream_page_link', 9, 2);
	function grace_church_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (grace_church_strpos($page, 'services')!==false) {
			$id = grace_church_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'grace_church_services_get_current_taxonomy' ) ) {
	//Handler of add_filter('grace_church_filter_get_current_taxonomy',	'grace_church_services_get_current_taxonomy', 9, 2);
	function grace_church_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( grace_church_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'grace_church_services_is_taxonomy' ) ) {
	//Handler of add_filter('grace_church_filter_is_taxonomy',	'grace_church_services_is_taxonomy', 9, 2);
	function grace_church_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'grace_church_services_query_add_filters' ) ) {
	//Handler of add_filter('grace_church_filter_query_add_filters',	'grace_church_services_query_add_filters', 9, 2);
	function grace_church_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

if ( !function_exists( 'grace_church_sc_services' ) ) {
	function grace_church_sc_services($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "asc",
			"readmore" => esc_html__('Leer más', 'grace-church'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Leer más', 'grace-church'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && grace_church_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		
		$ms = grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = grace_church_get_css_position_from_values('', '', '', '', $width);
		$hs = grace_church_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (grace_church_param_is_off($custom) && $count < $columns) $columns = $count;

		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_services_id'] = $id;
		$GRACE_CHURCH_GLOBALS['sc_services_style'] = $style;
		$GRACE_CHURCH_GLOBALS['sc_services_columns'] = $columns;
		$GRACE_CHURCH_GLOBALS['sc_services_counter'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_services_slider'] = $slider;
		$GRACE_CHURCH_GLOBALS['sc_services_css_wh'] = $ws . $hs;
		$GRACE_CHURCH_GLOBALS['sc_services_readmore'] = $readmore;
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_services_wrap'
						. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(grace_church_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(grace_church_get_slider_controls_classes($controls))
							. (grace_church_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (grace_church_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!empty($width) && grace_church_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && grace_church_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(grace_church_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(grace_church_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(grace_church_strmacros($description)) . '</div>' : '')
					. (grace_church_param_is_on($slider)
						? '<div class="slides swiper-wrapper">' 
						: ($columns > 1 
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		$content = do_shortcode($content);
	
		if (grace_church_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order,
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = grace_church_query_add_sort_order($args, $orderby, $order);
			$args = grace_church_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => grace_church_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= grace_church_show_post_layout($args);
			}
			wp_reset_postdata();
		}
	
		if (grace_church_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.grace_church_do_shortcode('[trx_button link="'.esc_url($link).'" icon="none" ]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		return apply_filters('grace_church_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_services', 'grace_church_sc_services');
}


if ( !function_exists( 'grace_church_sc_services_item' ) ) {
	function grace_church_sc_services_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_services_counter']++;

		$id = $id ? $id : ($GRACE_CHURCH_GLOBALS['sc_services_id'] ? $GRACE_CHURCH_GLOBALS['sc_services_id'] . '_' . $GRACE_CHURCH_GLOBALS['sc_services_counter'] : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? $GRACE_CHURCH_GLOBALS['sc_services_readmore'] : $readmore;

		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = grace_church_get_thumb_sizes(array('layout' => $GRACE_CHURCH_GLOBALS['sc_services_style']));
			$image = grace_church_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link
		);
		$args = array(
			'layout' => $GRACE_CHURCH_GLOBALS['sc_services_style'],
			'number' => $GRACE_CHURCH_GLOBALS['sc_services_counter'],
			'columns_count' => $GRACE_CHURCH_GLOBALS['sc_services_columns'],
			'slider' => $GRACE_CHURCH_GLOBALS['sc_services_slider'],
			'show' => false,
			'descr'  => 0,
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $GRACE_CHURCH_GLOBALS['sc_services_css_wh']
		);
		$output = grace_church_show_post_layout($args, $post_data);
		return apply_filters('grace_church_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_services_item', 'grace_church_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('grace_church_services_reg_shortcodes')) {
	//Handler of add_filter('grace_church_action_shortcodes_list',	'grace_church_services_reg_shortcodes');
	function grace_church_services_reg_shortcodes() {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['shortcodes'])) {

			$services_groups = grace_church_get_list_terms(false, 'services_group');
			$services_styles = grace_church_get_list_templates('services');
			$controls 		 = grace_church_get_list_slider_controls();

			grace_church_array_insert_after($GRACE_CHURCH_GLOBALS['shortcodes'], 'trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", "grace-church"),
					"desc" => esc_html__("Insert services list in your page (post)", "grace-church"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "grace-church"),
							"desc" => esc_html__("Title for the block", "grace-church"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "grace-church"),
							"desc" => esc_html__("Subtitle for the block", "grace-church"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "grace-church"),
							"desc" => esc_html__("Short description for the block", "grace-church"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Services style", "grace-church"),
							"desc" => esc_html__("Select style to display services list", "grace-church"),
							"value" => "services-2",
							"type" => "select",
							"options" => $services_styles
						),
						"type" => array(
							"title" => esc_html__("Icon's type", "grace-church"),
							"desc" => esc_html__("Select type of icons: font icon or image", "grace-church"),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'grace-church'),
								'images' => esc_html__('Images', 'grace-church')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", "grace-church"),
							"desc" => esc_html__("How many columns use to show services list", "grace-church"),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "grace-church"),
							"desc" => esc_html__("Select color scheme for this block", "grace-church"),
							"value" => "",
							"type" => "checklist",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
						),
						"slider" => array(
							"title" => esc_html__("Slider", "grace-church"),
							"desc" => esc_html__("Use slider to show services", "grace-church"),
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", "grace-church"),
							"desc" => esc_html__("Slider controls style and position", "grace-church"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", "grace-church"),
							"desc" => esc_html__("Size of space (in px) between slides", "grace-church"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", "grace-church"),
							"desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "grace-church"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "grace-church"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "grace-church"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "grace-church"),
							"desc" => esc_html__("Alignment of the services block", "grace-church"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "grace-church"),
							"desc" => esc_html__("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", "grace-church"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "grace-church"),
							"desc" => esc_html__("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", "grace-church"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'grace-church')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "grace-church"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "grace-church"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "grace-church"),
							"desc" => esc_html__("Skip posts before select next part.", "grace-church"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "grace-church"),
							"desc" => esc_html__("Select desired posts sorting method", "grace-church"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "grace-church"),
							"desc" => esc_html__("Select desired posts order", "grace-church"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "grace-church"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "grace-church"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"readmore" => array(
							"title" => esc_html__("Read more", "grace-church"),
							"desc" => esc_html__("Caption for the Read more link (if empty - link not showed)", "grace-church"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "grace-church"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "grace-church"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "grace-church"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "grace-church"),
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
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", "grace-church"),
						"desc" => esc_html__("Service item", "grace-church"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", "grace-church"),
								"desc" => esc_html__("Item's title", "grace-church"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'grace-church'),
								"desc" => esc_html__('Select icon for the item from Fontello icons set',  'grace-church'),
								"value" => "",
								"type" => "icons",
								"options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
							),
							"image" => array(
								"title" => esc_html__("Item's image", "grace-church"),
								"desc" => esc_html__("Item's image (if icon not selected)", "grace-church"),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", "grace-church"),
								"desc" => esc_html__("Link on service's item page", "grace-church"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", "grace-church"),
								"desc" => esc_html__("Caption for the Read more link (if empty - link not showed)", "grace-church"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "grace-church"),
								"desc" => esc_html__("Item's short description", "grace-church"),
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
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('grace_church_services_reg_shortcodes_vc')) {
	//Handler of add_filter('grace_church_action_shortcodes_list_vc',	'grace_church_services_reg_shortcodes_vc');
	function grace_church_services_reg_shortcodes_vc() {
		global $GRACE_CHURCH_GLOBALS;

		$services_groups = grace_church_get_list_terms(false, 'services_group');
		$services_styles = grace_church_get_list_templates('services');
		$controls		 = grace_church_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", "grace-church"),
				"description" => esc_html__("Insert services list", "grace-church"),
				"category" => esc_html__('Content', 'grace-church'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", "grace-church"),
						"description" => esc_html__("Select style to display services list", "grace-church"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", "grace-church"),
						"description" => esc_html__("Select type of icons: font icon or image", "grace-church"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
                            esc_html__('Icons', 'grace-church') => 'icons',
                            esc_html__('Images', 'grace-church') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "grace-church"),
						"description" => esc_html__("How many columns use to show services list", "grace-church"),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "grace-church"),
						"description" => esc_html__("Select color scheme for this block", "grace-church"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", "grace-church"),
						"description" => esc_html__("Use slider to show services", "grace-church"),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'grace-church'),
						"class" => "",
						"std" => "no",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", "grace-church"),
						"description" => esc_html__("Slider controls style and position", "grace-church"),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'grace-church'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", "grace-church"),
						"description" => esc_html__("Size of space (in px) between slides", "grace-church"),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'grace-church'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", "grace-church"),
						"description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "grace-church"),
						"group" => esc_html__('Slider', 'grace-church'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "grace-church"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "grace-church"),
						"group" => esc_html__('Slider', 'grace-church'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "grace-church"),
						"description" => esc_html__("Alignment of the services block", "grace-church"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "grace-church"),
						"description" => esc_html__("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", "grace-church"),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "grace-church"),
						"description" => esc_html__("Title for the block", "grace-church"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'grace-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "grace-church"),
						"description" => esc_html__("Subtitle for the block", "grace-church"),
						"group" => esc_html__('Captions', 'grace-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "grace-church"),
						"description" => esc_html__("Description for the block", "grace-church"),
						"group" => esc_html__('Captions', 'grace-church'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "grace-church"),
						"description" => esc_html__("Select category to show services. If empty - select services from any category (group) or from IDs list", "grace-church"),
						"group" => esc_html__('Query', 'grace-church'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'grace-church')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "grace-church"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "grace-church"),
						"admin_label" => true,
						"group" => esc_html__('Query', 'grace-church'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "grace-church"),
						"description" => esc_html__("Skip posts before select next part.", "grace-church"),
						"group" => esc_html__('Query', 'grace-church'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "grace-church"),
						"description" => esc_html__("Select desired posts sorting method", "grace-church"),
						"group" => esc_html__('Query', 'grace-church'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "grace-church"),
						"description" => esc_html__("Select desired posts order", "grace-church"),
						"group" => esc_html__('Query', 'grace-church'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Team member's IDs list", "grace-church"),
						"description" => esc_html__("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "grace-church"),
						"group" => esc_html__('Query', 'grace-church'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", "grace-church"),
						"description" => esc_html__("Caption for the Read more link (if empty - link not showed)", "grace-church"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'grace-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "grace-church"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "grace-church"),
						"group" => esc_html__('Captions', 'grace-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "grace-church"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "grace-church"),
						"group" => esc_html__('Captions', 'grace-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					grace_church_vc_width(),
					grace_church_vc_height(),
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
					$GRACE_CHURCH_GLOBALS['vc_params']['margin_right'],
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'grace-church' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'grace-church' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'grace-church' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'grace-church' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", "grace-church"),
				"description" => esc_html__("Custom services item - all data pull out from shortcode parameters", "grace-church"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_item trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "grace-church"),
						"description" => esc_html__("Item's title", "grace-church"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "grace-church"),
						"description" => esc_html__("Select icon for the item from Fontello icons set", "grace-church"),
						"class" => "",
						"value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", "grace-church"),
						"description" => esc_html__("Item's image (if icon is empty)", "grace-church"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "grace-church"),
						"description" => esc_html__("Link on item's page", "grace-church"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", "grace-church"),
						"description" => esc_html__("Caption for the Read more link (if empty - link not showed)", "grace-church"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GRACE_CHURCH_GLOBALS['vc_params']['id'],
					$GRACE_CHURCH_GLOBALS['vc_params']['class'],
					$GRACE_CHURCH_GLOBALS['vc_params']['animation'],
					$GRACE_CHURCH_GLOBALS['vc_params']['css']
				)
			) );
			
		class WPBakeryShortCode_Trx_Services extends GRACE_CHURCH_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends GRACE_CHURCH_VC_ShortCodeItem {}

	}
}
?>