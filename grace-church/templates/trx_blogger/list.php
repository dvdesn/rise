<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_list_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_list_theme_setup', 1 );
	function grace_church_template_list_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'list',
			'mode'   => 'blogger',
			'need_columns' => true,
			'title'  => esc_html__('Blogger layout: List', 'grace-church')
			));
	}
}


// Template output
if ( !function_exists( 'grace_church_template_list_output' ) ) {
	function grace_church_template_list_output($post_options, $post_data) {

    $columns = max(1, min(12, $post_options['columns_count']));
    echo '<li class="sc_blogger_item sc_list_item'
            . ( $columns > 1 ? ' column-1_'.esc_attr($columns) : '')
            . ( $post_data['post_type'] == 'tribe_events' ? ' event_item' : '')
        .'">';

        if ( $post_data['post_type'] == 'tribe_events' && function_exists('tribe_get_template_part') ){
            require(grace_church_get_file_dir('tribe-events/venue-for-blogger-list.php'));
        }

        $title = '<div class="sc_list_item_content">'
            . (!isset($post_options['links']) || $post_options['links'] ? '<a href="' . esc_url($post_data['post_link']) . '">' : '')
            . ( $post_data['post_type'] != 'tribe_events' ? '<span class="sc_list_icon '.($post_data['post_icon'] ? $post_data['post_icon'] : 'icon-right').'"></span>' : '')
            . '<span class="sc_list_title">'.($post_data['post_title']).'</span>'
            . (!isset($post_options['links']) || $post_options['links'] ? '</a>' : '')
            . '</div>';
        grace_church_show_layout( $title);

        ?>
            <div class="button">
            <?php echo do_shortcode('[trx_button type="square" style="border" size="large" icon="inherit" link="' . esc_url($post_data['post_link']) . '" popup="no"]' . esc_html__("Read more", 'grace-church') . '[/trx_button]'); ?>
            </div>
        <?php

    echo '</li>';
    }
}
?>