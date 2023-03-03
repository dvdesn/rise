<?php
/**
 * Month Single Event
 * This file contains one event in the month view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/month/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

global $post;

/**
 * We build and gather information specific to the individual event prior to
 * the tribe_events_template_data() call to reduce the opportunities for 3rd
 * party code to call wp_reset_postdata() or similar, which can result in the
 * $post global referencing something other than the event we're interested
 * in.
 */
$day      = tribe_events_get_current_month_day();
$event_id = "{$post->ID}-{$day['daynum']}";
$link     = tribe_get_event_link( $post );
$title    = get_the_title( $post );
?>

<div id="tribe-events-event-<?php echo esc_attr( $event_id ); ?>" class="<?php tribe_events_event_classes() ?>" data-tribejson='<?php echo esc_attr( tribe_events_template_data( $post ) ); ?>'>
	<h3 class="tribe-events-month-event-title entry-title summary"><a href="<?php echo esc_url( $link ) ?>" class="url"><?php grace_church_show_layout( $title) ?></a></h3>
</div><!-- #tribe-events-event-# -->

