<?php
/**
 * Block Partial: Events
 *
 * Events listing display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'events_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Events')
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


?>

<section class="events-partial-e5e1bb" data-lang="<?php echo esc_attr( wpm_get_language() ); ?>">
	<div class="container">
		<div class="content">
			<div class="filters">
				<div class="mobile-title" onclick="$(this).toggleClass('open')"><?php echo ( wpm_get_language() === 'en' ) ? 'Filter by Month' : 'Filtrar por mes'; ?> <span></span></div>
				<div class="list">
					<!-- Ajax -->
					<div class="item active" data-filter="all"><?php echo ( wpm_get_language() === 'en' ) ? 'See All' : 'Ver Todos'; ?></div>
				</div>
			</div>
			<div class="events">
				<div class="event-list">
					<!-- Ajax -->
				</div>
			</div>
		</div>
	</div>
</section>
					