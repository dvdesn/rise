<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_no_articles_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_no_articles_theme_setup', 1 );
	function grace_church_template_no_articles_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'grace-church'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_no_articles_output' ) ) {
	function grace_church_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'grace-church'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'grace-church' ); ?></p>
				<p><?php echo sprintf( esc_html__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'grace-church'), esc_url( home_url('/') ), get_bloginfo()); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'grace-church'); ?></p>
				<?php if(function_exists('grace_church_sc_search')) echo grace_church_sc_search(array('state'=>"fixed")); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>