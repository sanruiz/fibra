<?php
/**
 * Block Partial: randomInfoFullscreen
 *
 * Full-screen random information
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'random_info_fullscreen_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('randomInfoFullscreen')
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

<?php if ( get_query_var( 'soma_block_content' )['data'] && get_query_var( 'soma_block_content' )['image'] ) : ?>
<section class="randominfofullscreen-partial-c09bb0">
	<?php
		usort(
			get_query_var( 'soma_block_content' )['data'],
			function ( $a, $b ) {
				return $b['static'] <=> $a['static'];
			}
		);
	?>

	<div class="image">
		<img src="<?php echo esc_url( get_query_var( 'soma_block_content' )['image']['url'] ); ?>" alt="<?php echo esc_attr( get_query_var( 'soma_block_content' )['image']['alt'] ); ?>">
	</div>

	<?php $wall = ( count( get_query_var( 'soma_block_content' )['data'] ) < 4 ) ? count( get_query_var( 'soma_block_content' )['data'] ) : 4; ?>
	<div class="data-container">
		<?php for ( $i = 1; $i <= $wall; $i++ ) : ?>
			<div class="item">
				<div class="value"><?php echo esc_html( get_query_var( 'soma_block_content' )['data'][ ( $i - 1 ) ]['value'] ); ?></div>
				<div class="label"><?php echo esc_html( get_query_var( 'soma_block_content' )['data'][ ( $i - 1 ) ]['label'] ); ?></div>
			</div>
		<?php endfor ?>
	</div>
</section>
<?php endif; ?>
