<?php
//===================================== Social sharing =====================================
$show_share = grace_church_get_custom_option("show_share");
if (!grace_church_param_is_off($show_share) && function_exists('grace_church_show_share_links')) {
    if( function_exists('tribe_is_month') ){
        if(tribe_is_month()) {
            $rez = grace_church_show_share_links(array(
                'post_link' => tribe_get_events_link(),
                'post_title' => 'Events Calendar',
                'post_descr' => strip_tags($post_data['post_excerpt']),
                'post_thumb' => $post_data['post_attachment'],
                'type' => 'block',
                'echo' => false
            ));
        } else {
            $rez = grace_church_show_share_links(array(
                'post_id' => $post_data['post_id'],
                'post_link' => $post_data['post_link'],
                'post_title' => $post_data['post_title'],
                'post_descr' => strip_tags($post_data['post_excerpt']),
                'post_thumb' => $post_data['post_attachment'],
                'type' => 'block',
                'echo' => false
            ));
        }
    } else {
        $rez = grace_church_show_share_links(array(
            'post_id' => $post_data['post_id'],
            'post_link' => $post_data['post_link'],
            'post_title' => $post_data['post_title'],
            'post_descr' => strip_tags($post_data['post_excerpt']),
            'post_thumb' => $post_data['post_attachment'],
            'type' => 'block',
            'echo' => false
        ));
    }
    if ($rez) {
		?>
		<div class="post_info post_info_bottom post_info_share post_info_share_<?php echo esc_attr($show_share); ?>"><?php grace_church_show_layout( $rez); ?></div>
		<?php
	}
}
?>