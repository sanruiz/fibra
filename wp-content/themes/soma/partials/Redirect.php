<?php
/**
 * Block Partial: Redirect
 *
 * Page redirect functionality
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'redirect_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Redirect')
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
<section class="redirect-partial-39759a">
	<?php if ( get_query_var( 'soma_block_content' )['redirect_to'] ) : ?>
		<meta http-equiv="refresh" content="0; url=<?php echo esc_url( get_the_permalink( get_query_var( 'soma_block_content' )['redirect_to'] ) ); ?>">
	<?php endif; ?>
</section>
					