			<?php
			$info_parts = array_merge(array(
				'snippets' => false,	// For singular post/page/course/team etc.
				'date' => true,
				'author' => true,
				'terms' => true,
				'counters' => true,
				'tag' => 'div',			// 'p' for portfolio hovers 
				'shedule' => false,		// For single course
				'length' => false		// For single course
				), isset($info_parts) && is_array($info_parts) ? $info_parts : array());
			?>
			<<?php echo esc_attr($info_parts['tag']); ?> class="post_info">
				<?php
				if ($info_parts['date']) {
					$post_date = apply_filters('grace_church_filter_post_date', $post_data['post_date_sql'], $post_data['post_id'], $post_data['post_type']);
					$post_date_diff = grace_church_get_date_or_difference($post_date);
					?>
					<span class="post_info_item post_info_posted"><a href="<?php grace_church_show_layout(esc_url($post_data['post_link'])); ?>" class="post_info_date<?php echo esc_attr($info_parts['snippets'] ? ' date updated' : ''); ?>"<?php grace_church_show_layout( $info_parts['snippets'] ? ' itemprop="datePublished" content="'.get_the_date('Y-m-d').'"' : ''); ?>><?php grace_church_show_layout($post_date_diff); ?></a></span>
					<?php
				}
				if ($info_parts['author']) {
					?>
					<span class="post_info_item post_info_posted_by<?php grace_church_show_layout( $info_parts['snippets'] ? ' vcard' : ''); ?>"<?php grace_church_show_layout( $info_parts['snippets'] ? ' itemprop="author"' : ''); ?>><?php ($post_data['post_type'] == 'tribe_events' ? esc_html_e('Speaker ', 'grace-church') : esc_html_e('by ', 'grace-church') ); ?> <a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_info_author"><?php grace_church_show_layout( $post_data['post_author']); ?></a></span>
				<?php 
				}
				if ($info_parts['terms'] && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links)) {
					?>
					<span class="post_info_item post_info_tags"><?php esc_html_e('in', 'grace-church'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links); ?></span>
					<?php
				}
				if ($info_parts['counters']) {
					?>
					<span class="post_info_item post_info_counters"><?php require(grace_church_get_file_dir('templates/_parts/counters.php')); ?></span>
					<?php
				}
				if (is_single() && !grace_church_get_global('blog_streampage') && ($post_data['post_edit_enable'] || $post_data['post_delete_enable'])) {
					?>
					<span class="frontend_editor_buttons">
						<?php if ($post_data['post_edit_enable']) { ?>
						<span class="post_info_item post_info_button post_info_button_edit"><a id="frontend_editor_icon_edit" class="icon-pencil" title="<?php esc_attr_e('Edit post', 'grace-church'); ?>" href="#"><?php esc_html_e('Edit', 'grace-church'); ?></a></span>
						<?php } ?>
						<?php if ($post_data['post_delete_enable']) { ?>
						<span class="post_info_item post_info_button post_info_button_delete"><a id="frontend_editor_icon_delete" class="icon-trash" title="<?php esc_attr_e('Delete post', 'grace-church'); ?>" href="#"><?php esc_html_e('Delete', 'grace-church'); ?></a></span>
						<?php } ?>
					</span>
					<?php
				}
				?>
			</<?php echo esc_attr($info_parts['tag']); ?>>
