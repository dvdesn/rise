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


?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<span class="icon-events icon-location"></span>
	<span>
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

		<span class="author fn org"> <?php echo tribe_get_venue() ?> </span>

		<?php if ( tribe_address_exists() ) : ?>
			<span class="location">
				<span class="tribe-events-address">
					<?php echo tribe_get_full_address(); ?>
				</span>
			</span>
		<?php endif; ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
	</span>
</div>

