<?php
/**
 * Grace-Church Framework: Team post type settings
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Theme init
if (!function_exists('grace_church_team_theme_setup')) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_team_theme_setup' );
	function grace_church_team_theme_setup() {

		// Add item in the admin menu
        add_filter('trx_utils_filter_override_options',							'grace_church_team_add_override_options');

		// Save data from meta box
		add_action('save_post',								'grace_church_team_save_data');
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('grace_church_filter_get_blog_type',			'grace_church_team_get_blog_type', 9, 2);
		add_filter('grace_church_filter_get_blog_title',		'grace_church_team_get_blog_title', 9, 2);
		add_filter('grace_church_filter_get_current_taxonomy',	'grace_church_team_get_current_taxonomy', 9, 2);
		add_filter('grace_church_filter_is_taxonomy',			'grace_church_team_is_taxonomy', 9, 2);
		add_filter('grace_church_filter_get_stream_page_title',	'grace_church_team_get_stream_page_title', 9, 2);
		add_filter('grace_church_filter_get_stream_page_link',	'grace_church_team_get_stream_page_link', 9, 2);
		add_filter('grace_church_filter_get_stream_page_id',	'grace_church_team_get_stream_page_id', 9, 2);
		add_filter('grace_church_filter_query_add_filters',		'grace_church_team_query_add_filters', 9, 2);
		add_filter('grace_church_filter_detect_inheritance_key','grace_church_team_detect_inheritance_key', 9, 1);

		// Extra column for team members lists
		if (grace_church_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-team_columns',			'grace_church_post_add_options_column', 9);
			add_filter('manage_team_posts_custom_column',	'grace_church_post_fill_options_column', 9, 2);
		}

		// Add shortcodes [trx_team] and [trx_team_item]
		add_action('grace_church_action_shortcodes_list',		'grace_church_team_reg_shortcodes');
		add_action('grace_church_action_shortcodes_list_vc',	'grace_church_team_reg_shortcodes_vc');

		// Meta box fields
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['team_override_options'] = array(
			'id' => 'team-override-options',
			'title' => esc_html__('Team Member Details', 'grace-church'),
			'page' => 'team',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"team_member_position" => array(
					"title" => esc_html__('Position',  'grace-church'),
					"desc" => esc_html__("Position of the team member", 'grace-church'),
					"class" => "team_member_position",
					"std" => "",
					"type" => "text"),
				"team_member_email" => array(
					"title" => esc_html__("E-mail",  'grace-church'),
					"desc" => esc_html__("E-mail of the team member - need to take Gravatar (if registered)", 'grace-church'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "text"),
				"team_member_link" => array(
					"title" => esc_html__('Link to profile',  'grace-church'),
					"desc" => esc_html__("URL of the team member profile page (if not this page)", 'grace-church'),
					"class" => "team_member_link",
					"std" => "",
					"type" => "text"),
				"team_member_socials" => array(
					"title" => esc_html__("Social links",  'grace-church'),
					"desc" => esc_html__("Links to the social profiles of the team member", 'grace-church'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "social")
			)
		);
		
		if (function_exists('grace_church_require_data')) {
			// Prepare type "Team"
			grace_church_require_data( 'post_type', 'team', array(
				'label'               => esc_html__( 'Team member', 'grace-church' ),
				'description'         => esc_html__( 'Team Description', 'grace-church' ),
				'labels'              => array(
					'name'                => _x( 'Imagenes', 'Post Type General Name', 'grace-church' ),
					'singular_name'       => _x( 'Team member', 'Post Type Singular Name', 'grace-church' ),
					'menu_name'           => esc_html__( 'Imagens', 'grace-church' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'grace-church' ),
					'all_items'           => esc_html__( 'All Team', 'grace-church' ),
					'view_item'           => esc_html__( 'View Item', 'grace-church' ),
					'add_new_item'        => esc_html__( 'Add New Team member', 'grace-church' ),
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
				'menu_icon'			  => 'dashicons-admin-users',
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
				'rewrite'             => array('slug' => 'imagenes')
				)
			);
			
			// Prepare taxonomy for team
			grace_church_require_data( 'taxonomy', 'team_group', array(
				'post_type'			=> array( 'team' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Team Group', 'taxonomy general name', 'grace-church' ),
					'singular_name'     => _x( 'Group', 'taxonomy singular name', 'grace-church' ),
					'search_items'      => esc_html__( 'Search Groups', 'grace-church' ),
					'all_items'         => esc_html__( 'All Groups', 'grace-church' ),
					'parent_item'       => esc_html__( 'Parent Group', 'grace-church' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'grace-church' ),
					'edit_item'         => esc_html__( 'Edit Group', 'grace-church' ),
					'update_item'       => esc_html__( 'Update Group', 'grace-church' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'grace-church' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'grace-church' ),
					'menu_name'         => esc_html__( 'Team Group', 'grace-church' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'team_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'grace_church_team_settings_theme_setup2' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_team_settings_theme_setup2', 3 );
	function grace_church_team_settings_theme_setup2() {
		// Add post type 'team' and taxonomy 'team_group' into theme inheritance list
		grace_church_add_theme_inheritance( array('team' => array(
			'stream_template' => 'blog-team',
			'single_template' => 'single-team',
			'taxonomy' => array('team_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('team'),
			'override' => 'page'
			) )
		);
	}
}


// Add meta box
if (!function_exists('grace_church_team_add_override_options')) {
	//Handler of add_action('admin_menu', 'grace_church_team_add_override_options');
	function grace_church_team_add_override_options($boxes = array()) {
        $boxes[] = array_merge(grace_church_get_global('team_override_options'), array('callback' => 'grace_church_team_show_override_options'));
        return $boxes;
	}
}

// Callback function to show fields in meta box
if (!function_exists('grace_church_team_show_override_options')) {
	function grace_church_team_show_override_options() {
		global $post, $GRACE_CHURCH_GLOBALS;

		// Use nonce for verification
		$data = get_post_meta($post->ID, 'team_data', true);
		$fields = $GRACE_CHURCH_GLOBALS['team_override_options']['fields'];
		?>
        <input type="hidden" name="override_options_team_nonce" value="<?php echo esc_attr($GRACE_CHURCH_GLOBALS['admin_nonce']); ?>" />
		<table class="team_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td>
						<?php
						if ($id == 'team_member_socials') {
							$socials_type = grace_church_get_theme_setting('socials_type');
							$social_list = grace_church_get_theme_option('social_icons');
							if (is_array($social_list) && count($social_list) > 0) {
								foreach ($social_list as $soc) {
									if ($socials_type == 'icons') {
										$parts = explode('-', $soc['icon'], 2);
										$sn = isset($parts[1]) ? $parts[1] : $sn;
									} else {
										$sn = basename($soc['icon']);
										$sn = grace_church_substr($sn, 0, grace_church_strrpos($sn, '.'));
										if (($pos=grace_church_strrpos($sn, '_'))!==false)
											$sn = grace_church_substr($sn, 0, $pos);
									}   
									$link = isset($meta[$sn]) ? $meta[$sn] : '';
									?>
									<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_attr(grace_church_strtoproper($sn)); ?></label><br>
									<input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
									<?php
								}
							}
						} else {
							?>
							<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
							<?php
						}
						?>
						<br><small><?php echo esc_attr($field['desc']); ?></small>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('grace_church_team_save_data')) {
	//Handler of add_action('save_post', 'grace_church_team_save_data');
	function grace_church_team_save_data($post_id) {
        global $GRACE_CHURCH_GLOBALS;
		// verify nonce
        if (!isset($_POST['override_options_team_nonce']) || !wp_verify_nonce($_POST['override_options_team_nonce'], $GRACE_CHURCH_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $GRACE_CHURCH_GLOBALS;

		$data = array();

		$fields = $GRACE_CHURCH_GLOBALS['team_override_options']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
				if (isset($_POST[$id])) {
					if (is_array($_POST[$id]) && count($_POST[$id]) > 0) {
						foreach ($_POST[$id] as $sn=>$link) {
							$_POST[$id][$sn] = stripslashes($link);
						}
						$data[$id] = $_POST[$id];
					} else {
						$data[$id] = stripslashes($_POST[$id]);
					}
				}
			}
		}

		update_post_meta($post_id, 'team_data', $data);
	}
}



// Return true, if current page is team member page
if ( !function_exists( 'grace_church_is_team_page' ) ) {
	function grace_church_is_team_page() {
		return get_query_var('post_type')=='team' || is_tax('team_group') || (is_page() && grace_church_get_template_page_id('blog-team')==get_the_ID());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'grace_church_team_detect_inheritance_key' ) ) {
	//Handler of add_filter('grace_church_filter_detect_inheritance_key',	'grace_church_team_detect_inheritance_key', 9, 1);
	function grace_church_team_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return grace_church_is_team_page() ? 'team' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'grace_church_team_get_blog_type' ) ) {
	//Handler of add_filter('grace_church_filter_get_blog_type',	'grace_church_team_get_blog_type', 9, 2);
	function grace_church_team_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('team_group') || is_tax('team_group'))
			$page = 'team_category';
		else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
			$page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'grace_church_team_get_blog_title' ) ) {
	//Handler of add_filter('grace_church_filter_get_blog_title',	'grace_church_team_get_blog_title', 9, 2);
	function grace_church_team_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( grace_church_strpos($page, 'team')!==false ) {
			if ( $page == 'team_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'team_item' ) {
				$title = grace_church_get_post_title();
			} else {
				$title = esc_html__('All team', 'grace-church');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'grace_church_team_get_stream_page_title' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_title',	'grace_church_team_get_stream_page_title', 9, 2);
	function grace_church_team_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (grace_church_strpos($page, 'team')!==false) {
			if (($page_id = grace_church_team_get_stream_page_id(0, $page=='team' ? 'blog-team' : $page)) > 0)
				$title = grace_church_get_post_title($page_id);
			else
				$title = esc_html__('All team', 'grace-church');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'grace_church_team_get_stream_page_id' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_id',	'grace_church_team_get_stream_page_id', 9, 2);
	function grace_church_team_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (grace_church_strpos($page, 'team')!==false) $id = grace_church_get_template_page_id('blog-team');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'grace_church_team_get_stream_page_link' ) ) {
	//Handler of add_filter('grace_church_filter_get_stream_page_link',	'grace_church_team_get_stream_page_link', 9, 2);
	function grace_church_team_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (grace_church_strpos($page, 'team')!==false) {
			$id = grace_church_get_template_page_id('blog-team');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'grace_church_team_get_current_taxonomy' ) ) {
	//Handler of add_filter('grace_church_filter_get_current_taxonomy',	'grace_church_team_get_current_taxonomy', 9, 2);
	function grace_church_team_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( grace_church_strpos($page, 'team')!==false ) {
			$tax = 'team_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'grace_church_team_is_taxonomy' ) ) {
	//Handler of add_filter('grace_church_filter_is_taxonomy',	'grace_church_team_is_taxonomy', 9, 2);
	function grace_church_team_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'grace_church_team_query_add_filters' ) ) {
	//Handler of add_filter('grace_church_filter_query_add_filters',	'grace_church_team_query_add_filters', 9, 2);
	function grace_church_team_query_add_filters($args, $filter) {
		if ($filter == 'team') {
			$args['post_type'] = 'team';
		}
		return $args;
	}
}





// ---------------------------------- [trx_team] ---------------------------------------

if ( !function_exists( 'grace_church_sc_team' ) ) {
	function grace_church_sc_team($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "team-4",
			"slider" => "no",
			"controls" => "no",
			"slides_space" => 0,
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 3,
			"columns" => 3,
			"offset" => "",
			"orderby" => "date",
			"order" => "asc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'grace-church'),
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

		if (empty($id)) $id = "sc_team_".str_replace('.', '', mt_rand());
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
		$GRACE_CHURCH_GLOBALS['sc_team_id'] = $id;
		$GRACE_CHURCH_GLOBALS['sc_team_style'] = $style;
		$GRACE_CHURCH_GLOBALS['sc_team_columns'] = $columns;
		$GRACE_CHURCH_GLOBALS['sc_team_counter'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_team_slider'] = $slider;
		$GRACE_CHURCH_GLOBALS['sc_team_css_wh'] = $ws . $hs;

		if (grace_church_param_is_on($slider)) grace_church_enqueue_slider('swiper');
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_team_wrap'
						. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_team sc_team_style_'.esc_attr($style)
							. ' ' . esc_attr(grace_church_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(grace_church_get_slider_controls_classes($controls))
							. (grace_church_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (grace_church_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!empty($width) && grace_church_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && grace_church_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_team_subtitle sc_item_subtitle">' . trim(grace_church_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_team_title sc_item_title">' . trim(grace_church_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_team_descr sc_item_descr">' . trim(grace_church_strmacros($description)) . '</div>' : '')
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
				'post_type' => 'team',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order,
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = grace_church_query_add_sort_order($args, $orderby, $order);
			$args = grace_church_query_add_posts_and_cats($args, $ids, 'team', $cat, 'team_group');
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
					"columns_count" => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = grace_church_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], 'team_data', true);
				$thumb_sizes = grace_church_get_thumb_sizes(array('layout' => $style));
				$args['position'] = $post_meta['team_member_position'];
				$args['link'] = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : $post_data['post_link'];
				$args['email'] = $post_meta['team_member_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, grace_church_get_theme_option("retina_ready"))));
				$args['socials'] = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$args['socials'] = grace_church_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
	
				$output .= grace_church_show_post_layout($args, $post_data);
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

		$output .= (!empty($link) ? '<div class="sc_team_button sc_item_button">'.grace_church_do_shortcode('[trx_button link="'.esc_url($link).'" style="border" size="large" icon="none"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_team -->'
				. '</div><!-- /.sc_team_wrap -->';
	
		return apply_filters('grace_church_shortcode_output', $output, 'trx_team', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_team', 'grace_church_sc_team');
}


if ( !function_exists( 'grace_church_sc_team_item' ) ) {
	function grace_church_sc_team_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"user" => "",
			"member" => "",
			"name" => "",
			"position" => "",
			"photo" => "",
			"email" => "",
			"link" => "",
			"socials" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_team_counter']++;
	
		$id = $id ? $id : ($GRACE_CHURCH_GLOBALS['sc_team_id'] ? $GRACE_CHURCH_GLOBALS['sc_team_id'] . '_' . $GRACE_CHURCH_GLOBALS['sc_team_counter'] : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = grace_church_get_thumb_sizes(array('layout' => $GRACE_CHURCH_GLOBALS['sc_team_style']));
	
		if (!empty($socials)) $socials = grace_church_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($socials).'"][/trx_socials]');
	
		if (!empty($user) && $user!='none' && ($user_obj = get_user_by('login', $user)) != false) {
			$meta = get_user_meta($user_obj->ID);
			if (empty($email))		$email = $user_obj->data->user_email;
			if (empty($name))		$name = $user_obj->data->display_name;
			if (empty($position))	$position = isset($meta['user_position'][0]) ? $meta['user_position'][0] : '';
			if (empty($descr))		$descr = isset($meta['description'][0]) ? $meta['description'][0] : '';
			if (empty($socials))	$socials = grace_church_show_user_socials(array('author_id'=>$user_obj->ID, 'echo'=>false));
		}
	
		if (!empty($member) && $member!='none' && ($member_obj = (intval($member) > 0 ? get_post($member, OBJECT) : get_page_by_title($member, OBJECT, 'team'))) != null) {
			if (empty($name))		$name = $member_obj->post_title;
			if (empty($descr))		$descr = $member_obj->post_excerpt;
			$post_meta = get_post_meta($member_obj->ID, 'team_data', true);
			if (empty($position))	$position = $post_meta['team_member_position'];
			if (empty($link))		$link = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : get_permalink($member_obj->ID);
			if (empty($email))		$email = $post_meta['team_member_email'];
			if (empty($photo)) 		$photo = wp_get_attachment_url(get_post_thumbnail_id($member_obj->ID));
			if (empty($socials)) {
				$socials = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$socials = grace_church_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
			}
		}
		if (empty($photo)) {
			if (!empty($email)) $photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, grace_church_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = grace_church_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}
		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => $GRACE_CHURCH_GLOBALS['sc_team_style'],
			'number' => $GRACE_CHURCH_GLOBALS['sc_team_counter'],
			'columns_count' => $GRACE_CHURCH_GLOBALS['sc_team_columns'],
			'slider' => $GRACE_CHURCH_GLOBALS['sc_team_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $GRACE_CHURCH_GLOBALS['sc_team_css_wh'],
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo,
			'socials' => $socials
		);
		$output = grace_church_show_post_layout($args, $post_data);

		return apply_filters('grace_church_shortcode_output', $output, 'trx_team_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_team_item', 'grace_church_sc_team_item');
}
// ---------------------------------- [/trx_team] ---------------------------------------



// Add [trx_team] and [trx_team_item] in the shortcodes list
if (!function_exists('grace_church_team_reg_shortcodes')) {
	//Handler of add_filter('grace_church_action_shortcodes_list',	'grace_church_team_reg_shortcodes');
	function grace_church_team_reg_shortcodes() {
		global $GRACE_CHURCH_GLOBALS;
		if (isset($GRACE_CHURCH_GLOBALS['shortcodes'])) {

			$users = grace_church_get_list_users();
			$members = grace_church_get_list_posts(false, array(
				'post_type'=>'team',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$team_groups = grace_church_get_list_terms(false, 'team_group');
			$team_styles = grace_church_get_list_templates('team');
			$controls	 = grace_church_get_list_slider_controls();

			grace_church_array_insert_after($GRACE_CHURCH_GLOBALS['shortcodes'], 'trx_tabs', array(

				// Team
				"trx_team" => array(
					"title" => esc_html__("Team", "grace-church"),
					"desc" => esc_html__("Insert team in your page (post)", "grace-church"),
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
							"title" => esc_html__("Team style", "grace-church"),
							"desc" => esc_html__("Select style to display team members", "grace-church"),
							"value" => "4",
							"type" => "select",
							"options" => $team_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", "grace-church"),
							"desc" => esc_html__("How many columns use to show team members", "grace-church"),
							"value" => 3,
							"min" => 2,
							"max" => 5,
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
							"desc" => esc_html__("Use slider to show team members", "grace-church"),
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
							"desc" => esc_html__("Alignment of the team block", "grace-church"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "grace-church"),
							"desc" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "grace-church"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "grace-church"),
							"desc" => esc_html__("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "grace-church"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'grace-church')), $team_groups)
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
						"name" => "trx_team_item",
						"title" => esc_html__("Member", "grace-church"),
						"desc" => esc_html__("Team member", "grace-church"),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => esc_html__("Registerd user", "grace-church"),
								"desc" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", "grace-church"),
								"value" => "",
								"type" => "select",
								"options" => $users
							),
							"member" => array(
								"title" => esc_html__("Team member", "grace-church"),
								"desc" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", "grace-church"),
								"value" => "",
								"type" => "select",
								"options" => $members
							),
							"link" => array(
								"title" => esc_html__("Link", "grace-church"),
								"desc" => esc_html__("Link on team member's personal page", "grace-church"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => esc_html__("Name", "grace-church"),
								"desc" => esc_html__("Team member's name", "grace-church"),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", "grace-church"),
								"desc" => esc_html__("Team member's position", "grace-church"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", "grace-church"),
								"desc" => esc_html__("Team member's e-mail", "grace-church"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", "grace-church"),
								"desc" => esc_html__("Team member's photo (avatar)", "grace-church"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => esc_html__("Socials", "grace-church"),
								"desc" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "grace-church"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "grace-church"),
								"desc" => esc_html__("Team member's short description", "grace-church"),
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


// Add [trx_team] and [trx_team_item] in the VC shortcodes list
if (!function_exists('grace_church_team_reg_shortcodes_vc')) {
	//Handler of add_filter('grace_church_action_shortcodes_list_vc',	'grace_church_team_reg_shortcodes_vc');
	function grace_church_team_reg_shortcodes_vc() {
		global $GRACE_CHURCH_GLOBALS;

		$users = grace_church_get_list_users();
		$members = grace_church_get_list_posts(false, array(
			'post_type'=>'team',
			'orderby'=>'title',
			'order'=>'asc',
			'return'=>'title'
			)
		);
		$team_groups = grace_church_get_list_terms(false, 'team_group');
		$team_styles = grace_church_get_list_templates('team');
		$controls	 = grace_church_get_list_slider_controls();

		// Team
		vc_map( array(
				"base" => "trx_team",
				"name" => esc_html__("Team", "grace-church"),
				"description" => esc_html__("Insert team members", "grace-church"),
				"category" => esc_html__('Content', 'grace-church'),
				'icon' => 'icon_trx_team',
				"class" => "trx_sc_columns trx_sc_team",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_team_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Team style", "grace-church"),
						"description" => esc_html__("Select style to display team members", "grace-church"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($team_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "grace-church"),
						"description" => esc_html__("How many columns use to show team members", "grace-church"),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
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
						"description" => esc_html__("Use slider to show team members", "grace-church"),
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
						"description" => esc_html__("Alignment of the team block", "grace-church"),
						"class" => "",
						"value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "grace-church"),
						"description" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "grace-church"),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
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
						"description" => esc_html__("Select category to show team members. If empty - select team members from any category (group) or from IDs list", "grace-church"),
						"group" => esc_html__('Query', 'grace-church'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'grace-church')), $team_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "grace-church"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "grace-church"),
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
					[trx_team_item user="' . esc_html__( 'Member 1', 'grace-church' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 2', 'grace-church' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 4', 'grace-church' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_team_item",
				"name" => esc_html__("Team member", "grace-church"),
				"description" => esc_html__("Team member - all data pull out from it account on your site", "grace-church"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_item trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Registered user", "grace-church"),
						"description" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", "grace-church"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($users),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => esc_html__("Team member", "grace-church"),
						"description" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", "grace-church"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($members),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "grace-church"),
						"description" => esc_html__("Link on team member's personal page", "grace-church"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", "grace-church"),
						"description" => esc_html__("Team member's name", "grace-church"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", "grace-church"),
						"description" => esc_html__("Team member's position", "grace-church"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", "grace-church"),
						"description" => esc_html__("Team member's e-mail", "grace-church"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Member's Photo", "grace-church"),
						"description" => esc_html__("Team member's photo (avatar)", "grace-church"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Socials", "grace-church"),
						"description" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "grace-church"),
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
			
		class WPBakeryShortCode_Trx_Team extends GRACE_CHURCH_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Team_Item extends GRACE_CHURCH_VC_ShortCodeItem {}

	}
}
?>