<?php
$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';
 
if ($show_all_counters || grace_church_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php grace_church_show_layout( $counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo sprintf( esc_attr__('Views - %s', 'grace-church'), $post_data['post_views']); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><?php grace_church_show_layout( $post_data['post_views']); ?></<?php grace_church_show_layout( $counters_tag); ?>>
	<?php
}

if ($show_all_counters || grace_church_strpos($post_options['counters'], 'comments')!==false) {
	?>
        <?php if (is_single())
            {?>
                <span class="post_counters_item post_counters_comments icon-comment" title="<?php echo sprintf( esc_attr__('Comments - %s', 'grace-church'), $post_data['post_comments']); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php grace_church_show_layout( $post_data['post_comments']); ?></span></span>
            <?php
            } else { ?>
                <a class="post_counters_item post_counters_comments icon-comment" title="<?php echo sprintf( esc_attr__('Comments - %s', 'grace-church'), $post_data['post_comments']); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php grace_church_show_layout( $post_data['post_comments']); ?></span></a>
            <?php
        }
}
 
$rating = $post_data['post_reviews_'.(grace_church_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || grace_church_strpos($post_options['counters'], 'rating')!==false)) {
	?>
	<<?php grace_church_show_layout( $counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo sprintf( esc_attr__('Rating - %s', 'grace-church'), $rating); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php grace_church_show_layout( $rating); ?></span></<?php grace_church_show_layout( $counters_tag); ?>>
	<?php
}

if ($show_all_counters || grace_church_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	grace_church_enqueue_messages();
	$likes = isset($_COOKIE['grace_church_likes']) ? $_COOKIE['grace_church_likes'] : '';
	$allow = grace_church_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php grace_church_show_layout( $allow ? 'enabled' : 'disabled'); ?>" title="<?php echo esc_attr($allow ? esc_html__('Like', 'grace-church') : esc_html__('Dislike', 'grace-church')); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'grace-church'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'grace-church'); ?>"><span class="post_counters_number"><?php grace_church_show_layout( $post_data['post_likes']); ?></span></a>
	<?php
}

if (is_single() && grace_church_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(grace_church_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(grace_church_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>