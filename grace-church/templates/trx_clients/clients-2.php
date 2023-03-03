<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_clients_2_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_clients_2_theme_setup', 1 );
	function grace_church_template_clients_2_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'clients-2',
			'template' => 'clients-2',
			'mode'   => 'clients',
			'title'  => esc_html__('Clients /Style 2/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'grace-church'),
			'w'		 => 390,
			'h'		 => 390
		));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_clients_2_output' ) ) {
	function grace_church_template_clients_2_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		if (grace_church_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php grace_church_show_layout( $post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_clients_item sc_clients_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"<?php grace_church_show_layout(( $post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') . (!grace_church_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(grace_church_get_animation_classes($post_options['tag_animation'])).'"' : '')); ?>>
				<div class="sc_client_image"><?php grace_church_show_layout($post_options['client_image']); ?>
					<div class="sc_client_hover">
						<div class="sc_client_info">
							<div class="sc_client_description"><?php grace_church_show_layout(grace_church_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : grace_church_get_custom_option('post_excerpt_maxlength_masonry'))); ?></div>
							<h5 class="sc_client_title"><?php grace_church_show_layout(( $post_options['client_link'] ? '<a href="'.esc_url($post_options['client_link']).'">' : '') . ($post_options['client_name'] ? $post_options['client_name'] : $post_data['post_title']) . ($post_options['client_link'] ? '</a>' : '')); ?></h5>
							<div class="sc_client_position"><?php grace_church_show_layout($post_options['client_position']);?></div>
						</div>
					</div>
				</div>
			</div>
		<?php
		if (grace_church_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>