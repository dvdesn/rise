<?php
if ($post_data['post_type'] == 'lesson') {
	grace_church_show_layout(grace_church_get_lessons_links($parent_id, $post_data['post_id'], array(
		'header' => esc_html__('Course Content', 'grace-church'),
		'show_prev_next' => true
		)));
} else {
	wp_link_pages( array( 
		'before' => '<nav class="pagination_single" role="navigation"><span class="pager_pages">' . esc_html__( 'Pages:', 'grace-church' ) . '</span>',
		'after' => '</nav>',
		'link_before' => '<span class="pager_numbers">',
		'link_after' => '</span>'
		)
	); 
}
?>