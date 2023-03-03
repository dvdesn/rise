<a href="#" class="top_panel_cart_button">
	<span class="contact_icon icon-basket"></span>
	<span class="contact_label contact_cart_label"><?php esc_html_e('Your cart:', 'grace-church'); ?></span>
	<span class="contact_cart_totals">
		<span class="cart_items"><?php
			$items = WC()->cart->get_cart_contents_count();
			echo esc_html($items) . ' ' . ($items == 1 ? esc_html__('Item', 'grace-church') : esc_html__('Items', 'grace-church')); ?>
		</span>
		- 
		<span class="cart_summa"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
	</span>
</a>
<ul class="widget_area sidebar_cart sidebar"><li>
	<?php
	do_action( 'before_sidebar' );
	$GRACE_CHURCH_GLOBALS['current_sidebar'] = 'cart';
	if ( !dynamic_sidebar( 'sidebar-cart' ) ) { 
		the_widget( 'WC_Widget_Cart', 'title=&hide_if_empty=1' );
	}
	?>
</li></ul>