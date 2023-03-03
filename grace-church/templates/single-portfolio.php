<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_single_portfolio_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_single_portfolio_theme_setup', 1 );
	function grace_church_template_single_portfolio_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'single-portfolio',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Portfolio item', 'grace-church'),
			'thumb_title'  => esc_html__('Fullsize image', 'grace-church'),
			'w'		 => 1170,
			'h'		 => null,
			'h_crop' => 660
		));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_single_portfolio_output' ) ) {
	function grace_church_template_single_portfolio_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && grace_church_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = grace_church_get_custom_option('show_post_title')=='yes' && (grace_church_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));

		grace_church_open_wrapper('<article class="'
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single_portfolio'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="//schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		require(grace_church_get_file_dir('templates/_parts/prev-next-block.php'));

		if ($show_title) {
			?>
			<h1 itemprop="<?php grace_church_show_layout( $avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php grace_church_show_layout( $post_data['post_title']); ?></h1>
			<?php
		}

		if (!$post_data['post_protected'] && grace_church_get_custom_option('show_post_info')=='yes') {
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
			if (grace_church_strpos($post_data['post_content'], grace_church_get_reviews_placeholder())===false && function_exists('grace_church_sc_reviews') ) $post_data['post_content'] = grace_church_sc_reviews(array()) . ($post_data['post_content']);
			grace_church_show_layout(grace_church_gap_wrapper(grace_church_reviews_wrapper($post_data['post_content'])));
			require(grace_church_get_file_dir('templates/_parts/single-pagination.php'));
			if ( grace_church_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info">
					<span class="post_info_item post_info_tags"><?php esc_html_e('in', 'grace-church'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php
			} 
		}

		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			require(grace_church_get_file_dir('templates/_parts/editor-area.php'));
		}

		grace_church_close_wrapper();

		if (!$post_data['post_protected']) {
			require(grace_church_get_file_dir('templates/_parts/author-info.php'));
			require(grace_church_get_file_dir('templates/_parts/share.php'));
			require(grace_church_get_file_dir('templates/_parts/related-posts.php'));
			require(grace_church_get_file_dir('templates/_parts/comments.php'));
		}
	
		grace_church_close_wrapper();

		require(grace_church_get_file_dir('templates/_parts/views-counter.php'));
	}
}
?>