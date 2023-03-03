<?php
//===================================== Post author info =====================================
if (grace_church_get_custom_option("show_post_author") == 'yes') {
	$post_author_name = $post_author_socials = '';
	$show_post_author_socials = true;
	if ($post_data['post_type']=='post') {
		$post_author_title = esc_html__('About author', 'grace-church');
		$post_author_name = $post_data['post_author'];
		$post_author_url = $post_data['post_author_url'];
		$post_author_email = get_the_author_meta('user_email', $post_data['post_author_id']);
		$post_author_avatar = get_avatar($post_author_email, 75*min(2, max(1, grace_church_get_theme_option("retina_ready"))));
		$post_author_descr = grace_church_do_shortcode(nl2br(get_the_author_meta('description', $post_data['post_author_id'])));
		if ($show_post_author_socials) $post_author_socials = grace_church_show_user_socials(array('author_id'=>$post_data['post_author_id'], 'size'=>'tiny', 'echo' => false));
	}
	if (!empty($post_author_name)) {
		?>
		<section class="post_author author vcard" itemprop="author" itemscope itemtype="//schema.org/Person">
			<div class="post_author_avatar"><a href="<?php echo esc_url($post_data['post_author_url']); ?>" itemprop="image"><?php grace_church_show_layout( $post_author_avatar); ?></a></div>
			<h4 class="post_author_title"><span itemprop="name"><a href="<?php echo esc_url($post_author_url); ?>" class="fn"><?php grace_church_show_layout( $post_author_name); ?></a></span></h4>
			<div class="post_author_about"><span><?php echo esc_html($post_author_title); ?></span></div>
			<div class="post_author_info" itemprop="description"><?php grace_church_show_layout( $post_author_descr); ?></div>
			<?php if ($post_author_socials!='') grace_church_show_layout( $post_author_socials); ?>
		</section>
		<?php
	}
}
?>