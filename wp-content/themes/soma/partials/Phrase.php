<?php
/**
 * Block Partial: Phrase
 *
 * Highlighted quote or phrase block
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'phrase_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Phrase')
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

<section class="phrase-partial-3feb8a style-<?php echo esc_attr( get_query_var( 'soma_block_content' )['style'] ); ?>">
	<div class="container">
		<div class="content">
			<?php if ( get_query_var( 'soma_block_content' )['style'] === 'withimage' ) : ?>
				<div class="image">
					<?php if ( get_query_var( 'soma_block_content' )['image'] ) : ?>
						<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image']['alt'] ); ?>">
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="phrase">
				<h4><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['phrase'] ); ?></h4>
				<?php if ( get_query_var( 'soma_block_content' )['name'] ) : ?>
					<div class="name"><?php echo esc_html( get_query_var( 'soma_block_content' )['name'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
					