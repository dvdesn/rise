<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_single_standard_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_single_standard_theme_setup', 1 );
	function grace_church_template_single_standard_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'grace-church'),
			'thumb_title'  => esc_html__('Fullwidth image', 'grace-church'),
			'w'		 => 1170,
			'h'		 => 660
		));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_single_standard_output' ) ) {
	function grace_church_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && grace_church_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = grace_church_get_custom_option('show_post_title')=='yes' && (grace_church_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = grace_church_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		grace_church_open_wrapper('<article class="'
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="//schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && $post_options['location'] == 'center' && grace_church_get_custom_option('show_post_title')=='yes') {
			?>
			<<?php echo esc_attr($title_tag); ?> itemprop="<?php grace_church_show_layout( $avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php grace_church_show_layout( $post_data['post_title']); ?></<?php echo esc_attr($title_tag); ?>>
		<?php 
		}

        if (!$post_data['post_protected'] && $post_options['location'] == 'center' && grace_church_get_custom_option('show_post_info')=='yes') {
            $info_parts = array('terms'=> false);
            require(grace_church_get_file_dir('templates/_parts/post-info.php'));
        }

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(grace_church_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				grace_church_show_layout( $post_options['dedicated']);
			} else {
				grace_church_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php grace_church_show_layout( $post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		if ($post_options['location'] != 'center' && $show_title && grace_church_get_custom_option('show_post_title')=='yes') {
			?>
			<<?php echo esc_attr($title_tag); ?> itemprop="<?php grace_church_show_layout( $avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php grace_church_show_layout( $post_data['post_title']); ?></<?php echo esc_attr($title_tag); ?>>
			<?php 
		}

		if ($post_options['location'] != 'center'  && !$post_data['post_protected'] && grace_church_get_custom_option('show_post_info')=='yes') {
			$info_parts = array('terms'=> false);
			require(grace_church_get_file_dir('templates/_parts/post-info.php'));
		}

        if (function_exists('grace_church_reviews_theme_setup')) {
		    require(grace_church_get_file_dir('templates/_parts/reviews-block.php'));
        }

		grace_church_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');
			
		// Post content
		if ($post_data['post_protected']) { 
			grace_church_show_layout( $post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			global $GRACE_CHURCH_GLOBALS;
			if (function_exists('grace_church_sc_reviews') && grace_church_strpos($post_data['post_content'], grace_church_get_reviews_placeholder())===false ) $post_data['post_content'] = grace_church_sc_reviews(array()) . ($post_data['post_content']);
			if(function_exists('grace_church_reviews_wrapper')){
                grace_church_show_layout(grace_church_gap_wrapper(grace_church_reviews_wrapper($post_data['post_content'])));
            } else {
                grace_church_show_layout($post_data['post_content']);
            }
			require(grace_church_get_file_dir('templates/_parts/single-pagination.php'));
			if ( grace_church_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom post_tags">
					<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'grace-church'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php 
			}
		} 
		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			require(grace_church_get_file_dir('templates/_parts/editor-area.php'));
		}
			
		grace_church_close_wrapper();

        if (!$post_data['post_protected']) {
            require(grace_church_get_file_dir('templates/_parts/share.php'));
            require(grace_church_get_file_dir('templates/_parts/author-info.php'));
		}

		$sidebar_present = !grace_church_param_is_off(grace_church_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) grace_church_close_wrapper();
		require(grace_church_get_file_dir('templates/_parts/related-posts.php'));
		if ($sidebar_present) grace_church_close_wrapper();

		if (!$post_data['post_protected']) {
			require(grace_church_get_file_dir('templates/_parts/comments.php'));
		}

		require(grace_church_get_file_dir('templates/_parts/views-counter.php'));
	}
}
?>