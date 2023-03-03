<?php
/**
 * Grace-Church Framework: Testimonial post type settings
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Theme init
if (!function_exists('grace_church_testimonial_theme_setup')) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_testimonial_theme_setup' );
	function grace_church_testimonial_theme_setup() {
	
		// Add item in the admin menu
        add_filter('trx_utils_filter_override_options',			'grace_church_testimonial_add_override_options');

		// Save data from override options
		add_action('save_post',				'grace_church_testimonial_save_data');

		// Options fields
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['testimonial_override_options'] = array(
			'id' => 'testimonial-override-options',
			'title' => esc_html__('Testimonial Details', 'grace-church'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'grace-church'),
					"desc" => esc_html__("Name of the testimonial's author", 'grace-church'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'grace-church'),
					"desc" => esc_html__("Position of the testimonial's author", 'grace-church'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'grace-church'),
					"desc" => esc_html__("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'grace-church'),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'grace-church'),
					"desc" => esc_html__("URL of the testimonial source or author profile page", 'grace-church'),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);
		
		if (function_exists('grace_church_require_data')) {
			// Prepare type "Testimonial"
			grace_church_require_data( 'post_type', 'testimonial', array(
				'label'               => esc_html__( 'Testimonial', 'grace-church' ),
				'description'         => esc_html__( 'Testimonial Description', 'grace-church' ),
				'labels'              => array(
					'name'                => esc_html_x( 'Testimonials', 'Post Type General Name', 'grace-church' ),
					'singular_name'       => esc_html_x( 'Testimonial', 'Post Type Singular Name', 'grace-church' ),
					'menu_name'           => esc_html__( 'Testimonials', 'grace-church' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'grace-church' ),
					'all_items'           => esc_html__( 'All Testimonials', 'grace-church' ),
					'view_item'           => esc_html__( 'View Item', 'grace-church' ),
					'add_new_item'        => esc_html__( 'Add New Testimonial', 'grace-church' ),
					'add_new'             => esc_html__( 'Add New', 'grace-church' ),
					'edit_item'           => esc_html__( 'Edit Item', 'grace-church' ),
					'update_item'         => esc_html__( 'Update Item', 'grace-church' ),
					'search_items'        => esc_html__( 'Search Item', 'grace-church' ),
					'not_found'           => esc_html__( 'Not found', 'grace-church' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'grace-church' ),
				),
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-cloud',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 26,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				)
			);
			
			// Prepare taxonomy for testimonial
			grace_church_require_data( 'taxonomy', 'testimonial_group', array(
				'post_type'			=> array( 'testimonial' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html_x( 'Testimonials Group', 'taxonomy general name', 'grace-church' ),
					'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'grace-church' ),
					'search_items'      => esc_html__( 'Search Groups', 'grace-church' ),
					'all_items'         => esc_html__( 'All Groups', 'grace-church' ),
					'parent_item'       => esc_html__( 'Parent Group', 'grace-church' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'grace-church' ),
					'edit_item'         => esc_html__( 'Edit Group', 'grace-church' ),
					'update_item'       => esc_html__( 'Update Group', 'grace-church' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'grace-church' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'grace-church' ),
					'menu_name'         => esc_html__( 'Testimonial Group', 'grace-church' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'testimonial_group' ),
				)
			);
		}
	}
}


// Add override options
if (!function_exists('grace_church_testimonial_add_override_options')) {
	//Handler of add_action('admin_menu', 'grace_church_testimonial_add_override_options');
	function grace_church_testimonial_add_override_options($boxes = array()) {
        $boxes[] = array_merge(grace_church_get_global('testimonial_override_options'), array('callback' => 'grace_church_testimonial_show_override_options'));
        return $boxes;
	}
}

// Callback function to show fields in override options
if (!function_exists('grace_church_testimonial_show_override_options')) {
	function grace_church_testimonial_show_override_options() {
		global $post, $GRACE_CHURCH_GLOBALS;

		// Use nonce for verification
        echo '<input type="hidden" name="override_options_testimonial_nonce" value="', esc_attr($GRACE_CHURCH_GLOBALS['admin_nonce']), '" />';
		
		$data = get_post_meta($post->ID, 'testimonial_data', true);
	
		$fields = $GRACE_CHURCH_GLOBALS['testimonial_override_options']['fields'];
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_html($field['desc']); ?></small></td>
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
if (!function_exists('grace_church_testimonial_save_data')) {
	//Handler of add_action('save_post', 'grace_church_testimonial_save_data');
	function grace_church_testimonial_save_data($post_id) {
        global $GRACE_CHURCH_GLOBALS;
		// verify nonce
        if (!isset($_POST['override_options_testimonial_nonce']) || !wp_verify_nonce($_POST['override_options_testimonial_nonce'], $GRACE_CHURCH_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = $GRACE_CHURCH_GLOBALS['testimonial_override_options']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}
?>