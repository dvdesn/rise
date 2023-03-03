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
		
		if (function_exists('grace_church_require_data')) {
			// Prepare type "Team"
			grace_church_require_data( 'post_type', 'services', array(
				'label'               => esc_html__( 'Service item', 'grace-church' ),
				'description'         => esc_html__( 'Service Description', 'grace-church' ),
				'labels'              => array(
					'name'                => esc_html_x( 'Services', 'Post Type General Name', 'grace-church' ),
					'singular_name'       => esc_html_x( 'Service item', 'Post Type Singular Name', 'grace-church' ),
					'menu_name'           => esc_html__( 'Services', 'grace-church' ),
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
					'name'              => esc_html_x( 'Services Group', 'taxonomy general name', 'grace-church' ),
					'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'grace-church' ),
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
?>