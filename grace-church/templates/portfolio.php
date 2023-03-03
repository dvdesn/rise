<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_portfolio_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_portfolio_theme_setup', 1 );
	function grace_church_template_portfolio_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'portfolio_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile (with hovers, different height) /2 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Large image', 'grace-church'),
			'w'		 => 870,
			'h_crop' => 490,
			'h'		 => null
		));
		grace_church_add_template(array(
			'layout' => 'portfolio_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile /3 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium image', 'grace-church'),
			'w'		 => 390,
			'h_crop' => 220,
			'h'		 => null
		));
		grace_church_add_template(array(
			'layout' => 'portfolio_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile /4 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Small image', 'grace-church'),
			'w'		 => 300,
			'h_crop' => 170,
			'h'		 => null
		));
		grace_church_add_template(array(
			'layout' => 'grid_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Grid tile (with hovers, equal height) /2 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Large image (crop)', 'grace-church'),
			'w'		 => 870,
			'h' 	 => 490
		));
		grace_church_add_template(array(
			'layout' => 'grid_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Grid tile /3 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'grace-church'),
			'w'		 => 390,
			'h'		 => 220
		));
		grace_church_add_template(array(
			'layout' => 'grid_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Grid tile /4 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Small image (crop)', 'grace-church'),
			'w'		 => 300,
			'h'		 => 170
		));
		grace_church_add_template(array(
			'layout' => 'square_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Square tile (with hovers, width=height) /2 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Large square image (crop)', 'grace-church'),
			'w'		 => 870,
			'h' 	 => 870
		));
		grace_church_add_template(array(
			'layout' => 'square_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Square tile /3 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'grace-church'),
			'w'		 => 390,
			'h'		 => 390
		));
		grace_church_add_template(array(
			'layout' => 'square_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Square tile /4 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'grace-church'),
			'w'		 => 300,
			'h'		 => 300
		));
		grace_church_add_template(array(
			'layout' => 'colored_1',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored excerpt', 'grace-church'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'grace-church'),
			'w'		 => 300,
			'h'		 => 300
		));
		grace_church_add_template(array(
			'layout' => 'colored_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored tile (with hovers, width=height) /2 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Large square image (crop)', 'grace-church'),
			'w'		 => 870,
			'h' 	 => 870
		));
		grace_church_add_template(array(
			'layout' => 'colored_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored tile /3 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'grace-church'),
			'w'		 => 390,
			'h'		 => 390
		));
		grace_church_add_template(array(
			'layout' => 'colored_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored tile /4 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'grace-church'),
			'w'		 => 300,
			'h'		 => 300
		));
		grace_church_add_template(array(
			'layout' => 'short_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Short info /2 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Large square image (crop)', 'grace-church'),
			'w'		 => 870,
			'h' 	 => 870
		));
		grace_church_add_template(array(
			'layout' => 'short_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Short info /3 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'grace-church'),
			'w'		 => 390,
			'h'		 => 390
		));
		grace_church_add_template(array(
			'layout' => 'short_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Short info /4 columns/', 'grace-church'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'grace-church'),
			'w'		 => 300,
			'h'		 => 300
		));
		// Add template specific scripts
		add_action('grace_church_action_blog_scripts', 'grace_church_template_portfolio_add_scripts');

	}
}

// Add template specific scripts
if (!function_exists('grace_church_template_portfolio_add_scripts')) {
	//Handler of add_action('grace_church_action_blog_scripts', 'grace_church_template_portfolio_add_scripts');
	function grace_church_template_portfolio_add_scripts($style) {
        if (grace_church_substr($style, 0, 10) == 'portfolio_'
            || grace_church_substr($style, 0, 5) == 'grid_'
            || grace_church_substr($style, 0, 7) == 'square_'
            || grace_church_substr($style, 0, 6) == 'short_'
            || grace_church_substr($style, 0, 8) == 'colored_') {
            wp_enqueue_script( 'isotope', grace_church_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
			if ($style != 'colored_1')  {
				wp_enqueue_script( 'hoverdir', grace_church_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
				wp_enqueue_style( 'grace-church-portfolio-style', grace_church_get_file_url('css/core.portfolio.css'), array(), null );
			}
        }
	}
}

// Template output
if ( !function_exists( 'grace_church_template_portfolio_output' ) ) {
	function grace_church_template_portfolio_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = grace_church_in_shortcode_blogger(true) ? 'div' : 'article';
		if ($post_options['hover']=='square effect4') $post_options['hover']='square effect5';
		$link_start = !isset($post_options['links']) || $post_options['links'] ? '<a href="'.esc_url($post_data['post_link']).'">' : '';
		$link_end = !isset($post_options['links']) || $post_options['links'] ? '</a>' : '';

		if ($style == 'colored_1' && $columns==1) {				// colored excerpt style (1 column)
			?>
			<div class="isotope_item isotope_item_colored isotope_item_colored_1 isotope_column_1
						<?php
						if ($post_options['filters'] != '') {
							if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
								echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
							else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
								echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
						}
						?>">
				<<?php grace_church_show_layout( $tag); ?> class="post_item post_item_colored post_item_colored_1
					<?php echo 'post_format_'.esc_attr($post_data['post_format']) 
						. ($post_options['number']%2==0 ? ' even' : ' odd') 
						. ($post_options['number']==0 ? ' first' : '') 
						. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
					?>">
	
					<div class="post_content isotope_item_content">
						<div class="post_featured img">
							<?php
							grace_church_show_layout(( $link_start) . ($post_data['post_thumb']) . ($link_end));

                            if (function_exists('grace_church_reviews_theme_setup')) {
                                require(grace_church_get_file_dir('templates/_parts/reviews-summary.php'));
                            }

							$new = grace_church_get_custom_option('mark_as_new', '', $post_data['post_id'], $post_data['post_type']);						// !!!!!! Get option from specified post
							if ($new && $new > date('Y-m-d')) {
								?><div class="post_mark_new"><?php esc_html_e('NEW', 'grace-church'); ?></div><?php
							}
							?>
						</div>
		
						<div class="post_content clearfix">
							<h4 class="post_title"><?php grace_church_show_layout(( $link_start) . ($post_data['post_title']) . ($link_end)); ?></h4>
							<div class="post_category">
								<?php
								if (!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links))
									echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links);
								?>
							</div>
							<?php grace_church_show_layout( $reviews_summary); ?>
							<?php if (grace_church_substr($style, 0, 6) != 'short_') { ?>
								<div class="post_descr">
									<?php
									if ($post_data['post_protected']) {
										grace_church_show_layout(( $link_start) . ($post_data['post_excerpt']) . ($link_end));
									} else {
										if ($style=='colored_1') {
											if ($post_data['post_link'] != '')
												echo '<div class="post_buttons">';
											if ($post_data['post_link'] != '') {
												?>
												<div class="post_button"><?php
												if(function_exists('grace_church_sc_button')) echo grace_church_sc_button(array('size'=>'small', 'link'=>$post_data['post_link']), esc_html__('MORE', 'grace-church'));
												?></div>
												<?php
											}
											if ($post_data['post_link'] != '')
												echo '</div>';
										}
									}
									?>
								</div>
							<?php } ?>
						</div>
					</div>				<!-- /.post_content -->
				</<?php grace_church_show_layout( $tag); ?>>	<!-- /.post_item -->
			</div>						<!-- /.isotope_item -->
			<?php

		} else {										// All rest portfolio styles (portfolio, grid, square, colored) with 2 and more columns

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
					<?php echo 'post_format_'.esc_attr($post_data['post_format']) 
						. ($post_options['number']%2==0 ? ' even' : ' odd') 
						. ($post_options['number']==0 ? ' first' : '') 
						. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
					?>">

					<div class="post_content isotope_item_content ih-item colored<?php
									grace_church_show_layout(( $post_options['hover'] ? ' '.esc_attr($post_options['hover']) : '')
										.($post_options['hover_dir'] ? ' '.esc_attr($post_options['hover_dir']) : '')); ?>">
						<?php
						if ($post_options['hover'] == 'circle effect1') {
							?><div class="spinner"></div><?php
						}
						if ($post_options['hover'] == 'square effect4') {
							?><div class="mask1"></div><div class="mask2"></div><?php
						}
						if ($post_options['hover'] == 'circle effect8') {
							?><div class="img-container"><?php
						}
						?>
						<div class="post_featured img">
							<?php
							grace_church_show_layout(( $link_start) . ($post_data['post_thumb']) . ($link_end));

 							if ($style=='colored_1') {
                                if (function_exists('grace_church_reviews_theme_setup')) {
                                    require(grace_church_get_file_dir('templates/_parts/reviews-summary.php'));
                                }
								$new = grace_church_get_custom_option('mark_as_new', '', $post_data['post_id'], $post_data['post_type']);						// !!!!!! Get option from specified post
								if ($new && $new > date('Y-m-d')) {
									?><div class="post_mark_new"><?php esc_html_e('NEW', 'grace-church'); ?></div><?php
								}
								?>
								<h4 class="post_title"><?php grace_church_show_layout(( $link_start) . ($post_data['post_title']) . ($link_end)); ?></h4>
								<div class="post_descr">
									<?php
									$category = !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms)
												? ($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->link ? '<a href="'.esc_url($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->link).'">' : '')
													. ($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->name)
													. ($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->link ? '</a>' : '')
												: '';
									?>
									<div class="post_category"><?php grace_church_show_layout( $category); ?></div>
									<?php grace_church_show_layout( $reviews_summary); ?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
						if ($post_options['hover'] == 'circle effect8') {
							?>
							</div>	<!-- .img-container -->
							<div class="info-container">
							<?php
						}
						?>

						<div class="post_info_wrap info"><div class="info-back">

							<?php
							if ($show_title) {
								?><h4 class="post_title"><?php grace_church_show_layout(( $link_start) . ($post_data['post_title']) . ($link_end)); ?></h4><?php
							}
							?>

							<div class="post_descr">
							<?php
								if ($post_data['post_protected']) {
									grace_church_show_layout(( $link_start) . ($post_data['post_excerpt']) . ($link_end));
								} else {
									if (!$post_data['post_protected'] && $post_options['info']) {
										$info_parts = array('counters'=>true, 'terms'=>false, 'author' => false, 'tag' => 'p');
										require(grace_church_get_file_dir('templates/_parts/post-info.php'));
									}
									if ($post_data['post_excerpt']) {
										echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))
											? ( ($link_start) . ($post_data['post_excerpt']) . ($link_end) )
											: '<p>' . ($link_start)
												. (grace_church_strpos($post_options['hover'], 'square')!==false
													? trim(grace_church_strshort($post_data['post_excerpt'], 60))
                                                    : trim(grace_church_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : grace_church_get_custom_option('post_excerpt_maxlength_masonry')))
													)
												. ($link_end) . '</p>';
									}
									if ($post_data['post_link'] != '') {
										?><p class="post_buttons"><?php
										if ($style=='colored_1') {
											?><span class="post_button"><?php
                                            if(function_exists('grace_church_sc_button')) echo grace_church_sc_button(array('size'=>'small', 'link'=>$post_data['post_link']), esc_html__('Learn more', 'grace-church'));
											?></span><?php
										} else if (!grace_church_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
											?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_readmore"><span class="post_readmore_label"><?php grace_church_show_layout($post_options['readmore']); ?></span></a><?php
										}
										?></p><?php
									}
								}
							?>
							</div>
						</div></div>	<!-- /.info-back /.info -->
						<?php if ($post_options['hover'] == 'circle effect8') { ?>
						</div>			<!-- /.info-container -->
						<?php } ?>
					</div>				<!-- /.post_content -->
				</<?php grace_church_show_layout( $tag); ?>>	<!-- /.post_item -->
			</div>						<!-- /.isotope_item -->
			<?php
		}
	}
}
?>