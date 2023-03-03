<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'grace_church_template_header_5_theme_setup' ) ) {
	add_action( 'grace_church_action_before_init_theme', 'grace_church_template_header_5_theme_setup', 1 );
	function grace_church_template_header_5_theme_setup() {
		grace_church_add_template(array(
			'layout' => 'header_5',
			'mode'   => 'header',
			'title'  => esc_html__('Header 5', 'grace-church'),
			'icon'   => grace_church_get_file_url('templates/headers/images/5.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'grace_church_template_header_5_output' ) ) {
	function grace_church_template_header_5_output($post_options, $post_data) {
		global $GRACE_CHURCH_GLOBALS;

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background: url('.esc_url($header_image).') repeat center top"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_5 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_5 top_panel_position_<?php echo esc_attr(grace_church_get_custom_option('top_panel_position')); ?>">
			
			<?php if (grace_church_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
                        <?php
                        // Address
                        $contact_address_1=trim(grace_church_get_custom_option('contact_address_1'));
                        if (!empty($contact_address_1) ) {
                            ?><div class="contact_address">
                            <span class="contact_icon icon-location-light"></span>
                            <span class="contact_label contact_address_1"><?php grace_church_show_layout($contact_address_1); ?></span>
                            </div><?php
                        }?>
                        <?php
						$top_panel_top_components = array('contact_info', 'login', 'currency', 'donate');
						require_once( grace_church_get_file_dir('templates/headers/_parts/top-panel-top.php') );
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php grace_church_show_layout( $header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php require_once( grace_church_get_file_dir('templates/headers/_parts/logo.php') ); ?>
					</div>
				</div>
                <div class="content_wrap_menu">
                    <div class="menu_main_wrap clearfix">
                        <a href="#" class="menu_main_responsive_button icon-menu"></a>
                        <nav role="navigation" class="menu_main_nav_area">
                            <?php
                            if (empty($GRACE_CHURCH_GLOBALS['menu_main'])) $GRACE_CHURCH_GLOBALS['menu_main'] = grace_church_get_nav_menu('menu_main');
                            if (empty($GRACE_CHURCH_GLOBALS['menu_main'])) $GRACE_CHURCH_GLOBALS['menu_main'] = grace_church_get_nav_menu();
                            grace_church_show_layout( $GRACE_CHURCH_GLOBALS['menu_main']);
                            ?>
                        </nav>
                        <?php
                        if (grace_church_get_custom_option('show_search')=='yes' && function_exists('grace_church_sc_search'))
                            echo grace_church_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed"));
                        ?>
                    </div>
                </div>
			</div>

			</div>
		</header>

		<?php
	}
}
?>