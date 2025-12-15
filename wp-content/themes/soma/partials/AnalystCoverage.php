<?php
/**
 * Block Partial: AnalystCoverage
 *
 * Analyst coverage information
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'analyst_coverage_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('AnalystCoverage')
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

<section class="analystcoverage-partial-805458">
	<div class="container">
		<?php if ( get_query_var( 'soma_block_content' )['items'] ) : ?>
			<div class="content">
				<?php foreach ( get_query_var( 'soma_block_content' )['items'] as $key => $item ) : ?>
					<div class="item">
						<?php if ( $item['title'] ) : ?>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( $item['name'] ) : ?>
						<div class="name"><?php echo esc_html( $item['name'] ); ?></div>
						<?php endif; ?>
						<?php if ( $item['phone'] ) : ?>
							<div class="phone">
							<a href="<?php echo esc_url( $item['phone']['url'] ); ?>" target="<?php echo esc_attr( $item['phone']['target'] ); ?>"><?php echo esc_html( $item['phone']['title'] ); ?></a>
							</div>
						<?php endif; ?>
						<?php if ( $item['email'] ) : ?>
							<div class="email">
							<a href="<?php echo esc_url( $item['email']['url'] ); ?>" target="<?php echo esc_attr( $item['email']['target'] ); ?>"><?php echo esc_html( $item['email']['title'] ); ?></a>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
					