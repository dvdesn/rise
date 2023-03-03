<?php
/**
 * Single Event Meta (Venue) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/venue.php
 *
 * @package TribeEventsCalendar
 */

if ( ! tribe_get_venue_id() ) {
	return;
}

$phone   = tribe_get_phone();
$website = tribe_get_venue_website_link();

?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<span class="info_under_google_map">
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

		<span class="author fn org"> <?php echo tribe_get_venue() ?> </span>

		<?php if ( tribe_address_exists() ) : ?>
			<span class="location">
				<span class="tribe-events-address">
					<?php echo tribe_get_full_address(); ?>

					<?php if ( tribe_show_google_map_link() ) : ?>
						<?php echo tribe_get_map_link_html(); ?>
					<?php endif; ?>
				</span>
			</span>
		<?php endif; ?>

		<?php if ( ! empty( $phone ) ): ?>
			<span> <?php esc_html_e( 'Phone:', 'grace-church' ) ?> </span>
			<span class="tel"><a href="tel:<?php grace_church_show_layout( $phone) ?>"><?php grace_church_show_layout( $phone) ?></a></span>
		<?php endif ?>

		<?php if ( ! empty( $website ) ): ?>
			<span> <?php esc_html_e( 'Website:', 'grace-church' ) ?> </span>
			<span class="url"> <?php grace_church_show_layout( $website) ?> </span>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
	</span>
</div>
