<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_date_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_date_theme_setup', 1 );
	function grace_church_template_date_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'date',
			'mode'   => 'blogger',
			'title'  => esc_html__('Blogger layout: Timeline', 'grace-church')
			));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_date_output' ) ) {
	function grace_church_template_date_output($post_options, $post_data) {
		if (grace_church_param_is_on($post_options['scroll'])) grace_church_enqueue_slider();
        if (function_exists('grace_church_reviews_theme_setup')) {
            require(grace_church_get_file_dir('templates/_parts/reviews-summary.php'));
        }
		?>
		
		<div class="post_item sc_blogger_item
			<?php grace_church_show_layout( $post_options['number'] == $post_options['posts_on_page'] && !grace_church_param_is_on($post_options['loadmore']) ? ' sc_blogger_item_last' : ''); ?>"
			<?php grace_church_show_layout( $post_options['dir'] == 'horizontal' ? ' style="width:'.(100/$post_options['posts_on_page']).'%"' : ''); ?>>
			<div class="sc_blogger_date">
				<span class="day_month"><?php grace_church_show_layout( $post_data['post_date_part1']); ?></span>
				<span class="year"><?php grace_church_show_layout( $post_data['post_date_part2']); ?></span>
			</div>

			<div class="post_content">
				<h6 class="post_title sc_title sc_blogger_title">
					<?php echo (!isset($post_options['links']) || $post_options['links'] ? '<a href="' . esc_url($post_data['post_link']) . '">' : ''); ?>
					<?php grace_church_show_layout( $post_data['post_title']); ?>
					<?php echo (!isset($post_options['links']) || $post_options['links'] ? '</a>' : ''); ?>
				</h6>
				
				<?php grace_church_show_layout( $reviews_summary); ?>
	
				<?php if (grace_church_param_is_on($post_options['info'])) { ?>
				<div class="post_info">
					<span class="post_info_item post_info_posted_by"><?php esc_html_e('by', 'grace-church'); ?> <a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_info_author"><?php echo esc_html($post_data['post_author']); ?></a></span>
					<span class="post_info_item post_info_counters">
						<?php grace_church_show_layout( $post_options['orderby']=='comments' || $post_options['counters']=='comments' ? esc_html__('Comments', 'grace-church') : esc_html__('Views', 'grace-church')); ?>
						<span class="post_info_counters_number"><?php grace_church_show_layout( $post_options['orderby']=='comments' || $post_options['counters']=='comments' ? $post_data['post_comments'] : $post_data['post_views']); ?></span>
					</span>
				</div>
				<?php } ?>

			</div>	<!-- /.post_content -->
		
		</div>		<!-- /.post_item -->

		<?php
		if ($post_options['number'] == $post_options['posts_on_page'] && grace_church_param_is_on($post_options['loadmore'])) {
		?>
			<div class="load_more"<?php grace_church_show_layout( $post_options['dir'] == 'horizontal' ? ' style="width:'.(100/$post_options['posts_on_page']).'%"' : ''); ?>></div>
		<?php
		}
	}
}
?>