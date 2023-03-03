<?php
global $post;
$post_id = $post->ID;
$post_date = grace_church_get_date_or_difference(apply_filters('grace_church_filter_post_date', $post->post_date, $post->ID, $post->post_type));
$post_title = $post->post_title;
$post_link = !isset($show_links) || $show_links ? get_permalink($post_id) : '';

$output .= '<article class="post_item' . ($show_image == 0 ? ' no_thumb' : ' with_thumb') . ($post_number==1 ? ' first' : '') . '">';

if ($show_image) {
	$post_thumb = grace_church_get_resized_image_tag($post_id, 75, 75);
	if ($post_thumb) {
		$output .= '<div class="post_thumb">' . ($post_thumb) . '</div>';
	}
}

$output .= '<div class="post_content">'
			.'<h6 class="post_title">'
			.($post_link ? '<a href="' . esc_url($post_link) . '">' : '') . ($post_title) . ($post_link ? '</a>' : '')
			.'</h6>';

if ($show_counters) {
	if (grace_church_strpos($show_counters, 'views')!==false) {
		$post_counters = apply_filters('trx_utils_filter_get_post_views', 0, $post_id);
		$post_counters_icon = 'post_counters_views icon-eye';
	} else if (grace_church_strpos($show_counters, 'likes')!==false) {
		$likes = isset($_COOKIE['grace_church_likes']) ? $_COOKIE['grace_church_likes'] : '';
		$allow = grace_church_strpos($likes, ','.($post_id).',')===false;
		$post_counters = apply_filters('trx_utils_filter_get_post_likes', 0, $post_id);
		$post_counters_icon = 'post_counters_likes icon-heart '.($allow ? 'enabled' : 'disabled');
		grace_church_enqueue_messages();
	} else if (function_exists('grace_church_reviews_theme_setup') && grace_church_strpos($show_counters, 'stars')!==false || grace_church_strpos($show_counters, 'rating')!==false) {
		$post_counters = grace_church_reviews_marks_to_display(get_post_meta($post_id, $post_rating, true));
		$post_counters_icon = 'post_counters_rating icon-star';
	} else {
		$post_counters = get_comments_number($post_id);
		$post_counters_icon = 'post_counters_comments icon-comment';
	}

	if (grace_church_strpos($show_counters, 'stars')!==false && $post_counters > 0) {
		if (grace_church_strpos($post_counters, '.')===false)
			$post_counters .= '.0';
		if (grace_church_get_custom_option('show_reviews')=='yes') {
			$output .= '<div class="post_rating reviews_summary blog_reviews">'
				. '<div class="criteria_summary criteria_row">' . trim(grace_church_reviews_get_summary_stars($post_counters, false, false, 5)) . '</div>'
				. '</div>';
		}
	}
}
if ($show_date || $show_counters || $show_author) {
	$output .= '<div class="post_info">';
	if ($show_date) {
		$output .= '<span class="post_info_item post_info_posted">'.($post_link ? '<a href="' . esc_url($post_link) . '" class="post_info_date">' : '') . esc_html(date_i18n(  get_option( 'date_format' ), strtotime( $post->post_date ) )) . ($post_link ? '</a>' : '').'</span>';
	}
	if ($show_author) {
		$post_author_id   = $post->post_author;
		$post_author_name = get_the_author_meta('display_name', $post_author_id);
		$post_author_url  = get_author_posts_url($post_author_id, '');
		$output .= '<span class="post_info_item post_info_posted_by">' . esc_html__('by', 'grace-church') . ' ' . ($post_link ? '<a href="' . esc_url($post_author_url) . '" class="post_info_author">' : '') . ($post_author_name) . ($post_link ? '</a>' : '') . '</span>';
	}
	if ($show_counters && grace_church_strpos($show_counters, 'stars')===false) {
		$post_counters_link = grace_church_strpos($show_counters, 'comments')!==false
									? get_comments_link( $post_id )
									: (grace_church_strpos($show_counters, 'likes')!==false
									    ? '#'
									    : $post_link
									    );
		$output .= '<span class="post_info_item post_info_counters">'
			. ($post_counters_link ? '<a href="' . esc_url($post_counters_link) . '"' : '<span')
				. ' class="post_counters_item ' . esc_attr($post_counters_icon) . '"'
				. (grace_church_strpos($show_counters, 'likes')!==false
					? ' title="'. esc_attr($allow ? esc_html__('Like', 'grace-church') : esc_html__('Dislike', 'grace-church')).'"'
						. ' data-postid="' . esc_attr($post_id) . '"'
                        . ' data-likes="' . esc_attr($post_counters) . '"'
                        . ' data-title-like="' . esc_attr( esc_html__('Like', 'grace-church')) . '"'
                        . ' data-title-dislike="' . esc_attr( esc_html__('Dislike', 'grace-church')) . '"'
					: ''
				)
				. '>'
			. '<span class="post_counters_number">' . ($post_counters) . '</span>'
			. ($post_counters_link ? '</a>' : '</span>')
			. '</span>';
	}
	$output .= '</div>';
}
$output .= '</div>'
		.'</article>';
?>