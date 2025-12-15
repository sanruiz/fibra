<?php
/**
 * Block Partial: Image_Text
 *
 * Image with text content
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'image_text_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Image_Text')
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

<section class="image-text-partial-9cde64">
	<div class="container">
		<div class="content">
			<div class="image">
				<?php if ( get_query_var( 'soma_block_content' )['image'] ) : ?>
					<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image']['alt'] ); ?>">
				<?php endif; ?>
			</div>
			<div class="text-container">
				<?php if ( get_query_var( 'soma_block_content' )['logo'] ) : ?>
					<div class="logo">
						<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['logo']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['logo']['alt'] ); ?>">
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
					<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
					<div class="text">
						<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['link'] ) : ?>
					<div class="link">
						<a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['link']['url'] ); ?>" target="<?php echo esc_attr( get_query_var( 'soma_block_content' )['link']['target'] ); ?>"><?php echo esc_html( get_query_var( 'soma_block_content' )['link']['title'] ); ?></a>
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['file'] ) : ?>
					<div class="file">
						<a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['file']['url'] ); ?>" target="_blank">
							<?php echo do_shortcode( "[wpm_translate]{get_query_var('soma_block_content')['file']['title']}[/wpm_translate]" ); ?>
							<svg width="17px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<g transform="translate(-733.000000, -553.000000)">
										<g transform="translate(734.000000, 553.052734)">
											<g transform="translate(22.011719, 21.437902) rotate(-90.000000) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
												<line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" id="Line-2" stroke-width="2" stroke-linecap="square"></line>
												<polygon id="Shape" stroke-width="0.5" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
											</g>
										</g>
									</g>
								</g>
							</svg>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
					