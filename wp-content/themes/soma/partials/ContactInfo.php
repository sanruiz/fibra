<?php
/**
 * Block Partial: ContactInfo
 *
 * Contact information display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'contact_info_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('ContactInfo')
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

<section class="contactinfo-partial-b5328a">
	<div class="container">
		<div class="content">
			<div class="column-1">
				<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
				<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['address'] ) : ?>
					<div class="address">
					<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['address'] ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['link'] ) : ?>
					<div class="link">
					<a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['link']['url'] ); ?>" target="<?php echo esc_attr( get_query_var( 'soma_block_content' )['link']['target'] ); ?>"><?php echo esc_html( get_query_var( 'soma_block_content' )['link']['title'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>
			<div class="column-2">
				<?php if ( get_query_var( 'soma_block_content' )['contact_info'] ) : ?>
					<div class="contact-info">
						<?php foreach ( get_query_var( 'soma_block_content' )['contact_info'] as $key => $item ) : ?>
							<?php if ( $item['link'] ) : ?>
								<div class="item">
								<div class="title"><?php echo esc_html( $item['title'] ); ?></div>
								<div class="link">
									<a href="<?php echo esc_url( $item['link']['url'] ); ?>" target="<?php echo esc_attr( $item['link']['target'] ); ?>"><?php echo esc_html( $item['link']['title'] ); ?></a>
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
					
