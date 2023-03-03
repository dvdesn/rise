<?php
/**
 * Grace-Church Framework: shortcodes manipulations
 *
 * @package	grace_church
 * @since	grace_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('grace_church_sc_theme_setup')) {
	add_action( 'grace_church_action_init_theme', 'grace_church_sc_theme_setup', 1 );
	function grace_church_sc_theme_setup() {
		// Add sc stylesheets
		add_action('grace_church_action_add_styles', 'grace_church_sc_add_styles', 1);
	}
}

if (!function_exists('grace_church_sc_theme_setup2')) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_sc_theme_setup2' );
	function grace_church_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'grace_church_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('grace_church_sc_prepare_content')) grace_church_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('grace_church_shortcode_output', 'grace_church_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_contact_form',			'grace_church_sc_contact_form_send');
		add_action('wp_ajax_nopriv_send_contact_form',	'grace_church_sc_contact_form_send');

        // Add shortcodes [trx_clients] and [trx_clients_item] in the shortcodes list
        add_action('grace_church_action_shortcodes_list',		'grace_church_clients_reg_shortcodes');
        add_action('grace_church_action_shortcodes_list_vc',	'grace_church_clients_reg_shortcodes_vc');

        // Add shortcodes [trx_services] and [trx_services_item]
        add_action('grace_church_action_shortcodes_list',		'grace_church_services_reg_shortcodes');
        add_action('grace_church_action_shortcodes_list_vc',	'grace_church_services_reg_shortcodes_vc');

        // Add shortcodes [trx_team] and [trx_team_item]
        add_action('grace_church_action_shortcodes_list',		'grace_church_team_reg_shortcodes');
        add_action('grace_church_action_shortcodes_list_vc',	'grace_church_team_reg_shortcodes_vc');

        // Add shortcodes [trx_testimonials] and [trx_testimonials_item]
        add_action('grace_church_action_shortcodes_list',		'grace_church_testimonials_reg_shortcodes');
        add_action('grace_church_action_shortcodes_list_vc',	'grace_church_testimonials_reg_shortcodes_vc');

		// Show shortcodes list in admin editor
		add_action('media_buttons',						'grace_church_sc_selector_add_in_toolbar', 11);

	}
}


// Add shortcodes styles
if ( !function_exists( 'grace_church_sc_add_styles' ) ) {
	//add_action('grace_church_action_add_styles', 'grace_church_sc_add_styles', 1);
	function grace_church_sc_add_styles() {
		// Shortcodes
		wp_enqueue_style( 'grace-church-shortcodes-style',	trx_utils_get_file_url('shortcodes/shortcodes.css'), array(), null );
	}
}


// Add shortcodes init scripts
if ( !function_exists( 'grace_church_sc_add_scripts' ) ) {
	//add_filter('grace_church_shortcode_output', 'grace_church_sc_add_scripts', 10, 4);
	function grace_church_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		global $GRACE_CHURCH_GLOBALS;
		
		if (empty($GRACE_CHURCH_GLOBALS['shortcodes_scripts_added'])) {
			$GRACE_CHURCH_GLOBALS['shortcodes_scripts_added'] = true;
			wp_enqueue_script( 'grace-church-shortcodes-script', trx_utils_get_file_url('shortcodes/shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('grace_church_sc_prepare_content')) {
	function grace_church_sc_prepare_content() {
		if (function_exists('grace_church_sc_clear_around')) {
			$filters = array(
				array('trx_utils', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'grace_church_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('grace_church_sc_excerpt_shortcodes')) {
	function grace_church_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('grace_church_sc_clear_around')) {
	function grace_church_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Grace-Church shortcodes load scripts
if (!function_exists('grace_church_sc_load_scripts')) {
	function grace_church_sc_load_scripts() {
		wp_enqueue_script( 'grace-church-shortcodes-script', trx_utils_get_file_url('shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'grace-church-selection-script',  grace_church_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
	}
}

// Grace-Church shortcodes prepare scripts
if (!function_exists('grace_church_sc_prepare_scripts')) {
    function grace_church_sc_prepare_scripts() {
        global $GRACE_CHURCH_GLOBALS;
        if (!isset($GRACE_CHURCH_GLOBALS['shortcodes_prepared'])) {
            $GRACE_CHURCH_GLOBALS['shortcodes_prepared'] = true;
            $json_parse_func = 'eval';	// 'JSON.parse'
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    try {
                        GRACE_CHURCH_GLOBALS['shortcodes'] = <?php grace_church_show_layout($json_parse_func); ?>(<?php echo json_encode( grace_church_array_prepare_to_json($GRACE_CHURCH_GLOBALS['shortcodes']) ); ?>);
                    } catch (e) {}
                    GRACE_CHURCH_GLOBALS['shortcodes_cp'] = '<?php echo is_admin() ? (!empty($GRACE_CHURCH_GLOBALS['to_colorpicker']) ? $GRACE_CHURCH_GLOBALS['to_colorpicker'] : 'wp') : 'custom'; ?>';	// wp | tiny | custom
                });
            </script>
        <?php
        }
    }
}

// Show shortcodes list in admin editor
if (!function_exists('grace_church_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','grace_church_sc_selector_add_in_toolbar', 11);
	function grace_church_sc_selector_add_in_toolbar(){

		if ( !grace_church_options_is_used() ) return;

		grace_church_sc_load_scripts();
		grace_church_sc_prepare_scripts();

		global $GRACE_CHURCH_GLOBALS;

		$shortcodes = $GRACE_CHURCH_GLOBALS['shortcodes'];
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'. esc_html__('- Select Shortcode -', 'trx_utils').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo ($shortcodes_list);
	}
}

// ---------------------------------- [trx_clients] ---------------------------------------

if ( !function_exists( 'grace_church_sc_clients' ) ) {
    function grace_church_sc_clients($atts, $content=null){
        if (grace_church_in_shortcode_blogger()) return '';
        extract(grace_church_html_decode(shortcode_atts(array(
            // Individual params
            "style" => "clients-1",
            "columns" => 4,
            "slider" => "no",
            "slides_space" => 0,
            "controls" => "no",
            "interval" => "",
            "autoheight" => "no",
            "custom" => "no",
            "ids" => "",
            "cat" => "",
            "count" => 3,
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "title" => "",
            "subtitle" => "",
            "description" => "",
            "link_caption" => esc_html__('Learn more', 'trx_utils'),
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

        if (empty($id)) $id = "sc_clients_".str_replace('.', '', mt_rand());
        if (empty($width)) $width = "100%";
        if (!empty($height) && grace_church_param_is_on($autoheight)) $autoheight = "no";
        if (empty($interval)) $interval = mt_rand(5000, 10000);

        $ms = grace_church_get_css_position_from_values($top, $right, $bottom, $left);
        $ws = grace_church_get_css_position_from_values('', '', '', '', $width);
        $hs = grace_church_get_css_position_from_values('', '', '', '', '', $height);
        $css .= ($ms) . ($hs) . ($ws);

        if (grace_church_param_is_on($slider)) grace_church_enqueue_slider('swiper');

        $columns = max(1, min(12, $columns));
        $count = max(1, (int) $count);
        if (grace_church_param_is_off($custom) && $count < $columns) $columns = $count;

        global $GRACE_CHURCH_GLOBALS;
        $GRACE_CHURCH_GLOBALS['sc_clients_id'] = $id;
        $GRACE_CHURCH_GLOBALS['sc_clients_style'] = $style;
        $GRACE_CHURCH_GLOBALS['sc_clients_counter'] = 0;
        $GRACE_CHURCH_GLOBALS['sc_clients_columns'] = $columns;
        $GRACE_CHURCH_GLOBALS['sc_clients_slider'] = $slider;
        $GRACE_CHURCH_GLOBALS['sc_clients_css_wh'] = $ws . $hs;

        $output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
            . ' class="sc_clients_wrap'
            . ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
            .'">'
            . '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_clients sc_clients_style_'.esc_attr($style)
            . ' ' . esc_attr(grace_church_get_template_property($style, 'container_classes'))
            . ' ' . esc_attr(grace_church_get_slider_controls_classes($controls))
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . (grace_church_param_is_on($slider)
                ? ' sc_slider_swiper swiper-slider-container'
                . (grace_church_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
                . ($hs ? ' sc_slider_height_fixed' : '')
                : '')
            .'"'
            . (!empty($width) && grace_church_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
            . (!empty($height) && grace_church_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
            . ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
            . ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
            . ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
            . '>'
            . (!empty($subtitle) ? '<h6 class="sc_clients_subtitle sc_item_subtitle">' . trim(grace_church_strmacros($subtitle)) . '</h6>' : '')
            . (!empty($title) ? '<h2 class="sc_clients_title sc_item_title">' . trim(grace_church_strmacros($title)) . '</h2>' : '')
            . (!empty($description) ? '<div class="sc_clients_descr sc_item_descr">' . trim(grace_church_strmacros($description)) . '</div>' : '')
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
                'post_type' => 'clients',
                'post_status' => 'publish',
                'posts_per_page' => $count,
                'ignore_sticky_posts' => true,
                'order' => $order=='asc' ? 'asc' : 'desc',
            );

            if ($offset > 0 && empty($ids)) {
                $args['offset'] = $offset;
            }

            $args = grace_church_query_add_sort_order($args, $orderby, $order);
            $args = grace_church_query_add_posts_and_cats($args, $ids, 'clients', $cat, 'clients_group');

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
                    'columns_count' => $columns,
                    'slider' => $slider,
                    'tag_id' => $id ? $id . '_' . $post_number : '',
                    'tag_class' => '',
                    'tag_animation' => '',
                    'tag_css' => '',
                    'tag_css_wh' => $ws . $hs
                );
                $post_data = grace_church_get_post_data($args);
                $post_meta = get_post_meta($post_data['post_id'], 'post_custom_options', true);
                $thumb_sizes = grace_church_get_thumb_sizes(array('layout' => $style));
                $args['client_name'] = $post_meta['client_name'];
                $args['client_position'] = $post_meta['client_position'];
                $args['client_image'] = $post_data['post_thumb'];

                $client_show_link = $post_meta['client_show_link'];
                $args['client_link'] = grace_church_param_is_on($client_show_link)
                    ? (!empty($post_meta['client_link']) ? $post_meta['client_link'] : $post_data['post_link'])
                    : '';
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

        $output .= (!empty($link) ? '<div class="sc_clients_button sc_item_button">'.grace_church_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
            . '</div><!-- /.sc_clients -->'
            . '</div><!-- /.sc_clients_wrap -->';

        return apply_filters('grace_church_shortcode_output', $output, 'trx_clients', $atts, $content);
    }
    if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_clients', 'grace_church_sc_clients');
}


if ( !function_exists( 'grace_church_sc_clients_item' ) ) {
    function grace_church_sc_clients_item($atts, $content=null) {
        if (grace_church_in_shortcode_blogger()) return '';
        extract(grace_church_html_decode(shortcode_atts( array(
            // Individual params
            "name" => "",
            "position" => "",
            "image" => "",
            "link" => "",
            // Common params
            "id" => "",
            "class" => "",
            "animation" => "",
            "css" => ""
        ), $atts)));

        global $GRACE_CHURCH_GLOBALS;
        $GRACE_CHURCH_GLOBALS['sc_clients_counter']++;

        $id = $id ? $id : ($GRACE_CHURCH_GLOBALS['sc_clients_id'] ? $GRACE_CHURCH_GLOBALS['sc_clients_id'] . '_' . $GRACE_CHURCH_GLOBALS['sc_clients_counter'] : '');

        $descr = trim(chop(do_shortcode($content)));

        $thumb_sizes = grace_church_get_thumb_sizes(array('layout' => $GRACE_CHURCH_GLOBALS['sc_clients_style']));

        if ($image > 0) {
            $attach = wp_get_attachment_image_src( $image, 'full' );
            if (isset($attach[0]) && $attach[0]!='')
                $image = $attach[0];
        }
        $image = grace_church_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);

        $post_data = array(
            'post_title' => $name,
            'post_excerpt' => $descr
        );
        $args = array(
            'layout' => $GRACE_CHURCH_GLOBALS['sc_clients_style'],
            'number' => $GRACE_CHURCH_GLOBALS['sc_clients_counter'],
            'columns_count' => $GRACE_CHURCH_GLOBALS['sc_clients_columns'],
            'slider' => $GRACE_CHURCH_GLOBALS['sc_clients_slider'],
            'show' => false,
            'descr'  => 0,
            'tag_id' => $id,
            'tag_class' => $class,
            'tag_animation' => $animation,
            'tag_css' => $css,
            'tag_css_wh' => $GRACE_CHURCH_GLOBALS['sc_clients_css_wh'],
            'client_position' => $position,
            'client_link' => $link,
            'client_image' => $image
        );
        $output = grace_church_show_post_layout($args, $post_data);
        return apply_filters('grace_church_shortcode_output', $output, 'trx_clients_item', $atts, $content);
    }
    if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_clients_item', 'grace_church_sc_clients_item');
}
// ---------------------------------- [/trx_clients] ---------------------------------------



// Add [trx_clients] and [trx_clients_item] in the shortcodes list
if (!function_exists('grace_church_clients_reg_shortcodes')) {
    //Handler of add_filter('grace_church_action_shortcodes_list',	'grace_church_clients_reg_shortcodes');
    function grace_church_clients_reg_shortcodes() {
        global $GRACE_CHURCH_GLOBALS;
        if (isset($GRACE_CHURCH_GLOBALS['shortcodes'])) {

            $users = grace_church_get_list_users();
            $members = grace_church_get_list_posts(false, array(
                    'post_type'=>'clients',
                    'orderby'=>'title',
                    'order'=>'asc',
                    'return'=>'title'
                )
            );
            $clients_groups = grace_church_get_list_terms(false, 'clients_group');
            $clients_styles = grace_church_get_list_templates('clients');
            $controls 		= grace_church_get_list_slider_controls();

            grace_church_array_insert_after($GRACE_CHURCH_GLOBALS['shortcodes'], 'trx_chat', array(

                // Clients
                "trx_clients" => array(
                    "title" => esc_html__("Clients", "trx_utils"),
                    "desc" => esc_html__("Insert clients list in your page (post)", "trx_utils"),
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
                            "type" => "textarea"
                        ),
                        "style" => array(
                            "title" => esc_html__("Clients style", "trx_utils"),
                            "desc" => esc_html__("Select style to display clients list", "trx_utils"),
                            "value" => "clients-1",
                            "type" => "select",
                            "options" => $clients_styles
                        ),
                        "columns" => array(
                            "title" => esc_html__("Columns", "trx_utils"),
                            "desc" => esc_html__("How many columns use to show clients", "trx_utils"),
                            "value" => 3,
                            "min" => 2,
                            "max" => 6,
                            "step" => 1,
                            "type" => "spinner"
                        ),
                        "scheme" => array(
                            "title" => esc_html__("Color scheme", "trx_utils"),
                            "desc" => esc_html__("Select color scheme for this block", "trx_utils"),
                            "value" => "",
                            "type" => "checklist",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
                        ),
                        "slider" => array(
                            "title" => esc_html__("Slider", "trx_utils"),
                            "desc" => esc_html__("Use slider to show clients", "trx_utils"),
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "controls" => array(
                            "title" => esc_html__("Controls", "trx_utils"),
                            "desc" => esc_html__("Slider controls style and position", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "divider" => true,
                            "value" => "no",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => $controls
                        ),
                        "slides_space" => array(
                            "title" => esc_html__("Space between slides", "trx_utils"),
                            "desc" => esc_html__("Size of space (in px) between slides", "trx_utils"),
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
                            "title" => esc_html__("Slides change interval", "trx_utils"),
                            "desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => 7000,
                            "step" => 500,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "autoheight" => array(
                            "title" => esc_html__("Autoheight", "trx_utils"),
                            "desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "custom" => array(
                            "title" => esc_html__("Custom", "trx_utils"),
                            "desc" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                            "divider" => true,
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "cat" => array(
                            "title" => esc_html__("Categories", "trx_utils"),
                            "desc" => esc_html__("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "divider" => true,
                            "value" => "",
                            "type" => "select",
                            "style" => "list",
                            "multiple" => true,
                            "options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $clients_groups)
                        ),
                        "count" => array(
                            "title" => esc_html__("Number of posts", "trx_utils"),
                            "desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
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
                                'custom' => array('no')
                            ),
                            "value" => 0,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "orderby" => array(
                            "title" => esc_html__("Post order by", "trx_utils"),
                            "desc" => esc_html__("Select desired posts sorting method", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "title",
                            "type" => "select",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['sorting']
                        ),
                        "order" => array(
                            "title" => esc_html__("Post order", "trx_utils"),
                            "desc" => esc_html__("Select desired posts order", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "asc",
                            "type" => "switch",
                            "size" => "big",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['ordering']
                        ),
                        "ids" => array(
                            "title" => esc_html__("Post IDs list", "trx_utils"),
                            "desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
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
                    ),
                    "children" => array(
                        "name" => "trx_clients_item",
                        "title" => esc_html__("Client", "trx_utils"),
                        "desc" => esc_html__("Single client (custom parameters)", "trx_utils"),
                        "container" => true,
                        "params" => array(
                            "name" => array(
                                "title" => esc_html__("Name", "trx_utils"),
                                "desc" => esc_html__("Client's name", "trx_utils"),
                                "divider" => true,
                                "value" => "",
                                "type" => "text"
                            ),
                            "position" => array(
                                "title" => esc_html__("Position", "trx_utils"),
                                "desc" => esc_html__("Client's position", "trx_utils"),
                                "value" => "",
                                "type" => "text"
                            ),
                            "link" => array(
                                "title" => esc_html__("Link", "trx_utils"),
                                "desc" => esc_html__("Link on client's personal page", "trx_utils"),
                                "divider" => true,
                                "value" => "",
                                "type" => "text"
                            ),
                            "image" => array(
                                "title" => esc_html__("Image", "trx_utils"),
                                "desc" => esc_html__("Client's image", "trx_utils"),
                                "value" => "",
                                "readonly" => false,
                                "type" => "media"
                            ),
                            "_content_" => array(
                                "title" => esc_html__("Description", "trx_utils"),
                                "desc" => esc_html__("Client's short description", "trx_utils"),
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


// Add [trx_clients] and [trx_clients_item] in the VC shortcodes list
if (!function_exists('grace_church_clients_reg_shortcodes_vc')) {
    //Handler of add_filter('grace_church_action_shortcodes_list_vc',	'grace_church_clients_reg_shortcodes_vc');
    function grace_church_clients_reg_shortcodes_vc() {
        global $GRACE_CHURCH_GLOBALS;

        $clients_groups = grace_church_get_list_terms(false, 'clients_group');
        $clients_styles = grace_church_get_list_templates('clients');
        $controls		= grace_church_get_list_slider_controls();

        // Clients
        vc_map( array(
            "base" => "trx_clients",
            "name" => esc_html__("Clients", "trx_utils"),
            "description" => esc_html__("Insert clients list", "trx_utils"),
            "category" => esc_html__('Content', 'trx_utils'),
            'icon' => 'icon_trx_clients',
            "class" => "trx_sc_columns trx_sc_clients",
            "content_element" => true,
            "is_container" => true,
            "show_settings_on_create" => true,
            "as_parent" => array('only' => 'trx_clients_item'),
            "params" => array(
                array(
                    "param_name" => "style",
                    "heading" => esc_html__("Clients style", "trx_utils"),
                    "description" => esc_html__("Select style to display clients list", "trx_utils"),
                    "class" => "",
                    "admin_label" => true,
                    "value" => array_flip($clients_styles),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "columns",
                    "heading" => esc_html__("Columns", "trx_utils"),
                    "description" => esc_html__("How many columns use to show clients", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "4",
                    "type" => "textfield"
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
                    "param_name" => "slider",
                    "heading" => esc_html__("Slider", "trx_utils"),
                    "description" => esc_html__("Use slider to show testimonials", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
                    "class" => "",
                    "std" => "no",
                    "value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['yes_no']),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "controls",
                    "heading" => esc_html__("Controls", "trx_utils"),
                    "description" => esc_html__("Slider controls style and position", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Space between slides", "trx_utils"),
                    "description" => esc_html__("Size of space (in px) between slides", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Slides change interval", "trx_utils"),
                    "description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Autoheight", "trx_utils"),
                    "description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
                    'dependency' => array(
                        'element' => 'slider',
                        'value' => 'yes'
                    ),
                    "class" => "",
                    "value" => array("Autoheight" => "yes" ),
                    "type" => "checkbox"
                ),
                array(
                    "param_name" => "custom",
                    "heading" => esc_html__("Custom", "trx_utils"),
                    "description" => esc_html__("Allow get clients from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                    "class" => "",
                    "value" => array("Custom clients" => "yes" ),
                    "type" => "checkbox"
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
                    "param_name" => "cat",
                    "heading" => esc_html__("Categories", "trx_utils"),
                    "description" => esc_html__("Select category to show clients. If empty - select clients from any category (group) or from IDs list", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
                    'dependency' => array(
                        'element' => 'custom',
                        'is_empty' => true
                    ),
                    "class" => "",
                    "value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $clients_groups)),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "count",
                    "heading" => esc_html__("Number of posts", "trx_utils"),
                    "description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Offset before select posts", "trx_utils"),
                    "description" => esc_html__("Skip posts before select next part.", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post sorting", "trx_utils"),
                    "description" => esc_html__("Select desired posts sorting method", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post order", "trx_utils"),
                    "description" => esc_html__("Select desired posts order", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("client's IDs list", "trx_utils"),
                    "description" => esc_html__("Comma separated list of client's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                $GRACE_CHURCH_GLOBALS['vc_params']['margin_top'],
                $GRACE_CHURCH_GLOBALS['vc_params']['margin_bottom'],
                $GRACE_CHURCH_GLOBALS['vc_params']['margin_left'],
                $GRACE_CHURCH_GLOBALS['vc_params']['margin_right'],
                $GRACE_CHURCH_GLOBALS['vc_params']['id'],
                $GRACE_CHURCH_GLOBALS['vc_params']['class'],
                $GRACE_CHURCH_GLOBALS['vc_params']['animation'],
                $GRACE_CHURCH_GLOBALS['vc_params']['css']
            ),
            'js_view' => 'VcTrxColumnsView'
        ) );


        vc_map( array(
            "base" => "trx_clients_item",
            "name" => esc_html__("Client", "trx_utils"),
            "description" => esc_html__("Client - all data pull out from it account on your site", "trx_utils"),
            "show_settings_on_create" => true,
            "class" => "trx_sc_item trx_sc_column_item trx_sc_clients_item",
            "content_element" => true,
            "is_container" => false,
            'icon' => 'icon_trx_clients_item',
            "as_child" => array('only' => 'trx_clients'),
            "as_parent" => array('except' => 'trx_clients'),
            "params" => array(
                array(
                    "param_name" => "name",
                    "heading" => esc_html__("Name", "trx_utils"),
                    "description" => esc_html__("Client's name", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "position",
                    "heading" => esc_html__("Position", "trx_utils"),
                    "description" => esc_html__("Client's position", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "link",
                    "heading" => esc_html__("Link", "trx_utils"),
                    "description" => esc_html__("Link on client's personal page", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "image",
                    "heading" => esc_html__("Client's image", "trx_utils"),
                    "description" => esc_html__("Clients's image", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
                $GRACE_CHURCH_GLOBALS['vc_params']['id'],
                $GRACE_CHURCH_GLOBALS['vc_params']['class'],
                $GRACE_CHURCH_GLOBALS['vc_params']['animation'],
                $GRACE_CHURCH_GLOBALS['vc_params']['css']
            )
        ) );

        class WPBakeryShortCode_Trx_Clients extends GRACE_CHURCH_VC_ShortCodeColumns {}
        class WPBakeryShortCode_Trx_Clients_Item extends GRACE_CHURCH_VC_ShortCodeItem {}

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
            "readmore" => esc_html__('More', 'trx_utils'),
            "title" => "",
            "subtitle" => "",
            "description" => "",
            "link_caption" => esc_html__('More', 'trx_utils'),
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
                    "title" => esc_html__("Services", "trx_utils"),
                    "desc" => esc_html__("Insert services list in your page (post)", "trx_utils"),
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
                            "type" => "textarea"
                        ),
                        "style" => array(
                            "title" => esc_html__("Services style", "trx_utils"),
                            "desc" => esc_html__("Select style to display services list", "trx_utils"),
                            "value" => "services-2",
                            "type" => "select",
                            "options" => $services_styles
                        ),
                        "type" => array(
                            "title" => esc_html__("Icon's type", "trx_utils"),
                            "desc" => esc_html__("Select type of icons: font icon or image", "trx_utils"),
                            "value" => "icons",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => array(
                                'icons'  => esc_html__('Icons', 'trx_utils'),
                                'images' => esc_html__('Images', 'trx_utils')
                            )
                        ),
                        "columns" => array(
                            "title" => esc_html__("Columns", "trx_utils"),
                            "desc" => esc_html__("How many columns use to show services list", "trx_utils"),
                            "value" => 4,
                            "min" => 2,
                            "max" => 6,
                            "step" => 1,
                            "type" => "spinner"
                        ),
                        "scheme" => array(
                            "title" => esc_html__("Color scheme", "trx_utils"),
                            "desc" => esc_html__("Select color scheme for this block", "trx_utils"),
                            "value" => "",
                            "type" => "checklist",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
                        ),
                        "slider" => array(
                            "title" => esc_html__("Slider", "trx_utils"),
                            "desc" => esc_html__("Use slider to show services", "trx_utils"),
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "controls" => array(
                            "title" => esc_html__("Controls", "trx_utils"),
                            "desc" => esc_html__("Slider controls style and position", "trx_utils"),
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
                            "title" => esc_html__("Space between slides", "trx_utils"),
                            "desc" => esc_html__("Size of space (in px) between slides", "trx_utils"),
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
                            "title" => esc_html__("Slides change interval", "trx_utils"),
                            "desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => 7000,
                            "step" => 500,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "autoheight" => array(
                            "title" => esc_html__("Autoheight", "trx_utils"),
                            "desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => "yes",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "align" => array(
                            "title" => esc_html__("Alignment", "trx_utils"),
                            "desc" => esc_html__("Alignment of the services block", "trx_utils"),
                            "divider" => true,
                            "value" => "",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
                        ),
                        "custom" => array(
                            "title" => esc_html__("Custom", "trx_utils"),
                            "desc" => esc_html__("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                            "divider" => true,
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "cat" => array(
                            "title" => esc_html__("Categories", "trx_utils"),
                            "desc" => esc_html__("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "divider" => true,
                            "value" => "",
                            "type" => "select",
                            "style" => "list",
                            "multiple" => true,
                            "options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $services_groups)
                        ),
                        "count" => array(
                            "title" => esc_html__("Number of posts", "trx_utils"),
                            "desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
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
                                'custom' => array('no')
                            ),
                            "value" => 0,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "orderby" => array(
                            "title" => esc_html__("Post order by", "trx_utils"),
                            "desc" => esc_html__("Select desired posts sorting method", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "title",
                            "type" => "select",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['sorting']
                        ),
                        "order" => array(
                            "title" => esc_html__("Post order", "trx_utils"),
                            "desc" => esc_html__("Select desired posts order", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "asc",
                            "type" => "switch",
                            "size" => "big",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['ordering']
                        ),
                        "ids" => array(
                            "title" => esc_html__("Post IDs list", "trx_utils"),
                            "desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "",
                            "type" => "text"
                        ),
                        "readmore" => array(
                            "title" => esc_html__("Read more", "trx_utils"),
                            "desc" => esc_html__("Caption for the Read more link (if empty - link not showed)", "trx_utils"),
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
                    ),
                    "children" => array(
                        "name" => "trx_services_item",
                        "title" => esc_html__("Service item", "trx_utils"),
                        "desc" => esc_html__("Service item", "trx_utils"),
                        "container" => true,
                        "params" => array(
                            "title" => array(
                                "title" => esc_html__("Title", "trx_utils"),
                                "desc" => esc_html__("Item's title", "trx_utils"),
                                "divider" => true,
                                "value" => "",
                                "type" => "text"
                            ),
                            "icon" => array(
                                "title" => esc_html__("Item's icon",  'trx_utils'),
                                "desc" => esc_html__('Select icon for the item from Fontello icons set',  'trx_utils'),
                                "value" => "",
                                "type" => "icons",
                                "options" => $GRACE_CHURCH_GLOBALS['sc_params']['icons']
                            ),
                            "image" => array(
                                "title" => esc_html__("Item's image", "trx_utils"),
                                "desc" => esc_html__("Item's image (if icon not selected)", "trx_utils"),
                                "dependency" => array(
                                    'icon' => array('is_empty', 'none')
                                ),
                                "value" => "",
                                "readonly" => false,
                                "type" => "media"
                            ),
                            "link" => array(
                                "title" => esc_html__("Link", "trx_utils"),
                                "desc" => esc_html__("Link on service's item page", "trx_utils"),
                                "divider" => true,
                                "value" => "",
                                "type" => "text"
                            ),
                            "readmore" => array(
                                "title" => esc_html__("Read more", "trx_utils"),
                                "desc" => esc_html__("Caption for the Read more link (if empty - link not showed)", "trx_utils"),
                                "value" => "",
                                "type" => "text"
                            ),
                            "_content_" => array(
                                "title" => esc_html__("Description", "trx_utils"),
                                "desc" => esc_html__("Item's short description", "trx_utils"),
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
            "name" => esc_html__("Services", "trx_utils"),
            "description" => esc_html__("Insert services list", "trx_utils"),
            "category" => esc_html__('Content', 'trx_utils'),
            "icon" => 'icon_trx_services',
            "class" => "trx_sc_columns trx_sc_services",
            "content_element" => true,
            "is_container" => true,
            "show_settings_on_create" => true,
            "as_parent" => array('only' => 'trx_services_item'),
            "params" => array(
                array(
                    "param_name" => "style",
                    "heading" => esc_html__("Services style", "trx_utils"),
                    "description" => esc_html__("Select style to display services list", "trx_utils"),
                    "class" => "",
                    "admin_label" => true,
                    "value" => array_flip($services_styles),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "type",
                    "heading" => esc_html__("Icon's type", "trx_utils"),
                    "description" => esc_html__("Select type of icons: font icon or image", "trx_utils"),
                    "class" => "",
                    "admin_label" => true,
                    "value" => array(
                        esc_html__('Icons', 'trx_utils') => 'icons',
                        esc_html__('Images', 'trx_utils') => 'images'
                    ),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "columns",
                    "heading" => esc_html__("Columns", "trx_utils"),
                    "description" => esc_html__("How many columns use to show services list", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "4",
                    "type" => "textfield"
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
                    "param_name" => "slider",
                    "heading" => esc_html__("Slider", "trx_utils"),
                    "description" => esc_html__("Use slider to show services", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
                    "class" => "",
                    "std" => "no",
                    "value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['yes_no']),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "controls",
                    "heading" => esc_html__("Controls", "trx_utils"),
                    "description" => esc_html__("Slider controls style and position", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Space between slides", "trx_utils"),
                    "description" => esc_html__("Size of space (in px) between slides", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Slides change interval", "trx_utils"),
                    "description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Autoheight", "trx_utils"),
                    "description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Alignment", "trx_utils"),
                    "description" => esc_html__("Alignment of the services block", "trx_utils"),
                    "class" => "",
                    "value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "custom",
                    "heading" => esc_html__("Custom", "trx_utils"),
                    "description" => esc_html__("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                    "class" => "",
                    "value" => array("Custom services" => "yes" ),
                    "type" => "checkbox"
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
                    "param_name" => "cat",
                    "heading" => esc_html__("Categories", "trx_utils"),
                    "description" => esc_html__("Select category to show services. If empty - select services from any category (group) or from IDs list", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
                    'dependency' => array(
                        'element' => 'custom',
                        'is_empty' => true
                    ),
                    "class" => "",
                    "value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $services_groups)),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "count",
                    "heading" => esc_html__("Number of posts", "trx_utils"),
                    "description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Offset before select posts", "trx_utils"),
                    "description" => esc_html__("Skip posts before select next part.", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post sorting", "trx_utils"),
                    "description" => esc_html__("Select desired posts sorting method", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post order", "trx_utils"),
                    "description" => esc_html__("Select desired posts order", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Team member's IDs list", "trx_utils"),
                    "description" => esc_html__("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Read more", "trx_utils"),
                    "description" => esc_html__("Caption for the Read more link (if empty - link not showed)", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Captions', 'trx_utils'),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
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
					[trx_services_item title="' . esc_html__( 'Service item 1', 'trx_utils' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'trx_utils' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'trx_utils' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'trx_utils' ) . '"][/trx_services_item]
				',
            'js_view' => 'VcTrxColumnsView'
        ) );


        vc_map( array(
            "base" => "trx_services_item",
            "name" => esc_html__("Services item", "trx_utils"),
            "description" => esc_html__("Custom services item - all data pull out from shortcode parameters", "trx_utils"),
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
                    "heading" => esc_html__("Title", "trx_utils"),
                    "description" => esc_html__("Item's title", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "icon",
                    "heading" => esc_html__("Icon", "trx_utils"),
                    "description" => esc_html__("Select icon for the item from Fontello icons set", "trx_utils"),
                    "class" => "",
                    "value" => $GRACE_CHURCH_GLOBALS['sc_params']['icons'],
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "image",
                    "heading" => esc_html__("Image", "trx_utils"),
                    "description" => esc_html__("Item's image (if icon is empty)", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
                array(
                    "param_name" => "link",
                    "heading" => esc_html__("Link", "trx_utils"),
                    "description" => esc_html__("Link on item's page", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "readmore",
                    "heading" => esc_html__("Read more", "trx_utils"),
                    "description" => esc_html__("Caption for the Read more link (if empty - link not showed)", "trx_utils"),
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
            "link_caption" => esc_html__('Learn more', 'trx_utils'),
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
                    "title" => esc_html__("Team", "trx_utils"),
                    "desc" => esc_html__("Insert team in your page (post)", "trx_utils"),
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
                            "type" => "textarea"
                        ),
                        "style" => array(
                            "title" => esc_html__("Team style", "trx_utils"),
                            "desc" => esc_html__("Select style to display team members", "trx_utils"),
                            "value" => "4",
                            "type" => "select",
                            "options" => $team_styles
                        ),
                        "columns" => array(
                            "title" => esc_html__("Columns", "trx_utils"),
                            "desc" => esc_html__("How many columns use to show team members", "trx_utils"),
                            "value" => 3,
                            "min" => 2,
                            "max" => 5,
                            "step" => 1,
                            "type" => "spinner"
                        ),
                        "scheme" => array(
                            "title" => esc_html__("Color scheme", "trx_utils"),
                            "desc" => esc_html__("Select color scheme for this block", "trx_utils"),
                            "value" => "",
                            "type" => "checklist",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['schemes']
                        ),
                        "slider" => array(
                            "title" => esc_html__("Slider", "trx_utils"),
                            "desc" => esc_html__("Use slider to show team members", "trx_utils"),
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "controls" => array(
                            "title" => esc_html__("Controls", "trx_utils"),
                            "desc" => esc_html__("Slider controls style and position", "trx_utils"),
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
                            "title" => esc_html__("Space between slides", "trx_utils"),
                            "desc" => esc_html__("Size of space (in px) between slides", "trx_utils"),
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
                            "title" => esc_html__("Slides change interval", "trx_utils"),
                            "desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => 7000,
                            "step" => 500,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "autoheight" => array(
                            "title" => esc_html__("Autoheight", "trx_utils"),
                            "desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => "yes",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "align" => array(
                            "title" => esc_html__("Alignment", "trx_utils"),
                            "desc" => esc_html__("Alignment of the team block", "trx_utils"),
                            "divider" => true,
                            "value" => "",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
                        ),
                        "custom" => array(
                            "title" => esc_html__("Custom", "trx_utils"),
                            "desc" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                            "divider" => true,
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "cat" => array(
                            "title" => esc_html__("Categories", "trx_utils"),
                            "desc" => esc_html__("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "divider" => true,
                            "value" => "",
                            "type" => "select",
                            "style" => "list",
                            "multiple" => true,
                            "options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $team_groups)
                        ),
                        "count" => array(
                            "title" => esc_html__("Number of posts", "trx_utils"),
                            "desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
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
                                'custom' => array('no')
                            ),
                            "value" => 0,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "orderby" => array(
                            "title" => esc_html__("Post order by", "trx_utils"),
                            "desc" => esc_html__("Select desired posts sorting method", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "title",
                            "type" => "select",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['sorting']
                        ),
                        "order" => array(
                            "title" => esc_html__("Post order", "trx_utils"),
                            "desc" => esc_html__("Select desired posts order", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "asc",
                            "type" => "switch",
                            "size" => "big",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['ordering']
                        ),
                        "ids" => array(
                            "title" => esc_html__("Post IDs list", "trx_utils"),
                            "desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
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
                    ),
                    "children" => array(
                        "name" => "trx_team_item",
                        "title" => esc_html__("Member", "trx_utils"),
                        "desc" => esc_html__("Team member", "trx_utils"),
                        "container" => true,
                        "params" => array(
                            "user" => array(
                                "title" => esc_html__("Registerd user", "trx_utils"),
                                "desc" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", "trx_utils"),
                                "value" => "",
                                "type" => "select",
                                "options" => $users
                            ),
                            "member" => array(
                                "title" => esc_html__("Team member", "trx_utils"),
                                "desc" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", "trx_utils"),
                                "value" => "",
                                "type" => "select",
                                "options" => $members
                            ),
                            "link" => array(
                                "title" => esc_html__("Link", "trx_utils"),
                                "desc" => esc_html__("Link on team member's personal page", "trx_utils"),
                                "divider" => true,
                                "value" => "",
                                "type" => "text"
                            ),
                            "name" => array(
                                "title" => esc_html__("Name", "trx_utils"),
                                "desc" => esc_html__("Team member's name", "trx_utils"),
                                "divider" => true,
                                "dependency" => array(
                                    'user' => array('is_empty', 'none'),
                                    'member' => array('is_empty', 'none')
                                ),
                                "value" => "",
                                "type" => "text"
                            ),
                            "position" => array(
                                "title" => esc_html__("Position", "trx_utils"),
                                "desc" => esc_html__("Team member's position", "trx_utils"),
                                "dependency" => array(
                                    'user' => array('is_empty', 'none'),
                                    'member' => array('is_empty', 'none')
                                ),
                                "value" => "",
                                "type" => "text"
                            ),
                            "email" => array(
                                "title" => esc_html__("E-mail", "trx_utils"),
                                "desc" => esc_html__("Team member's e-mail", "trx_utils"),
                                "dependency" => array(
                                    'user' => array('is_empty', 'none'),
                                    'member' => array('is_empty', 'none')
                                ),
                                "value" => "",
                                "type" => "text"
                            ),
                            "photo" => array(
                                "title" => esc_html__("Photo", "trx_utils"),
                                "desc" => esc_html__("Team member's photo (avatar)", "trx_utils"),
                                "dependency" => array(
                                    'user' => array('is_empty', 'none'),
                                    'member' => array('is_empty', 'none')
                                ),
                                "value" => "",
                                "readonly" => false,
                                "type" => "media"
                            ),
                            "socials" => array(
                                "title" => esc_html__("Socials", "trx_utils"),
                                "desc" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "trx_utils"),
                                "dependency" => array(
                                    'user' => array('is_empty', 'none'),
                                    'member' => array('is_empty', 'none')
                                ),
                                "value" => "",
                                "type" => "text"
                            ),
                            "_content_" => array(
                                "title" => esc_html__("Description", "trx_utils"),
                                "desc" => esc_html__("Team member's short description", "trx_utils"),
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
            "name" => esc_html__("Team", "trx_utils"),
            "description" => esc_html__("Insert team members", "trx_utils"),
            "category" => esc_html__('Content', 'trx_utils'),
            'icon' => 'icon_trx_team',
            "class" => "trx_sc_columns trx_sc_team",
            "content_element" => true,
            "is_container" => true,
            "show_settings_on_create" => true,
            "as_parent" => array('only' => 'trx_team_item'),
            "params" => array(
                array(
                    "param_name" => "style",
                    "heading" => esc_html__("Team style", "trx_utils"),
                    "description" => esc_html__("Select style to display team members", "trx_utils"),
                    "class" => "",
                    "admin_label" => true,
                    "value" => array_flip($team_styles),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "columns",
                    "heading" => esc_html__("Columns", "trx_utils"),
                    "description" => esc_html__("How many columns use to show team members", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "3",
                    "type" => "textfield"
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
                    "param_name" => "slider",
                    "heading" => esc_html__("Slider", "trx_utils"),
                    "description" => esc_html__("Use slider to show team members", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
                    "class" => "",
                    "std" => "no",
                    "value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['yes_no']),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "controls",
                    "heading" => esc_html__("Controls", "trx_utils"),
                    "description" => esc_html__("Slider controls style and position", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Space between slides", "trx_utils"),
                    "description" => esc_html__("Size of space (in px) between slides", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Slides change interval", "trx_utils"),
                    "description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Autoheight", "trx_utils"),
                    "description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Alignment", "trx_utils"),
                    "description" => esc_html__("Alignment of the team block", "trx_utils"),
                    "class" => "",
                    "value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "custom",
                    "heading" => esc_html__("Custom", "trx_utils"),
                    "description" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                    "class" => "",
                    "value" => array("Custom members" => "yes" ),
                    "type" => "checkbox"
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
                    "param_name" => "cat",
                    "heading" => esc_html__("Categories", "trx_utils"),
                    "description" => esc_html__("Select category to show team members. If empty - select team members from any category (group) or from IDs list", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
                    'dependency' => array(
                        'element' => 'custom',
                        'is_empty' => true
                    ),
                    "class" => "",
                    "value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $team_groups)),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "count",
                    "heading" => esc_html__("Number of posts", "trx_utils"),
                    "description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Offset before select posts", "trx_utils"),
                    "description" => esc_html__("Skip posts before select next part.", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post sorting", "trx_utils"),
                    "description" => esc_html__("Select desired posts sorting method", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post order", "trx_utils"),
                    "description" => esc_html__("Select desired posts order", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Team member's IDs list", "trx_utils"),
                    "description" => esc_html__("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
					[trx_team_item user="' . esc_html__( 'Member 1', 'trx_utils' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 2', 'trx_utils' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 4', 'trx_utils' ) . '"][/trx_team_item]
				',
            'js_view' => 'VcTrxColumnsView'
        ) );


        vc_map( array(
            "base" => "trx_team_item",
            "name" => esc_html__("Team member", "trx_utils"),
            "description" => esc_html__("Team member - all data pull out from it account on your site", "trx_utils"),
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
                    "heading" => esc_html__("Registered user", "trx_utils"),
                    "description" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => array_flip($users),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "member",
                    "heading" => esc_html__("Team member", "trx_utils"),
                    "description" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => array_flip($members),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "link",
                    "heading" => esc_html__("Link", "trx_utils"),
                    "description" => esc_html__("Link on team member's personal page", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "name",
                    "heading" => esc_html__("Name", "trx_utils"),
                    "description" => esc_html__("Team member's name", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "position",
                    "heading" => esc_html__("Position", "trx_utils"),
                    "description" => esc_html__("Team member's position", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "email",
                    "heading" => esc_html__("E-mail", "trx_utils"),
                    "description" => esc_html__("Team member's e-mail", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "photo",
                    "heading" => esc_html__("Member's Photo", "trx_utils"),
                    "description" => esc_html__("Team member's photo (avatar)", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
                array(
                    "param_name" => "socials",
                    "heading" => esc_html__("Socials", "trx_utils"),
                    "description" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "trx_utils"),
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

// ---------------------------------- [trx_testimonials] ---------------------------------------

if (!function_exists('grace_church_sc_testimonials')) {
    function grace_church_sc_testimonials($atts, $content=null){
        if (grace_church_in_shortcode_blogger()) return '';
        extract(grace_church_html_decode(shortcode_atts(array(
            // Individual params
            "style" => "testimonials-1",
            "columns" => 1,
            "slider" => "yes",
            "slides_space" => 0,
            "controls" => "no",
            "interval" => "",
            "autoheight" => "no",
            "align" => "",
            "custom" => "no",
            "ids" => "",
            "cat" => "",
            "count" => "3",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "scheme" => "",
            "bg_color" => "",
            "bg_image" => "",
            "bg_overlay" => "",
            "bg_texture" => "",
            "title" => "",
            "subtitle" => "",
            "description" => "",
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

        if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
        if (empty($width)) $width = "100%";
        if (!empty($height) && grace_church_param_is_on($autoheight)) $autoheight = "no";
        if (empty($interval)) $interval = mt_rand(5000, 10000);

        if ($bg_image > 0) {
            $attach = wp_get_attachment_image_src( $bg_image, 'full' );
            if (isset($attach[0]) && $attach[0]!='')
                $bg_image = $attach[0];
        }

        if ($bg_overlay > 0) {
            if ($bg_color=='') $bg_color = grace_church_get_scheme_color('bg');
            $rgb = grace_church_hex2rgb($bg_color);
        }

        $ms = grace_church_get_css_position_from_values($top, $right, $bottom, $left);
        $ws = grace_church_get_css_position_from_values('', '', '', '', $width);
        $hs = grace_church_get_css_position_from_values('', '', '', '', '', $height);
        $css .= ($ms) . ($hs) . ($ws);

        $count = max(1, (int) $count);
        $columns = max(1, min(12, (int) $columns));
        if (grace_church_param_is_off($custom) && $count < $columns) $columns = $count;

        global $GRACE_CHURCH_GLOBALS;
        $GRACE_CHURCH_GLOBALS['sc_testimonials_id'] = $id;
        $GRACE_CHURCH_GLOBALS['sc_testimonials_style'] = $style;
        $GRACE_CHURCH_GLOBALS['sc_testimonials_columns'] = $columns;
        $GRACE_CHURCH_GLOBALS['sc_testimonials_counter'] = 0;
        $GRACE_CHURCH_GLOBALS['sc_testimonials_slider'] = $slider;
        $GRACE_CHURCH_GLOBALS['sc_testimonials_css_wh'] = $ws . $hs;
        $GRACE_CHURCH_GLOBALS['title_testimonials_1'] = $title;


        if (grace_church_param_is_on($slider)) grace_church_enqueue_slider('swiper');

        $output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || grace_church_strlen($bg_texture)>2 || ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme))
                ? '<div class="sc_testimonials_wrap sc_section'
                . ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
                . '"'
                .' style="'
                . ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
                . ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
                . '"'
                . (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
                . '>'
                . '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
                . ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
                . (grace_church_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
                . '"'
                . ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
                . '>'
                : '')
            . '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
            . ' ' . esc_attr(grace_church_get_template_property($style, 'container_classes'))
            . (grace_church_param_is_on($slider)
                ? ' sc_slider_swiper swiper-slider-container'
                . ' ' . esc_attr(grace_church_get_slider_controls_classes($controls))
                . (grace_church_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
                . ($hs ? ' sc_slider_height_fixed' : '')
                : '')
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
            . '"'
            . ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
            . (!empty($width) && grace_church_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
            . (!empty($height) && grace_church_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
            . ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
            . ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
            . ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . '>'
            . (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(grace_church_strmacros($subtitle)) . '</h6>' : '')
            . (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(grace_church_strmacros($title)) . '</h2>' : '')
            . (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(grace_church_strmacros($description)) . '</div>' : '')
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
                'post_type' => 'testimonial',
                'post_status' => 'publish',
                'posts_per_page' => $count,
                'ignore_sticky_posts' => true,
                'order' => $order=='asc' ? 'asc' : 'desc',
            );

            if ($offset > 0 && empty($ids)) {
                $args['offset'] = $offset;
            }

            $args = grace_church_query_add_sort_order($args, $orderby, $order);
            $args = grace_church_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');

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
                    'columns_count' => $columns,
                    'slider' => $slider,
                    'tag_id' => $id ? $id . '_' . $post_number : '',
                    'tag_class' => '',
                    'tag_animation' => '',
                    'tag_css' => '',
                    'tag_css_wh' => $ws . $hs
                );
                $post_data = grace_church_get_post_data($args);
                $post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
                $post_meta = get_post_meta($post_data['post_id'], 'testimonial_data', true);
                $thumb_sizes = grace_church_get_thumb_sizes(array('layout' => $style));
                $args['author'] = $post_meta['testimonial_author'];
                $args['position'] = $post_meta['testimonial_position'];
                $args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';
                $args['email'] = $post_meta['testimonial_email'];
                $args['photo'] = $post_data['post_thumb'];
                if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, grace_church_get_theme_option("retina_ready"))));
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

        $output .= '</div>'
            . ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || grace_church_strlen($bg_texture)>2
                ?  '</div></div>'
                : '');

        return apply_filters('grace_church_shortcode_output', $output, 'trx_testimonials', $atts, $content);
    }
    if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_testimonials', 'grace_church_sc_testimonials');
}


if (!function_exists('grace_church_sc_testimonials_item')) {
    function grace_church_sc_testimonials_item($atts, $content=null){
        if (grace_church_in_shortcode_blogger()) return '';
        extract(grace_church_html_decode(shortcode_atts(array(
            // Individual params
            "author" => "",
            "position" => "",
            "link" => "",
            "photo" => "",
            "email" => "",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
        ), $atts)));

        global $GRACE_CHURCH_GLOBALS;
        $GRACE_CHURCH_GLOBALS['sc_testimonials_counter']++;

        $id = $id ? $id : ($GRACE_CHURCH_GLOBALS['sc_testimonials_id'] ? $GRACE_CHURCH_GLOBALS['sc_testimonials_id'] . '_' . $GRACE_CHURCH_GLOBALS['sc_testimonials_counter'] : '');

        $thumb_sizes = grace_church_get_thumb_sizes(array('layout' => $GRACE_CHURCH_GLOBALS['sc_testimonials_style']));

        if (empty($photo)) {
            if (!empty($email))
                $photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, grace_church_get_theme_option("retina_ready"))));
        } else {
            if ($photo > 0) {
                $attach = wp_get_attachment_image_src( $photo, 'full' );
                if (isset($attach[0]) && $attach[0]!='')
                    $photo = $attach[0];
            }
            $photo = grace_church_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
        }

        $post_data = array(
            'post_content' => do_shortcode($content)
        );
        $args = array(
            'layout' => $GRACE_CHURCH_GLOBALS['sc_testimonials_style'],
            'number' => $GRACE_CHURCH_GLOBALS['sc_testimonials_counter'],
            'columns_count' => $GRACE_CHURCH_GLOBALS['sc_testimonials_columns'],
            'slider' => $GRACE_CHURCH_GLOBALS['sc_testimonials_slider'],
            'show' => false,
            'descr'  => 0,
            'tag_id' => $id,
            'tag_class' => $class,
            'tag_animation' => '',
            'tag_css' => $css,
            'tag_css_wh' => $GRACE_CHURCH_GLOBALS['sc_testimonials_css_wh'],
            'author' => $author,
            'position' => $position,
            'link' => $link,
            'email' => $email,
            'photo' => $photo
        );
        $output = grace_church_show_post_layout($args, $post_data);

        return apply_filters('grace_church_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
    }
    if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_testimonials_item', 'grace_church_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------

// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('grace_church_testimonials_reg_shortcodes')) {
    //Handler of add_filter('grace_church_action_shortcodes_list',	'grace_church_testimonials_reg_shortcodes');
    function grace_church_testimonials_reg_shortcodes() {
        global $GRACE_CHURCH_GLOBALS;
        if (isset($GRACE_CHURCH_GLOBALS['shortcodes'])) {

            $testimonials_groups = grace_church_get_list_terms(false, 'testimonial_group');
            $testimonials_styles = grace_church_get_list_templates('testimonials');
            $controls = grace_church_get_list_slider_controls();

            grace_church_array_insert_before($GRACE_CHURCH_GLOBALS['shortcodes'], 'trx_title', array(

                // Testimonials
                "trx_testimonials" => array(
                    "title" => esc_html__("Testimonials", "trx_utils"),
                    "desc" => esc_html__("Insert testimonials into post (page)", "trx_utils"),
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
                            "type" => "textarea"
                        ),
                        "style" => array(
                            "title" => esc_html__("Testimonials style", "trx_utils"),
                            "desc" => esc_html__("Select style to display testimonials", "trx_utils"),
                            "value" => "testimonials-1",
                            "type" => "select",
                            "options" => $testimonials_styles
                        ),
                        "columns" => array(
                            "title" => esc_html__("Columns", "trx_utils"),
                            "desc" => esc_html__("How many columns use to show testimonials", "trx_utils"),
                            "dependency" => array(
                                'style' => array('testimonials-2','testimonials-3','testimonials-4')
                            ),
                            "value" => 1,
                            "min" => 1,
                            "max" => 6,
                            "step" => 1,
                            "type" => "spinner"
                        ),
                        "slider" => array(
                            "title" => esc_html__("Slider", "trx_utils"),
                            "desc" => esc_html__("Use slider to show testimonials", "trx_utils"),
                            "value" => "yes",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "controls" => array(
                            "title" => esc_html__("Controls", "trx_utils"),
                            "desc" => esc_html__("Slider controls style and position", "trx_utils"),
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
                            "title" => esc_html__("Space between slides", "trx_utils"),
                            "desc" => esc_html__("Size of space (in px) between slides", "trx_utils"),
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
                            "title" => esc_html__("Slides change interval", "trx_utils"),
                            "desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => 7000,
                            "step" => 500,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "autoheight" => array(
                            "title" => esc_html__("Autoheight", "trx_utils"),
                            "desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                            "dependency" => array(
                                'slider' => array('yes')
                            ),
                            "value" => "yes",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "align" => array(
                            "title" => esc_html__("Alignment", "trx_utils"),
                            "desc" => esc_html__("Alignment of the testimonials block", "trx_utils"),
                            "divider" => true,
                            "value" => "",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['align']
                        ),
                        "custom" => array(
                            "title" => esc_html__("Custom", "trx_utils"),
                            "desc" => esc_html__("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                            "divider" => true,
                            "value" => "no",
                            "type" => "switch",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['yes_no']
                        ),
                        "cat" => array(
                            "title" => esc_html__("Categories", "trx_utils"),
                            "desc" => esc_html__("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "divider" => true,
                            "value" => "",
                            "type" => "select",
                            "style" => "list",
                            "multiple" => true,
                            "options" => grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $testimonials_groups)
                        ),
                        "count" => array(
                            "title" => esc_html__("Number of posts", "trx_utils"),
                            "desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
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
                                'custom' => array('no')
                            ),
                            "value" => 0,
                            "min" => 0,
                            "type" => "spinner"
                        ),
                        "orderby" => array(
                            "title" => esc_html__("Post order by", "trx_utils"),
                            "desc" => esc_html__("Select desired posts sorting method", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "date",
                            "type" => "select",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['sorting']
                        ),
                        "order" => array(
                            "title" => esc_html__("Post order", "trx_utils"),
                            "desc" => esc_html__("Select desired posts order", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
                            "value" => "desc",
                            "type" => "switch",
                            "size" => "big",
                            "options" => $GRACE_CHURCH_GLOBALS['sc_params']['ordering']
                        ),
                        "ids" => array(
                            "title" => esc_html__("Post IDs list", "trx_utils"),
                            "desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
                            "dependency" => array(
                                'custom' => array('no')
                            ),
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
                    ),
                    "children" => array(
                        "name" => "trx_testimonials_item",
                        "title" => esc_html__("Item", "trx_utils"),
                        "desc" => esc_html__("Testimonials item (custom parameters)", "trx_utils"),
                        "container" => true,
                        "params" => array(
                            "author" => array(
                                "title" => esc_html__("Author", "trx_utils"),
                                "desc" => esc_html__("Name of the testimonmials author", "trx_utils"),
                                "value" => "",
                                "type" => "text"
                            ),
                            "link" => array(
                                "title" => esc_html__("Link", "trx_utils"),
                                "desc" => esc_html__("Link URL to the testimonmials author page", "trx_utils"),
                                "value" => "",
                                "type" => "text"
                            ),
                            "email" => array(
                                "title" => esc_html__("E-mail", "trx_utils"),
                                "desc" => esc_html__("E-mail of the testimonmials author (to get gravatar)", "trx_utils"),
                                "value" => "",
                                "type" => "text"
                            ),
                            "photo" => array(
                                "title" => esc_html__("Photo", "trx_utils"),
                                "desc" => esc_html__("Select or upload photo of testimonmials author or write URL of photo from other site", "trx_utils"),
                                "value" => "",
                                "type" => "media"
                            ),
                            "_content_" => array(
                                "title" => esc_html__("Testimonials text", "trx_utils"),
                                "desc" => esc_html__("Current testimonials text", "trx_utils"),
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
                )

            ));
        }
    }
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('grace_church_testimonials_reg_shortcodes_vc')) {
    //Handler of add_filter('grace_church_action_shortcodes_list_vc',	'grace_church_testimonials_reg_shortcodes_vc');
    function grace_church_testimonials_reg_shortcodes_vc() {
        global $GRACE_CHURCH_GLOBALS;

        $testimonials_groups = grace_church_get_list_terms(false, 'testimonial_group');
        $testimonials_styles = grace_church_get_list_templates('testimonials');
        $controls			 = grace_church_get_list_slider_controls();

        // Testimonials
        vc_map( array(
            "base" => "trx_testimonials",
            "name" => esc_html__("Testimonials", "trx_utils"),
            "description" => esc_html__("Insert testimonials slider", "trx_utils"),
            "category" => esc_html__('Content', 'trx_utils'),
            'icon' => 'icon_trx_testimonials',
            "class" => "trx_sc_collection trx_sc_testimonials",
            "content_element" => true,
            "is_container" => true,
            "show_settings_on_create" => true,
            "as_parent" => array('only' => 'trx_testimonials_item'),
            "params" => array(
                array(
                    "param_name" => "style",
                    "heading" => esc_html__("Testimonials style", "trx_utils"),
                    "description" => esc_html__("Select style to display testimonials", "trx_utils"),
                    "class" => "",
                    "admin_label" => true,
                    "value" => array_flip($testimonials_styles),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "columns",
                    "heading" => esc_html__("Columns", "trx_utils"),
                    "description" => esc_html__("How many columns use to show testimonials", "trx_utils"),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('testimonials-2','testimonials-3','testimonials-4')
                    ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "1",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "slider",
                    "heading" => esc_html__("Slider", "trx_utils"),
                    "description" => esc_html__("Use slider to show testimonials", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
                    "class" => "",
                    "std" => "yes",
                    "value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['yes_no']),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "controls",
                    "heading" => esc_html__("Controls", "trx_utils"),
                    "description" => esc_html__("Slider controls style and position", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Space between slides", "trx_utils"),
                    "description" => esc_html__("Size of space (in px) between slides", "trx_utils"),
                    "admin_label" => true,
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Slides change interval", "trx_utils"),
                    "description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Autoheight", "trx_utils"),
                    "description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "trx_utils"),
                    "group" => esc_html__('Slider', 'trx_utils'),
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
                    "heading" => esc_html__("Alignment", "trx_utils"),
                    "description" => esc_html__("Alignment of the testimonials block", "trx_utils"),
                    "class" => "",
                    "value" => array_flip($GRACE_CHURCH_GLOBALS['sc_params']['align']),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "custom",
                    "heading" => esc_html__("Custom", "trx_utils"),
                    "description" => esc_html__("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "trx_utils"),
                    "class" => "",
                    "value" => array("Custom slides" => "yes" ),
                    "type" => "checkbox"
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
                    "param_name" => "cat",
                    "heading" => esc_html__("Categories", "trx_utils"),
                    "description" => esc_html__("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
                    'dependency' => array(
                        'element' => 'custom',
                        'is_empty' => true
                    ),
                    "class" => "",
                    "value" => array_flip(grace_church_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $testimonials_groups)),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "count",
                    "heading" => esc_html__("Number of posts", "trx_utils"),
                    "description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Offset before select posts", "trx_utils"),
                    "description" => esc_html__("Skip posts before select next part.", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post sorting", "trx_utils"),
                    "description" => esc_html__("Select desired posts sorting method", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post order", "trx_utils"),
                    "description" => esc_html__("Select desired posts order", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
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
                    "heading" => esc_html__("Post IDs list", "trx_utils"),
                    "description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "trx_utils"),
                    "group" => esc_html__('Query', 'trx_utils'),
                    'dependency' => array(
                        'element' => 'custom',
                        'is_empty' => true
                    ),
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
            )
        ) );


        vc_map( array(
            "base" => "trx_testimonials_item",
            "name" => esc_html__("Testimonial", "trx_utils"),
            "description" => esc_html__("Single testimonials item", "trx_utils"),
            "show_settings_on_create" => true,
            "class" => "trx_sc_single trx_sc_testimonials_item",
            "content_element" => true,
            "is_container" => false,
            'icon' => 'icon_trx_testimonials_item',
            "as_child" => array('only' => 'trx_testimonials'),
            "as_parent" => array('except' => 'trx_testimonials'),
            "params" => array(
                array(
                    "param_name" => "author",
                    "heading" => esc_html__("Author", "trx_utils"),
                    "description" => esc_html__("Name of the testimonmials author", "trx_utils"),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "link",
                    "heading" => esc_html__("Link", "trx_utils"),
                    "description" => esc_html__("Link URL to the testimonmials author page", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "email",
                    "heading" => esc_html__("E-mail", "trx_utils"),
                    "description" => esc_html__("E-mail of the testimonmials author", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "photo",
                    "heading" => esc_html__("Photo", "trx_utils"),
                    "description" => esc_html__("Select or upload photo of testimonmials author or write URL of photo from other site", "trx_utils"),
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
                array(
                    "param_name" => "content",
                    "heading" => esc_html__("Testimonials text", "trx_utils"),
                    "description" => esc_html__("Current testimonials text", "trx_utils"),
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

        class WPBakeryShortCode_Trx_Testimonials extends GRACE_CHURCH_VC_ShortCodeColumns {}
        class WPBakeryShortCode_Trx_Testimonials_Item extends GRACE_CHURCH_VC_ShortCodeSingle {}

    }
}

// Grace-Church shortcodes builder settings
require_once( trx_utils_get_file_dir('shortcodes/shortcodes_settings.php') );

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once( trx_utils_get_file_dir('shortcodes/shortcodes_vc.php') );
}

// Grace-Church shortcodes implementation
require_once( trx_utils_get_file_dir('shortcodes/shortcodes.php') );
?>