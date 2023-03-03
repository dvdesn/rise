<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

$event_id = get_the_ID();

?>

<div id="tribe-events-content" class="tribe-events-single vevent hentry">

	<p class="tribe-events-back">
		<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( esc_html__( '&laquo; All %s', 'grace-church' ), $events_label_plural ); ?></a>
	</p>

	<!-- Notices -->
	<?php tribe_the_notices() ?>



	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'grace-church' ), $events_label_singular ); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
		</ul>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-header -->

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <!-- Event featured image, but exclude link -->
            <?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>

            <!-- Schedule & Recurrence Details -->
            <div class="updated published time-details-start">
	            <?php tribe_get_template_part( 'function-data-start-2' ); ?>
            </div>

            <?php the_title( '<h2 class="tribe-events-single-event-title summary entry-title">', '</h2>' ); ?>


			<div class="tribe-events-schedule updated published tribe-clearfix">
				<div class="updated published time-details">
					<span class="icon-events icon-clock"></span>
					<?php echo tribe_events_event_schedule_details( $event_id, '<h3 class="date-event">', '</h3>' ); ?>
				</div>
				<?php echo 	tribe_get_template_part( 'modules/meta/venue-for-single-event' ); ?>
                <?php if ( tribe_get_cost() ) : ?>
                    <div class="tribe-events-cost"><span class="cost"><?php echo esc_html__('Cost:  ', 'grace-church')?></span><?php echo tribe_get_cost( null, true ) ?></div>
                <?php endif; ?>

                <?php
                /*  Popup button
                --------------------------------------*/
                $phone = tribe_get_organizer_phone();
                $email = tribe_get_organizer_email();
                $website = tribe_get_organizer_website_link();
                $organizer = tribe_get_organizer(  );

                if( !empty($phone) || !empty($email) || !empty($website) || !empty($organizer) ) {
                    ?><div class="tribe-events-popup-organizer"><?php
                    echo grace_church_do_shortcode('
                    [trx_button style="filled" size="small" link="#popupform_single_event" popup="on" ]Contact us[/trx_button]
                    [trx_popup id="popupform_single_event"]'
                        . '<div class="popup_info title"><h4>' . esc_html__('Organizer', 'grace-church') . '</h4></div>'
                        . (!empty($organizer)
                            ? '<div class="popup_info organizer">' . '<span>' . esc_html__('Title: ', 'grace-church') . '</span>' . esc_html($organizer) . '</div>'
                            : '')
                        . (!empty($phone)
                            ? '<div class="popup_info phone">' . '<span>' . esc_html__('Phone: ', 'grace-church') . '</span><a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a></div>'
                            : '')
                        . (!empty($email)
                            ? '<div class="popup_info email">' . '<span>' . esc_html__('Email: ', 'grace-church') . '</span><a href="mailto:' . antispambot($email) . '">' . esc_html($email) . '</a></div>'
                            : '')
                        . (!empty($website)
                            ? '<div class="popup_info website">' . '<span>' . esc_html__('Website: ', 'grace-church') . '</span>' . wp_kses_data($website) . '</div>'
                            : '')
                    . '[/trx_popup]');
                    ?></div><?php
                }
                ?>

            </div>

			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content entry-content description">
				<div class="description-title"><?php echo esc_html__('Description', 'grace-church'); ?></div>
                <?php the_content(); ?>
			</div>
			<!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>

			<!-- Event meta -->
			<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
			<?php
			/**
			 * The tribe_events_single_event_meta() function has been deprecated and has been
			 * left in place only to help customers with existing meta factory customizations
			 * to transition: if you are one of those users, please review the new meta templates
			 * and make the switch!
			 */
			if ( ! apply_filters( 'tribe_events_single_event_meta_legacy_mode', false ) ) {
				tribe_get_template_part( 'modules/meta' );
			} else {
				echo tribe_events_single_event_meta();
			}
			?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'grace-church' ), $events_label_singular ); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( esc_html__('PREV', 'grace-church') ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( esc_html__('NEXT', 'grace-church') ) ?></li>
		</ul>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->