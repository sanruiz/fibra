<?php
/**
 * Block Partial: FibrasomaHomeEvents
 *
 * Events section for Fibrasoma homepage
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'fibrasoma_home_events_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('FibrasomaHomeEvents')
 *
 * Example Usage:
 * <code>
 * $counter = get_query_var('soma_block_counter');
 * $content = get_query_var('soma_block_content');
 * $layout  = get_query_var('soma_block_layout');
 * </code>
 *
 * @see \Soma\PageBuilder\BlockRenderer
 * @see \Soma\PageBuilder\BlockRegistry
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


function printEvent( $id ) {

	$arrow    = '<svg width="18px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-733.000000, -553.000000)"><g transform="translate(734.000000, 553.052734)"><g class="color transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)"><line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="2" stroke-linecap="square"></line><polygon stroke-width="1" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon></g></g></g></g></svg>';
	$calendar = '<svg width="34px" height="33px" viewBox="0 0 34 33"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-529.000000, -338.000000)"><g transform="translate(530.000000, 338.936000)"><polygon stroke="#000000" stroke-width="1.21196244" points="24.037255 30.4801685 0.201993739 30.4801685 0.201993739 3.00901994 32.1170045 3.00901994 32.1170045 22.1984252"></polygon><polygon fill="#000000" points="6.26180592 14.010003 9.71024304 14.010003 9.71024304 12.523329 6.26180592 12.523329"></polygon><polygon fill="#000000" points="14.6372743 14.010003 18.0861154 14.010003 18.0861154 12.523329 14.6372743 12.523329"></polygon><polygon fill="#000000" points="23.0127427 14.010003 26.4611798 14.010003 26.4611798 12.523329 23.0127427 12.523329"></polygon><polygon fill="#000000" points="6.26180592 18.4070027 9.71024304 18.4070027 9.71024304 16.9207327 6.26180592 16.9207327"></polygon><polygon fill="#000000" points="14.6372743 18.4070027 18.0861154 18.4070027 18.0861154 16.9207327 14.6372743 16.9207327"></polygon><polygon fill="#000000" points="23.0127427 18.4070027 26.4611798 18.4070027 26.4611798 16.9207327 23.0127427 16.9207327"></polygon><polygon fill="#000000" points="6.26180592 22.8044064 9.71024304 22.8044064 9.71024304 21.3177325 6.26180592 21.3177325"></polygon><polygon fill="#000000" points="14.6372743 22.8044064 18.0861154 22.8044064 18.0861154 21.3177325 14.6372743 21.3177325"></polygon><polygon fill="#000000" points="6.26180592 1.48639113 9.71024304 1.48639113 9.71024304 0.000121196244 6.26180592 0.000121196244"></polygon><polygon fill="#000000" points="14.6368703 1.48639113 18.0857114 1.48639113 18.0857114 0.000121196244 14.6368703 0.000121196244"></polygon><polygon fill="#000000" points="23.0127427 1.48639113 26.4611798 1.48639113 26.4611798 0.000121196244 23.0127427 0.000121196244"></polygon></g></g></g></svg>';

	$content            = get_field( 'event_info', $id );
	$formated_init_date = $content['end_date'] ? soma_translate_date( date( 'M j, Y', $content['init_date'] ), 'short' ) : soma_translate_date( date( 'M j, Y', $content['init_date'] ) );
	$formated_end_date  = $content['end_date'] ? soma_translate_date( date( 'M j, Y', $content['end_date'] ), 'short' ) : null;
	$formated_date      = $formated_end_date ? $formated_init_date . ' - ' . $formated_end_date : $formated_init_date;

	$mainFile = ( wpm_get_language() === 'en' ) ? $content['file'] : $content['file_es'];

	if ( $mainFile ) {
		$link = $mainFile ? "<a target='_BLANK' href='{$mainFile['url']}'>{$content['file_label']}{$arrow}</a>" : '';

		$output = "<div class='event'>" .
			"<div class='label'>{$content['label']}</div>" .
			"<h3>{$formated_date} {$calendar}</h3>" .
			"<div class='description'>{$content['description']}</div>" .
			"<div class='link'>" .
				$link .
			'</div>' .
		'</div>';
	} else {
		$output = '';
	}

	return $output;
}

$events = [];

if ( get_query_var( 'soma_block_content' )['fill_mode'] === 'featured' ) {
	if ( get_query_var( 'soma_block_content' )['events'] && wpm_get_language() === 'en' ) {
		foreach ( get_query_var( 'soma_block_content' )['events'] as $key => $event ) {
			if ( $event ) {
				$events[] = $event['event']->ID;
			}
		}
	}
	if ( get_query_var( 'soma_block_content' )['events_es'] && wpm_get_language() === 'es' ) {
		foreach ( get_query_var( 'soma_block_content' )['events_es'] as $key => $event ) {
			if ( $event ) {
				$events[] = $event['event']->ID;
			}
		}
	}
} else {
	$eventsQuery = get_posts(
		[
			'numberposts' => -1,
			'post_type'   => 'events',
			'post_status' => array( 'publish' ),
			'order'       => 'ASC',
			'meta_key'    => 'event_info_init_date',
			'orderby'     => 'meta_value',
		]
	);
	if ( $eventsQuery ) {
		$counter = 1;
		foreach ( $eventsQuery as $key => $item ) {
			if ( $counter <= 4 ) {
				$content = get_field( 'event_info', $item->ID );
				if ( ( (int) $content['init_date'] + 86400 ) > (int) date( 'U' ) || ( (int) $content['end_date'] + 86400 ) > (int) date( 'U' ) ) {
					$events[] = $item->ID;
					++$counter;
				}
			}
		}
	}
}
?>

<section class="fibrasomahomeevents-partial-c9ff47">
	<div class="container">
		<?php if ( get_query_var( 'soma_block_content' )['number'] ) : ?>
			<div class="number">
				<h2><?php echo esc_html( get_query_var( 'soma_block_content' )['number'] ); ?></h2>
			</div>
		<?php endif; ?>
		<div class="header">
			<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
				<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
			<?php endif; ?>
			<?php if ( get_query_var( 'soma_block_content' )['link'] ) : ?>
				<a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['link']['url'] ); ?>" target="_blank"><?php echo esc_html( get_query_var( 'soma_block_content' )['link']['title'] ); ?></a>
			<?php endif; ?>
		</div>
		<div class="events">
			<?php if ( $events ) : ?>
				<div class="event-list">
					<?php foreach ( $events as $key => $event ) : ?>
						<?php echo wp_kses_post( printEvent( $event ) ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
					