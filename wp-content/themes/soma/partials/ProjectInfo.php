<?php
/**
 * Block Partial: ProjectInfo
 *
 * Project information block
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'project_info_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('ProjectInfo')
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

<section class="projectinfo-partial-dcffdb">
	<div class="container">
		<div class="content">
			<div class="description">
				<?php if ( get_query_var( 'soma_block_content' )['column_1'] ) : ?>
				<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['column_1'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="info_1">
				<?php if ( get_query_var( 'soma_block_content' )['column_2'] ) : ?>
				<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['column_2'] ); ?></p>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['designed'] ) : ?>

						<?php if ( get_query_var( 'soma_block_content' )['designed']['type'] === 'Link' && ! empty( get_query_var( 'soma_block_content' )['designed']['designed_by_link'] ) ) : ?>   
								<div class="designed-by">
									<p><br><?php esc_html_e( 'Designed by', 'soma' ); ?> <br></p>
									<u>
										<a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['designed']['designed_by_link']['url'] ); ?>" target="<?php echo esc_attr( get_query_var( 'soma_block_content' )['designed']['designed_by_link']['target'] ); ?>">
											<?php echo esc_html( get_query_var( 'soma_block_content' )['designed']['designed_by_link']['title'] ); ?>
										</a>
									</u>
								</div>
						<?php elseif ( get_query_var( 'soma_block_content' )['designed']['type'] === 'Text' ) : ?>
								<div class="designed-by">
									<p><br><?php esc_html_e( 'Designed by', 'soma' ); ?> <br></p>
								<u><?php echo esc_html( get_query_var( 'soma_block_content' )['designed']['designed_by'] ); ?></u> 
								</div>
						<?php else : ?>
							
						<?php endif ?>       
				<?php endif; ?>
			</div>
			<div class="info_2">
				<?php if ( get_query_var( 'soma_block_content' )['column_3'] ) : ?>
				<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['column_3'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( get_query_var( 'soma_block_content' )['designed'] ) && get_query_var( 'soma_block_content' )['designed']['type'] === 'Link' && ! empty( get_query_var( 'soma_block_content' )['designed']['designed_by_link'] ) ) : ?>   
						<div class="designed-by">
						<p><br><?php esc_html_e( 'Designed by', 'soma' ); ?> <br></p>
						<u><a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['designed']['designed_by_link']['url'] ); ?>" target="<?php echo esc_attr( get_query_var( 'soma_block_content' )['designed']['designed_by_link']['target'] ); ?>">
							<?php echo esc_html( get_query_var( 'soma_block_content' )['designed']['designed_by_link']['title'] ); ?>
								</a>
							</u>
						</div>
				<?php elseif ( ! empty( get_query_var( 'soma_block_content' )['designed'] ) && get_query_var( 'soma_block_content' )['designed']['type'] === 'Text' ) : ?>
						<div class="designed-by">
							<p><br><?php esc_html_e( 'Designed by', 'soma' ); ?> <br></p>
							<u><?php echo esc_html( get_query_var( 'soma_block_content' )['designed']['designed_by'] ); ?></u> 
						</div>
				<?php else : ?>
				<?php endif ?>  
			</div>
			<div class="link">
				<?php if ( get_query_var( 'soma_block_content' )['link'] ) : ?>
					<a href="<?php echo esc_url( get_query_var( 'soma_block_content' )['link']['url'] ); ?>" target="<?php echo esc_attr( get_query_var( 'soma_block_content' )['link']['target'] ); ?>">
						<?php echo esc_html( get_query_var( 'soma_block_content' )['link']['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
