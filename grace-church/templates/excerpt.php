<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_excerpt_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_excerpt_theme_setup', 1 );
	function grace_church_template_excerpt_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'excerpt',
			'mode'   => 'blog',
			'title'  => esc_html__('Excerpt', 'grace-church'),
			'thumb_title'  => esc_html__('Large image (crop)', 'grace-church'),
			'w'		 => 870,
			'h'		 => 490
		));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_excerpt_output' ) ) {
	function grace_church_template_excerpt_output($post_options, $post_data) {
		$show_title = true;
		$tag = grace_church_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<<?php grace_church_show_layout( $tag); ?> <?php post_class('post_item post_item_excerpt post_featured_' . esc_attr($post_options['post_class']) . ' post_format_'.esc_attr($post_data['post_format']) . ($post_options['number']%2==0 ? ' even' : ' odd') . ($post_options['number']==0 ? ' first' : '') . ($post_options['number']==$post_options['posts_on_page']? ' last' : '') . ($post_options['add_view_more'] ? ' viewmore' : '')); ?>>
			<?php
			if ($post_data['post_flags']['sticky']) {
				?><span class="sticky_label"></span><?php
			}

			if ($post_options['location'] == 'center' && $show_title && !empty($post_data['post_title'])) {
				?><h6 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php grace_church_show_layout( $post_data['post_title']); ?></a></h6><?php
			}
            if ($post_options['location'] == 'center' && !$post_data['post_protected'] && $post_options['info']) {
                require(grace_church_get_file_dir('templates/_parts/post-info.php'));
            }

			if (!$post_data['post_protected'] && (!empty($post_options['dedicated']) || $post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio'])) {
				?>
				<div class="post_featured">
				<?php
				if (!empty($post_options['dedicated'])) {
					grace_church_show_layout( $post_options['dedicated']);
				} else if ($post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio']) {
					require(grace_church_get_file_dir('templates/_parts/post-featured.php'));
				}
				?>
				</div>
			<?php
			}
			?>
	
			<div class="post_content clearfix">
				<?php
                if ($post_options['location'] != 'center' && $show_title && !empty($post_data['post_title'])) {
                    ?><h6 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php grace_church_show_layout( $post_data['post_title']); ?></a></h6><?php
                }
                if ($post_options['location'] != 'center' && !$post_data['post_protected'] && $post_options['info']) {
                    require(grace_church_get_file_dir('templates/_parts/post-info.php'));
                }
				?>
		
				<div class="post_descr">
				<?php
					if ($post_data['post_protected']) {
						grace_church_show_layout( $post_data['post_excerpt']);
					} else {
						if ($post_data['post_excerpt']) {
							echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(grace_church_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : grace_church_get_custom_option('post_excerpt_maxlength'))).'</p>';
						}
					}
					if (empty($post_options['readmore'])) $post_options['readmore'] = esc_html__('Read more', 'grace-church');
					if (!grace_church_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
                        if (function_exists('grace_church_sc_button')) {
                            echo grace_church_sc_button(array('link' => $post_data['post_link']), $post_options['readmore']);
                        } else {
                            grace_church_show_layout('<a href="' . $post_data['post_link'] . '" class="sc_button sc_button_square sc_button_style_filled sc_button_size_small">' . $post_options['readmore'] . '</a>');
                        }
                    }
				?>
				</div>

			</div>	<!-- /.post_content -->

		</<?php grace_church_show_layout( $tag); ?>>	<!-- /.post_item -->

	<?php
	}
}
?>