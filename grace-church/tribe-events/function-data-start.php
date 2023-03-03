<?php
    global $post;
    $event = $post;

if ( is_numeric( $event ) ) {
    $event = get_post( $event );
}

$schedule                 = '<span class="date-start on-image">';
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


    $schedule .= '<span class="day">';
    $schedule .= tribe_get_start_date( $event, true, $format_day );
    $schedule .= '</span>';
    $schedule .= '<span class="month_year">';
    $schedule .= tribe_get_start_date( $event, true, $format_month );
    $schedule .= '</span>';

$schedule .= '</span>';

grace_church_show_layout( $schedule);