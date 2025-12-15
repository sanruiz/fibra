<?php
/**
 * Block Partial: FeaturedText
 *
 * Featured/highlighted text content
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'featured_text_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('FeaturedText')
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

<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
<section class="featuredtext-partial-c8599e">
	<div class="container">
		<h3><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></h3>
	</div>
</section>
<?php endif; ?>
