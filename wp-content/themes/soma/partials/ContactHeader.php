<?php
/**
 * Block Partial: ContactHeader
 *
 * Header for contact pages
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'contact_header_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('ContactHeader')
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

<section class="contactheader-partial-a07d41">
	<div class="container">
		<div class="content">
			<div class="text">
				<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
					<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="info">
				<?php if ( get_query_var( 'soma_block_content' )['info'] ) : ?>
					<?php foreach ( get_query_var( 'soma_block_content' )['info'] as $key => $item ) : ?>
						<div class="item">
							<?php if ( $item['label'] ) : ?>
							<p><?php echo esc_html( $item['label'] ); ?></p>
						<?php endif; ?>
						<?php if ( $item['link'] ) : ?>
							<a href="<?php echo esc_url( $item['link']['url'] ); ?>" target="<?php echo esc_attr( $item['link']['target'] ); ?>">
								<?php echo esc_html( $item['link']['title'] ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
					
