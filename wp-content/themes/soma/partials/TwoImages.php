<?php
/**
 * Block Partial: TwoImages
 *
 * Two-image layout block
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'two_images_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('TwoImages')
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

<section class="twoimages-partial-efb0cd">
	<div class="container">
		<div class="content">
			<div class="image-1">
				<?php if ( get_query_var( 'soma_block_content' )['featured_text'] ) : ?>
				<h4><?php echo esc_html( get_query_var( 'soma_block_content' )['featured_text'] ); ?></h4>
				<?php endif; ?>
					<div class="image-2 tablet">
					<?php if ( get_query_var( 'soma_block_content' )['image_2'] ) : ?>
					<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image_2']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image_2']['alt'] ); ?>">
					<?php endif; ?>
					</div>
				<?php if ( get_query_var( 'soma_block_content' )['image_1'] ) : ?>
				<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image_1']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image_1']['alt'] ); ?>">
				<?php endif; ?>
			</div>
			<div class="image-2 desk">
				<?php if ( get_query_var( 'soma_block_content' )['image_2'] ) : ?>
				<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image_2']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image_2']['alt'] ); ?>">
				<?php endif; ?>
			</div>
			<div class="text">
				<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
				<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
				<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></p>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['link'] ) : ?>
					<div class="link">
					<a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['link']['url'] ); ?>" target="<?php echo esc_attr( get_query_var( 'soma_block_content' )['link']['target'] ); ?>"><?php echo esc_html( get_query_var( 'soma_block_content' )['link']['title'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>