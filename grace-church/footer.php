<?php
/**
 * The template for displaying the footer.
 */

global $GRACE_CHURCH_GLOBALS;

				grace_church_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (grace_church_get_custom_option('body_style')!='fullscreen') grace_church_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
			if (grace_church_get_custom_option('show_testimonials_in_footer')=='yes') {
				$count = max(1, grace_church_get_custom_option('testimonials_count'));
				$data = grace_church_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(grace_church_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php grace_church_show_layout( $data); ?></div>
						</div>
					</footer>
					<?php
				}
			}

            // Call to action
            $call_to_action  = grace_church_get_custom_option('show_call_to_action');
            $call_to_action_link = grace_church_get_custom_option('call_to_action_link');
            $call_to_action_title   = grace_church_get_custom_option('call_to_action_title');
            $call_to_action_picture   = grace_church_get_custom_option('call_to_action_picture');
            $call_to_action_description  = grace_church_get_custom_option('call_to_action_description');
            $call_to_action_link_caption   = grace_church_get_custom_option('call_to_action_link_caption');
            if ( $call_to_action == 'yes' ) {
                ?>
                <footer class="footer_wrap call_to_action scheme_<?php echo esc_attr(grace_church_get_custom_option('call_to_action_scheme')); ?> <?php grace_church_show_layout( $call_to_action_picture ? ' width_image'  : ''); ?>">
                    <div class="call_to_action_inner content_wrap">
                            <?php
                            grace_church_show_layout( $call_to_action_picture ? '<div class="block_image"><img src="'.esc_url($call_to_action_picture).'" alt="'.esc_attr__('img', 'grace-church').'" class="call_to_action_image"></div>' : '');
                            echo do_shortcode('[trx_call_to_action style="2" align="left" accent="yes" title="' . esc_attr($call_to_action_title) . '" description="' . esc_attr($call_to_action_description) . '" link="' . esc_attr($call_to_action_link) . '" link_caption="' . esc_attr($call_to_action_link_caption) . '"][/trx_call_to_action]');
                            ?>
                    </div>	<!-- /.footer_wrap_inner -->
                </footer>	<!-- /.footer_wrap -->
            <?php
            }

			// Footer sidebar
			$footer_show  = grace_church_get_custom_option('show_sidebar_footer');
			$sidebar_name = grace_church_get_custom_option('sidebar_footer');
			if (!grace_church_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) {
				$GRACE_CHURCH_GLOBALS['current_sidebar'] = 'footer';
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(grace_church_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
                                if ( is_active_sidebar( $sidebar_name ) ) {
                                    dynamic_sidebar( $sidebar_name );
                                }
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							grace_church_show_layout(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
			<?php
			}


			// Footer Twitter stream
			if (grace_church_get_custom_option('show_twitter_in_footer')=='yes' && function_exists('grace_church_sc_twitter')) {
				$count = max(1, grace_church_get_custom_option('twitter_count'));
				$data = grace_church_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(grace_church_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php grace_church_show_layout( $data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Google map
			if ( grace_church_get_custom_option('show_googlemap')=='yes' &&  function_exists('grace_church_sc_googlemap')) {
				$map_address = grace_church_get_custom_option('googlemap_address');
				$map_latlng  = grace_church_get_custom_option('googlemap_latlng');
				$map_zoom    = grace_church_get_custom_option('googlemap_zoom');
				$map_style   = grace_church_get_custom_option('googlemap_style');
				$map_height  = grace_church_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
					echo grace_church_sc_googlemap($args);
				}
			}


			// Footer contacts
			if (grace_church_get_custom_option('show_contacts_in_footer')=='yes') {
                $fax = grace_church_get_theme_option('contact_fax');
                $phone = grace_church_get_theme_option('contact_phone');
                $address_1 = grace_church_get_theme_option('contact_address_1');
                $contact_open_hours = grace_church_get_theme_option('contact_open_hours');
				$contact_open_hours_2 = grace_church_get_theme_option('contact_open_hours_2');
				$logo_footer = grace_church_get_custom_option('logo_footer');
				if (!empty($address_1) || !empty($phone) || !empty($fax) || !empty($logo_footer)) {
					?>
					<footer class="contacts_wrap scheme_<?php echo esc_attr(grace_church_get_custom_option('contacts_scheme')); ?>">
						<div class="contacts_wrap_inner">
							<div class="content_wrap">
                                <?php if ($logo_footer) {?>
                                    <div class="logo_in_footer"><?php echo ('<img src="'.esc_url($logo_footer).'" alt="'.esc_attr__('img', 'grace-church').'" >') ; ?></div>
                                <?php } ?>
								<div class="contacts_address">
                                    <div class="address address_left">
                                        <?php if (!empty($address_1)) echo ('<span class="icon-location"></span>') . '  ' .($address_1); ?>
                                    </div>
									<div class="address address_center">
                                        <?php if (!empty($phone)) echo ('<span class="icon-phone"></span>') . '  ' . '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>'; ?>
										<?php if (!empty($fax)) grace_church_show_layout( $fax); ?>
									</div>
                                    <div class="address address_right">
                                        <span><?php if (!empty($contact_open_hours)) grace_church_show_layout( $contact_open_hours) ; ?></span>
                                        <span><?php if (!empty($contact_open_hours_2)) grace_church_show_layout( $contact_open_hours_2) ; ?></span>
                                    </div>
                                </div>
							</div>	<!-- /.content_wrap -->
                        </div>	<!-- /.contacts_wrap_inner -->

                        <?php // Footer contacts form
                        if ( grace_church_get_custom_option('show_footer_contacts_form')=='yes' ) { ?>
                            <div class="content_wrap content_contacts_form">
                                <?php echo do_shortcode('[trx_contact_form style="1" custom="no"][/trx_contact_form]'); ?>
                            </div>
                        <?php } ?>

					</footer>	<!-- /.contacts_wrap -->
					<?php
				}
			}


			// Copyright area
			$copyright_style = grace_church_get_custom_option('show_copyright_in_footer');
			if (!grace_church_param_is_off($copyright_style)) {
			?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(grace_church_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<?php
							if ($copyright_style == 'menu') {
								if (empty($GRACE_CHURCH_GLOBALS['menu_footer']))	$GRACE_CHURCH_GLOBALS['menu_footer'] = grace_church_get_nav_menu('menu_footer');
								if (!empty($GRACE_CHURCH_GLOBALS['menu_footer']))	grace_church_show_layout( $GRACE_CHURCH_GLOBALS['menu_footer']);
							} else if ($copyright_style == 'socials' && function_exists('grace_church_sc_socials')) {
								echo grace_church_sc_socials(array('size'=>"tiny"));
							}
							?>
							<div class="copyright_text"><?php grace_church_show_layout(do_shortcode(str_replace(array('{{Y}}', '{Y}'), date('Y'), grace_church_get_theme_option('footer_copyright')))); ?></div>

                        </div>
					</div>
				</div>
			<?php } ?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !grace_church_param_is_off(grace_church_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
if (grace_church_get_custom_option('show_theme_customizer')=='yes') {
	require_once( grace_church_get_file_dir('core/core.customizer/front.customizer.php') );
}
?>

<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'grace-church'); ?>"></a>

<div class="custom_html_section">
<?php grace_church_show_layout(grace_church_get_custom_option('custom_code')); ?>
</div>

<?php grace_church_show_layout(grace_church_get_custom_option('gtm_code2')); ?>

<?php wp_footer(); ?>

</body>
</html>