<?php
/**
 * Block Partial: VimeoPlayer
 *
 * Embedded Vimeo video player
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'vimeo_player_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('VimeoPlayer')
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


$cover = get_query_var( 'soma_block_content' )['cover'] ? get_query_var( 'soma_block_content' )['cover']['url'] : '';
?>

<section class="vimeoplayer-partial-8e5131 <?php echo esc_attr( get_query_var( 'soma_block_content' )['dark_style'] ? 'dark-style' : '' ); ?>" data-video-id="<?php echo esc_attr( get_query_var( 'soma_block_content' )['vimeo_id'] ); ?>" data-cover="<?php echo esc_attr( $cover ); ?>">
	<div class="container">
		<div class="content"></div>
		<?php if ( get_query_var( 'soma_block_content' )['label'] ) : ?>
			<div class="label">
				<?php echo esc_html( get_query_var( 'soma_block_content' )['label'] ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
					
