<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_services_2_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_services_2_theme_setup', 1 );
	function grace_church_template_services_2_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'services-2',
			'template' => 'services-2',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 2/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'grace-church'),
			'w'		 => 390,
			'h'		 => 390
		));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_services_2_output' ) ) {
	function grace_church_template_services_2_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (grace_church_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php grace_church_show_layout( $post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_services_item sc_services_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php grace_church_show_layout(( $post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '')
					. (!grace_church_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($post_options['tag_animation'])).'"' : '')); ?>>
				<?php 
				if ($post_data['post_icon'] && $post_options['tag_type']=='icons') {
					$html = grace_church_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]');
					if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
						?><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php grace_church_show_layout($html); ?></a><?php
					} else
						grace_church_show_layout($html);
				} else {
					?>
					<div class="sc_services_item_featured post_featured">
						<?php require(grace_church_get_file_dir('templates/_parts/post-featured.php')); ?>
					</div>
					<?php
				}
				?>
				<div class="sc_services_item_content">
					<?php
					if ($show_title) {
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><h4 class="sc_services_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php grace_church_show_layout( $post_data['post_title']); ?></a></h4><?php
						} else {
							?><h4 class="sc_services_item_title"><?php grace_church_show_layout( $post_data['post_title']); ?></h4><?php
						}
					}
					?>

					<div class="sc_services_item_description">
						<?php
						if ($post_data['post_protected']) {
							grace_church_show_layout( $post_data['post_excerpt']);
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(grace_church_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : grace_church_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							}
							if (!empty($post_data['post_link']) && !grace_church_param_is_off($post_options['readmore'])) {
                            echo do_shortcode('[trx_button style="border" link="' . esc_url($post_data['post_link']) . '" class=" sc_services_item_readmore " bg_style="custom"]' . trim($post_options['readmore']) . '[/trx_button]'); ?>
                        <?php
							}
						}
						?>
					</div>
				</div>
			</div>
		<?php
		if (grace_church_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}
	}
}
?>