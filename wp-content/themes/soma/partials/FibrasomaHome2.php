<?php
/**
 * Block Partial: FibrasomaHome2
 *
 * Fibrasoma homepage section 2
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'fibrasoma_home_2_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('FibrasomaHome2')
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


$arrow = '
<svg width="16px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" fill="none" fill-rule="evenodd">
        <g transform="translate(1.000000, 0.000000)" stroke="#171717">
            <g transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="3" stroke-linecap="square"></line>
                <polygon stroke-width="2" fill="#171717" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
            </g>
        </g>
    </g>
</svg>
';
?>

<section class="fibrasomahome2-partial-99fd7f">
	<div class="container">
		<div class="content">
			<div class="text">
				<?php if ( get_query_var( 'soma_block_content' )['number'] ) : ?>
					<div class="number">
					<h2><?php echo esc_html( get_query_var( 'soma_block_content' )['number'] ); ?></h2>
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
					<div class="title">
					<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
					</div>
				<?php endif; ?>
			<?php $extra_file = ( wpm_get_language() === 'en' ) ? get_query_var( 'soma_block_content' )['file'] : get_query_var( 'soma_block_content' )['file_es']; ?>
			<?php if ( $extra_file && get_query_var( 'soma_block_content' )['file_label'] ) : ?>
			<a class="extra-file" href="<?php echo esc_url( $extra_file['url'] ); ?>" target="_blank">
					<?php echo esc_html( get_query_var( 'soma_block_content' )['file_label'] ) . wp_kses_post( $arrow ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="image">
				<?php if ( get_query_var( 'soma_block_content' )['image'] ) : ?>
				<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image']['alt'] ); ?>">
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['documents'] ) : ?>
				<div class="documents <?php echo esc_attr( ( count( get_query_var( 'soma_block_content' )['documents'] ) > 1 ) ? '' : 'separator-hidden' ); ?>">
					<?php foreach ( get_query_var( 'soma_block_content' )['documents'] as $key => $item ) : ?>
						<?php $content = get_field( 'document_content', $item ); ?>

						<?php $main_file = ( wpm_get_language() === 'en' ) ? $content['file'] : $content['file_es']; ?>
						<?php if ( $main_file ) : ?>
						<div class="item">
						<a href="<?php echo esc_url( $main_file['url'] ); ?>" target="_blank">
								<div class="file-title">
								<span><?php echo esc_html( get_the_title( $item ) ) . wp_kses_post( $arrow ); ?></span>
								</div>
								<div class="label">
									<?php echo esc_html( $content['label'] ); ?>
									</div>
								</a>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>  
			</div>
		</div>
	</div>
</section>
					
