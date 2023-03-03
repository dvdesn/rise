<?php
/* Tribe Events (TE) support functions
------------------------------------------------------------------------------- */

// Theme init
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

            add_filter('grace_church_filter_add_sort_order', 				'grace_church_tribe_events_add_sort_order', 10, 3);/*+*/
            add_filter('grace_church_filter_orderby_need',					'grace_church_tribe_events_orderby_need', 9, 2);/*+*/

			add_action( 'grace_church_action_add_styles',				'grace_church_tribe_events_frontend_scripts' );

			add_filter('grace_church_filter_list_post_types', 			'grace_church_tribe_events_list_post_types', 10, 1);

            // Add Google API key to the map's link
            add_filter('tribe_events_google_maps_api',					'grace_church_tribe_events_google_maps_api');
            
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


// Add Google API key to the map's link
if ( !function_exists( 'grace_church_tribe_events_google_maps_api' ) ) {
    //Handler of add_filter('tribe_events_google_maps_api',	'grace_church_tribe_events_google_maps_api');
    function grace_church_tribe_events_google_maps_api($url) {
        $api_key = grace_church_get_theme_option('api_google');
        if ($api_key) {
            $url = grace_church_add_to_url($url, array(
                'key' => $api_key
            ));
        }
        return $url;
    }
}


// Add sorting parameter in query arguments
if (!function_exists('grace_church_tribe_events_add_sort_order')) {/*+*/
    function grace_church_tribe_events_add_sort_order($q, $orderby, $order) {
        if ($orderby == 'event_date') {
            $q['orderby'] = 'meta_value';
            $q['meta_key'] = '_EventStartDate';
        }
        return $q;
    }
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'grace_church_tribe_events_orderby_need' ) ) {/*+*/
    //Handler of add_filter('grace_church_filter_orderby_need',	'grace_church_tribe_events_orderby_need', 9, 1);
    function grace_church_tribe_events_orderby_need($need) {
        global $GRACE_CHURCH_STORAGE;
        if ($need == false || empty($GRACE_CHURCH_STORAGE['pre_query']))
            return $need;
        else {
            return ! ( grace_church_storage_get_obj_property('pre_query', 'tribe_is_event')
                || grace_church_storage_get_obj_property('pre_query', 'tribe_is_multi_posttype')
                || grace_church_storage_get_obj_property('pre_query', 'tribe_is_event_category')
                || grace_church_storage_get_obj_property('pre_query', 'tribe_is_event_venue')
                || grace_church_storage_get_obj_property('pre_query', 'tribe_is_event_organizer')
                || grace_church_storage_get_obj_property('pre_query', 'tribe_is_event_query')
                || grace_church_storage_get_obj_property('pre_query', 'tribe_is_past')
            );
        }
    }
}


if ( !function_exists( 'grace_church_tribe_events_settings_theme_setup2' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_tribe_events_settings_theme_setup2', 3 );
	function grace_church_tribe_events_settings_theme_setup2() {
		if (grace_church_exists_tribe_events()) {
			grace_church_add_theme_inheritance( array('tribe_events' => array(
				'stream_template' => 'tribe-events/default-template',
				'single_template' => '',
				'taxonomy' => array(Tribe__Events__Main::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(
					Tribe__Events__Main::POSTTYPE,
					Tribe__Events__Main::VENUE_POST_TYPE,
					Tribe__Events__Main::ORGANIZER_POST_TYPE
				),
				'override' => 'post'
				) )
			);
		}
	}
}

// Check if Tribe Events installed and activated
if (!function_exists('grace_church_exists_tribe_events')) {
	function grace_church_exists_tribe_events() {
		return class_exists( 'Tribe__Events__Main' );
	}
}


// Return true, if current page is any TE page
if ( !function_exists( 'grace_church_is_tribe_events_page' ) ) {
	function grace_church_is_tribe_events_page() {
		return !is_search() && class_exists('Tribe__Events__Main')
			? tribe_is_event() || tribe_is_event_query() || tribe_is_event_category() || tribe_is_event_venue() || tribe_is_event_organizer()
			: false;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'grace_church_tribe_events_detect_inheritance_key' ) ) {
	//Handler of add_filter('grace_church_filter_detect_inheritance_key',	'grace_church_tribe_events_detect_inheritance_key', 9, 1);
	function grace_church_tribe_events_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return grace_church_is_tribe_events_page() ? 'tribe_events' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'grace_church_tribe_events_get_blog_type' ) ) {
	//Handler of add_filter('grace_church_filter_get_blog_type',	'grace_church_tribe_events_get_blog_type', 10, 2);
	function grace_church_tribe_events_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if (!is_search() && grace_church_is_tribe_events_page()) {
			if (isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='day') 			$page = 'tribe_day';
			else if ( isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='month')	$page = 'tribe_month';
			else if (is_single())																									$page = 'tribe_event';
			else if (isset($query->tribe_is_event_venue) && $query->tribe_is_event_venue)			$page = 'tribe_venue';
			else if (isset($query->tribe_is_event_organizer) && $query->tribe_is_event_organizer)	$page = 'tribe_organizer';
			else if (isset($query->tribe_is_event_category) && $query->tribe_is_event_category)		$page = 'tribe_category';
			else if (is_tag())															$page = 'tribe_tag';
			else if (isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='upcoming')					$page = 'tribe_list';
			else																													$page = 'tribe';
		}
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'grace_church_tribe_events_get_blog_title' ) ) {
	//Handler of add_filter('grace_church_filter_get_blog_title',	'grace_church_tribe_events_get_blog_title', 10, 2);
	function grace_church_tribe_events_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( grace_church_strpos($page, 'tribe')!==false ) {
			if ( $page == 'tribe_category' ) {
				$cat = get_term_by( 'slug', get_query_var( 'tribe_events_cat' ), 'tribe_events_cat', ARRAY_A);
				$title = $cat['name'];
			} else if ( $page == 'tribe_tag' ) {
				$title = sprintf( esc_html__( 'Tag: %s', 'grace-church' ), single_tag_title( '', false ) );
			} else if ( $page == 'tribe_venue' ) {
				$title = sprintf( esc_html__( 'Lugar: %s', 'grace-church' ), tribe_get_venue());
			} else if ( $page == 'tribe_organizer' ) {
				$title = sprintf( esc_html__( 'Organizador: %s', 'grace-church' ), tribe_get_organizer());
			} else if ( $page == 'tribe_day' ) {
				$title = sprintf( esc_html__( 'Eventos diarios: %s', 'grace-church' ), date_i18n(tribe_get_date_format(true), strtotime(get_query_var( 'start_date' ))) );
			} else if ( $page == 'tribe_month' ) {
				$title = sprintf( esc_html__( 'Eventos mensuales: %s', 'grace-church' ), date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())));
			} else if ( $page == 'tribe_event' ) {
				$title = grace_church_get_post_title();
			} else {
				$title = esc_html__( 'Tribe Events', 'grace-church' );
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'grace_church_tribe_events_get_stream_page_title' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_title',	'grace_church_tribe_events_get_stream_page_title', 9, 2);
	function grace_church_tribe_events_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (grace_church_strpos($page, 'tribe')!==false) {
			if (($page_id = grace_church_tribe_events_get_stream_page_id(0, $page)) > 0)
				$title = grace_church_get_post_title($page_id);
			else
				$title = esc_html__( 'Todos los eventos', 'grace-church');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'grace_church_tribe_events_get_stream_page_id' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_id',	'grace_church_tribe_events_get_stream_page_id', 9, 2);
	function grace_church_tribe_events_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (grace_church_strpos($page, 'tribe')!==false) $id = grace_church_get_template_page_id('tribe-events/default-template');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'grace_church_tribe_events_get_stream_page_link' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_link',	'grace_church_tribe_events_get_stream_page_link', 9, 2);
	function grace_church_tribe_events_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (grace_church_strpos($page, 'tribe')!==false) $url = tribe_get_events_link();
		return $url;
	}
}

// Filter to return breadcrumbs links to the parent period
if ( !function_exists( 'grace_church_tribe_events_get_period_links' ) ) {
	//Handler of add_filter('grace_church_filter_get_period_links',	'grace_church_tribe_events_get_period_links', 9, 3);
	function grace_church_tribe_events_get_period_links($links, $page, $delimiter='') {
		if (!empty($links)) return $links;
		global $post;
		if ($page == 'tribe_day' && is_object($post))
			$links = '<a class="breadcrumbs_item cat_parent" href="' . esc_url(tribe_get_gridview_link(false)) . '">' . date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())) . '</a>';
		return $links;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'grace_church_tribe_events_get_current_taxonomy' ) ) {
	//Handler of add_filter('grace_church_filter_get_current_taxonomy',	'grace_church_tribe_events_get_current_taxonomy', 9, 2);
	function grace_church_tribe_events_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( grace_church_strpos($page, 'tribe')!==false ) {
			$tax = Tribe__Events__Main::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'grace_church_tribe_events_is_taxonomy' ) ) {
	//Handler of add_filter('grace_church_filter_is_taxonomy',	'grace_church_tribe_events_is_taxonomy', 10, 2);
	function grace_church_tribe_events_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else
			return $query && isset($query->tribe_is_event_category) && $query->tribe_is_event_category || is_tax(Tribe__Events__Main::TAXONOMY) ? Tribe__Events__Main::TAXONOMY : '';
	}
}

// Add custom post type into list
if ( !function_exists( 'grace_church_tribe_events_list_post_types' ) ) {
	//Handler of add_filter('grace_church_filter_list_post_types', 	'grace_church_tribe_events_list_post_types', 10, 1);
	function grace_church_tribe_events_list_post_types($list) {
		$list['tribe_events'] = esc_html__('Eventos', 'grace-church');
		return $list;
	}
}



// Return previous month and year with published posts
if ( !function_exists( 'grace_church_tribe_events_calendar_get_month_link' ) ) {
	//Handler of add_filter('grace_church_filter_calendar_get_month_link',	'grace_church_tribe_events_calendar_get_month_link', 9, 2);
	function grace_church_tribe_events_calendar_get_month_link($link, $opt) {
		if (!empty($opt['posts_types']) && in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types']) && count($opt['posts_types'])==1) {
			$events = Tribe__Events__Main::instance();
			$link = $events->getLink('month', ($opt['year']).'-'.($opt['month']), null);
		}
		return $link;
	}
}

// Return previous month and year with published posts
if ( !function_exists( 'grace_church_tribe_events_calendar_get_prev_month' ) ) {
	//Handler of add_filter('grace_church_filter_calendar_get_prev_month',	'grace_church_tribe_events_calendar_get_prev_month', 9, 2);
	function grace_church_tribe_events_calendar_get_prev_month($prev, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return;
		if (!empty($prev['done']) && in_array(Tribe__Events__Main::POSTTYPE, $prev['done'])) return;
		$args = array(
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'ignore_sticky_posts' => true,
			'orderby' => 'meta_value',
			'meta_key' => '_EventStartDate',
			'order' => 'desc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => ($opt['year']).'-'.($opt['month']).'-01',
					'compare' => '<',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($prev) || ($year+$month > 0 && ($prev['year']+$prev['month']==0 || ($prev['year']).($prev['month']) < ($year).($month)))) {
			$prev['year'] = $year;
			$prev['month'] = $month;
		}
		if (empty($prev['done'])) $prev['done'] = array();
		$prev['done'][] = Tribe__Events__Main::POSTTYPE;
		return $prev;
	}
}

// Return next month and year with published posts
if ( !function_exists( 'grace_church_tribe_events_calendar_get_next_month' ) ) {
	//Handler of add_filter('grace_church_filter_calendar_get_next_month',	'grace_church_tribe_events_calendar_get_next_month', 9, 2);
	function grace_church_tribe_events_calendar_get_next_month($next, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return;
		if (!empty($next['done']) && in_array(Tribe__Events__Main::POSTTYPE, $next['done'])) return;
		$args = array(
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'orderby' => 'meta_value',
			'ignore_sticky_posts' => true,
			'meta_key' => '_EventStartDate',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59',
					'compare' => '>',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($next) || ($year+$month > 0 && ($next['year']+$next['month'] ==0 || ($next['year']).($next['month']) > ($year).($month)))) {
			$next['year'] = $year;
			$next['month'] = $month;
		}
		if (empty($next['done'])) $next['done'] = array();
		$next['done'][] = Tribe__Events__Main::POSTTYPE;
		return $next;
	}
}

// Return current month published posts
if ( !function_exists( 'grace_church_tribe_events_calendar_get_curr_month_posts' ) ) {
	//Handler of add_filter('grace_church_filter_calendar_get_curr_month_posts',	'grace_church_tribe_events_calendar_get_curr_month_posts', 9, 2);
	function grace_church_tribe_events_calendar_get_curr_month_posts($posts, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return;
		if (!empty($posts['done']) && in_array(Tribe__Events__Main::POSTTYPE, $posts['done'])) return;
		$args = array(
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => -1,
			'ignore_sticky_posts' => true,
			'orderby' => 'meta_value',
			'meta_key' => '_EventStartDate',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => array(($opt['year']).'-'.($opt['month']).'-01', ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59'),
					'compare' => 'BETWEEN',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		if ($q->have_posts()) {
			if (empty($posts)) $posts = array();
			$events = Tribe__Events__Main::instance();
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$day = (int) date('d', $dt);
				$title = get_the_title();
				if (empty($posts[$day]))
					$posts[$day] = array();
				if (empty($posts[$day]['link']) && count($opt['posts_types'])==1)
					$posts[$day]['link'] = $events->getLink('day', ($opt['year']).'-'.($opt['month']).'-'.($day), null);
				if (empty($posts[$day]['titles']))
					$posts[$day]['titles'] = $title;
				else
					$posts[$day]['titles'] = is_int($posts[$day]['titles']) ? $posts[$day]['titles']+1 : 2;
				if (empty($posts[$day]['posts'])) $posts[$day]['posts'] = array();
				$posts[$day]['posts'][] = array(
					'post_id' => get_the_ID(),
					'post_type' => get_post_type(),
					'post_date' => date(get_option('date_format'), $dt),
					'post_title' => $title,
					'post_link' => get_permalink()
				);
			}
			wp_reset_postdata();
		}
		if (empty($posts['done'])) $posts['done'] = array();
		$posts['done'][] = Tribe__Events__Main::POSTTYPE;
		return $posts;
	}
}



// Enqueue Tribe Events custom styles
if ( !function_exists( 'grace_church_tribe_events_frontend_scripts' ) ) {
	//Handler of add_action( 'grace_church_action_add_styles', 'grace_church_tribe_events_frontend_scripts' );
	function grace_church_tribe_events_frontend_scripts() {
		global $wp_styles;
		$wp_styles->done[] = 'tribe-events-custom-jquery-styles';
		wp_enqueue_style( 'grace-church-tribe-style',  grace_church_get_file_url('css/tribe-style.css'), array(), null );


	}
}




// Before main content
if ( !function_exists( 'grace_church_tribe_events_wrapper_start' ) ) {
	//Handler of add_filter('tribe_events_before_html', 'grace_church_tribe_events_wrapper_start');
	function grace_church_tribe_events_wrapper_start($html) {
		return '
		<section class="post tribe_events_wrapper">
			<article class="post_content">
		' . ($html);
	}
}

// After main content
if ( !function_exists( 'grace_church_tribe_events_wrapper_end' ) ) {
	//Handler of add_filter('tribe_events_after_html', 'grace_church_tribe_events_wrapper_end');
	function grace_church_tribe_events_wrapper_end($html) {
		return $html . '
			</article><!-- .post_content -->
		</section>
		';
	}
}

/* Add image sizes from Events */
add_image_size( 'grace-church-events', 600, 806 );
?>