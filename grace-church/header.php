<?php
/**
 * The Header for our theme.
 */

global $GRACE_CHURCH_GLOBALS;

// Theme init - don't remove next row! Load custom options
grace_church_core_init_theme();

$theme_skin = grace_church_esc(grace_church_get_custom_option('theme_skin'));
$body_scheme = grace_church_get_custom_option('body_scheme');
if (empty($body_scheme)  || grace_church_is_inherit_option($body_scheme)) $body_scheme = 'original';
$blog_style = grace_church_get_custom_option(is_singular() && !grace_church_get_global('blog_streampage') ? 'single_style' : 'blog_style');
$body_style  = grace_church_get_custom_option('body_style');
$article_style = grace_church_get_custom_option('article_style');
$top_panel_style = grace_church_get_custom_option('top_panel_style');
$top_panel_position = grace_church_get_custom_option('top_panel_position');
$top_panel_scheme = grace_church_get_custom_option('top_panel_scheme');
$video_bg_show  = grace_church_get_custom_option('show_video_bg')=='yes' && (grace_church_get_custom_option('video_bg_youtube_code')!='' || grace_church_get_custom_option('video_bg_url')!='');
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr('scheme_'.$body_scheme); ?>">
<head>
    <?php
    wp_head();
    ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

	<?php grace_church_show_layout(grace_church_get_custom_option('gtm_code')); ?>

	<?php do_action( 'before' ); ?>

	<?php
	// Add TOC items 'Home' and "To top"
	if (grace_church_get_custom_option('menu_toc_home')=='yes' && function_exists('grace_church_sc_anchor'))
		echo grace_church_sc_anchor(array(
			'id' => "toc_home",
			'title' => esc_html__('Home', 'grace-church'),
			'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'grace-church'),
			'icon' => "icon-home",
			'separator' => "yes",
			'url' => esc_url( home_url( '/' ) ))
			); 
	if (grace_church_get_custom_option('menu_toc_top')=='yes' && function_exists('grace_church_sc_anchor'))
		echo grace_church_sc_anchor(array(
			'id' => "toc_top",
			'title' => esc_html__('To Top', 'grace-church'),
			'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'grace-church'),
			'icon' => "icon-double-up",
			'separator' => "yes")
			); 
	?>

	<?php if ( !grace_church_param_is_off(grace_church_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>

	<?php require_once( grace_church_get_file_dir('sidebar_outer.php') ); ?>

	<?php
		$class = $style = '';
		if ($body_style=='boxed' || grace_church_get_custom_option('bg_image_load')=='always') {
			if (($img = (int) grace_church_get_custom_option('bg_image', 0)) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = (int) grace_church_get_custom_option('bg_pattern', 0)) > 0)
				$class = 'bg_pattern_'.($img);
			else if (($img = grace_church_get_custom_option('bg_color', '')) != '')
				$style = 'background-color: '.($img).';';
			else if (grace_church_get_custom_option('bg_custom')=='yes') {
				if (($img = grace_church_get_custom_option('bg_image_custom')) != '')
					$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', grace_church_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
				else if (($img = grace_church_get_custom_option('bg_pattern_custom')) != '')
					$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
				else if (($img = grace_church_get_custom_option('bg_image')) > 0)
					$class = 'bg_image_'.($img);
				else if (($img = grace_church_get_custom_option('bg_pattern')) > 0)
					$class = 'bg_pattern_'.($img);
				if (($img = grace_church_get_custom_option('bg_color')) != '')
					$style .= 'background-color: '.($img).';';
			}
		}
	?>

	<div class="body_wrap<?php grace_church_show_layout( $class ? ' '.esc_attr($class) : ''); ?>"<?php grace_church_show_layout( $style ? ' style="'.esc_attr($style).'"' : ''); ?>>

		<?php
		if ($video_bg_show) {
			$youtube = grace_church_get_custom_option('video_bg_youtube_code');
			$video   = grace_church_get_custom_option('video_bg_url');
			$overlay = grace_church_get_custom_option('video_bg_overlay')=='yes';
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php grace_church_show_layout( $overlay ? ' video_bg_overlay' : ''); ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$info = pathinfo($video);
				$ext = !empty($info['extension']) ? $info['extension'] : 'src';
				?>
				<div class="video_bg<?php echo esc_attr($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>

		<div class="page_wrap">

			<?php
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				grace_church_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}
			// Slider
			require_once( grace_church_get_file_dir('templates/headers/_parts/slider.php') );
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				grace_church_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}

			// Top of page section: page title and breadcrumbs
			$show_title = grace_church_get_custom_option('show_page_title')=='yes' && !is_home() && !is_front_page();
			$show_breadcrumbs = grace_church_get_custom_option('show_breadcrumbs')=='yes' && !is_home() && !is_front_page();
			if ($show_title) {
				?>
				<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php grace_church_show_layout(( $show_title ? ' title_present' : '') . ($show_breadcrumbs ? ' breadcrumbs_present' : '')); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
					<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php grace_church_show_layout(( $show_title ? ' title_present_inner' : '') . ($show_breadcrumbs ? ' breadcrumbs_present_inner' : '')); ?>">
						<div class="content_wrap">
							<?php if ($show_title) { ?>
								<h1 class="page_title"><?php echo wp_kses_post(grace_church_get_blog_title()); ?></h1>
							<?php } ?>
							<?php if ($show_breadcrumbs) { ?>
								<div class="breadcrumbs">
									<?php if (!is_404()) grace_church_show_breadcrumbs(); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<div class="page_content_wrap page_paddings_<?php echo esc_attr(grace_church_get_custom_option('body_paddings')); ?>">

                <?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') grace_church_open_wrapper('<div class="content_wrap">');
				
				// Main content wrapper
				grace_church_open_wrapper('<div class="content">');
				?>