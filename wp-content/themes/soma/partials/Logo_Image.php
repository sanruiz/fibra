<?php
/**
 * Block Partial: Logo_Image
 *
 * Logo with image layout
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'logo_image_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Logo_Image')
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

<section class="logo-image-partial-8bd138">
	<div class="container">
		<div class="content">
			<div class="logo">
				<svg width="106px" height="109px" viewBox="0 0 106 109" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<g transform="translate(-79.000000, -2883.000000)">
							<g transform="translate(79.000000, 2883.273143)">
								<polygon fill="#171717" points="106 90.384203 90.0913455 43.7074192 75.1941215 0 30.5243463 0 0 89.5594954 0 107.752066 19.9512483 107.752066 47.9584883 24.654692 57.7589368 24.654692 85.7651341 107.752066 106 107.752066"></polygon> 
							</g>
						</g>
					</g>
				</svg>
			</div>
			<div class="text">
				<?php if ( get_query_var( 'soma_block_content' )['image'] ) : ?>
					<div class="image">
						<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image']['alt'] ); ?>">
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
					<div class="title">
						<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
					<div class="two-columns">
						<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></p>
					</div>
					<div class="one-columns">
						<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
					
