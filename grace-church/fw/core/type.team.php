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

		// Save data from override options
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

		// Options fields
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
					'name'                => esc_html_x( 'Team', 'Post Type General Name', 'grace-church' ),
					'singular_name'       => esc_html_x( 'Team member', 'Post Type Singular Name', 'grace-church' ),
					'menu_name'           => esc_html__( 'Team', 'grace-church' ),
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
				'rewrite'             => true
				)
			);
			
			// Prepare taxonomy for team
			grace_church_require_data( 'taxonomy', 'team_group', array(
				'post_type'			=> array( 'team' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html_x( 'Team Group', 'taxonomy general name', 'grace-church' ),
					'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'grace-church' ),
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


// Add override options
if (!function_exists('grace_church_team_add_override_options')) {
	//Handler of add_action('admin_menu', 'grace_church_team_add_override_options');
	function grace_church_team_add_override_options($boxes = array()) {
        $boxes[] = array_merge(grace_church_get_global('team_override_options'), array('callback' => 'grace_church_team_show_override_options'));
        return $boxes;
	}
}

// Callback function to show fields in override options
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
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($field['title']); ?></label></td>
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
									<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_html(grace_church_strtoproper($sn)); ?></label><br>
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
						<br><small><?php echo esc_html($field['desc']); ?></small>
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


// Save data from override options
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
                $social_temp = array();
                if (isset($_POST[$id])) {
                    if (is_array($_POST[$id]) && count($_POST[$id]) > 0) {
                        foreach ($_POST[$id] as $sn=>$link) {
                            $social_temp[$sn] = stripslashes($link);
                        }
                        $data[$id] = $social_temp;
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
?>