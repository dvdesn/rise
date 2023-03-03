<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_404_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_404_theme_setup', 1 );
	function grace_church_template_404_theme_setup() {
		grace_church_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_404_output' ) ) {
	function grace_church_template_404_output() {
		global $GRACE_CHURCH_CLOBALS;
        ?>
		<article class="post_item post_item_404">
            <div class="image_page_404"></div>
			<div class="post_content">
				<h1 class="page_title"><?php echo wp_kses( __( 'We are sorry! <span>Error 404!</span><br> This page could not be found.', 'grace-church' ), $GRACE_CHURCH_CLOBALS['allowed_tags']); ?></h1>
				<p class="page_description"><?php echo sprintf( wp_kses( __('Can\'t find what you need? Take a moment and do<br> a search below or start from <a href="%s">our homepage</a>.', 'grace-church'), $GRACE_CHURCH_CLOBALS['allowed_tags']), esc_url( home_url('/') ) ); ?></p>
				<div class="page_search"><?php if(function_exists('grace_church_sc_search')) echo grace_church_sc_search(array('state'=>'fixed', 'title'=>esc_attr__('Search...', 'grace-church'))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>