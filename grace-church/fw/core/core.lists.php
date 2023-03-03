<?php
/**
 * Grace-Church Framework: return lists
 *
 * @package grace_church
 * @since grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'grace_church_get_list_styles' ) ) {
	function grace_church_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf( esc_html__('Style %d', 'grace-church'), $i);
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'grace_church_get_list_animations' ) ) {
	function grace_church_get_list_animations($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_animations']))
			$list = $GRACE_CHURCH_GLOBALS['list_animations'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'grace-church');
			$list['bounced']		= esc_html__('Bounced',		'grace-church');
			$list['flash']			= esc_html__('Flash',		'grace-church');
			$list['flip']			= esc_html__('Flip',		'grace-church');
			$list['pulse']			= esc_html__('Pulse',		'grace-church');
			$list['rubberBand']		= esc_html__('Rubber Band',	'grace-church');
			$list['shake']			= esc_html__('Shake',		'grace-church');
			$list['swing']			= esc_html__('Swing',		'grace-church');
			$list['tada']			= esc_html__('Tada',		'grace-church');
			$list['wobble']			= esc_html__('Wobble',		'grace-church');
			$GRACE_CHURCH_GLOBALS['list_animations'] = $list = apply_filters('grace_church_filter_list_animations', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'grace_church_get_list_animations_in' ) ) {
	function grace_church_get_list_animations_in($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_animations_in']))
			$list = $GRACE_CHURCH_GLOBALS['list_animations_in'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'grace-church');
			$list['bounceIn']		= esc_html__('Bounce In',			'grace-church');
			$list['bounceInUp']		= esc_html__('Bounce In Up',		'grace-church');
			$list['bounceInDown']	= esc_html__('Bounce In Down',		'grace-church');
			$list['bounceInLeft']	= esc_html__('Bounce In Left',		'grace-church');
			$list['bounceInRight']	= esc_html__('Bounce In Right',		'grace-church');
			$list['fadeIn']			= esc_html__('Fade In',				'grace-church');
			$list['fadeInUp']		= esc_html__('Fade In Up',			'grace-church');
			$list['fadeInDown']		= esc_html__('Fade In Down',		'grace-church');
			$list['fadeInLeft']		= esc_html__('Fade In Left',		'grace-church');
			$list['fadeInRight']	= esc_html__('Fade In Right',		'grace-church');
			$list['fadeInUpBig']	= esc_html__('Fade In Up Big',		'grace-church');
			$list['fadeInDownBig']	= esc_html__('Fade In Down Big',	'grace-church');
			$list['fadeInLeftBig']	= esc_html__('Fade In Left Big',	'grace-church');
			$list['fadeInRightBig']	= esc_html__('Fade In Right Big',	'grace-church');
			$list['flipInX']		= esc_html__('Flip In X',			'grace-church');
			$list['flipInY']		= esc_html__('Flip In Y',			'grace-church');
			$list['lightSpeedIn']	= esc_html__('Light Speed In',		'grace-church');
			$list['rotateIn']		= esc_html__('Rotate In',			'grace-church');
			$list['rotateInUpLeft']		= esc_html__('Rotate In Down Left',	'grace-church');
			$list['rotateInUpRight']	= esc_html__('Rotate In Up Right',	'grace-church');
			$list['rotateInDownLeft']	= esc_html__('Rotate In Up Left',	'grace-church');
			$list['rotateInDownRight']	= esc_html__('Rotate In Down Right','grace-church');
			$list['rollIn']				= esc_html__('Roll In',			'grace-church');
			$list['slideInUp']			= esc_html__('Slide In Up',		'grace-church');
			$list['slideInDown']		= esc_html__('Slide In Down',	'grace-church');
			$list['slideInLeft']		= esc_html__('Slide In Left',	'grace-church');
			$list['slideInRight']		= esc_html__('Slide In Right',	'grace-church');
			$list['zoomIn']				= esc_html__('Zoom In',			'grace-church');
			$list['zoomInUp']			= esc_html__('Zoom In Up',		'grace-church');
			$list['zoomInDown']			= esc_html__('Zoom In Down',	'grace-church');
			$list['zoomInLeft']			= esc_html__('Zoom In Left',	'grace-church');
			$list['zoomInRight']		= esc_html__('Zoom In Right',	'grace-church');
			$GRACE_CHURCH_GLOBALS['list_animations_in'] = $list = apply_filters('grace_church_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'grace_church_get_list_animations_out' ) ) {
	function grace_church_get_list_animations_out($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_animations_out']))
			$list = $GRACE_CHURCH_GLOBALS['list_animations_out'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'grace-church');
			$list['bounceOut']		= esc_html__('Bounce Out',			'grace-church');
			$list['bounceOutUp']	= esc_html__('Bounce Out Up',		'grace-church');
			$list['bounceOutDown']	= esc_html__('Bounce Out Down',		'grace-church');
			$list['bounceOutLeft']	= esc_html__('Bounce Out Left',		'grace-church');
			$list['bounceOutRight']	= esc_html__('Bounce Out Right',	'grace-church');
			$list['fadeOut']		= esc_html__('Fade Out',			'grace-church');
			$list['fadeOutUp']		= esc_html__('Fade Out Up',			'grace-church');
			$list['fadeOutDown']	= esc_html__('Fade Out Down',		'grace-church');
			$list['fadeOutLeft']	= esc_html__('Fade Out Left',		'grace-church');
			$list['fadeOutRight']	= esc_html__('Fade Out Right',		'grace-church');
			$list['fadeOutUpBig']	= esc_html__('Fade Out Up Big',		'grace-church');
			$list['fadeOutDownBig']	= esc_html__('Fade Out Down Big',	'grace-church');
			$list['fadeOutLeftBig']	= esc_html__('Fade Out Left Big',	'grace-church');
			$list['fadeOutRightBig']= esc_html__('Fade Out Right Big',	'grace-church');
			$list['flipOutX']		= esc_html__('Flip Out X',			'grace-church');
			$list['flipOutY']		= esc_html__('Flip Out Y',			'grace-church');
			$list['hinge']			= esc_html__('Hinge Out',			'grace-church');
			$list['lightSpeedOut']	= esc_html__('Light Speed Out',		'grace-church');
			$list['rotateOut']		= esc_html__('Rotate Out',			'grace-church');
			$list['rotateOutUpLeft']	= esc_html__('Rotate Out Down Left',	'grace-church');
			$list['rotateOutUpRight']	= esc_html__('Rotate Out Up Right',		'grace-church');
			$list['rotateOutDownLeft']	= esc_html__('Rotate Out Up Left',		'grace-church');
			$list['rotateOutDownRight']	= esc_html__('Rotate Out Down Right',	'grace-church');
			$list['rollOut']			= esc_html__('Roll Out',		'grace-church');
			$list['slideOutUp']			= esc_html__('Slide Out Up',		'grace-church');
			$list['slideOutDown']		= esc_html__('Slide Out Down',	'grace-church');
			$list['slideOutLeft']		= esc_html__('Slide Out Left',	'grace-church');
			$list['slideOutRight']		= esc_html__('Slide Out Right',	'grace-church');
			$list['zoomOut']			= esc_html__('Zoom Out',			'grace-church');
			$list['zoomOutUp']			= esc_html__('Zoom Out Up',		'grace-church');
			$list['zoomOutDown']		= esc_html__('Zoom Out Down',	'grace-church');
			$list['zoomOutLeft']		= esc_html__('Zoom Out Left',	'grace-church');
			$list['zoomOutRight']		= esc_html__('Zoom Out Right',	'grace-church');
			$GRACE_CHURCH_GLOBALS['list_animations_out'] = $list = apply_filters('grace_church_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('grace_church_get_animation_classes')) {
	function grace_church_get_animation_classes($animation, $speed='normal', $loop='none') {
		return grace_church_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!grace_church_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'grace_church_get_list_categories' ) ) {
	function grace_church_get_list_categories($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_categories']))
			$list = $GRACE_CHURCH_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$GRACE_CHURCH_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'grace_church_get_list_terms' ) ) {
	function grace_church_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_taxonomies_'.($taxonomy)]))
			$list = $GRACE_CHURCH_GLOBALS['list_taxonomies_'.($taxonomy)];
		else {
			$list = array();
			$args = array(
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => $taxonomy,
				'pad_counts'               => false );
			$taxonomies = get_terms( $taxonomy, $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$GRACE_CHURCH_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'grace_church_get_list_posts_types' ) ) {
	function grace_church_get_list_posts_types($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_posts_types']))
			$list = $GRACE_CHURCH_GLOBALS['list_posts_types'];
		else {
			$list = array();
			// Return only theme inheritance supported post types
			$GRACE_CHURCH_GLOBALS['list_posts_types'] = $list = apply_filters('grace_church_filter_list_post_types', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'grace_church_get_list_posts' ) ) {
	function grace_church_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $GRACE_CHURCH_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($GRACE_CHURCH_GLOBALS[$hash]))
			$list = $GRACE_CHURCH_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'grace-church');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			$GRACE_CHURCH_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'grace_church_get_list_users' ) ) {
	function grace_church_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_users']))
			$list = $GRACE_CHURCH_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'grace-church');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			$GRACE_CHURCH_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'grace_church_get_list_sliders' ) ) {
	function grace_church_get_list_sliders($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_sliders']))
			$list = $GRACE_CHURCH_GLOBALS['list_sliders'];
		else {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'grace-church')
			);
			if (grace_church_exists_revslider())
				$list["revo"] = esc_html__("Layer slider (Revolution)", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_sliders'] = $list = apply_filters('grace_church_filter_list_sliders', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'grace_church_get_list_revo_sliders' ) ) {
	function grace_church_get_list_revo_sliders($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_revo_sliders']))
			$list = $GRACE_CHURCH_GLOBALS['list_revo_sliders'];
		else {
			$list = array();
			if (grace_church_exists_revslider()) {
				global $wpdb;
                // Attention! The use of wpdb->prepare() is not required
                // because the query does not use external data substitution
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$GRACE_CHURCH_GLOBALS['list_revo_sliders'] = $list = apply_filters('grace_church_filter_list_revo_sliders', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'grace_church_get_list_slider_controls' ) ) {
	function grace_church_get_list_slider_controls($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_slider_controls']))
			$list = $GRACE_CHURCH_GLOBALS['list_slider_controls'];
		else {
			$list = array(
				'no' => esc_html__('None', 'grace-church'),
				'side' => esc_html__('Side', 'grace-church'),
				'bottom' => esc_html__('Bottom', 'grace-church'),
				'pagination' => esc_html__('Pagination', 'grace-church')
			);
			$GRACE_CHURCH_GLOBALS['list_slider_controls'] = $list = apply_filters('grace_church_filter_list_slider_controls', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'grace_church_get_slider_controls_classes' ) ) {
	function grace_church_get_slider_controls_classes($controls) {
		if (grace_church_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')				$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')			$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else										$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'grace_church_get_list_popup_engines' ) ) {
	function grace_church_get_list_popup_engines($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_popup_engines']))
			$list = $GRACE_CHURCH_GLOBALS['list_popup_engines'];
		else {
			$list = array();
			$list["pretty"] = esc_html__("Pretty photo", 'grace-church');
			$list["magnific"] = esc_html__("Magnific popup", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_popup_engines'] = $list = apply_filters('grace_church_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'grace_church_get_list_menus' ) ) {
	function grace_church_get_list_menus($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_menus']))
			$list = $GRACE_CHURCH_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = esc_html__("Default", 'grace-church');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$GRACE_CHURCH_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'grace_church_get_list_sidebars' ) ) {
	function grace_church_get_list_sidebars($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_sidebars'])) {
			$list = $GRACE_CHURCH_GLOBALS['list_sidebars'];
		} else {
			$list = isset($GRACE_CHURCH_GLOBALS['registered_sidebars']) ? $GRACE_CHURCH_GLOBALS['registered_sidebars'] : array();
			$GRACE_CHURCH_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'grace_church_get_list_sidebars_positions' ) ) {
	function grace_church_get_list_sidebars_positions($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_sidebars_positions']))
			$list = $GRACE_CHURCH_GLOBALS['list_sidebars_positions'];
		else {
			$list = array();
			$list['none']  = esc_html__('Hide',  'grace-church');
			$list['left']  = esc_html__('Left',  'grace-church');
			$list['right'] = esc_html__('Right', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'grace_church_get_sidebar_class' ) ) {
	function grace_church_get_sidebar_class() {
		$sb_main = grace_church_get_custom_option('show_sidebar_main');
		$sb_outer = grace_church_get_custom_option('show_sidebar_outer');
		return (grace_church_param_is_off($sb_main) || !is_active_sidebar(grace_church_get_custom_option('sidebar_main')) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (grace_church_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_body_styles' ) ) {
	function grace_church_get_list_body_styles($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_body_styles']))
			$list = $GRACE_CHURCH_GLOBALS['list_body_styles'];
		else {
			$list = array();
			$list['boxed']		= esc_html__('Boxed',		'grace-church');
			$list['wide']		= esc_html__('Wide',		'grace-church');
			if (grace_church_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'grace-church');
				$list['fullscreen']	= esc_html__('Fullscreen',	'grace-church');
			}
			$GRACE_CHURCH_GLOBALS['list_body_styles'] = $list = apply_filters('grace_church_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'grace_church_get_list_skins' ) ) {
	function grace_church_get_list_skins($prepend_inherit=false) {
        $list = array(
            'default' => esc_html__('Grace Church', 'grace-church')
        );
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'grace_church_get_list_templates' ) ) {
	function grace_church_get_list_templates($mode='') {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_templates_'.($mode)]))
			$list = $GRACE_CHURCH_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			if (is_array($GRACE_CHURCH_GLOBALS['registered_templates']) && count($GRACE_CHURCH_GLOBALS['registered_templates']) > 0) {
				foreach ($GRACE_CHURCH_GLOBALS['registered_templates'] as $k=>$v) {
					if ($mode=='' || grace_church_strpos($v['mode'], $mode)!==false)
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: grace_church_strtoproper($v['layout'])
										);
				}
			}
			$GRACE_CHURCH_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_templates_blog' ) ) {
	function grace_church_get_list_templates_blog($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_templates_blog']))
			$list = $GRACE_CHURCH_GLOBALS['list_templates_blog'];
		else {
			$list = grace_church_get_list_templates('blog');
			$GRACE_CHURCH_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_templates_blogger' ) ) {
	function grace_church_get_list_templates_blogger($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_templates_blogger']))
			$list = $GRACE_CHURCH_GLOBALS['list_templates_blogger'];
		else {
			$list = grace_church_array_merge(grace_church_get_list_templates('blogger'), grace_church_get_list_templates('blog'));
			$GRACE_CHURCH_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_templates_single' ) ) {
	function grace_church_get_list_templates_single($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_templates_single']))
			$list = $GRACE_CHURCH_GLOBALS['list_templates_single'];
		else {
			$list = grace_church_get_list_templates('single');
			$GRACE_CHURCH_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_templates_header' ) ) {
	function grace_church_get_list_templates_header($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_templates_header']))
			$list = $GRACE_CHURCH_GLOBALS['list_templates_header'];
		else {
			$list = grace_church_get_list_templates('header');
			$GRACE_CHURCH_GLOBALS['list_templates_header'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_article_styles' ) ) {
	function grace_church_get_list_article_styles($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_article_styles']))
			$list = $GRACE_CHURCH_GLOBALS['list_article_styles'];
		else {
			$list = array();
			$list["boxed"]   = esc_html__('Boxed', 'grace-church');
			$list["stretch"] = esc_html__('Stretch', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'grace_church_get_list_post_formats_filters' ) ) {
	function grace_church_get_list_post_formats_filters($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_post_formats_filters']))
			$list = $GRACE_CHURCH_GLOBALS['list_post_formats_filters'];
		else {
			$list = array();
			$list["no"]      = esc_html__('All posts', 'grace-church');
			$list["thumbs"]  = esc_html__('With thumbs', 'grace-church');
			$list["reviews"] = esc_html__('With reviews', 'grace-church');
			$list["video"]   = esc_html__('With videos', 'grace-church');
			$list["audio"]   = esc_html__('With audios', 'grace-church');
			$list["gallery"] = esc_html__('With galleries', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'grace_church_get_list_portfolio_filters' ) ) {
	function grace_church_get_list_portfolio_filters($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_portfolio_filters']))
			$list = $GRACE_CHURCH_GLOBALS['list_portfolio_filters'];
		else {
			$list = array();
			$list["hide"] = esc_html__('Hide', 'grace-church');
			$list["tags"] = esc_html__('Tags', 'grace-church');
			$list["categories"] = esc_html__('Categories', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_hovers' ) ) {
	function grace_church_get_list_hovers($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_hovers']))
			$list = $GRACE_CHURCH_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'grace-church');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'grace-church');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'grace-church');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'grace-church');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'grace-church');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'grace-church');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'grace-church');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'grace-church');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'grace-church');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'grace-church');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'grace-church');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'grace-church');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'grace-church');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'grace-church');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'grace-church');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'grace-church');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'grace-church');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'grace-church');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'grace-church');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'grace-church');
			$list['square effect1']  = esc_html__('Square Effect 1',  'grace-church');
			$list['square effect2']  = esc_html__('Square Effect 2',  'grace-church');
			$list['square effect3']  = esc_html__('Square Effect 3',  'grace-church');
			$list['square effect5']  = esc_html__('Square Effect 5',  'grace-church');
			$list['square effect6']  = esc_html__('Square Effect 6',  'grace-church');
			$list['square effect7']  = esc_html__('Square Effect 7',  'grace-church');
			$list['square effect8']  = esc_html__('Square Effect 8',  'grace-church');
			$list['square effect9']  = esc_html__('Square Effect 9',  'grace-church');
			$list['square effect10'] = esc_html__('Square Effect 10',  'grace-church');
			$list['square effect11'] = esc_html__('Square Effect 11',  'grace-church');
			$list['square effect12'] = esc_html__('Square Effect 12',  'grace-church');
			$list['square effect13'] = esc_html__('Square Effect 13',  'grace-church');
			$list['square effect14'] = esc_html__('Square Effect 14',  'grace-church');
			$list['square effect15'] = esc_html__('Square Effect 15',  'grace-church');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'grace-church');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'grace-church');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'grace-church');
			$list['square effect_more']  = esc_html__('Square Effect More',  'grace-church');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'grace-church');
			$GRACE_CHURCH_GLOBALS['list_hovers'] = $list = apply_filters('grace_church_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'grace_church_get_list_hovers_directions' ) ) {
	function grace_church_get_list_hovers_directions($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_hovers_directions']))
			$list = $GRACE_CHURCH_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = esc_html__('Left to Right',  'grace-church');
			$list['right_to_left'] = esc_html__('Right to Left',  'grace-church');
			$list['top_to_bottom'] = esc_html__('Top to Bottom',  'grace-church');
			$list['bottom_to_top'] = esc_html__('Bottom to Top',  'grace-church');
			$list['scale_up']      = esc_html__('Scale Up',  'grace-church');
			$list['scale_down']    = esc_html__('Scale Down',  'grace-church');
			$list['scale_down_up'] = esc_html__('Scale Down-Up',  'grace-church');
			$list['from_left_and_right'] = esc_html__('From Left and Right',  'grace-church');
			$list['from_top_and_bottom'] = esc_html__('From Top and Bottom',  'grace-church');
			$GRACE_CHURCH_GLOBALS['list_hovers_directions'] = $list = apply_filters('grace_church_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'grace_church_get_list_label_positions' ) ) {
	function grace_church_get_list_label_positions($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_label_positions']))
			$list = $GRACE_CHURCH_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= esc_html__('Top',		'grace-church');
			$list['bottom']	= esc_html__('Bottom',		'grace-church');
			$list['left']	= esc_html__('Left',		'grace-church');
			$list['over']	= esc_html__('Over',		'grace-church');
			$GRACE_CHURCH_GLOBALS['list_label_positions'] = $list = apply_filters('grace_church_filter_label_positions', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'grace_church_get_list_bg_image_positions' ) ) {
	function grace_church_get_list_bg_image_positions($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_bg_image_positions']))
			$list = $GRACE_CHURCH_GLOBALS['list_bg_image_positions'];
		else {
			$list = array();
			$list['left top']	  = esc_html__('Left Top', 'grace-church');
			$list['center top']   = esc_html__("Center Top", 'grace-church');
			$list['right top']    = esc_html__("Right Top", 'grace-church');
			$list['left center']  = esc_html__("Left Center", 'grace-church');
			$list['center center']= esc_html__("Center Center", 'grace-church');
			$list['right center'] = esc_html__("Right Center", 'grace-church');
			$list['left bottom']  = esc_html__("Left Bottom", 'grace-church');
			$list['center bottom']= esc_html__("Center Bottom", 'grace-church');
			$list['right bottom'] = esc_html__("Right Bottom", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_bg_image_positions'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'grace_church_get_list_bg_image_repeats' ) ) {
	function grace_church_get_list_bg_image_repeats($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_bg_image_repeats']))
			$list = $GRACE_CHURCH_GLOBALS['list_bg_image_repeats'];
		else {
			$list = array();
			$list['repeat']	  = esc_html__('Repeat', 'grace-church');
			$list['repeat-x'] = esc_html__('Repeat X', 'grace-church');
			$list['repeat-y'] = esc_html__('Repeat Y', 'grace-church');
			$list['no-repeat']= esc_html__('No Repeat', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_bg_image_repeats'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'grace_church_get_list_bg_image_attachments' ) ) {
	function grace_church_get_list_bg_image_attachments($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_bg_image_attachments']))
			$list = $GRACE_CHURCH_GLOBALS['list_bg_image_attachments'];
		else {
			$list = array();
			$list['scroll']	= esc_html__('Scroll', 'grace-church');
			$list['fixed']	= esc_html__('Fixed', 'grace-church');
			$list['local']	= esc_html__('Local', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_bg_image_attachments'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'grace_church_get_list_bg_tints' ) ) {
	function grace_church_get_list_bg_tints($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_bg_tints']))
			$list = $GRACE_CHURCH_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['white']	= esc_html__('White', 'grace-church');
			$list['light']	= esc_html__('Light', 'grace-church');
			$list['dark']	= esc_html__('Dark', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_bg_tints'] = $list = apply_filters('grace_church_filter_bg_tints', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'grace_church_get_list_field_types' ) ) {
	function grace_church_get_list_field_types($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_field_types']))
			$list = $GRACE_CHURCH_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = esc_html__('Text',  'grace-church');
			$list['textarea'] = esc_html__('Text Area','grace-church');
			$list['password'] = esc_html__('Password',  'grace-church');
			$list['radio']    = esc_html__('Radio',  'grace-church');
			$list['checkbox'] = esc_html__('Checkbox',  'grace-church');
			$list['select']   = esc_html__('Select',  'grace-church');
			$list['button']   = esc_html__('Button','grace-church');
			$GRACE_CHURCH_GLOBALS['list_field_types'] = $list = apply_filters('grace_church_filter_field_types', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'grace_church_get_list_googlemap_styles' ) ) {
	function grace_church_get_list_googlemap_styles($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_googlemap_styles']))
			$list = $GRACE_CHURCH_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = esc_html__('Default', 'grace-church');
			$list['simple'] = esc_html__('Simple', 'grace-church');
			$list['greyscale'] = esc_html__('Greyscale', 'grace-church');
			$list['greyscale2'] = esc_html__('Greyscale 2', 'grace-church');
			$list['invert'] = esc_html__('Invert', 'grace-church');
			$list['dark'] = esc_html__('Dark', 'grace-church');
			$list['style1'] = esc_html__('Custom style 1', 'grace-church');
			$list['style2'] = esc_html__('Custom style 2', 'grace-church');
			$list['style3'] = esc_html__('Custom style 3', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_googlemap_styles'] = $list = apply_filters('grace_church_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'grace_church_get_list_icons' ) ) {
	function grace_church_get_list_icons($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_icons']))
			$list = $GRACE_CHURCH_GLOBALS['list_icons'];
		else
			$GRACE_CHURCH_GLOBALS['list_icons'] = $list = grace_church_parse_icons_classes(grace_church_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'grace_church_get_list_socials' ) ) {
	function grace_church_get_list_socials($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_socials']))
			$list = $GRACE_CHURCH_GLOBALS['list_socials'];
		else
			$GRACE_CHURCH_GLOBALS['list_socials'] = $list = grace_church_get_list_files("images/socials", "png");
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'grace_church_get_list_flags' ) ) {
	function grace_church_get_list_flags($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_flags']))
			$list = $GRACE_CHURCH_GLOBALS['list_flags'];
		else
			$GRACE_CHURCH_GLOBALS['list_flags'] = $list = grace_church_get_list_files("images/flags", "png");
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'grace_church_get_list_yesno' ) ) {
	function grace_church_get_list_yesno($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_yesno']))
			$list = $GRACE_CHURCH_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = esc_html__("Yes", 'grace-church');
			$list["no"]  = esc_html__("No", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'grace_church_get_list_onoff' ) ) {
	function grace_church_get_list_onoff($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_onoff']))
			$list = $GRACE_CHURCH_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = esc_html__("On", 'grace-church');
			$list["off"] = esc_html__("Off", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'grace_church_get_list_showhide' ) ) {
	function grace_church_get_list_showhide($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_showhide']))
			$list = $GRACE_CHURCH_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = esc_html__("Show", 'grace-church');
			$list["hide"] = esc_html__("Hide", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'grace_church_get_list_orderings' ) ) {
	function grace_church_get_list_orderings($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_orderings']))
			$list = $GRACE_CHURCH_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = esc_html__("Ascending", 'grace-church');
			$list["desc"] = esc_html__("Descending", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'grace_church_get_list_directions' ) ) {
	function grace_church_get_list_directions($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_directions']))
			$list = $GRACE_CHURCH_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = esc_html__("Horizontal", 'grace-church');
			$list["vertical"] = esc_html__("Vertical", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'grace_church_get_list_shapes' ) ) {
	function grace_church_get_list_shapes($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_shapes']))
			$list = $GRACE_CHURCH_GLOBALS['list_shapes'];
		else {
			$list = array();
			$list["round"]  = esc_html__("Round", 'grace-church');
			$list["square"] = esc_html__("Square", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_shapes'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'grace_church_get_list_sizes' ) ) {
	function grace_church_get_list_sizes($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_sizes']))
			$list = $GRACE_CHURCH_GLOBALS['list_sizes'];
		else {
			$list = array();
			$list["tiny"]   = esc_html__("Tiny", 'grace-church');
			$list["small"]  = esc_html__("Small", 'grace-church');
			$list["medium"] = esc_html__("Medium", 'grace-church');
			$list["large"]  = esc_html__("Large", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_sizes'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'grace_church_get_list_floats' ) ) {
	function grace_church_get_list_floats($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_floats']))
			$list = $GRACE_CHURCH_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'grace-church');
			$list["left"] = esc_html__("Float Left", 'grace-church');
			$list["right"] = esc_html__("Float Right", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'grace_church_get_list_alignments' ) ) {
	function grace_church_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_alignments']))
			$list = $GRACE_CHURCH_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'grace-church');
			$list["left"] = esc_html__("Left", 'grace-church');
			$list["center"] = esc_html__("Center", 'grace-church');
			$list["right"] = esc_html__("Right", 'grace-church');
			if ($justify) $list["justify"] = esc_html__("Justify", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'grace_church_get_list_sortings' ) ) {
	function grace_church_get_list_sortings($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_sortings']))
			$list = $GRACE_CHURCH_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = esc_html__("Date", 'grace-church');
			$list["title"] = esc_html__("Alphabetically", 'grace-church');
			$list["views"] = esc_html__("Popular (views count)", 'grace-church');
			$list["comments"] = esc_html__("Most commented (comments count)", 'grace-church');
			$list["author_rating"] = esc_html__("Author rating", 'grace-church');
			$list["users_rating"] = esc_html__("Visitors (users) rating", 'grace-church');
			$list["random"] = esc_html__("Random", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_sortings'] = $list = apply_filters('grace_church_filter_list_sortings', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'grace_church_get_list_columns' ) ) {
	function grace_church_get_list_columns($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_columns']))
			$list = $GRACE_CHURCH_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'grace-church');
			$list["1_1"] = esc_html__("100%", 'grace-church');
			$list["1_2"] = esc_html__("1/2", 'grace-church');
			$list["1_3"] = esc_html__("1/3", 'grace-church');
			$list["2_3"] = esc_html__("2/3", 'grace-church');
			$list["1_4"] = esc_html__("1/4", 'grace-church');
			$list["3_4"] = esc_html__("3/4", 'grace-church');
			$list["1_5"] = esc_html__("1/5", 'grace-church');
			$list["2_5"] = esc_html__("2/5", 'grace-church');
			$list["3_5"] = esc_html__("3/5", 'grace-church');
			$list["4_5"] = esc_html__("4/5", 'grace-church');
			$list["1_6"] = esc_html__("1/6", 'grace-church');
			$list["5_6"] = esc_html__("5/6", 'grace-church');
			$list["1_7"] = esc_html__("1/7", 'grace-church');
			$list["2_7"] = esc_html__("2/7", 'grace-church');
			$list["3_7"] = esc_html__("3/7", 'grace-church');
			$list["4_7"] = esc_html__("4/7", 'grace-church');
			$list["5_7"] = esc_html__("5/7", 'grace-church');
			$list["6_7"] = esc_html__("6/7", 'grace-church');
			$list["1_8"] = esc_html__("1/8", 'grace-church');
			$list["3_8"] = esc_html__("3/8", 'grace-church');
			$list["5_8"] = esc_html__("5/8", 'grace-church');
			$list["7_8"] = esc_html__("7/8", 'grace-church');
			$list["1_9"] = esc_html__("1/9", 'grace-church');
			$list["2_9"] = esc_html__("2/9", 'grace-church');
			$list["4_9"] = esc_html__("4/9", 'grace-church');
			$list["5_9"] = esc_html__("5/9", 'grace-church');
			$list["7_9"] = esc_html__("7/9", 'grace-church');
			$list["8_9"] = esc_html__("8/9", 'grace-church');
			$list["1_10"]= esc_html__("1/10", 'grace-church');
			$list["3_10"]= esc_html__("3/10", 'grace-church');
			$list["7_10"]= esc_html__("7/10", 'grace-church');
			$list["9_10"]= esc_html__("9/10", 'grace-church');
			$list["1_11"]= esc_html__("1/11", 'grace-church');
			$list["2_11"]= esc_html__("2/11", 'grace-church');
			$list["3_11"]= esc_html__("3/11", 'grace-church');
			$list["4_11"]= esc_html__("4/11", 'grace-church');
			$list["5_11"]= esc_html__("5/11", 'grace-church');
			$list["6_11"]= esc_html__("6/11", 'grace-church');
			$list["7_11"]= esc_html__("7/11", 'grace-church');
			$list["8_11"]= esc_html__("8/11", 'grace-church');
			$list["9_11"]= esc_html__("9/11", 'grace-church');
			$list["10_11"]= esc_html__("10/11", 'grace-church');
			$list["1_12"]= esc_html__("1/12", 'grace-church');
			$list["5_12"]= esc_html__("5/12", 'grace-church');
			$list["7_12"]= esc_html__("7/12", 'grace-church');
			$list["10_12"]= esc_html__("10/12", 'grace-church');
			$list["11_12"]= esc_html__("11/12", 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_columns'] = $list = apply_filters('grace_church_filter_list_columns', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'grace_church_get_list_dedicated_locations' ) ) {
	function grace_church_get_list_dedicated_locations($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_dedicated_locations']))
			$list = $GRACE_CHURCH_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = esc_html__('As in the post defined', 'grace-church');
			$list["center"]  = esc_html__('Above the text of the post', 'grace-church');
			$list["left"]    = esc_html__('To the left the text of the post', 'grace-church');
			$list["right"]   = esc_html__('To the right the text of the post', 'grace-church');
			$list["alter"]   = esc_html__('Alternates for each post', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_dedicated_locations'] = $list = apply_filters('grace_church_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'grace_church_get_post_format_name' ) ) {
	function grace_church_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'grace-church') : esc_html__('galleries', 'grace-church');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'grace-church') : esc_html__('videos', 'grace-church');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'grace-church') : esc_html__('audios', 'grace-church');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'grace-church') : esc_html__('images', 'grace-church');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'grace-church') : esc_html__('quotes', 'grace-church');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'grace-church') : esc_html__('links', 'grace-church');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'grace-church') : esc_html__('statuses', 'grace-church');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'grace-church') : esc_html__('asides', 'grace-church');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'grace-church') : esc_html__('chats', 'grace-church');
		else						$name = $single ? esc_html__('standard', 'grace-church') : esc_html__('standards', 'grace-church');
		return apply_filters('grace_church_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'grace_church_get_post_format_icon' ) ) {
	function grace_church_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('grace_church_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'grace_church_get_list_fonts_styles' ) ) {
	function grace_church_get_list_fonts_styles($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_fonts_styles']))
			$list = $GRACE_CHURCH_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = esc_html__('I','grace-church');
			$list['u'] = esc_html__('U', 'grace-church');
			$GRACE_CHURCH_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'grace_church_get_list_fonts' ) ) {
	function grace_church_get_list_fonts($prepend_inherit=false) {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['list_fonts']))
			$list = $GRACE_CHURCH_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = grace_church_array_merge($list, grace_church_get_list_fonts_custom());
			// Google and custom fonts list:
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$list['Warugaki'] = array();
			$GRACE_CHURCH_GLOBALS['list_fonts'] = $list = apply_filters('grace_church_filter_list_fonts', $list);
		}
		return $prepend_inherit ? grace_church_array_merge(array('inherit' => esc_html__("Inherit", 'grace-church')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'grace_church_get_list_fonts_custom' ) ) {
    function grace_church_get_list_fonts_custom($prepend_inherit=false) {
        static $list = false;
        if (is_array($list)) return $list;
        $fonts = grace_church_get_global('required_custom_fonts');
        $list = array();
        if (is_array($fonts)) {
            foreach ($fonts as $font) {
                if (($url = grace_church_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
                    $list[sprintf(esc_html__('%s', 'grace-church'), $font)] = array('css' => $url);
                }
            }
        }
        return $list;
    }
}
?>