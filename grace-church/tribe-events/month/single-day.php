<?php
/**
 * Month View Single Day
 * This file contains one day in the month grid
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/month/single-day.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

$day = tribe_events_get_current_month_day();
?>

<!-- Day Header -->
<div id="tribe-events-daynum-<?php echo esc_attr($day['daynum']) ?>">

	<?php if ( $day['total_events'] > 0 && tribe_events_is_view_enabled( 'day' ) ) : ?>
		<a href="<?php echo esc_url( tribe_get_day_link( $day['date'] ) ); ?>"><?php echo esc_html($day['daynum'])?></a>
	<?php else : ?>
		<?php echo esc_html($day['daynum']) ?>
	<?php endif; ?>

</div>

<!-- Events List -->
<?php while ( $day['events']->have_posts() ) : $day['events']->the_post(); ?>
	<?php tribe_get_template_part( 'month/single', 'event' ) ?>
<?php endwhile; ?>

<!-- View More -->
<?php if ( $day['view_more'] ) : ?>
	<div class="tribe-events-viewmore">
		<?php

        $events_label = ( 1 === $day['total_events'] ) ? tribe_get_event_label_singular() : tribe_get_event_label_plural();
        $view_all_label = ( 1 === $day['total_events'] )
            ? sprintf( esc_html__( 'View %1$s %2$s', 'grace-church' ), $day['total_events'], $events_label )
            : sprintf( esc_html__( 'View All %1$s %2$s', 'grace-church' ), $day['total_events'], $events_label );

		?>
		<a href="<?php echo esc_url( $day['view_more'] ); ?>"><?php grace_church_show_layout( $view_all_label) ?> &raquo;</a>
	</div>
<?php
endif;
