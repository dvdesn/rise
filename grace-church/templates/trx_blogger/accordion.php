<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_accordion_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_accordion_theme_setup', 1 );
	function grace_church_template_accordion_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'accordion-1',
			'template' => 'accordion',
			'container_classes' => 'sc_accordion sc_accordion_style_1',
			'mode'   => 'blogger',
			'title'  => esc_html__('Blogger layout: Accordion (Style 1)', 'grace-church')
			));
		grace_church_add_template(array(
			'layout' => 'accordion-2',
			'template' => 'accordion',
			'container_classes' => 'sc_accordion sc_accordion_style_2',
			'mode'   => 'blogger',
			'title'  => esc_html__('Blogger layout: Accordion (Style 2)', 'grace-church')
			));
		// Add template specific scripts
		add_action('grace_church_action_blog_scripts', 'grace_church_template_accordion_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('grace_church_template_accordion_add_scripts')) {
	//Handler of add_action('grace_church_action_blog_scripts', 'grace_church_template_accordion_add_scripts');
	function grace_church_template_accordion_add_scripts($style) {
		if (grace_church_substr($style, 0, 10) == 'accordion-') {
			wp_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		}
	}
}

// Template output
if ( !function_exists( 'grace_church_template_accordion_output' ) ) {
	function grace_church_template_accordion_output($post_options, $post_data) {
		?>
		<div class="post_item sc_blogger_item sc_accordion_item<?php grace_church_show_layout( $post_options['number'] == $post_options['posts_on_page'] && !grace_church_param_is_on($post_options['loadmore']) ? ' sc_blogger_item_last' : ''); ?>">
			
			<h5 class="post_title sc_title sc_blogger_title sc_accordion_title"><span class="sc_accordion_icon sc_accordion_icon_closed icon-plus"></span><span class="sc_accordion_icon sc_accordion_icon_opened icon-minus"></span><?php grace_church_show_layout( $post_data['post_title']); ?></h5>
			
			<div class="post_content sc_accordion_content">
				<?php
				if (grace_church_param_is_on($post_options['info'])) {
					?>
					<div class="post_info">
						<span class="post_info_item post_info_posted_by"><?php esc_html_e('Posted by', 'grace-church'); ?> <a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_info_author"><?php echo esc_html($post_data['post_author']); ?></a></span>
						<span class="post_info_item post_info_counters">
							<?php grace_church_show_layout( $post_options['orderby']=='comments' || $post_options['counters']=='comments' ? esc_html__('Comments', 'grace-church') : esc_html__('Views', 'grace-church')); ?>
							<span class="post_info_counters_number"><?php grace_church_show_layout( $post_options['orderby']=='comments' || $post_options['counters']=='comments' ? $post_data['post_comments'] : $post_data['post_views']); ?></span>
						</span>
					</div>
					<?php
				}
				if ($post_options['descr'] >= 0) {
					?>
					<div class="post_descr">
					<?php
					if (!in_array($post_data['post_format'], array('quote', 'link', 'chat')) && $post_options['descr'] > 0 && grace_church_strlen($post_data['post_excerpt']) > $post_options['descr']) {
						$post_data['post_excerpt'] = grace_church_strshort($post_data['post_excerpt'], $post_options['descr'], $post_options['readmore'] ? '' : '...');
					}
					grace_church_show_layout( $post_data['post_excerpt']);
					?>
					</div>
					<?php
				}
				if (empty($post_options['readmore'])) $post_options['readmore'] = esc_html__('READ MORE', 'grace-church');
				if (!grace_church_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
					echo grace_church_do_shortcode('[trx_button link="'.esc_url($post_data['post_link']).'"]'.($post_options['readmore']).'[/trx_button]');
				}
				?>
			
			</div>	<!-- /.post_content -->

		</div>		<!-- /.post_item -->

		<?php
	}
}
?>