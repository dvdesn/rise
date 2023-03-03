<?php
/**
 * Grace-Church Shortcodes
*/


// ---------------------------------- [trx_accordion] ---------------------------------------

/*
[trx_accordion style="1" counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('grace_church_sc_accordion')) {
	function grace_church_sc_accordion($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$style = max(1, min(2, $style));
		$initial = max(0, (int) $initial);
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_accordion_counter'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_accordion_show_counter'] = grace_church_param_is_on($counter);
		$GRACE_CHURCH_GLOBALS['sc_accordion_icon_closed'] = empty($icon_closed) || grace_church_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed;
		$GRACE_CHURCH_GLOBALS['sc_accordion_icon_opened'] = empty($icon_opened) || grace_church_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened;
		wp_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion sc_accordion_style_'.esc_attr($style)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (grace_church_param_is_on($counter) ? ' sc_show_counter' : '')
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_accordion', 'grace_church_sc_accordion');
}


if (!function_exists('grace_church_sc_accordion_item')) {
	function grace_church_sc_accordion_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "",
			"icon_opened" => "",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_accordion_counter']++;
		if (empty($icon_closed) || grace_church_param_is_inherit($icon_closed)) $icon_closed = $GRACE_CHURCH_GLOBALS['sc_accordion_icon_closed'] ? $GRACE_CHURCH_GLOBALS['sc_accordion_icon_closed'] : "icon-plus";
		if (empty($icon_opened) || grace_church_param_is_inherit($icon_opened)) $icon_opened = $GRACE_CHURCH_GLOBALS['sc_accordion_icon_opened'] ? $GRACE_CHURCH_GLOBALS['sc_accordion_icon_opened'] : "icon-minus";
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. ($GRACE_CHURCH_GLOBALS['sc_accordion_counter'] % 2 == 1 ? ' odd' : ' even')
				. ($GRACE_CHURCH_GLOBALS['sc_accordion_counter'] == 1 ? ' first' : '')
				. '">'
				. '<h5 class="sc_accordion_title">'
                . '<span class="first-letter-accordion"></span>'
                . '<span class="sc_accordion_addtional">'
                    . (!grace_church_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
                    . (!grace_church_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
                    . ($GRACE_CHURCH_GLOBALS['sc_accordion_show_counter'] ? '<span class="sc_items_counter">'.($GRACE_CHURCH_GLOBALS['sc_accordion_counter']).'</span>' : '')
                    . ($title)
                .'</span>'
				. '</h5>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_accordion_item', 'grace_church_sc_accordion_item');
}
// ---------------------------------- [/trx_accordion] ---------------------------------------






// ---------------------------------- [trx_anchor] ---------------------------------------

						
/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('grace_church_sc_anchor')) {
	function grace_church_sc_anchor($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(grace_church_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (grace_church_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_anchor", "grace_church_sc_anchor");
}
// ---------------------------------- [/trx_anchor] ---------------------------------------





// ---------------------------------- [trx_audio] ---------------------------------------

/*
[trx_audio url="http://trex2.grace_church.dnw/wp-content/uploads/2014/12/Dream-Music-Relax.mp3" image="http://trex2.grace_church.dnw/wp-content/uploads/2014/10/post_audio.jpg" title="Insert Audio Title Here" author="Lily Hunter" controls="show" autoplay="off"]
*/

if (!function_exists('grace_church_sc_audio')) {
	function grace_church_sc_audio($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"type" => '',
			"align" => '',
			"controls" => "",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"inverse_color_player" => "",
		), $atts)));

		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '');
		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . ' sc_audio_type_'.esc_attr($type)  . '"'
			. ' src="'.esc_url($src).'"'
			. (grace_church_param_is_on($controls) ? ' controls="controls"' : '')
			. (grace_church_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ' width="'.esc_attr($width).'" height="'.esc_attr($height).'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)

            . ($inverse_color_player == 'yes' ? ' data-inverse="' . esc_attr($inverse_color_player) . '"'  : '')

			. '></audio>';
		if ( grace_church_get_custom_option('substitute_audio')=='no') {
			if (grace_church_param_is_on($frame)) {
				$audio = grace_church_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = grace_church_substitute_audio($audio, false);
			}
		}
		if (grace_church_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('grace_church_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_audio", "grace_church_sc_audio");
}
//// ---------------------------------- [/trx_audio] ---------------------------------------






// ---------------------------------- [trx_blogger] ---------------------------------------

/*
[trx_blogger id="unique_id" ids="comma_separated_list" cat="id|slug" orderby="date|views|comments" order="asc|desc" count="5" descr="0" dir="horizontal|vertical" style="regular|date|image_large|image_medium|image_small|accordion|list" border="0"]
*/
global $GRACE_CHURCH_GLOBALS;
$GRACE_CHURCH_GLOBALS['sc_blogger_busy'] = false;

if (!function_exists('grace_church_sc_blogger')) {
	function grace_church_sc_blogger($atts, $content=null){
		if (grace_church_in_shortcode_blogger(true)) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "accordion-1",
            "show_button" => "",
			"filters" => "no",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"columns" => "",
			"offset" => "",
			"orderby" => "date",
			"order" => "asc",
			"only" => "no",
			"descr" => "",
			"readmore" => "",
			"loadmore" => "no",
			"location" => "default",
			"dir" => "horizontal",
			"hover" => grace_church_get_theme_option('hover_style'),
			"hover_dir" => grace_church_get_theme_option('hover_dir'),
			"scroll" => "no",
			"controls" => "no",
			"rating" => "no",
			"info" => "yes",
			"links" => "yes",
			"date_format" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'trx_utils'),
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

        $css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$width  = grace_church_prepare_css_value($width);
		$height = grace_church_prepare_css_value($height);
	
		global $post, $GRACE_CHURCH_GLOBALS;
	
		$GRACE_CHURCH_GLOBALS['sc_blogger_busy'] = true;
		$GRACE_CHURCH_GLOBALS['sc_blogger_counter'] = 0;
	
		if (empty($id)) $id = "sc_blogger_".str_replace('.', '', mt_rand());
		
		if ($style=='date' && empty($date_format)) $date_format = 'd.m+Y';
	
		if (!empty($ids)) {
			$posts = explode(',', str_replace(' ', '', $ids));
			$count = count($posts);
        }

		if ($descr == '') $descr = grace_church_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : ''));
	
		if (!grace_church_param_is_off($scroll)) {
			grace_church_enqueue_slider();
			if (empty($id)) $id = 'sc_blogger_'.str_replace('.', '', mt_rand());
		}
		
		$class = apply_filters('grace_church_filter_blog_class',
					'sc_blogger'
					. ' layout_'.esc_attr($style)
					. ' template_'.esc_attr(grace_church_get_template_name($style))
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ' ' . esc_attr(grace_church_get_template_property($style, 'container_classes'))
					. ' sc_blogger_' . ($dir=='vertical' ? 'vertical' : 'horizontal')
					. (grace_church_param_is_on($scroll) && grace_church_param_is_on($controls) ? ' sc_scroll_controls sc_scroll_controls_type_top sc_scroll_controls_'.esc_attr($dir) : '')
					. ($descr == 0 ? ' no_description' : ''),
					array('style'=>$style, 'dir'=>$dir, 'descr'=>$descr)
		);
	
		$container = apply_filters('grace_church_filter_blog_container', grace_church_get_template_property($style, 'container'), array('style'=>$style, 'dir'=>$dir));
		$container_start = $container_end = '';
		if (!empty($container)) {
			$container = explode('%s', $container);
			$container_start = !empty($container[0]) ? $container[0] : '';
			$container_end = !empty($container[1]) ? $container[1] : '';
		}


		$output = '<div'
				. ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="'.($style=='list' ? 'sc_list sc_list_style_iconed ' : '') . esc_attr($class).'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. '>'
			. ($container_start)
			. (!empty($subtitle) ? '<h6 class="sc_blogger_subtitle sc_item_subtitle">' . trim(grace_church_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_blogger_title sc_item_title">' . trim(grace_church_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_blogger_descr sc_item_descr">' . trim(grace_church_strmacros($description)) . '</div>' : '')
			. ($style=='list' ? '<ul class="sc_list sc_list_style_iconed' . ( $show_button == 'yes' ? ' with_button' : '') .'">' : '')
            . ($dir=='horizontal' && $columns > 1 && grace_church_get_template_property($style, 'need_columns') ? '<div class="columns_wrap">' : '')
			. (grace_church_param_is_on($scroll)
				? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($dir).' sc_slider_noresize swiper-slider-container scroll-container"'
					. ' style="'.($dir=='vertical' ? 'height:'.($height != '' ? $height : "230px").';' : 'width:'.($width != '' ? $width.';' : "100%;")).'"'
					. '>'
					. '<div class="sc_scroll_wrapper swiper-wrapper">' 
						. '<div class="sc_scroll_slide swiper-slide">' 
				: '');
	
		if (grace_church_get_template_property($style, 'need_isotope')) {
			if (!grace_church_param_is_off($filters))
				$output .= '<div class="isotope_filters"></div>';
			if ($columns<1) $columns = grace_church_substr($style, -1);
			$output .= '<div class="isotope_wrap" data-columns="'.max(1, min(12, $columns)).'">';
		}

        $args = array(
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='desc' ? 'desc' : 'asc',
			'orderby' => 'date',
            'tribe_suppress_query_filters' => true,
        );

		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}

		if ($post_type == 'tribe_events') {
			if ( empty( $ids ) ) {
				$args['meta_query'] = array(
					array(
						'key'     => '_EventStartDate',
						'value'   => date( 'Y-m-d' ),
						'compare' => '>='
					)
				);
			}
		}

		// $args = grace_church_query_add_sort_order($args, $orderby, $order);
		if (!grace_church_param_is_off($only)) $args = grace_church_query_add_filters($args, $only);
		$args = grace_church_query_add_posts_and_cats($args, $ids, $post_type, $cat);

        $query = new WP_Query( $args );

        $flt_ids = array();

        while ( $query->have_posts() ) { $query->the_post();
	
			$GRACE_CHURCH_GLOBALS['sc_blogger_counter']++;
	
			$args = array(
				'layout' => $style,
				'show' => false,
				'number' => $GRACE_CHURCH_GLOBALS['sc_blogger_counter'],
				'add_view_more' => false,
				'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
				// Additional options to layout generator
				"location" => $location,
				"descr" => $descr,
				"readmore" => $readmore,
				"loadmore" => $loadmore,
				"reviews" => grace_church_param_is_on($rating),
				"dir" => $dir,
				"scroll" => grace_church_param_is_on($scroll),
				"info" => grace_church_param_is_on($info),
				"links" => grace_church_param_is_on($links),
				"orderby" => $orderby,
				"columns_count" => $columns,
				"date_format" => $date_format,
				// Get post data
				'strip_teaser' => false,
				'content' => grace_church_get_template_property($style, 'need_content'),
				'terms_list' => !grace_church_param_is_off($filters) || grace_church_get_template_property($style, 'need_terms'),
				'filters' => grace_church_param_is_off($filters) ? '' : $filters,
				'hover' => $hover,
				'hover_dir' => $hover_dir
			);

            $post_data = grace_church_get_post_data($args);
			$output .= grace_church_show_post_layout($args, $post_data);

            if (!grace_church_param_is_off($filters)) {
				if ($filters == 'tags') {			// Use tags as filter items
					if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
						foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
							$flt_ids[$tag->term_id] = $tag->name;
						}
					}
				}
			}
	
		}
	
		wp_reset_postdata();
	
		// Close isotope wrapper
		if (grace_church_get_template_property($style, 'need_isotope'))
			$output .= '</div>';
	
		// Isotope filters list
		if (!grace_church_param_is_off($filters)) {
			$filters_list = '';
			if ($filters == 'categories') {			// Use categories as filter items
				$taxonomy = grace_church_get_taxonomy_categories_by_post_type($post_type);
				$portfolio_parent = $cat ? max(0, grace_church_get_parent_taxonomy_by_property($cat, 'show_filters', 'yes', true, $taxonomy)) : 0;
				$args2 = array(
					'type'			=> $post_type,
					'child_of'		=> $portfolio_parent,
					'orderby'		=> 'name',
					'order'			=> 'ASC',
					'hide_empty'	=> 1,
					'hierarchical'	=> 0,
					'exclude'		=> '',
					'include'		=> '',
					'number'		=> '',
					'taxonomy'		=> $taxonomy,
					'pad_counts'	=> false
				);
				$portfolio_list = get_categories($args2);
				if (is_array($portfolio_list) && count($portfolio_list) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'. esc_html__('All', 'trx_utils').'</a>';
					foreach ($portfolio_list as $cat) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($cat->term_id).'" class="theme_button">'.($cat->name).'</a>';
					}
				}
			} else {								// Use tags as filter items
				if (is_array($flt_ids) && count($flt_ids) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'. esc_html__('All', 'trx_utils').'</a>';
					foreach ($flt_ids as $flt_id=>$flt_name) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($flt_id).'" class="theme_button">'.($flt_name).'</a>';
					}
				}
			}
			if ($filters_list) {
				$output .= '<script type="text/javascript">'
					. 'jQuery(document).ready(function () {'
						. 'jQuery("#'.esc_attr($id).' .isotope_filters").append("'.addslashes($filters_list).'");'
					. '});'
					. '</script>';
			}
		}
		$output	.= (grace_church_param_is_on($scroll)
				? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
					. (!grace_church_param_is_off($controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
				: '')
			. ($dir=='horizontal' && $columns > 1 && grace_church_get_template_property($style, 'need_columns') ? '</div>' : '')
			. ($style == 'list' ? '</ul>' : '')
			. (!empty($link) ? '<div class="sc_blogger_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
			. ($container_end)
			. '</div>';
	
		// Add template specific scripts and styles
		do_action('grace_church_action_blog_scripts', $style);
		
		$GRACE_CHURCH_GLOBALS['sc_blogger_busy'] = false;
	
		return apply_filters('grace_church_shortcode_output', $output, 'trx_blogger', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_blogger', 'grace_church_sc_blogger');
}
// ---------------------------------- [/trx_blogger] ---------------------------------------





// ---------------------------------- [trx_br] ---------------------------------------
						
/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('grace_church_sc_br')) {
	function grace_church_sc_br($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_br", "grace_church_sc_br");
}
// ---------------------------------- [/trx_br] ---------------------------------------






// ---------------------------------- [trx_button] ---------------------------------------

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('grace_church_sc_button')) {
	function grace_church_sc_button($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (grace_church_param_is_on($popup)) grace_church_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (grace_church_param_is_on($popup) ? ' popup_link' : '')
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_button', 'grace_church_sc_button');
}
// ---------------------------------- [/trx_button] ---------------------------------------






// ---------------------------------- [trx_call_to_action] ---------------------------------------

/*
[trx_call_to_action id="unique_id" style="1|2" align="left|center|right"]
	[inner shortcodes]
[/trx_call_to_action]
*/

if (!function_exists('grace_church_sc_call_to_action')) {
	function grace_church_sc_call_to_action($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"align" => "center",
			"custom" => "no",
			"accent" => "no",
			"image" => "",
			"video" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'trx_utils'),
			"link2" => '',
			"link2_caption" => '',
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
	
		if (empty($id)) $id = "sc_call_to_action_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if (!empty($image)) {
			$thumb_sizes = grace_church_get_thumb_sizes(array('layout' => 'excerpt'));
			$image = !empty($video) 
				? grace_church_get_resized_image_url($image, $thumb_sizes['w'], $thumb_sizes['h'])
				: grace_church_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		if (!empty($video)) {
			$video = '<video' . ($id ? ' id="' . esc_attr($id.'_video') . '"' : '') 
				. ' class="sc_video"'
				. ' src="' . esc_url(grace_church_get_video_player_url($video)) . '"'
				. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
				. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
				. ' data-ratio="16:9"'
				. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
				. ' controls="controls" loop="loop"'
				. '>'
				. '</video>';
			if (grace_church_get_custom_option('substitute_video')=='no') {
				$video = grace_church_get_video_frame($video, $image, '', '');
			} else {
				if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
					$video = grace_church_substitute_video($video, $width, $height, false);
				}
			}
			if (grace_church_get_theme_option('use_mediaelement')=='yes')
				wp_enqueue_script('wp-mediaelement');
		}
		
		$css = grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		
		$content = do_shortcode($content);
		
		$featured = ($style==1 && (!empty($content) || !empty($image) || !empty($video))
					? '<div class="sc_call_to_action_featured column-1_2">'
						. (!empty($content) 
							? $content 
							: (!empty($video) 
								? $video 
								: $image)
							)
						. '</div>'
					: '');
	
		$need_columns = ($featured || $style==2) && !in_array($align, array('center', 'none'))
							? ($style==2 ? 4 : 2)
							: 0;
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_call_to_action_buttons sc_item_buttons'.($need_columns && $style==2 ? ' column-1_'.esc_attr($need_columns) : '').'">'
							. (!empty($link) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="none"]'.esc_html($link_caption).'[/trx_button]').'</div>'
								: '')
							. (!empty($link2) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'" icon="none"]'.esc_html($link2_caption).'[/trx_button]').'</div>'
								: '')
							. '</div>'
						: '');
	
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_call_to_action'
					. (grace_church_param_is_on($accent) ? ' sc_call_to_action_accented' : '')
					. ' sc_call_to_action_style_' . esc_attr($style) 
					. ' sc_call_to_action_align_'.esc_attr($align)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (grace_church_param_is_on($accent) ? '<div class="content_wrap">' : '')
				. ($need_columns ? '<div class="columns_wrap">' : '')
				. ($align!='right' ? $featured : '')
				. ($style==2 && $align=='right' ? $buttons : '')
				. '<div class="sc_call_to_action_info'.($need_columns ? ' column-'.esc_attr($need_columns-1).'_'.esc_attr($need_columns) : '').'">'
					. (!empty($subtitle) ? '<h6 class="sc_call_to_action_subtitle sc_item_subtitle">' . trim(grace_church_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h4 class="sc_call_to_action_title sc_item_title">' . trim(grace_church_strmacros($title)) . '</h4>' : '')
					. (!empty($description) ? '<div class="sc_call_to_action_descr sc_item_descr">' . trim(grace_church_strmacros($description)) . '</div>' : '')
					. ($style==1 ? $buttons : '')
				. '</div>'
				. ($style==2 && $align!='right' ? $buttons : '')
				. ($align=='right' ? $featured : '')
				. ($need_columns ? '</div>' : '')
				. (grace_church_param_is_on($accent) ? '</div>' : '')
			. '</div>';
	
		return apply_filters('grace_church_shortcode_output', $output, 'trx_call_to_action', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_call_to_action', 'grace_church_sc_call_to_action');
}
// ---------------------------------- [/trx_call_to_action] ---------------------------------------





// ---------------------------------- [trx_chat] ---------------------------------------

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/

if (!function_exists('grace_church_sc_chat')) {
	function grace_church_sc_chat($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"photo" => "",
			"title" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$title = $title=='' ? $link : $title;
		if (!empty($photo)) {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = grace_church_get_resized_image_tag($photo, 75, 75);
		}
		$content = do_shortcode($content);
		if (grace_church_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_chat' . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
					. '<div class="sc_chat_inner">'
						. ($photo ? '<div class="sc_chat_avatar">'.($photo).'</div>' : '')
						. '<div class="sc_chat_field">'
                            . ($title == '' ? '' : ('<div class="sc_chat_title">' . ($link!='' ? '<a href="'.esc_url($link).'">' : '') . ($title) . ($link!='' ? '</a>' : '') . '</div>'))
                            . '<div class="sc_chat_content">'.($content).'</div>'
                        .'</div>'
					. '</div>'
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_chat', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_chat', 'grace_church_sc_chat');
}
// ---------------------------------- [/trx_chat] ---------------------------------------




// ---------------------------------- [trx_columns] ---------------------------------------

/*
[trx_columns id="unique_id" count="number"]
	[trx_column_item id="unique_id" span="2 - number_columns"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_column_item]
	[trx_column_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_column_item]
	[trx_column_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_column_item]
	[trx_column_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_column_item]
[/trx_columns]
*/

if (!function_exists('grace_church_sc_columns')) {
	function grace_church_sc_columns($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"count" => "2",
			"fluid" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$count = max(1, min(12, (int) $count));
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_columns_counter'] = 1;
		$GRACE_CHURCH_GLOBALS['sc_columns_after_span2'] = false;
		$GRACE_CHURCH_GLOBALS['sc_columns_after_span3'] = false;
		$GRACE_CHURCH_GLOBALS['sc_columns_after_span4'] = false;
		$GRACE_CHURCH_GLOBALS['sc_columns_count'] = $count;
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="columns_wrap sc_columns'
					. ' columns_' . (grace_church_param_is_on($fluid) ? 'fluid' : 'nofluid')
					. ' sc_columns_count_' . esc_attr($count)
					. (!empty($class) ? ' '.esc_attr($class) : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>'
					. do_shortcode($content)
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_columns', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_columns', 'grace_church_sc_columns');
}


if (!function_exists('grace_church_sc_column_item')) {
	function grace_church_sc_column_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"span" => "1",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"bg_image" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => ""
		), $atts)));
		$css .= ($align !== '' ? 'text-align:' . esc_attr($align) . ';' : '') 
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '');
		$span = max(1, min(11, (int) $span));
		if (!empty($bg_image)) {
			if ($bg_image > 0) {
				$attach = wp_get_attachment_image_src( $bg_image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$bg_image = $attach[0];
			}
		}
		global $GRACE_CHURCH_GLOBALS;
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="column-'.($span > 1 ? esc_attr($span) : 1).'_'.esc_attr($GRACE_CHURCH_GLOBALS['sc_columns_count']).' sc_column_item sc_column_item_'.esc_attr($GRACE_CHURCH_GLOBALS['sc_columns_counter'])
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($GRACE_CHURCH_GLOBALS['sc_columns_counter'] % 2 == 1 ? ' odd' : ' even')
					. ($GRACE_CHURCH_GLOBALS['sc_columns_counter'] == 1 ? ' first' : '')
					. ($span > 1 ? ' span_'.esc_attr($span) : '') 
					. ($GRACE_CHURCH_GLOBALS['sc_columns_after_span2'] ? ' after_span_2' : '')
					. ($GRACE_CHURCH_GLOBALS['sc_columns_after_span3'] ? ' after_span_3' : '')
					. ($GRACE_CHURCH_GLOBALS['sc_columns_after_span4'] ? ' after_span_4' : '')
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. '>'
					. ($bg_color!=='' || $bg_image !== '' ? '<div class="sc_column_item_inner" style="'
							. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '">' : '')
						. do_shortcode($content)
					. ($bg_color!=='' || $bg_image !== '' ? '</div>' : '')
					. '</div>';
		$GRACE_CHURCH_GLOBALS['sc_columns_counter'] += $span;
		$GRACE_CHURCH_GLOBALS['sc_columns_after_span2'] = $span==2;
		$GRACE_CHURCH_GLOBALS['sc_columns_after_span3'] = $span==3;
		$GRACE_CHURCH_GLOBALS['sc_columns_after_span4'] = $span==4;
		return apply_filters('grace_church_shortcode_output', $output, 'trx_column_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_column_item', 'grace_church_sc_column_item');
}
// ---------------------------------- [/trx_columns] ---------------------------------------





// ---------------------------------- [trx_contact_form] ---------------------------------------

/*
[trx_contact_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna."]
*/

if (!function_exists('grace_church_sc_contact_form')) {
	function grace_church_sc_contact_form($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"custom" => "no",
			"action" => "",
			"align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_contact_form_".str_replace('.', '', mt_rand());
		$css = grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width);
	
		grace_church_enqueue_messages();	// Load core messages
	
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_contact_form_id'] = $id;
		$GRACE_CHURCH_GLOBALS['sc_contact_form_counter'] = 0;
	
		$content = do_shortcode($content);
	
		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
					. ' class="sc_contact_form_wrap'
					. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
					. '">'
			.'<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_contact_form'
					. ' sc_contact_form_'.($content != '' && grace_church_param_is_on($custom) ? 'custom' : 'standard')
					. ' sc_contact_form_style_'.($style) 
					. (!empty($align) && !grace_church_param_is_off($align) ? ' align'.esc_attr($align) : '')
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>'
					. (!empty($subtitle) 
						? '<h6 class="sc_contact_form_subtitle sc_item_subtitle">' . trim(grace_church_strmacros($subtitle)) . '</h6>'
						: '')
					. (!empty($title) 
						? '<h2 class="sc_contact_form_title sc_item_title">' . trim(grace_church_strmacros($title)) . '</h2>'
						: '')
					. (!empty($description) 
						? '<div class="sc_contact_form_descr sc_item_descr">' . trim(grace_church_strmacros($description)) . ($style == 1 ? do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]') : '') . '</div>'
						: '');
		
		if ($style == 2) {
			$address_1 = grace_church_get_theme_option('contact_address_1');
//			$address_2 = grace_church_get_theme_option('contact_address_2');
			$phone = grace_church_get_theme_option('contact_phone');
			$fax = grace_church_get_theme_option('contact_fax');
			$email = grace_church_get_theme_option('contact_email');
			$open_hours = grace_church_get_theme_option('contact_open_hours');
			$open_hours_2 = grace_church_get_theme_option('contact_open_hours_2');
			$output .= '<div class="sc_columns columns_wrap">'
				. '<div class="sc_contact_form_address column-1_3">'
				. '<div class="sc_contact_form_address_field">'
					. '<span class="sc_contact_form_address_label">'. esc_html__('Address', 'trx_utils').'</span>'
					. '<span class="sc_contact_form_address_data">'.trim($address_1).'</span>'/*   '.trim($address_1).(!empty($address_1) && !empty($address_2) ? ', ' : '').$address_2.'  */
				. '</div>'
				. '<div class="sc_contact_form_address_field">'
					. '<span class="sc_contact_form_address_label">'. esc_html__('We are open', 'trx_utils').'</span>'
					. '<span class="sc_contact_form_address_data">'.trim($open_hours).'</span>'
					. '<span class="sc_contact_form_address_data">'.trim($open_hours_2).'</span>'
				. '</div>'
				. '<div class="sc_contact_form_address_field">'
					. '<span class="sc_contact_form_address_label">'. esc_html__('Phone', 'trx_utils').'</span>'
					. '<span class="sc_contact_form_address_data">'.trim($phone).(!empty($phone) && !empty($fax) ? ', ' : '').$fax.'</span>'
				. '</div>'
				. '<div class="sc_contact_form_address_field">'
					. '<span class="sc_contact_form_address_label">'. esc_html__('E-mail', 'trx_utils').'</span>'
					. '<span class="sc_contact_form_address_data">'.trim($email).'</span>'
				. '</div>'
				. do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]')
				. '</div>'
				. '<div class="sc_contact_form_fields column-2_3">'
				;
		}

        static $cnt = 0;
        $cnt++;
        $privacy = trx_utils_get_privacy_text();
		$output .= '<form' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' data-formtype="'.($content ? 'custom' : 'contact').'" method="post" action="' . esc_url($action ? $action : $GRACE_CHURCH_GLOBALS['ajax_url']) . '">'
					. ($content != '' && grace_church_param_is_on($custom)
						? $content 
						: '<div class="sc_contact_form_info">'
								.'<div class="sc_contact_form_item sc_contact_form_field label_over"><label class="required" for="sc_contact_form_username">' . esc_html__('Name', 'trx_utils') . '</label><input id="sc_contact_form_username" type="text" name="username" placeholder="' . esc_html__('First and last name *', 'trx_utils') . '"></div>'
								.'<div class="sc_contact_form_item sc_contact_form_field label_over"><label class="required" for="sc_contact_form_email">' . esc_html__('E-mail', 'trx_utils') . '</label><input id="sc_contact_form_email" type="text" name="email" placeholder="' . esc_html__('Your Email *', 'trx_utils') . '"></div>'
								.'<div class="sc_contact_form_item sc_contact_form_field label_over"><label class="required" for="sc_contact_form_phone">' . esc_html__('Phone', 'trx_utils') . '</label><input id="sc_contact_form_phone" type="text" name="phone" placeholder="' . esc_html__('Phone', 'trx_utils') . '"></div>'
								.'<div class="sc_contact_form_item sc_contact_form_field label_over"><label class="required" for="sc_contact_form_subj">' . esc_html__('Subject', 'trx_utils') . '</label><input id="sc_contact_form_subj" type="text" name="subject" placeholder="' . esc_html__('Subject *', 'trx_utils') . '"></div>'
							.'</div>'
							.'<div class="sc_contact_form_item sc_contact_form_message label_over"><label class="required" for="sc_contact_form_message">' . esc_html__('Message', 'trx_utils') . '</label><textarea id="sc_contact_form_message" name="message" placeholder="' . esc_html__('Your Message *', 'trx_utils') . '"></textarea></div>'
                . ((!empty($privacy)) ? '<div class="sc_form_field sc_form_field_checkbox">
                  <input type="checkbox" id="i_agree_privacy_policy_sc_form_' . esc_attr($cnt) . '" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
                  <label for="i_agree_privacy_policy_sc_form_' . esc_attr($cnt) . '">' . $privacy . '</label></div>' : '')
                .'<div class="sc_contact_form_item sc_contact_form_button"><button' . (!empty($privacy) ? ' disabled="disabled"' : '') . '>'. esc_html__('SEND', 'trx_utils').'</button></div>')
					. '<div class="result sc_infobox"></div>'
				. '</form>';
		
		if ($style==2) {
			$output .= '</div></div>';
		}
	
		$output .= '</div>'
				. '</div>';
	
		return apply_filters('grace_church_shortcode_output', $output, 'trx_contact_form', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_contact_form", "grace_church_sc_contact_form");
}

if (!function_exists('grace_church_sc_contact_form_item')) {
	function grace_church_sc_contact_form_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "text",
			"name" => "",
			"value" => "",
			"options" => "",
			"align" => "",
			"label" => "",
			"label_position" => "top",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_contact_form_counter']++;
	
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		if (empty($id)) $id = ($GRACE_CHURCH_GLOBALS['sc_contact_form_id']).'_'.($GRACE_CHURCH_GLOBALS['sc_contact_form_counter']);
	
		$label = $type!='button' && $type!='submit' && $label ? '<label for="' . esc_attr($id) . '">' . esc_attr($label) . '</label>' : $label;
	
		// Open field container
		$output = '<div class="sc_contact_form_item sc_contact_form_item_'.esc_attr($type)
						.' sc_contact_form_'.($type == 'textarea' ? 'message' : ($type == 'button' || $type == 'submit' ? 'button' : 'field'))
						.' label_'.esc_attr($label_position)
						.($class ? ' '.esc_attr($class) : '')
						.($align && $align!='none' ? ' align'.esc_attr($align) : '')
					.'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. '>';
		
		// Label top or left
		if ($type!='button' && $type!='submit' && ($label_position=='top' || $label_position=='left'))
			$output .= $label;
		// Field
		if ($type == 'textarea')
			$output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>';
		else if ($type=='button' || $type=='submit')
			$output .= '<button id="' . esc_attr($id) . '">'.($label ? $label : $value).'</button>';
		else if ($type=='radio' || $type=='checkbox') {
			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$i = 0;
					foreach ($options as $v) {
						$i++;
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<div class="sc_contact_form_element">'
										. '<input type="'.esc_attr($type) . '"'
											. ' id="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '"'
											. ' name="' . esc_attr($name ? $name : $id) . (count($options) > 1 && $type=='checkbox' ? '[]' : '') . '"'
											. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
											. (in_array($parts[0], explode(',', $value)) ? ' checked="checked"' : '') 
										. '>'
										. '<label for="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '">' . trim(chop($parts[1])) . '</label>'
									. '</div>';
					}
				}
			}
		} else if ($type=='select') {
			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$output .= '<div class="sc_contact_form_select_container">'
						. '<select id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">';
					foreach ($options as $v) {
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<option'
										. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
										. (in_array($parts[0], explode(',', $value)) ? ' selected="selected"' : '') 
									. '>'
									. trim(chop($parts[1]))
									. '</option>';
					}
					$output .= '</select>'
							. '</div>';
				}
			}
		} else
			$output .= '<input type="'.esc_attr($type ? $type : 'text').'" id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '">';
		// Label bottom
		if ($type!='button' && $type!='submit' && $label_position=='bottom')
			$output .= $label;
		
		// Close field container
		$output .= '</div>';
	
		return apply_filters('grace_church_shortcode_output', $output, 'trx_form_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_form_item', 'grace_church_sc_contact_form_item');
}

// AJAX Callback: Send contact form data
if ( !function_exists( 'grace_church_sc_contact_form_send' ) ) {
	function grace_church_sc_contact_form_send() {
        global $_REQUEST, $GRACE_CHURCH_GLOBALS;

        if ( !wp_verify_nonce( $_REQUEST['nonce'], $GRACE_CHURCH_GLOBALS['ajax_url'] ) )
			die();
	
		$response = array('error'=>'');
		if (!($contact_email = grace_church_get_theme_option('contact_email')) && !($contact_email = grace_church_get_theme_option('admin_email')))
			$response['error'] = esc_html__('Unknown admin email!', 'trx_utils');
		else {
			$type = grace_church_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

			if ($type=='contact') {
				$user_name	= grace_church_strshort($post_data['username'],	100);
				$user_email	= grace_church_strshort($post_data['email'],	100);
				$user_subj	= grace_church_strshort($post_data['subject'],	100);
				$user_msg	= grace_church_strshort($post_data['message'],	grace_church_get_theme_option('message_maxlength_contacts'));
		
				$subj = sprintf( esc_html__('Site %s - Contact form message from %s', 'trx_utils'), get_bloginfo('site_name'), $user_name);
				$msg = "\n". esc_html__('Name:', 'trx_utils')   .' '.esc_html($user_name)
					.  "\n". esc_html__('E-mail:', 'trx_utils') .' '.esc_html($user_email)
					.  "\n". esc_html__('Subject:', 'trx_utils').' '.esc_html($user_subj)
					.  "\n". esc_html__('Message:', 'trx_utils').' '.esc_html($user_msg);

			} else {

				$subj = sprintf( esc_html__('Site %s - Custom form data', 'trx_utils'), get_bloginfo('site_name'));
				$msg = '';
				if (is_array($post_data) && count($post_data) > 0) {
					foreach ($post_data as $k=>$v)
						$msg .= "\n{$k}: $v";
				}
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . esc_url( home_url( '/' ) ) . ") ............";

			$mail = grace_church_get_theme_option('mail_function') == 'mail' ? 'mail' : 'wp_mail';
			if (is_email($contact_email) && !@$mail($contact_email, $subj, apply_filters('grace_church_filter_contact_form_message', $msg))) {
				$response['error'] = esc_html__('Error send message!', 'trx_utils');
			}
		
			echo json_encode($response);
			die();
		}
	}
}

// ---------------------------------- [/trx_contact_form] ---------------------------------------




// ---------------------------------- [trx_content] ---------------------------------------

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('grace_church_sc_content')) {
	function grace_church_sc_content($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values('!'.($top), '', '!'.($bottom));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_content', 'grace_church_sc_content');
}
// ---------------------------------- [/trx_content] ---------------------------------------





// ---------------------------------- [trx_countdown] ---------------------------------------

//[trx_countdown date="" time=""]

if (!function_exists('grace_church_sc_countdown')) {
	function grace_church_sc_countdown($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"date" => "",
			"time" => "",
			"style" => "2",
			"align" => "center",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if (empty($id)) $id = "sc_countdown_".str_replace('.', '', mt_rand());
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		if (empty($interval)) $interval = 1;
		wp_enqueue_script( 'grace-church-jquery-plugin-script', grace_church_get_file_url('js/countdown/jquery.plugin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'grace-church-countdown-script', grace_church_get_file_url('js/countdown/jquery.countdown.js'), array('jquery'), null, true );
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_countdown sc_countdown_style_' . esc_attr(max(1, min(2, $style))) . (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') . (!empty($class) ? ' '.esc_attr($class) : '') .'"'
			. ($css ? ' style="'.esc_attr($css).'"' : '')
			. ' data-date="'.esc_attr(empty($date) ? date('Y-m-d') : $date).'"'
			. ' data-time="'.esc_attr(empty($time) ? '00:00:00' : $time).'"'
			. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. '>'
				. '<div class="sc_countdown_item sc_countdown_days">'
					. '<span class="sc_countdown_digits"><span></span><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'. esc_html__('Days', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_hours">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'. esc_html__('Hours', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_minutes">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'. esc_html__('Minutes', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_seconds">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'. esc_html__('Seconds', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_placeholder hide"></div>'
			. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_countdown', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_countdown", "grace_church_sc_countdown");
}
// ---------------------------------- [/trx_countdown] ---------------------------------------



						


// ---------------------------------- [trx_dropcaps] ---------------------------------------

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('grace_church_sc_dropcaps')) {
	function grace_church_sc_dropcaps($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$style = min(4, max(1, $style));
		$content = do_shortcode($content);
		$output = grace_church_substr($content, 0, 1) == '<'
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(grace_church_substr($content, 0, 1)) . '</span>' . trim(grace_church_substr($content, 1))
			. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_dropcaps', 'grace_church_sc_dropcaps');
}
// ---------------------------------- [/trx_dropcaps] ---------------------------------------





// ---------------------------------- [trx_emailer] ---------------------------------------

//[trx_emailer group=""]

if (!function_exists('grace_church_sc_emailer')) {
	function grace_church_sc_emailer($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "yes",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		// Load core messages
		grace_church_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (grace_church_param_is_on($open) ? ' sc_emailer_opened' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. '>'
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="'. esc_html__('Please, enter you email address.', 'trx_utils').'">'
				. '<a href="#" class="sc_emailer_button icon-mail" title="'. esc_html__('Submit', 'trx_utils').'" data-group="'.($group ? $group : esc_html__('E-mailer subscription', 'trx_utils')).'"></a>'
				. '</form>'
			. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_emailer", "grace_church_sc_emailer");
}
// ---------------------------------- [/trx_emailer] ---------------------------------------





// ---------------------------------- [trx_gap] ---------------------------------------
						
//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('grace_church_sc_gap')) {
	function grace_church_sc_gap($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		$output = grace_church_gap_start() . do_shortcode($content) . grace_church_gap_end();
		return apply_filters('grace_church_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_gap", "grace_church_sc_gap");
}
// ---------------------------------- [/trx_gap] ---------------------------------------






// ---------------------------------- [trx_googlemap] ---------------------------------------

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('grace_church_sc_googlemap')) {
	function grace_church_sc_googlemap($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'greyscale',
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = grace_church_get_custom_option('googlemap_style');
        if (grace_church_get_theme_option('api_google') != ''){
            $api_key = grace_church_get_theme_option('api_google');
            wp_enqueue_script( 'googlemap', grace_church_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
            wp_enqueue_script( 'grace-church-googlemap-script', trx_utils_get_file_url('js/core.googlemap.js'), array(), null, true );
        }
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_googlemap_markers'] = array();
		$content = do_shortcode($content);
		$output = '';
		if (count($GRACE_CHURCH_GLOBALS['sc_googlemap_markers']) == 0) {
			$GRACE_CHURCH_GLOBALS['sc_googlemap_markers'][] = array(
				'title' => grace_church_get_custom_option('googlemap_title'),
				'description' => grace_church_strmacros(grace_church_get_custom_option('googlemap_description')),
				'latlng' => grace_church_get_custom_option('googlemap_latlng'),
				'address' => grace_church_get_custom_option('googlemap_address'),
				'point' => grace_church_get_custom_option('googlemap_marker')
			);
		}
		$output .= '<div id="'.esc_attr($id).'"'
			. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. ' data-zoom="'.esc_attr($zoom).'"'
			. ' data-style="'.esc_attr($style).'"'
			. '>';
		$cnt = 0;
		foreach ($GRACE_CHURCH_GLOBALS['sc_googlemap_markers'] as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.$cnt;
            if (grace_church_get_theme_option('api_google') != ''){
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(grace_church_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
            } else {
                $output .= '<iframe src="https://maps.google.com/maps?t=m&output=embed&iwloc=near&z='.esc_attr($zoom > 0 ? $zoom : 14).'&q='
                    . esc_attr(!empty($marker['address']) ? urlencode($marker['address']) : '')
                    . ( !empty($marker['latlng'])
                        ? ( !empty($marker['address']) ? '@' : '' ) . str_replace(' ', '', $marker['latlng'])
                        : ''
                    )
                    . '" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"'
                    . ' aria-label="' . esc_attr(!empty($marker['title']) ? $marker['title'] : '') . '"></iframe>';
            }
        }
		$output .= '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_googlemap", "grace_church_sc_googlemap");
}


if (!function_exists('grace_church_sc_googlemap_marker')) {
	function grace_church_sc_googlemap_marker($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_googlemap_markers'][] = array(
			'id' => $id,
			'title' => $title,
			'description' => do_shortcode($content),
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : grace_church_get_custom_option('googlemap_marker')
		);
		return '';
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_googlemap_marker", "grace_church_sc_googlemap_marker");
}
// ---------------------------------- [/trx_googlemap] ---------------------------------------





// ---------------------------------- [trx_hide] ---------------------------------------

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('grace_church_sc_hide')) {
	function grace_church_sc_hide($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_hide', 'grace_church_sc_hide');
}
// ---------------------------------- [/trx_hide] ---------------------------------------





// ---------------------------------- [trx_highlight] ---------------------------------------

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('grace_church_sc_highlight')) {
	function grace_church_sc_highlight($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(grace_church_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_highlight', 'grace_church_sc_highlight');
}
// ---------------------------------- [/trx_highlight] ---------------------------------------





// ---------------------------------- [trx_icon] ---------------------------------------

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('grace_church_sc_icon')) {
	function grace_church_sc_icon($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !grace_church_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(grace_church_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || grace_church_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !grace_church_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($css ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_icon', 'grace_church_sc_icon');
}
// ---------------------------------- [/trx_icon] ---------------------------------------





// ---------------------------------- [trx_image] ---------------------------------------

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('grace_church_sc_image')) {
	function grace_church_sc_image($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values('!'.($top), '!'.($right), '!'.($bottom), '!'.($left), $width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = grace_church_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) grace_church_enqueue_popup();
         $output = empty($src) ? '' : ('<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="figure sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="" />'
                . (trim($title) || trim($icon) ? '<span class="figcaption"><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span><span class="title-wrap">' . ($title) . '</span></span>' : '')
                . (trim($link) ? '</a>' : '')
			. '</div>');
		return apply_filters('grace_church_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_image', 'grace_church_sc_image');
}
// ---------------------------------- [/trx_image] ---------------------------------------






// ---------------------------------- [trx_infobox] ---------------------------------------

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('grace_church_sc_infobox')) {
	function grace_church_sc_infobox($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (grace_church_param_is_on($closeable) ? ' sc_infobox_closeable' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='none' && $icon!='' && !grace_church_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '')
					. '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_infobox', 'grace_church_sc_infobox');
}
// ---------------------------------- [/trx_infobox] ---------------------------------------





// ---------------------------------- [trx_line] ---------------------------------------

/*
[trx_line id="unique_id" style="none|solid|dashed|dotted|double|groove|ridge|inset|outset" top="margin_in_pixels" bottom="margin_in_pixels" width="width_in_pixels_or_percent" height="line_thickness_in_pixels" color="line_color's_name_or_#rrggbb"]
*/

if (!function_exists('grace_church_sc_line')) {
	function grace_church_sc_line($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "solid",
			"color" => "",
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
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width)
			.($height !='' ? 'border-top-width:' . esc_attr($height) . 'px;' : '')
			.($style != '' ? 'border-top-style:' . esc_attr($style) . ';' : '')
			.($color != '' ? 'border-top-color:' . esc_attr($color) . ';' : '');
		$output = '<div' . ($id ? ' id="'.esc_attr($id) . '"' : '') 
				. ' class="sc_line' . ($style != '' ? ' sc_line_style_'.esc_attr($style) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '></div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_line', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_line', 'grace_church_sc_line');
}
// ---------------------------------- [/trx_line] ---------------------------------------





// ---------------------------------- [trx_list] ---------------------------------------

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('grace_church_sc_list')) {
	function grace_church_sc_list($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '');
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_list_counter'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_list_icon'] = empty($icon) || grace_church_param_is_inherit($icon) ? "icon-right" : $icon;
		$GRACE_CHURCH_GLOBALS['sc_list_icon_color'] = $icon_color;
		$GRACE_CHURCH_GLOBALS['sc_list_style'] = $style;
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_list', 'grace_church_sc_list');
}


if (!function_exists('grace_church_sc_list_item')) {
	function grace_church_sc_list_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
        global $GRACE_CHURCH_GLOBALS;
        $GRACE_CHURCH_GLOBALS['sc_list_counter']++;
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || grace_church_param_is_inherit($icon)) $icon = $GRACE_CHURCH_GLOBALS['sc_list_icon'];
		if (trim($color) == '' || grace_church_param_is_inherit($icon_color)) $icon_color = $GRACE_CHURCH_GLOBALS['sc_list_icon_color'];
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. ($GRACE_CHURCH_GLOBALS['sc_list_counter'] % 2 == 1 ? ' odd' : ' even')
			. ($GRACE_CHURCH_GLOBALS['sc_list_counter'] == 1 ? ' first' : '')
            . '"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>'
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. ($GRACE_CHURCH_GLOBALS['sc_list_style']=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. do_shortcode($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_list_item', 'grace_church_sc_list_item');
}
// ---------------------------------- [/trx_list] ---------------------------------------






// ---------------------------------- [trx_number] ---------------------------------------

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('grace_church_sc_number')) {
	function grace_church_sc_number($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < grace_church_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(grace_church_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_number', 'grace_church_sc_number');
}
// ---------------------------------- [/trx_number] ---------------------------------------





// ---------------------------------- [trx_parallax] ---------------------------------------

/*
[trx_parallax id="unique_id" style="light|dark" dir="up|down" image="" color='']Content for parallax block[/trx_parallax]
*/

if (!function_exists('grace_church_sc_parallax')) {
	function grace_church_sc_parallax($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"gap" => "no",
			"dir" => "up",
			"speed" => 0.3,
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_image_x" => "",
			"bg_image_y" => "",
			"bg_video" => "",
			"bg_video_ratio" => "16:9",
			"bg_overlay" => "",
			"bg_texture" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if ($bg_video!='') {
			$info = pathinfo($bg_video);
			$ext = !empty($info['extension']) ? $info['extension'] : 'mp4';
			$bg_video_ratio = empty($bg_video_ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $bg_video_ratio);
			$ratio = explode(':', $bg_video_ratio);
			$bg_video_width = !empty($width) && grace_church_substr($width, -1) >= '0' && grace_church_substr($width, -1) <= '9'  ? $width : 1280;
			$bg_video_height = round($bg_video_width / $ratio[0] * $ratio[1]);
			if (grace_church_get_theme_option('use_mediaelement')=='yes')
				wp_enqueue_script('wp-mediaelement');
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		$bg_image_x = $bg_image_x!='' ? str_replace('%', '', $bg_image_x).'%' : "50%";
		$bg_image_y = $bg_image_y!='' ? str_replace('%', '', $bg_image_y).'%' : "50%";
		$speed = ($dir=='down' ? -1 : 1) * abs($speed);
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = grace_church_get_scheme_color('bg');
			$rgb = grace_church_hex2rgb($bg_color);
		}
		$css .= grace_church_get_css_position_from_values($top, '!'.($right), $bottom, '!'.($left), $width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			;
		$output = (grace_church_param_is_on($gap) ? grace_church_gap_start() : '')
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_parallax' 
					. ($bg_video!='' ? ' sc_parallax_with_video' : '') 
					. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. ' data-parallax-speed="'.esc_attr($speed).'"'
				. ' data-parallax-x-pos="'.esc_attr($bg_image_x).'"'
				. ' data-parallax-y-pos="'.esc_attr($bg_image_y).'"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>'
			. ($bg_video!='' 
				? '<div class="sc_video_bg_wrapper"><video class="sc_video_bg"'
					. ' width="'.esc_attr($bg_video_width).'" height="'.esc_attr($bg_video_height).'" data-width="'.esc_attr($bg_video_width).'" data-height="'.esc_attr($bg_video_height).'" data-ratio="'.esc_attr($bg_video_ratio).'" data-frame="no"'
					. ' preload="metadata" autoplay="autoplay" loop="loop" src="'.esc_attr($bg_video).'"><source src="'.esc_url($bg_video).'" type="video/'.esc_attr($ext).'"></source></video></div>' 
				: '')
			. '<div class="sc_parallax_content" style="' . ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . '); background-position:'.esc_attr($bg_image_x).' '.esc_attr($bg_image_y).';' : '').'">'
			. ($bg_overlay>0 || $bg_texture!=''
				? '<div class="sc_parallax_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
					. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
						. (grace_church_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
						. '"'
						. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
						. '>' 
				: '')
			. do_shortcode($content)
			. ($bg_overlay > 0 || $bg_texture!='' ? '</div>' : '')
			. '</div>'
			. '</div>'
			. (grace_church_param_is_on($gap) ? grace_church_gap_end() : '');
		return apply_filters('grace_church_shortcode_output', $output, 'trx_parallax', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_parallax', 'grace_church_sc_parallax');
}
// ---------------------------------- [/trx_parallax] ---------------------------------------




// ---------------------------------- [trx_popup] ---------------------------------------

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('grace_church_sc_popup')) {
	function grace_church_sc_popup($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		grace_church_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_popup', 'grace_church_sc_popup');
}
// ---------------------------------- [/trx_popup] ---------------------------------------






// ---------------------------------- [trx_price] ---------------------------------------

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('grace_church_sc_price')) {
	function grace_church_sc_price($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		if (!empty($money)) {
			$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
			$m = explode('.', str_replace(',', '.', $money));
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. '>'
				. '<span class="sc_price_currency">'.($currency).'</span>'
				. '<span class="sc_price_money">'.($m[0]).'</span>'
				. (!empty($m[1]) ? '<span class="sc_price_info">' : '')
				. (!empty($m[1]) ? '<span class="sc_price_penny">'.($m[1]).'</span>' : '')
				. (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : (!empty($m[1]) ? '<span class="sc_price_period_empty"></span>' : ''))
				. (!empty($m[1]) ? '</span>' : '')
				. '</div>';
		}
		return apply_filters('grace_church_shortcode_output', $output, 'trx_price', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_price', 'grace_church_sc_price');
}
// ---------------------------------- [/trx_price] ---------------------------------------





// ---------------------------------- [trx_price_block] ---------------------------------------

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('grace_church_sc_price_block')) {
	function grace_church_sc_price_block($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
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
		$output = '';
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title">'.($title).'</div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_price_block', 'grace_church_sc_price_block');
}
// ---------------------------------- [/trx_price_block] ---------------------------------------




// ---------------------------------- [trx_quote] ---------------------------------------

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('grace_church_sc_quote')) {
	function grace_church_sc_quote($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			"style" => "",
            // Special from style Transparent
            "bg_color" => "",
            "bg_image" => "",
            "bg_overlay" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

        if ($bg_image > 0) {
            $attach = wp_get_attachment_image_src( $bg_image, 'full' );
            if (isset($attach[0]) && $attach[0]!='')
                $bg_image = $attach[0];
        }

        if ($bg_overlay > 0) {
            if ($bg_color=='') $bg_color = grace_church_get_scheme_color('bg');
            $rgb = grace_church_hex2rgb($bg_color);
        }

		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width)
            .($bg_color !== '' && $bg_overlay==0 ? ' background-color:' . esc_attr($bg_color) . ';' : '')
            .($bg_image !== '' ? ' background-image:url(' . esc_url($bg_image) . ');' : '');

		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (grace_church_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '') . (!empty($style) ? ' '.esc_attr($style) : '') .'"'
			. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).  '"' : '')
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_quote', 'grace_church_sc_quote');
}
// ---------------------------------- [/trx_quote] ---------------------------------------





// ---------------------------------- [trx_reviews] ---------------------------------------
						
/*
[trx_reviews]
*/

if (!function_exists('grace_church_sc_reviews')) {
	function grace_church_sc_reviews($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "right",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$output = grace_church_param_is_off(grace_church_get_custom_option('show_sidebar_main'))
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_reviews'
							. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class ? ' '.esc_attr($class) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
						. '>'
					. trim(grace_church_get_reviews_placeholder())
					. '</div>'
			: '';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_reviews', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_reviews", "grace_church_sc_reviews");
}
// ---------------------------------- [/trx_reviews] ---------------------------------------




// ---------------------------------- [trx_search] ---------------------------------------

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('grace_church_sc_search')) {
	function grace_church_sc_search($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'trx_utils'),
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		if (empty($ajax)) $ajax = grace_church_get_theme_option('use_ajax_search');
		// Load core messages
		grace_church_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (grace_church_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/' ) ) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_html__('Open search', 'trx_utils') : esc_html__('Start search', 'trx_utils')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_search', 'grace_church_sc_search');
}
// ---------------------------------- [/trx_search] ---------------------------------------




// ---------------------------------- [trx_section] and [trx_block] ---------------------------------------

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

global $GRACE_CHURCH_GLOBALS;
$GRACE_CHURCH_GLOBALS['sc_section_dedicated'] = '';

if (!function_exists('grace_church_sc_section')) {
	function grace_church_sc_section($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "no",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"font_size" => "",
			"font_weight" => "",
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = grace_church_get_scheme_color('bg');
			$rgb = grace_church_hex2rgb($bg_color);
		}
	
		$css .= grace_church_get_css_position_from_values('!'.($top), '!'.($right), '!'.($bottom), '!'.($left))
			.($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
			.(!grace_church_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(grace_church_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !grace_church_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = grace_church_get_css_position_from_values('', '', '', '', $width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && grace_church_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = grace_church_prepare_css_value($width);
		$height = grace_church_prepare_css_value($height);
	
		if ((!grace_church_param_is_off($scroll) || !grace_church_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!grace_church_param_is_off($scroll)) grace_church_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (grace_church_param_is_on($scroll) && !grace_church_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || grace_church_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (grace_church_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content"'
									. ($css_dim)
									. '>'
						: '')
					. (grace_church_param_is_on($scroll)
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (grace_church_param_is_on($pan)
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
					. do_shortcode($content)
					. (grace_church_param_is_on($pan) ? '</div>' : '')
					. (grace_church_param_is_on($scroll)
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!grace_church_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || grace_church_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (grace_church_param_is_on($dedicated)) {
			global $GRACE_CHURCH_GLOBALS;
			if ($GRACE_CHURCH_GLOBALS['sc_section_dedicated']=='') {
				$GRACE_CHURCH_GLOBALS['sc_section_dedicated'] = $output;
			}
			$output = '';
		}
		return apply_filters('grace_church_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_section', 'grace_church_sc_section');
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_block', 'grace_church_sc_section');
}
// ---------------------------------- [/trx_section] ---------------------------------------





// ---------------------------------- [trx_skills] ---------------------------------------

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" max_value="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
[/trx_skills]
*/

if (!function_exists('grace_church_sc_skills')) {
	function grace_church_sc_skills($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"arc_caption" => esc_html__("Skills", "trx_utils"),
			"pie_compact" => "on",
			"pie_cutout" => 0,
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'trx_utils'),
			"link" => '',
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
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_skills_counter'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_skills_columns'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_skills_height']  = 0;
		$GRACE_CHURCH_GLOBALS['sc_skills_type']    = $type;
		$GRACE_CHURCH_GLOBALS['sc_skills_pie_compact'] = $pie_compact;
		$GRACE_CHURCH_GLOBALS['sc_skills_pie_cutout']  = max(0, min(99, $pie_cutout));
		$GRACE_CHURCH_GLOBALS['sc_skills_color']   = $color;
		$GRACE_CHURCH_GLOBALS['sc_skills_bg_color']= $bg_color;
		$GRACE_CHURCH_GLOBALS['sc_skills_border_color']= $border_color;
		$GRACE_CHURCH_GLOBALS['sc_skills_legend']  = '';
		$GRACE_CHURCH_GLOBALS['sc_skills_data']    = '';
		grace_church_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') $GRACE_CHURCH_GLOBALS['sc_skills_columns'] = $columns;
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			$GRACE_CHURCH_GLOBALS['sc_skills_style'] = $style;
		}
		$GRACE_CHURCH_GLOBALS['sc_skills_max'] = $max_value;
		$GRACE_CHURCH_GLOBALS['sc_skills_dir'] = $dir;
		$GRACE_CHURCH_GLOBALS['sc_skills_height'] = grace_church_prepare_css_value($height);
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"'
					. ' class="sc_skills sc_skills_' . esc_attr($type)
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '')
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr($pie_compact) : '')
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title">' . esc_html($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc'
						? ('<div class="sc_skills_legend">'.($GRACE_CHURCH_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . ($GRACE_CHURCH_GLOBALS['sc_skills_data']) . '</div>'
						  )
						: '')
					. ($type=='pie' && grace_church_param_is_on($pie_compact)
						? ('<div class="sc_skills_legend">'.($GRACE_CHURCH_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie" class="sc_skills_pie_canvas"></canvas>'
                                . '<div class="max_skill_value_pie">' . esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_max']) . '</div>'
								. '<div class="sc_skills_data" style="display:none;">' . ($GRACE_CHURCH_GLOBALS['sc_skills_data']) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_skills', 'grace_church_sc_skills');
}


if (!function_exists('grace_church_sc_skills_item')) {
	function grace_church_sc_skills_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_skills_counter']++;
		$ed = grace_church_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if ($GRACE_CHURCH_GLOBALS['sc_skills_max'] < $value) $GRACE_CHURCH_GLOBALS['sc_skills_max'] = $value;
		$percent = round($value / $GRACE_CHURCH_GLOBALS['sc_skills_max'] * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round($GRACE_CHURCH_GLOBALS['sc_skills_max']/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$title_block = '<div class="sc_skills_info"><div class="sc_skills_label">' . ($title) . '</div></div>';
		$old_color = $color;
		if (empty($color)) $color = $GRACE_CHURCH_GLOBALS['sc_skills_color'];
		if (empty($color)) $color = grace_church_get_scheme_color('accent1', $color);
		if (empty($bg_color)) $bg_color = $GRACE_CHURCH_GLOBALS['sc_skills_bg_color'];
		if (empty($bg_color)) $bg_color = grace_church_get_scheme_color('bg_color', $bg_color);
		if (empty($border_color)) $border_color = $GRACE_CHURCH_GLOBALS['sc_skills_border_color'];
		if (empty($border_color)) $border_color = grace_church_get_scheme_color('bd_color', $border_color);;
		if (empty($style)) $style = $GRACE_CHURCH_GLOBALS['sc_skills_style'];
		$style = max(1, min(4, $style));
		$output = '';
		if ($GRACE_CHURCH_GLOBALS['sc_skills_type'] == 'arc' || ($GRACE_CHURCH_GLOBALS['sc_skills_type'] == 'pie' && grace_church_param_is_on($GRACE_CHURCH_GLOBALS['sc_skills_pie_compact']))) {
			if ($GRACE_CHURCH_GLOBALS['sc_skills_type'] == 'arc' && empty($old_color)) {
				$rgb = grace_church_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*($GRACE_CHURCH_GLOBALS['sc_skills_counter']-1)).')';
			}
			$GRACE_CHURCH_GLOBALS['sc_skills_legend'] .= '<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) . '</span></div>';
			$GRACE_CHURCH_GLOBALS['sc_skills_data'] .= '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_type']).'"'
				. ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='pie'
					? ( ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'"'
						)
					: '')
				. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>';
		} else {
			$output .= ($GRACE_CHURCH_GLOBALS['sc_skills_columns'] > 0 ? '<div class="sc_skills_column column-1_'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_columns']).'">' : '')
					. ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='bar' && $GRACE_CHURCH_GLOBALS['sc_skills_dir']=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($GRACE_CHURCH_GLOBALS['sc_skills_counter'] % 2 == 1 ? ' odd' : ' even')
							. ($GRACE_CHURCH_GLOBALS['sc_skills_counter'] == 1 ? ' first' : '')
							. '"'
						. ($GRACE_CHURCH_GLOBALS['sc_skills_height'] !='' || $css ? ' style="height: '.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_height']).';'.($css).'"' : '')
					. '>'
					. (!empty($icon) ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : '');
//          New version
            if ($GRACE_CHURCH_GLOBALS['sc_skills_type'] == 'bar') {
				$output .= '<div class="sc_skills_count"' . ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='bar' && $color ? ' style="background-color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' : '') . '>'
						.'</div>'
                        . '<div class="sc_skills_total"'
                        . ' data-start="'.esc_attr($start).'"'
                        . ' data-stop="'.esc_attr($stop).'"'
                        . ' data-step="'.esc_attr($step).'"'
                        . ' data-max="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_max']).'"'
                        . ' data-speed="'.esc_attr($speed).'"'
                        . ' data-duration="'.esc_attr($animation).'"'
                        . ' data-ed="'.esc_attr($ed).'">'
                        . ($start) . ($ed)
						. '</div>';
            }
            if ($GRACE_CHURCH_GLOBALS['sc_skills_type'] == 'counter') {
				$output .= '<div class="sc_skills_count"' . ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='counter' && $color ? ' style="color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' : '') . '>'
							. '<div class="sc_skills_total"'
								. ' data-start="'.esc_attr($start).'"'
								. ' data-stop="'.esc_attr($stop).'"'
								. ' data-step="'.esc_attr($step).'"'
								. ' data-max="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_max']).'"'
								. ' data-speed="'.esc_attr($speed).'"'
								. ' data-duration="'.esc_attr($animation).'"'
								. ' data-ed="'.esc_attr($ed).'">'
								. ($start) . ($ed)
							.'</div>'
						. '</div>';
//          Old version
//			if (in_array($GRACE_CHURCH_GLOBALS['sc_skills_type'], array('bar', 'counter'))) {
//				$output .= '<div class="sc_skills_count"' . ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='bar' && $color ? ' style="background-color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' : '') . '>'
//							. '<div class="sc_skills_total"'
//								. ' data-start="'.esc_attr($start).'"'
//								. ' data-stop="'.esc_attr($stop).'"'
//								. ' data-step="'.esc_attr($step).'"'
//								. ' data-max="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_max']).'"'
//								. ' data-speed="'.esc_attr($speed).'"'
//								. ' data-duration="'.esc_attr($animation).'"'
//								. ' data-ed="'.esc_attr($ed).'">'
//								. ($start) . ($ed)
//							.'</div>'
//						. '</div>';
//
			} else if ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'"></canvas>'
					. '<div class="sc_skills_total"'
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			}
			$output .=
					  ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='counter' ? $title_block : '')
					. '</div>'
					. ($GRACE_CHURCH_GLOBALS['sc_skills_type']=='bar' && $GRACE_CHURCH_GLOBALS['sc_skills_dir']=='vertical' || $GRACE_CHURCH_GLOBALS['sc_skills_type'] == 'pie' ? $title_block : '')
					. ($GRACE_CHURCH_GLOBALS['sc_skills_columns'] > 0 ? '</div>' : '');
		}
		return apply_filters('grace_church_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_skills_item', 'grace_church_sc_skills_item');
}
// ---------------------------------- [/trx_skills] ---------------------------------------







// ---------------------------------- [trx_slider] ---------------------------------------

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="id|slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

if (!function_exists('grace_church_sc_slider')) {
	function grace_church_sc_slider($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"engine" => 'swiper',
			"custom" => "no",
			"alias" => "",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "0",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"controls" => "no",
			"pagination" => "yes",
			"slides_space" => 0,
			"slides_per_view" => 1,
			"titles" => "no",
			"descriptions" => grace_church_get_custom_option('slider_info_descriptions'),
			"links" => "no",
			"align" => "",
			"interval" => "",
			"date_format" => "",
			"crop" => "yes",
			"autoheight" => "no",
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

		if (empty($width) && $pagination!='full') $width = "100%";
		if (empty($height) && ($pagination=='full' || $pagination=='over')) $height = 250;
		if (!empty($height) && grace_church_param_is_on($autoheight)) $autoheight = "off";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		if (empty($custom)) $custom = 'no';
		if (empty($controls)) $controls = 'no';
		if (empty($pagination)) $pagination = 'no';
		if (empty($titles)) $titles = 'no';
		if (empty($links)) $links = 'no';
		if (empty($autoheight)) $autoheight = 'no';
		if (empty($crop)) $crop = 'no';

		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_slider_engine'] = $engine;
		$GRACE_CHURCH_GLOBALS['sc_slider_width']  = grace_church_prepare_css_value($width);
		$GRACE_CHURCH_GLOBALS['sc_slider_height'] = grace_church_prepare_css_value($height);
		$GRACE_CHURCH_GLOBALS['sc_slider_links']  = grace_church_param_is_on($links);
		$GRACE_CHURCH_GLOBALS['sc_slider_bg_image'] = grace_church_get_theme_setting('slides_type')=='bg';
		$GRACE_CHURCH_GLOBALS['sc_slider_crop_image'] = $crop;

		if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());

		$ms = grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = grace_church_get_css_position_from_values('', '', '', '', $width);
		$hs = grace_church_get_css_position_from_values('', '', '', '', '', $height);

		$css .= (!in_array($pagination, array('full', 'over')) ? $ms : '') . ($hs) . ($ws);

		if ($engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';

		$output = (in_array($pagination, array('full', 'over'))
					? '<div class="sc_slider_pagination_area sc_slider_pagination_'.esc_attr($pagination)
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
						. (($ms).($hs) ? ' style="'.esc_attr(($ms).($hs)).'"' : '')
						.'>'
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_slider sc_slider_' . esc_attr($engine)
					. ($engine=='swiper' ? ' swiper-slider-container' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (grace_church_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
					. ($hs ? ' sc_slider_height_fixed' : '')
					. (grace_church_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
					. (grace_church_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
					. ($GRACE_CHURCH_GLOBALS['sc_slider_bg_image'] ? ' sc_slider_bg' : ' sc_slider_images')
					. (!in_array($pagination, array('full', 'over')) && $align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. (!in_array($pagination, array('full', 'over')) && !grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($slides_per_view > 1 ? ' data-slides-per_view="' . esc_attr($slides_per_view) . '"' : '')
				. (!empty($width) && grace_church_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && grace_church_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>';

		grace_church_enqueue_slider($engine);

		if ($engine=='revo') {
			if (grace_church_exists_revslider() && !empty($alias))
				$output .= do_shortcode('[rev_slider '.esc_attr($alias).']');
			else
				$output = '';
		} else if ($engine=='swiper') {

			$caption = '';

			$output .= '<div class="slides'
				.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
				.($engine=='swiper' && $GRACE_CHURCH_GLOBALS['sc_slider_bg_image'] ? ' style="'.esc_attr($hs).'"' : '')
				.'>';

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
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
				);

				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}

				$args = grace_church_query_add_sort_order($args, $orderby, $order);
				$args = grace_church_query_add_filters($args, 'thumbs');
				$args = grace_church_query_add_posts_and_cats($args, $ids, $post_type, $cat);

				$query = new WP_Query( $args );

				$post_number = 0;
				$pagination_items = '';
				$show_image 	= 1;
				$show_types 	= 0;
				$show_date 		= 1;
				$show_author 	= 0;
				$show_links 	= 0;
				$show_counters	= 'views';	//comments | rating

				while ( $query->have_posts() ) {
					$query->the_post();
					$post_number++;
					$post_id = get_the_ID();
					$post_type = get_post_type();
					$post_title = get_the_title();
					$post_link = get_permalink();
					$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
					$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					if (grace_church_param_is_on($crop)) {
						$post_attachment = $GRACE_CHURCH_GLOBALS['sc_slider_bg_image']
							? grace_church_get_resized_image_url($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null)
							: grace_church_get_resized_image_tag($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null);
					} else if (!$GRACE_CHURCH_GLOBALS['sc_slider_bg_image']) {
						$post_attachment = '<img src="'.esc_url($post_attachment).'" alt="">';
					}
					$post_accent_color = '';
					$post_category = '';
					$post_category_link = '';

					if (in_array($pagination, array('full', 'over'))) {
						$old_output = $output;
						$output = '';
						if (file_exists(grace_church_get_file_dir('templates/_parts/widgets-posts.php'))) {
							require(grace_church_get_file_dir('templates/_parts/widgets-posts.php'));
						}
						$pagination_items .= $output;
						$output = $old_output;
					}
					$output .= '<div'
						. ' class="'.esc_attr($engine).'-slide"'
						. ' data-style="'.esc_attr(($ws).($hs)).'"'
						. ' style="'
							. ($GRACE_CHURCH_GLOBALS['sc_slider_bg_image'] ? 'background-image:url(' . esc_url($post_attachment) . ');' : '') . ($ws) . ($hs)
							. '"'
						. '>'
						. (grace_church_param_is_on($links) ? '<a href="'.esc_url($post_link).'" title="'.esc_attr($post_title).'">' : '')
						. (!$GRACE_CHURCH_GLOBALS['sc_slider_bg_image'] ? $post_attachment : '')
						;
					$caption = $engine=='swiper' ? '' : $caption;
					if (!grace_church_param_is_off($titles)) {
						$post_hover_bg  = grace_church_get_scheme_color('accent1');
						$post_bg = '';
						if ($post_hover_bg!='' && !grace_church_is_inherit_option($post_hover_bg)) {
							$rgb = grace_church_hex2rgb($post_hover_bg);
							$post_hover_ie = str_replace('#', '', $post_hover_bg);
							$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
						}
						$caption .= '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.esc_attr($post_bg).'"' : '').'>';
						$post_descr = grace_church_get_post_excerpt();
						if (grace_church_get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
							// Get all post's categories
							$post_tax = grace_church_get_taxonomy_categories_by_post_type($post_type);
							if (!empty($post_tax)) {
								$post_terms = grace_church_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$post_tax));
								if (!empty($post_terms[$post_tax])) {
									if (!empty($post_terms[$post_tax]->closest_parent)) {
										$post_category = $post_terms[$post_tax]->closest_parent->name;
										$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									}
									if ($post_category!='') {
										$caption .= '<div class="sc_slider_category"'.(grace_church_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.esc_attr($post_accent_color).'"' : '').'><a href="'.esc_url($post_category_link).'">'.($post_category).'</a></div>';
									}
								}
							}
						}
						$output_reviews = '';
						if (grace_church_get_custom_option('show_reviews')=='yes' && grace_church_get_custom_option('slider_info_reviews')=='yes') {
							$avg_author = grace_church_reviews_marks_to_display(get_post_meta($post_id, 'reviews_avg'.((grace_church_get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
							if ($avg_author > 0) {
								$output_reviews .= '<div class="sc_slider_reviews post_rating reviews_summary blog_reviews' . (grace_church_get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
									. '<div class="criteria_summary criteria_row">' . trim(grace_church_reviews_get_summary_stars($avg_author, false, false, 5)) . '</div>'
									. '</div>';
							}
						}
						if (grace_church_get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
						$caption .= '<h3 class="sc_slider_subtitle"><a href="'.esc_url($post_link).'">'.($post_title).'</a></h3>';
						if (grace_church_get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
						if ($descriptions > 0) {
							$caption .= '<div class="sc_slider_descr">'.trim(grace_church_strshort($post_descr, $descriptions)).'</div>';
						}
						$caption .= '</div>';
					}
					$output .= ($engine=='swiper' ? $caption : '') . (grace_church_param_is_on($links) ? '</a>' : '' ) . '</div>';
				}
				wp_reset_postdata();
			}

			$output .= '</div>';
			if ($engine=='swiper') {
				if (grace_church_param_is_on($controls))
					$output .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				if (grace_church_param_is_on($pagination))
					$output .= '<div class="sc_slider_pagination_wrap"></div>';
			}

		} else
			$output = '';

		if (!empty($output)) {
			$output .= '</div>';
			if (!empty($pagination_items)) {
				$output .= '
					<div class="sc_slider_pagination widget_area"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
						<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
							<div class="sc_scroll_wrapper swiper-wrapper">
								<div class="sc_scroll_slide swiper-slide">
									'.($pagination_items).'
								</div>
							</div>
							<div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
						</div>
					</div>';
				$output .= '</div>';
			}
		}

		return apply_filters('grace_church_shortcode_output', $output, 'trx_slider', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_slider', 'grace_church_sc_slider');
}


if (!function_exists('grace_church_sc_slider_item')) {
	function grace_church_sc_slider_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"src" => "",
			"url" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $GRACE_CHURCH_GLOBALS;
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}

		if ($src && grace_church_param_is_on($GRACE_CHURCH_GLOBALS['sc_slider_crop_image'])) {
			$src = $GRACE_CHURCH_GLOBALS['sc_slider_bg_image']
				? grace_church_get_resized_image_url($src, !empty($GRACE_CHURCH_GLOBALS['sc_slider_width']) && grace_church_strpos($GRACE_CHURCH_GLOBALS['sc_slider_width'], '%')===false ? $GRACE_CHURCH_GLOBALS['sc_slider_width'] : null, !empty($GRACE_CHURCH_GLOBALS['sc_slider_height']) && grace_church_strpos($GRACE_CHURCH_GLOBALS['sc_slider_height'], '%')===false ? $GRACE_CHURCH_GLOBALS['sc_slider_height'] : null)
				: grace_church_get_resized_image_tag($src, !empty($GRACE_CHURCH_GLOBALS['sc_slider_width']) && grace_church_strpos($GRACE_CHURCH_GLOBALS['sc_slider_width'], '%')===false ? $GRACE_CHURCH_GLOBALS['sc_slider_width'] : null, !empty($GRACE_CHURCH_GLOBALS['sc_slider_height']) && grace_church_strpos($GRACE_CHURCH_GLOBALS['sc_slider_height'], '%')===false ? $GRACE_CHURCH_GLOBALS['sc_slider_height'] : null);
		} else if ($src && !$GRACE_CHURCH_GLOBALS['sc_slider_bg_image']) {
			$src = '<img src="'.esc_url($src).'" alt="">';
		}

		$css .= ($GRACE_CHURCH_GLOBALS['sc_slider_bg_image'] ? 'background-image:url(' . esc_url($src) . ');' : '')
				. (!empty($GRACE_CHURCH_GLOBALS['sc_slider_width'])  ? 'width:'  . esc_attr($GRACE_CHURCH_GLOBALS['sc_slider_width'])  . ';' : '')
				. (!empty($GRACE_CHURCH_GLOBALS['sc_slider_height']) ? 'height:' . esc_attr($GRACE_CHURCH_GLOBALS['sc_slider_height']) . ';' : '');

		$content = do_shortcode($content);

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '').' class="'.esc_attr($GRACE_CHURCH_GLOBALS['sc_slider_engine']).'-slide' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				.'>'
				. ($src && grace_church_param_is_on($GRACE_CHURCH_GLOBALS['sc_slider_links']) ? '<a href="'.esc_url($src).'">' : '')
				. ($src && !$GRACE_CHURCH_GLOBALS['sc_slider_bg_image'] ? $src : $content)
				. ($src && grace_church_param_is_on($GRACE_CHURCH_GLOBALS['sc_slider_links']) ? '</a>' : '')
			. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_slider_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_slider_item', 'grace_church_sc_slider_item');
}
// ---------------------------------- [/trx_slider] ---------------------------------------





// ---------------------------------- [trx_socials] ---------------------------------------

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('grace_church_sc_socials')) {
	function grace_church_sc_socials($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => grace_church_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_social_icons'] = false;
		$GRACE_CHURCH_GLOBALS['sc_social_type'] = $type;
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? grace_church_get_socials_url($s[0]) : 'icon-'.$s[0],
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) $GRACE_CHURCH_GLOBALS['sc_social_icons'] = $list;
		} else if (grace_church_param_is_off($custom))
			$content = do_shortcode($content);
		if ($GRACE_CHURCH_GLOBALS['sc_social_icons']===false) $GRACE_CHURCH_GLOBALS['sc_social_icons'] = grace_church_get_custom_option('social_icons');
		$output = grace_church_prepare_socials($GRACE_CHURCH_GLOBALS['sc_social_icons']);
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_socials', 'grace_church_sc_socials');
}


if (!function_exists('grace_church_sc_social_item')) {
	function grace_church_sc_social_item($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		global $GRACE_CHURCH_GLOBALS;
		if (!empty($name) && empty($icon)) {
			$type = $GRACE_CHURCH_GLOBALS['sc_social_type'];
			if ($type=='images') {
				if (file_exists(grace_church_get_socials_dir($name.'.png')))
					$icon = grace_church_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if ($GRACE_CHURCH_GLOBALS['sc_social_icons']===false) $GRACE_CHURCH_GLOBALS['sc_social_icons'] = array();
			$GRACE_CHURCH_GLOBALS['sc_social_icons'][] = array(
				'icon' => $icon,
				'url' => $url
			);
		}
		return '';
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_social_item', 'grace_church_sc_social_item');
}
// ---------------------------------- [/trx_socials] ---------------------------------------





// ---------------------------------- [trx_table] ---------------------------------------

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/

if (!function_exists('grace_church_sc_table')) {
	function grace_church_sc_table($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%"
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width);
		$content = str_replace(
					array('<p><table', 'table></p>', '><br />'),
					array('<table', 'table>', '>'),
					html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_table' 
					. (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				.'>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_table', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_table', 'grace_church_sc_table');
}
// ---------------------------------- [/trx_table] ---------------------------------------






// ---------------------------------- [trx_tabs] ---------------------------------------

/*
[trx_tabs id="unique_id" tab_names="Planning|Development|Support" style="1|2" initial="1 - num_tabs"]
	[trx_tab]Randomised words which don't look even slightly believable. If you are going to use a passage. You need to be sure there isn't anything embarrassing hidden in the middle of text established fact that a reader will be istracted by the readable content of a page when looking at its layout.[/trx_tab]
	[trx_tab]Fact reader will be distracted by the <a href="#" class="main_link">readable content</a> of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have evolved over. There are many variations of passages of Lorem Ipsum available, but the majority.[/trx_tab]
	[trx_tab]Distracted by the  readable content  of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have  evolved over.  There are many variations of passages of Lorem Ipsum available.[/trx_tab]
[/trx_tabs]
*/

if (!function_exists('grace_church_sc_tabs')) {
	function grace_church_sc_tabs($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"scroll" => "no",
			"style" => "1",
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
	
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width);
	
		if (!grace_church_param_is_off($scroll)) grace_church_enqueue_slider();
		if (empty($id)) $id = 'sc_tabs_'.str_replace('.', '', mt_rand());
	
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_tab_counter'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_tab_scroll'] = $scroll;
		$GRACE_CHURCH_GLOBALS['sc_tab_height'] = grace_church_prepare_css_value($height);
		$GRACE_CHURCH_GLOBALS['sc_tab_id']     = $id;
		$GRACE_CHURCH_GLOBALS['sc_tab_titles'] = array();
	
		$content = do_shortcode($content);
	
		$sc_tab_titles = $GRACE_CHURCH_GLOBALS['sc_tab_titles'];
	
		$initial = max(1, min(count($sc_tab_titles), (int) $initial));
	
		$tabs_output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
							. ' class="sc_tabs sc_tabs_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
							. ' data-active="' . ($initial-1) . '"'
							. '>'
						.'<ul class="sc_tabs_titles">';
		$titles_output = '';
		for ($i = 0; $i < count($sc_tab_titles); $i++) {
			$classes = array('sc_tabs_title');
			if ($i == 0) $classes[] = 'first';
			else if ($i == count($sc_tab_titles) - 1) $classes[] = 'last';
			$titles_output .= '<li class="'.join(' ', $classes).'">'
								. '<a href="#'.esc_attr($sc_tab_titles[$i]['id']).'" class="theme_button" id="'.esc_attr($sc_tab_titles[$i]['id']).'_tab">' . ($sc_tab_titles[$i]['title']) . '</a>'
								. '</li>';
		}
	
		wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
		wp_enqueue_script('jquery-effects-fade', false, array('jquery','jquery-effects-core'), null, true);
	
		$tabs_output .= $titles_output
			. '</ul>' 
			. ($content)
			.'</div>';
		return apply_filters('grace_church_shortcode_output', $tabs_output, 'trx_tabs', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_tabs", "grace_church_sc_tabs");
}


if (!function_exists('grace_church_sc_tab')) {
	function grace_church_sc_tab($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"tab_id" => "",		// get it from VC
			"title" => "",		// get it from VC
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_tab_counter']++;
		if (empty($id))
			$id = !empty($tab_id) ? $tab_id : ($GRACE_CHURCH_GLOBALS['sc_tab_id']).'_'.($GRACE_CHURCH_GLOBALS['sc_tab_counter']);
		$sc_tab_titles = $GRACE_CHURCH_GLOBALS['sc_tab_titles'];
		if (isset($sc_tab_titles[$GRACE_CHURCH_GLOBALS['sc_tab_counter']-1])) {
			$sc_tab_titles[$GRACE_CHURCH_GLOBALS['sc_tab_counter']-1]['id'] = $id;
			if (!empty($title))
				$sc_tab_titles[$GRACE_CHURCH_GLOBALS['sc_tab_counter']-1]['title'] = $title;
		} else {
			$sc_tab_titles[] = array(
				'id' => $id,
				'title' => $title
			);
		}
		$GRACE_CHURCH_GLOBALS['sc_tab_titles'] = $sc_tab_titles;
		$output = '<div id="'.esc_attr($id).'"'
					.' class="sc_tabs_content' 
						. ($GRACE_CHURCH_GLOBALS['sc_tab_counter'] % 2 == 1 ? ' odd' : ' even')
						. ($GRACE_CHURCH_GLOBALS['sc_tab_counter'] == 1 ? ' first' : '')
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. '>' 
				. (grace_church_param_is_on($GRACE_CHURCH_GLOBALS['sc_tab_scroll'])
					? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical" style="height:'.($GRACE_CHURCH_GLOBALS['sc_tab_height'] != '' ? $GRACE_CHURCH_GLOBALS['sc_tab_height'] : '200px').';"><div class="sc_scroll_wrapper swiper-wrapper"><div class="sc_scroll_slide swiper-slide">'
					: '')
				. do_shortcode($content) 
				. (grace_church_param_is_on($GRACE_CHURCH_GLOBALS['sc_tab_scroll'])
					? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical '.esc_attr($id).'_scroll_bar"></div></div>' 
					: '')
			. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_tab', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_tab", "grace_church_sc_tab");
}
// ---------------------------------- [/trx_tabs] ---------------------------------------





// ---------------------------------- [trx_title] ---------------------------------------

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('grace_church_sc_title')) {
	function grace_church_sc_title($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left, $width)
			.($align && $align!='none' && !grace_church_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !grace_church_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(grace_church_strpos($image, 'http:')!==false ? $image : grace_church_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !grace_church_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_title', 'grace_church_sc_title');
}
// ---------------------------------- [/trx_title] ---------------------------------------






// ---------------------------------- [trx_toggles] ---------------------------------------

if (!function_exists('grace_church_sc_toggles')) {
	function grace_church_sc_toggles($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_toggle_counter'] = 0;
		$GRACE_CHURCH_GLOBALS['sc_toggle_style']   = max(1, min(2, $style));
		$GRACE_CHURCH_GLOBALS['sc_toggle_show_counter'] = grace_church_param_is_on($counter);
		$GRACE_CHURCH_GLOBALS['sc_toggles_icon_closed'] = empty($icon_closed) || grace_church_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed;
		$GRACE_CHURCH_GLOBALS['sc_toggles_icon_opened'] = empty($icon_opened) || grace_church_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened;
		wp_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles sc_toggles_style_'.esc_attr($style)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (grace_church_param_is_on($counter) ? ' sc_show_counter' : '')
					. '"'
				. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_toggles', 'grace_church_sc_toggles');
}


if (!function_exists('grace_church_sc_toggles_item')) {
	function grace_church_sc_toggles_item($atts, $content=null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $GRACE_CHURCH_GLOBALS;
		$GRACE_CHURCH_GLOBALS['sc_toggle_counter']++;
		if (empty($icon_closed) || grace_church_param_is_inherit($icon_closed)) $icon_closed = $GRACE_CHURCH_GLOBALS['sc_toggles_icon_closed'] ? $GRACE_CHURCH_GLOBALS['sc_toggles_icon_closed'] : "icon-plus";
		if (empty($icon_opened) || grace_church_param_is_inherit($icon_opened)) $icon_opened = $GRACE_CHURCH_GLOBALS['sc_toggles_icon_opened'] ? $GRACE_CHURCH_GLOBALS['sc_toggles_icon_opened'] : "icon-minus";
		$css .= grace_church_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(grace_church_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($GRACE_CHURCH_GLOBALS['sc_toggle_counter'] % 2 == 1 ? ' odd' : ' even')
					. ($GRACE_CHURCH_GLOBALS['sc_toggle_counter'] == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(grace_church_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (!grace_church_param_is_off($icon_closed) ? '<span class="sc_toggles_icon sc_toggles_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
					. (!grace_church_param_is_off($icon_opened) ? '<span class="sc_toggles_icon sc_toggles_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
					. ($GRACE_CHURCH_GLOBALS['sc_toggle_show_counter'] ? '<span class="sc_items_counter">'.($GRACE_CHURCH_GLOBALS['sc_toggle_counter']).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_toggles_item', 'grace_church_sc_toggles_item');
}
// ---------------------------------- [/trx_toggles] ---------------------------------------





// ---------------------------------- [trx_tooltip] ---------------------------------------

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('grace_church_sc_tooltip')) {
	function grace_church_sc_tooltip($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('grace_church_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_tooltip', 'grace_church_sc_tooltip');
}
// ---------------------------------- [/trx_tooltip] ---------------------------------------






// ---------------------------------- [trx_twitter] ---------------------------------------

/*
[trx_twitter id="unique_id" user="username" consumer_key="" consumer_secret="" token_key="" token_secret=""]
*/

if (!function_exists('grace_church_sc_twitter')) {
	function grace_church_sc_twitter($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"user" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			"count" => "3",
			"controls" => "yes",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
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
	
		$twitter_username = $user ? $user : grace_church_get_theme_option('twitter_username');
		$twitter_consumer_key = $consumer_key ? $consumer_key : grace_church_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = $consumer_secret ? $consumer_secret : grace_church_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = $token_key ? $token_key : grace_church_get_theme_option('twitter_token_key');
		$twitter_token_secret = $token_secret ? $token_secret : grace_church_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, $count ? $count : intval(grace_church_get_theme_option('twitter_count')));
	
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
	
		$output = '';
	
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
			$data = grace_church_get_twitter_data(array(
				'mode'            => 'user_timeline',
				'consumer_key'    => $twitter_consumer_key,
				'consumer_secret' => $twitter_consumer_secret,
				'token'           => $twitter_token_key,
				'secret'          => $twitter_token_secret
				)
			);
			if ($data && isset($data[0]['text'])) {
				grace_church_enqueue_slider('swiper');
				$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || grace_church_strlen($bg_texture)>2 || ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme))
						? '<div class="sc_twitter_wrap sc_section'
								. ($scheme && !grace_church_param_is_off($scheme) && !grace_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
								. ($align && $align!='none' && !grace_church_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							.' style="'
								. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
								. ($bg_image !== '' ? 'background-image:url('.esc_url($bg_image).');' : '')
								. '"'
							. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
							. '>'
							. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
									. ' style="' 
										. ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
										. (grace_church_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
										. '"'
										. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
										. '>' 
						: '')
						. '<div class="sc_twitter sc_slider_swiper sc_slider_nopagination swiper-slider-container'
								. (grace_church_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
								. (grace_church_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
								. ($hs ? ' sc_slider_height_fixed' : '')
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && $align && $align!='none' && !grace_church_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
							. (!empty($width) && grace_church_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
							. (!empty($height) && grace_church_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
							. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
							. '>'
							. '<div class="slides swiper-wrapper">';
				$cnt = 0;
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $tweet) {
						if (grace_church_substr($tweet['text'], 0, 1)=='@') continue;
							$output .= '<div class="swiper-slide" data-style="'.esc_attr(($ws).($hs)).'" style="'.esc_attr(($ws).($hs)).'">'
										. '<div class="sc_twitter_item">'
											. '<span class="sc_twitter_icon icon-twitter"></span>'
											. '<div class="sc_twitter_content">'
												. '<a href="' . esc_url('https://twitter.com/'.($twitter_username)).'" class="sc_twitter_author" target="_blank">@' . esc_html($tweet['user']['screen_name']) . '</a> '
												. force_balance_tags(grace_church_prepare_twitter_text($tweet))
											. '</div>'
										. '</div>'
									. '</div>';
						if (++$cnt >= $twitter_count) break;
					}
				}
				$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
					. '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || grace_church_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
			}
		}
		return apply_filters('grace_church_shortcode_output', $output, 'trx_twitter', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_twitter', 'grace_church_sc_twitter');
}
// ---------------------------------- [/trx_twitter] ---------------------------------------

						


// ---------------------------------- [trx_video] ---------------------------------------

//[trx_video id="unique_id" url="http://player.vimeo.com/video/20245032?title=0&amp;byline=0&amp;portrait=0" width="" height=""]

if (!function_exists('grace_church_sc_video')) {
	function grace_church_sc_video($atts, $content = null) {
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"url" => '',
			"src" => '',
			"image" => '',
			"ratio" => '16:9',
			"autoplay" => 'off',
			"align" => '',
			"bg_image" => '',
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($autoplay)) $autoplay = 'off';
		
		$ratio = empty($ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $ratio);
		$ratio_parts = explode(':', $ratio);
		if (empty($height) && empty($width)) {
			$width='100%';
			if (grace_church_param_is_off(grace_church_get_custom_option('substitute_video'))) $height="400";
		}
		$ed = grace_church_substr($width, -1);
		if (empty($height) && !empty($width) && $ed!='%') {
			$height = round($width / $ratio_parts[0] * $ratio_parts[1]);
		}
		if (!empty($height) && empty($width)) {
			$width = round($height * $ratio_parts[0] / $ratio_parts[1]);
		}
		$css .= grace_church_get_css_position_from_values($top, $right, $bottom, $left);
		$css_dim = grace_church_get_css_position_from_values('', '', '', '', $width, $height);
		$css_bg = grace_church_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
	
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		$url = $src!='' ? $src : $url;
		if ($image!='' && grace_church_param_is_off($image))
			$image = '';
		else {
			if (grace_church_param_is_on($autoplay) && is_singular() && !grace_church_get_global('blog_streampage'))
				$image = '';
			else {
				if ($image > 0) {
					$attach = wp_get_attachment_image_src( $image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$image = $attach[0];
				}
				if ($bg_image) {
					$thumb_sizes = grace_church_get_thumb_sizes(array(
						'layout' => 'grid_3'
					));
					if (!is_single() || !empty($image)) $image = grace_church_get_resized_image_url(empty($image) ? get_the_ID() : $image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, false);
				} else
					if (!is_single() || !empty($image)) $image = grace_church_get_resized_image_url(empty($image) ? get_the_ID() : $image, $ed!='%' ? $width : null, $height);
				if (empty($image) && (!is_singular() || grace_church_get_global('blog_streampage')))	// || grace_church_param_is_off($autoplay)))
					$image = grace_church_get_video_cover_image($url);
			}
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
	
		$url = grace_church_get_video_player_url($src!='' ? $src : $url);
		
		$video = '<video' . ($id ? ' id="' . esc_attr($id) . '"' : '') 
			. ' class="sc_video'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ' src="' . esc_url($url) . '"'
			. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
			. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
			. ' data-ratio="'.esc_attr($ratio).'"'
			. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
			. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
			. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
			. ($bg_image ? ' data-bg-image="'.esc_attr($bg_image).'"' : '') 
			. ($css_bg!='' ? ' data-style="'.esc_attr($css_bg).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (($image && grace_church_param_is_on(grace_church_get_custom_option('substitute_video'))) || (grace_church_param_is_on($autoplay) && is_singular() && !grace_church_get_global('blog_streampage')) ? ' autoplay="autoplay"' : '')
			. ' controls="controls" loop="loop"'
			. '>'
			. '</video>';
		if (grace_church_param_is_off(grace_church_get_custom_option('substitute_video'))) {
			if (grace_church_param_is_on($frame)) $video = grace_church_get_video_frame($video, $image, $css, $css_bg);
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$video = grace_church_substitute_video($video, $width, $height, false);
			}
		}
		if (grace_church_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('grace_church_shortcode_output', $video, 'trx_video', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode("trx_video", "grace_church_sc_video");
}
// ---------------------------------- [/trx_video] ---------------------------------------






// ---------------------------------- [trx_zoom] ---------------------------------------

/*
[trx_zoom id="unique_id" border="none|light|dark"]
*/

if (!function_exists('grace_church_sc_zoom')) {
	function grace_church_sc_zoom($atts, $content=null){
		if (grace_church_in_shortcode_blogger()) return '';
		extract(grace_church_html_decode(shortcode_atts(array(
			// Individual params
			"effect" => "zoom",
			"src" => "",
			"url" => "",
			"over" => "",
			"align" => "",
			"bg_image" => "",
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
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
	
		wp_enqueue_script( 'grace-church-elevate-zoom-script', grace_church_get_file_url('js/jquery.elevateZoom.js'), array(), null, true );
	
		$css .= grace_church_get_css_position_from_values('!'.($top), '!'.($right), '!'.($bottom), '!'.($left));
		$css_dim = grace_church_get_css_position_from_values('', '', '', '', $width, $height);
		$css_bg = grace_church_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
		$width  = grace_church_prepare_css_value($width);
		$height = grace_church_prepare_css_value($height);
		if (empty($id)) $id = 'sc_zoom_'.str_replace('.', '', mt_rand());
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if ($over > 0) {
			$attach = wp_get_attachment_image_src( $over, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$over = $attach[0];
		}
		if ($effect=='lens' && ((int) $width > 0 && grace_church_substr($width, -2, 2)=='px') || ((int) $height > 0 && grace_church_substr($height, -2, 2)=='px')) {
			if ($src)
				$src = grace_church_get_resized_image_url($src, (int) $width > 0 && grace_church_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && grace_church_substr($height, -2, 2)=='px' ? (int) $height : null);
			if ($over)
				$over = grace_church_get_resized_image_url($over, (int) $width > 0 && grace_church_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && grace_church_substr($height, -2, 2)=='px' ? (int) $height : null);
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
		$output = empty($src) 
				? '' 
				: (
					(!empty($bg_image) 
						? '<div class="sc_zoom_wrap'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
								. '"'
							. (!grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
							. ($css_bg!='' ? ' style="'.esc_attr($css_bg).'"' : '') 
							. '>' 
						: '')
					.'<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_zoom' 
								. (empty($bg_image) && !empty($class) ? ' '.esc_attr($class) : '') 
								. (empty($bg_image) && $align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (empty($bg_image) && !grace_church_param_is_off($animation) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. '>'
							. '<img src="'.esc_url($src).'"' . ($css_dim!='' ? ' style="'.esc_attr($css_dim).'"' : '') . ' data-zoom-image="'.esc_url($over).'" alt="" />'
					. '</div>'
					. (!empty($bg_image) 
						? '</div>' 
						: '')
				);
		return apply_filters('grace_church_shortcode_output', $output, 'trx_zoom', $atts, $content);
	}
	if (function_exists('grace_church_require_shortcode')) grace_church_require_shortcode('trx_zoom', 'grace_church_sc_zoom');
}
// ---------------------------------- [/trx_zoom] ---------------------------------------

// Check if we are in the shortcode "trx_blogger" now
if (!function_exists('grace_church_in_shortcode_blogger')) {
	function grace_church_in_shortcode_blogger($from_blogger = false) {
		if (!$from_blogger) return false;
		global $GRACE_CHURCH_GLOBALS;
		return !empty($GRACE_CHURCH_GLOBALS['sc_blogger_busy']);
	}
}

?>