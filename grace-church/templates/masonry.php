<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_masonry_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_masonry_theme_setup', 1 );
	function grace_church_template_masonry_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'masonry_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile (different height) /2 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Large image', 'grace-church'),
			'w'		 => 870,
			'h_crop' => 490,
			'h'      => null
		));
		grace_church_add_template(array(
			'layout' => 'masonry_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /3 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium image', 'grace-church'),
			'w'		 => 390,
			'h_crop' => 220,
			'h'      => null
		));
		grace_church_add_template(array(
			'layout' => 'masonry_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /4 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Small image', 'grace-church'),
			'w'		 => 300,
			'h_crop' => 170,
			'h'      => null
		));
		grace_church_add_template(array(
			'layout' => 'classic_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile (equal height) /2 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Large image (crop)', 'grace-church'),
			'w'		 => 870,
			'h'		 => 490
		));
		grace_church_add_template(array(
			'layout' => 'classic_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile /3 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium image (slassic)', 'grace-church'),
			'w'		 => 370,
			'h'		 => 250
		));
		grace_church_add_template(array(
			'layout' => 'classic_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile /4 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Small image (crop)', 'grace-church'),
			'w'		 => 300,
			'h'		 => 170
		));
		// Add template specific scripts
		add_action('grace_church_action_blog_scripts', 'grace_church_template_masonry_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('grace_church_template_masonry_add_scripts')) {
	//Handler of add_action('grace_church_action_blog_scripts', 'grace_church_template_masonry_add_scripts');
	function grace_church_template_masonry_add_scripts($style) {
		if (in_array(grace_church_substr($style, 0, 8), array('classic_', 'masonry_'))) {
			wp_enqueue_script( 'isotope', grace_church_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'grace_church_template_masonry_output' ) ) {
	function grace_church_template_masonry_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = grace_church_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
					<?php
					if ($post_options['filters'] != '') {
						if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
						else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
					}
					?>">
			<<?php grace_church_show_layout( $tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
				 <?php echo ' post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
				?>">



				<?php
                if ($show_title && $style != 'classic') {
                    ?><div class="masonry_border_title"><?php
                        if (!isset($post_options['links']) || $post_options['links']) {
                            ?>
                            <h6 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php grace_church_show_layout( $post_data['post_title']); ?></a></h6>
                        <?php
                        } else {
                            ?>
                            <h6 class="post_title"><?php grace_church_show_layout( $post_data['post_title']); ?></h6>
                        <?php
                        }

                    if (!$post_data['post_protected'] && $post_options['info'] && $post_data['post_format'] != 'quote') {
                        $info_parts = array(
                            'counters'=>( $style == 'classic' ? false : true ),
                            'date'=>( $style == 'classic' ? false : true ),
                            'terms'=>( $style == 'classic' ? false : true),
                            'author'=>( $style == 'classic' ? true : false)
                        );
                        require(grace_church_get_file_dir('templates/_parts/post-info.php'));
                    }

                    ?></div><?php
                }?>

				<?php if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) { ?>
					<div class="post_featured">
                        <?php if ( $style == 'classic' ) {
							$post_date = apply_filters('grace_church_filter_post_date', $post_data['post_date_sql'], $post_data['post_id'], $post_data['post_type']);
							?>
                            <div class="post_info_item post_info_posted">
                                <div class="day"><?php echo date_i18n('d', strtotime($post_date)); ?></div>
                                <div class="month_year"><?php echo date_i18n('M ,y', strtotime($post_date)); ?></div>
                            </div>
                        <?php } ?>
						<?php require(grace_church_get_file_dir('templates/_parts/post-featured.php')); ?>
					</div>
				<?php } ?>

              <div class="massonry_border">
                    <?php
                    if ($show_title && $style == 'classic') {
                        if (!isset($post_options['links']) || $post_options['links']) {
                            ?>
                            <h5 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php grace_church_show_layout( $post_data['post_title']); ?></a></h5>
                        <?php
                        } else {
                            ?>
                            <h5 class="post_title"><?php grace_church_show_layout( $post_data['post_title']); ?></h5>
                        <?php
                        }
                    }?>



                    <div class="post_content isotope_item_content">


                        <div class="post_descr">
                            <?php
                            if ($post_data['post_protected']) {
                                grace_church_show_layout( $post_data['post_excerpt']);
                            } else {
                                if ($post_data['post_excerpt']) {
                                    echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(grace_church_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : grace_church_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
                                }
                                if (empty($post_options['readmore'])) $post_options['readmore'] = esc_html__('MORE', 'grace-church');
                                if (!grace_church_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
                                    ?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_readmore sc_button sc_button_square sc_button_style_filled sc_button_size_small"><span class="post_readmore_label"><?php grace_church_show_layout($post_options['readmore']); ?></span></a><?php
                                }
                            }
                            ?>
                        </div>

                    </div>				<!-- /.post_content -->
				</div>				<!-- /.massonry_border -->
			</<?php grace_church_show_layout( $tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php
	}
}
?>