<?php
/*
Template Name: Blog streampage
*/

global $GRACE_CHURCH_GLOBALS;
$GRACE_CHURCH_GLOBALS['blog_streampage'] = true;

get_header(); 

global $wp_query, $post;

if (empty($blog_style))	$blog_style = grace_church_get_custom_option('blog_style');
$blog_columns	= max(1, (int) grace_church_substr($blog_style, -1));
$show_sidebar 	= grace_church_get_custom_option('show_sidebar_main');
$show_filters 	= grace_church_get_custom_option('show_filters');
$ppp			= (int) grace_church_get_custom_option('posts_per_page');
$hover			= grace_church_get_custom_option('hover_style');
if (empty($hover)) $hover = 'square effect_shift';
$hover_dir		= grace_church_get_custom_option('hover_dir');
if (empty($hover_dir)) $hover_dir = 'left_to_right';

$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);

$wp_query_need_restore = false;

$args = $wp_query->query_vars;
$args['post_status'] = current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish';

if ( is_page() || !empty($GRACE_CHURCH_GLOBALS['blog_filters']) ) {
	unset($args['p']);
	unset($args['page_id']);
	unset($args['pagename']);
	unset($args['name']);
	$args['posts_per_page'] = $ppp;
	if ($page_number > 1) {
		$args['paged'] = $page_number;
		$args['ignore_sticky_posts'] = true;
	}
	$args = grace_church_query_add_sort_order($args);
	$args = grace_church_query_add_filters($args, !empty($GRACE_CHURCH_GLOBALS['blog_filters']) ? $GRACE_CHURCH_GLOBALS['blog_filters'] : '');
	query_posts( $args );
	$wp_query_need_restore = true;
}

$per_page = count($wp_query->posts);
$post_number = 0;
$parent_tax_id = (int) grace_church_get_custom_option('taxonomy_id');
$flt_ids = array();

$container = apply_filters('grace_church_filter_blog_container', grace_church_get_template_property($blog_style, 'container'), array('style'=>$blog_style, 'dir'=>'horizontal'));
$container_start = $container_end = '';
if (!empty($container)) {
	$container = explode('%s', $container);
	$container_start = !empty($container[0]) ? $container[0] : '';
	$container_end = !empty($container[1]) ? $container[1] : '';
}

grace_church_show_layout( $container_start);

if (grace_church_get_template_property($blog_style, 'need_columns') && $blog_columns > 1) {
	?>
	<div class="columns_wrap <?php echo esc_attr(grace_church_get_template_property($blog_style, 'container_classes')); ?>">
	<?php
}

if (grace_church_get_template_property($blog_style, 'need_isotope')) {
	if (!grace_church_param_is_off($show_filters)) {
		?>
		<div class="isotope_filters"></div>
		<?php
	}
	?>
	<div class="isotope_wrap <?php echo esc_attr(grace_church_get_template_property($blog_style, 'container_classes')); ?>" data-columns="<?php echo esc_attr($blog_columns); ?>">
	<?php
}


while ( have_posts() ) { the_post();
	$post_number++;
	$post_args = array(
		'layout' => $blog_style,
		'number' => $post_number,
		'add_view_more' => false,
		'posts_on_page' => $per_page,
		'columns_count' => $blog_columns,
		// Get post data
		'strip_teaser' => false,
		'content' => grace_church_get_template_property($blog_style, 'need_content'),
		'terms_list' => !grace_church_param_is_off($show_filters) || grace_church_get_template_property($blog_style, 'need_terms'),
		'parent_tax_id' => $parent_tax_id,
		'descr' => grace_church_get_custom_option('post_excerpt_maxlength'.($blog_columns > 1 ? '_masonry' : '')),
		'sidebar' => !grace_church_param_is_off($show_sidebar),
		'filters' => $show_filters != 'hide' ? $show_filters : '',
		'hover' => $hover,
		'hover_dir' => $hover_dir
	);

	$post_data = grace_church_get_post_data($post_args);

	grace_church_show_post_layout($post_args, $post_data);

	if ($show_filters=='tags') {					// Use tags as filter items
		if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
			foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
				$flt_ids[$tag->term_id] = $tag->name;
			}
		}
	}
}


if (grace_church_get_template_property($blog_style, 'need_isotope')) {
	?>
	</div> <!-- /.isotope_wrap -->
	<?php 
}

if (grace_church_get_template_property($blog_style, 'need_columns') && $blog_columns > 1) {
	?>
	</div> <!-- /.columns_wrap -->
	<?php
}

grace_church_show_layout( $container_end);

if (!$post_number) { 
	if ( is_search() ) {
		grace_church_show_post_layout( array('layout' => 'no-search'), false );
	} else {
		grace_church_show_post_layout( array('layout' => 'no-articles'), false );
	}
} else {
	// Isotope filters list
	$filters = '';
	$filter_button_classes = 'isotope_filters_button';
	if ($show_filters == 'categories') {            // Use categories as filter items
		$taxonomy = grace_church_is_taxonomy();
		$cur_term = $taxonomy ? grace_church_get_current_term($taxonomy) : 0;
		$cur_term_id = $cur_term ? $cur_term->term_id : 0;
		$portfolio_parent = $cur_term_id ? max(0, grace_church_get_parent_taxonomy_by_property($cur_term_id, 'show_filters', 'yes', true, $taxonomy)) : 0;
		$args2 = array(
			'type' => !empty($args['post_type']) ? $args['post_type'] : 'post',
			'child_of' => $portfolio_parent,
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => 1,
			'hierarchical' => 0,
			'exclude' => '',
			'include' => '',
			'number' => '',
			'taxonomy' => $taxonomy,
			'pad_counts' => false
		);
		$portfolio_list = get_categories($args2);
		if (is_array($portfolio_list) && count($portfolio_list) > 0) {
			$filters .= '<a href="#" data-filter="*" class="' . esc_attr($filter_button_classes . ($portfolio_parent == $cur_term_id ? ' active' : '')) . '">' . esc_html__('All', 'grace-church') . '</a>';
			foreach ($portfolio_list as $cat) {
				if (isset($cat->term_id)) $filters .= '<a href="#" data-filter=".flt_' . esc_attr($cat->term_id) . '" class="' . esc_attr($filter_button_classes . ($cat->term_id == $cur_term_id ? ' active' : '')) . '">' . ($cat->name) . '</a>';
			}
		}
	} else if ($show_filters == 'tags') {                                                                    // Use tags as filter items
		if (is_array($flt_ids) && count($flt_ids) > 0) {
			$filters .= '<a href="#" data-filter="*" class="' . esc_attr($filter_button_classes) . ' active">' . esc_html__('All', 'grace-church') . '</a>';
			foreach ($flt_ids as $flt_id => $flt_name) {
				$filters .= '<a href="#" data-filter=".flt_' . esc_attr($flt_id) . '" class="' . esc_attr($filter_button_classes) . '">' . ($flt_name) . '</a>';
			}
		}
	}
	if ($filters) {
        grace_church_set_global_array('js_vars', 'ppp', intval($ppp));
        //The following script is dynamically generated.
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery(".isotope_filters").append('<?php grace_church_show_layout(wp_kses_post($filters)); ?>');
            });
        </script>
        <?php
	}
}

if ($post_number > 0) {
	// Pagination
	$pagination = grace_church_get_custom_option('blog_pagination');
	if (in_array($pagination, array('viewmore', 'infinite'))) {
		if ($page_number < $wp_query->max_num_pages) {
			?>
			<div id="viewmore" class="pagination_wrap pagination_<?php echo esc_attr($pagination); ?>">
				<a href="#" id="viewmore_link" class="theme_button viewmore_button"><span class="icon-spin3 animate-spin viewmore_loading"></span><span class="viewmore_text_1"><?php esc_html_e('LOAD MORE', 'grace-church'); ?></span><span class="viewmore_text_2"><?php esc_html_e('Loading ...', 'grace-church'); ?></span></a>
				<span class="viewmore_loader"></span>
                <?php
                grace_church_set_global_array('js_vars', 'viewmore_page', intval($page_number));
                grace_church_set_global_array('js_vars', 'viewmore_data', serialize($args));
                grace_church_set_global_array('js_vars', 'viewmore_vars', serialize(array(
                    'blog_style' => $blog_style,
                    'columns_count' => $blog_columns,
                    'parent_tax_id' => $parent_tax_id,
                    'show_sidebar' => $show_sidebar,
                    'filters' => $show_filters!='hide' ? $show_filters : '',
                    'hover' => $hover,
                    'hover_dir' => $hover_dir,
                    'ppp' => $ppp
                )));
                ?>
			</div>
			<?php
		}
	} else {
		grace_church_show_pagination(array(
			'class' => 'pagination_wrap pagination_'.esc_attr(grace_church_get_theme_option('blog_pagination_style')),
			'style' => grace_church_get_theme_option('blog_pagination_style'),
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => grace_church_get_theme_option('blog_pagination_style')=='pages' ? 10 : 20
			)
		);
	}
}

// Add template specific scripts and styles
do_action('grace_church_action_blog_scripts', $blog_style);

// Restore main WP query
wp_reset_postdata();

get_footer();
?>