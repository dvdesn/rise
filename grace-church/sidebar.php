<?php
/**
 * The Sidebar containing the main widget areas.
 */

$sidebar_show   = grace_church_get_custom_option('show_sidebar_main');
$sidebar_scheme = grace_church_get_custom_option('sidebar_main_scheme');
$sidebar_name   = grace_church_get_custom_option('sidebar_main');

if (!grace_church_param_is_off($sidebar_show) && is_active_sidebar($sidebar_name)) {
	?>
	<div class="sidebar widget_area scheme_<?php echo esc_attr($sidebar_scheme); ?>" role="complementary">
		<div class="sidebar_inner widget_area_inner">
			<?php
			ob_start();
			do_action( 'before_sidebar' );
			global $GRACE_CHURCH_GLOBALS;
			if (!empty($GRACE_CHURCH_GLOBALS['reviews_markup']))
				echo '<aside class="column-1_1 widget widget_reviews">' . ($GRACE_CHURCH_GLOBALS['reviews_markup']) . '</aside>';
			$GRACE_CHURCH_GLOBALS['current_sidebar'] = 'main';
            if ( is_active_sidebar( $sidebar_name ) ) {
                dynamic_sidebar( $sidebar_name );
            }
			do_action( 'after_sidebar' );
			$out = ob_get_contents();
			ob_end_clean();
			grace_church_show_layout(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
			?>
		</div>
	</div> <!-- /.sidebar -->
	<?php
}
?>