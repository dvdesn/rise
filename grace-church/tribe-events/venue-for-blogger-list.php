<?php
global $post;
$event = $post;


if ( is_numeric( $event ) ) {
    $event = get_post( $event );
}

$schedule                 = '<div class="sc_list_event_date_start">';
$format_day                   = 'd';
$format_month                 = ' M \'y';
$date_with_year_format    = tribe_get_date_format( true );

$settings = array(
    'show_end_time' => true,
    'time'          => true,
);

$settings = wp_parse_args( apply_filters( 'tribe_events_event_schedule_details_formatting', $settings ), $settings );
if ( ! $settings['time'] ) {
    $settings['show_end_time'] = false;
}
extract( $settings );

    $schedule .= '<div class="position_date">';
        $schedule .= '<span class="day">';
        $schedule .= tribe_get_start_date( $event, false, $format_day );
        $schedule .= '</span>';
        $schedule .= '<span class="month_year">';
        $schedule .= tribe_get_start_date( $event, false, $format_month );
        $schedule .= '</span>';
    $schedule .= '</div>';

$schedule .= '</div>';

grace_church_show_layout( $schedule);

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
}?>



<?php
/**
 * Single Event Meta (Details) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */


$time_format = get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT );
$time_range_separator = tribe_get_option( 'timeRangeSeparator', ' - ' );

$start_datetime = tribe_get_start_date();
$start_date = tribe_get_start_date( null, false );
$start_time = tribe_get_start_date( null, false, $time_format );
$start_ts = tribe_get_start_date( null, false, Tribe__Date_Utils::DBDATEFORMAT );

$end_datetime = tribe_get_end_date();
$end_date = tribe_get_end_date( null, false );
$end_time = tribe_get_end_date( null, false, $time_format );
$end_ts = tribe_get_end_date( null, false, Tribe__Date_Utils::DBDATEFORMAT );

?>


<div class="sc_list_event_details">
        <?php

        // Multiday events
            ?>
            <span class="blogger-list-icon icon-clock"></span>
            <div class="sc_list_event_details_content">
                <span class="tribe-events-abbr updated published dtstart" title="<?php ( $start_ts ) ?>"> <?php grace_church_show_layout( $start_datetime ) ?> </span>
                <span class="tribe-events-abbr dtend" title="<?php ( $end_ts ) ?>"> <?php grace_church_show_layout( $end_datetime ) ?> </span>
            </div>
</div>




<div class="sc_list_event_location">
	<span class="blogger-list-icon icon-location"></span>
    <div class="sc_list_event_location_content">
        <span>
            <span class="author"> <?php echo tribe_get_venue() ?> </span>

            <?php if ( tribe_address_exists() ) : ?>
                <span class="location">
                    <span class="tribe-events-address">
                        <?php echo tribe_get_full_address(); ?>
                    </span>
                </span>
            <?php endif; ?>

        </span>
    </div>
</div>