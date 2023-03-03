<?php
/**
 * Grace-Church Framework: attachment manipulations
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Theme init
if ( !function_exists( 'grace_church_attachment_settings_theme_setup2' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_attachment_settings_theme_setup2', 3 );
	function grace_church_attachment_settings_theme_setup2() {
		grace_church_add_theme_inheritance( array('attachment' => array(
			'stream_template' => '',
			'single_template' => 'attachment',
			'taxonomy' => array(),
			'taxonomy_tags' => array(),
			'post_type' => array('attachment'),
			'override' => 'post'
			) )
		);
	}
}

if (!function_exists('grace_church_attachment_theme_setup')) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_attachment_theme_setup');
	function grace_church_attachment_theme_setup() {

		// Add folders in ajax query
		add_filter('ajax_query_attachments_args',				'grace_church_attachment_ajax_query_args');

		// Add folders in filters for js view
		add_filter('media_view_settings',						'grace_church_attachment_view_filters');

		// Add folders list in js view compat area
		add_filter('attachment_fields_to_edit',					'grace_church_attachment_view_compat');

		// Prepare media folders for save
		add_filter( 'attachment_fields_to_save',				'grace_church_attachment_save_compat');

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('grace_church_filter_detect_inheritance_key',	'grace_church_attachmnent_detect_inheritance_key', 9, 1);

		// Prepare taxonomy for attachment
		if (function_exists('grace_church_require_data')) {
			grace_church_require_data( 'taxonomy', 'media_folder', array(
				'post_type'			=> array( 'attachment' ),
				'hierarchical' 		=> true,
				'labels' 			=> array(
					'name'              => esc_html__('Media Folders', 'grace-church'),
					'singular_name'     => esc_html__('Media Folder', 'grace-church'),
					'search_items'      => esc_html__('Search Media Folders', 'grace-church'),
					'all_items'         => esc_html__('All Media Folders', 'grace-church'),
					'parent_item'       => esc_html__('Parent Media Folder', 'grace-church'),
					'parent_item_colon' => esc_html__('Parent Media Folder:', 'grace-church'),
					'edit_item'         => esc_html__('Edit Media Folder', 'grace-church'),
					'update_item'       => esc_html__('Update Media Folder', 'grace-church'),
					'add_new_item'      => esc_html__('Add New Media Folder', 'grace-church'),
					'new_item_name'     => esc_html__('New Media Folder Name', 'grace-church'),
					'menu_name'         => esc_html__('Media Folders', 'grace-church'),
				),
				'query_var'			=> true,
				'rewrite' 			=> true,
				'show_admin_column'	=> true
				)
			);
		}
	}
}


// Add folders in ajax query
if (!function_exists('grace_church_attachment_ajax_query_args')) {
	//Handler of add_filter('ajax_query_attachments_args', 'grace_church_attachment_ajax_query_args');
	function grace_church_attachment_ajax_query_args($query) {
		if (isset($query['post_mime_type'])) {
			$v = $query['post_mime_type'];
			if (grace_church_substr($v, 0, 13)=='media_folder.') {
				unset($query['post_mime_type']);
				if (grace_church_strlen($v) > 13)
					$query['media_folder'] = grace_church_substr($v, 13);
				else {
					$list_ids = array();
					$terms = grace_church_get_terms_by_taxonomy('media_folder');
					if (is_array($terms) && count($terms) > 0) {
						foreach ($terms as $term) {
							$list_ids[] = $term->term_id;
						}
					}
					if (count($list_ids) > 0) {
						$query['tax_query'] = array(
							array(
								'taxonomy' => 'media_folder',
								'field' => 'id',
								'terms' => $list_ids,
								'operator' => 'NOT IN'
							)
						);
					}
				}
			}
		}
		return $query;
	}
}

// Add folders in filters for js view
if (!function_exists('grace_church_attachment_view_filters')) {
	//Handler of add_filter('media_view_settings', 'grace_church_attachment_view_filters');
	function grace_church_attachment_view_filters($settings, $post=null) {
		$taxes = array('media_folder');
		if (is_array($taxes) && count($taxes) > 0) {
			foreach ($taxes as $tax) {
				$terms = grace_church_get_terms_by_taxonomy($tax, array(
					'hide_empty' => false,
					'childless' => false,
					'child_of' => 0, 
					'parent' => ''
					));
				if (is_array($terms) && count($terms) > 0) {
					$settings['mimeTypes'][$tax.'.'] = esc_html__('Media without folders', 'grace-church');
					$settings['mimeTypes'] = array_merge($settings['mimeTypes'], grace_church_get_terms_hierarchical_list($terms, array(
						'prefix_key' => 'media_folder.',
						'prefix_level' => '-'
						)
					));
				}
			}
		}
		return $settings;
	}
}

// Add folders list in js view compat area
if (!function_exists('grace_church_attachment_view_compat')) {
	//Handler of add_filter('attachment_fields_to_edit', 'grace_church_attachment_view_compat');
	function grace_church_attachment_view_compat($form_fields, $post=null) {
		static $terms = null, $id = 0;
		if (isset($form_fields['media_folder'])) {
			$field = $form_fields['media_folder'];
			if (!$terms) {
				$terms = grace_church_get_terms_by_taxonomy('media_folder', array(
					'hide_empty' => false,
					'childless' => false,
					'child_of' =>0, 
					'parent' => ''
					));
				$terms = grace_church_get_terms_hierarchical_list($terms, array(
					'prefix_key' => 'media_folder.',
					'prefix_level' => '-'
					));
			}
			$values = array_map('trim', explode(',', $field['value']));
			$readonly = '';
			$required = !empty($field['required']) ? '<span class="alignright"><abbr title="required" class="required">*</abbr></span>' : '';
			$aria_required = !empty($field['required']) ? " aria-required='true' " : '';
			$html = '';
			$html .= '<input type="text" class="text" id="media_folder_'.esc_attr($id).'" name="media_folder___new" value="" placeholder="' . esc_attr__('Create new folder', 'grace-church') . '"' . ($readonly) . ' ' . ($aria_required) . ' />';
			if (is_array($terms) && count($terms) > 0) {
				foreach ($terms as $slug=>$name) {
					$id++;
					$slug = grace_church_substr($slug, 13);
					$html .= ($html ? '<br />' : '') . '<input type="checkbox" class="text" id="media_folder_'.esc_attr($id).'" name="media_folder_' . esc_attr($slug) . '" value="' . esc_attr( $slug ) . '"' . (in_array($slug, $values) ? ' checked="checked"' : '' ) . ' ' . ($readonly) . ' ' . ($aria_required) . ' /><label for="media_folder_'.esc_attr($id).'"> ' . ($name) . '</label>';
				}
			}
			$form_fields['media_folder']['input'] = 'media_folder_input';
			$form_fields['media_folder']['media_folder_input'] = '<div class="media_folder_selector">' . ($html) . '</div>';
		}
		return $form_fields;
	}
}

// Prepare media folders for save
if (!function_exists('grace_church_attachment_save_compat')) {
	//Handler of add_filter( 'attachment_fields_to_save', 'grace_church_attachment_save_compat');
	function grace_church_attachment_save_compat($post=null, $attachment_data=null) {
		if (!empty($post['ID']) && ($id = intval($post['ID'])) > 0) {
			$folders = array();
			$from_media_library = !empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder']);
			// From AJAX query
			if (!$from_media_library) {
				foreach ($_REQUEST as $k => $v) {
					if (grace_church_substr($k, 0, 12)=='media_folder')
						$folders[] = $v;
				}
			} else {
				if (count($folders)==0) {
					if (!empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder'])) {
						foreach ($_REQUEST['tax_input']['media_folder'] as $k => $v) {
							if ((int)$v > 0)
								$folders[] = $v;
						}
					}
				}
			}
			if (is_array($folders) && count($folders) > 0) {
				foreach ($folders as $k=>$v) {
					if ((int) $v > 0) {
						$term = get_term_by('id', $v, 'media_folder');
						$folders[$k] = $term->slug;
					}
				}
			} else
				$folders = null;
			// Save folders list only from AJAX
			if (!$from_media_library)
				wp_set_object_terms( $id, $folders, 'media_folder', false );
			// Clear terms filter's cache
			delete_transient("grace_church_terms_filter_media_folder");
		}
		return $post;
	}
}


// Filter to detect current page inheritance key
if ( !function_exists( 'grace_church_attachmnent_detect_inheritance_key' ) ) {
	//Handler of add_filter('grace_church_filter_detect_inheritance_key',	'grace_church_attachmnent_detect_inheritance_key', 9, 1);
	function grace_church_attachmnent_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return is_attachment() ? 'attachment' : '';
	}
}
?>
