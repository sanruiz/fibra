<?php
/**
 * Block Partial: randomInfo
 *
 * Random information display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'random_info_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('randomInfo')
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

<?php if ( get_query_var( 'soma_block_content' )['data'] ) : ?>
<section class="randominfo-partial-716012">
	<?php
		shuffle( get_query_var( 'soma_block_content' )['data'] );
		usort(
			get_query_var( 'soma_block_content' )['data'],
			function ( $a, $b ) {
				return $b['static'] <=> $a['static'];
			}
		);
	?>
	<div class="container">
		<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
			<div class="title">
				<h2><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h2>
			</div>
		<?php endif; ?>
		<div class="data">
			<?php $wall = ( count( get_query_var( 'soma_block_content' )['data'] ) < 6 ) ? count( get_query_var( 'soma_block_content' )['data'] ) : 6; ?>
			<?php for ( $i = 1; $i <= $wall; $i++ ) : ?>
				<div class="item">
					<div class="value"><?php echo esc_html( get_query_var( 'soma_block_content' )['data'][ ( $i - 1 ) ]['value'] ); ?></div>
					<div class="label"><?php echo esc_html( get_query_var( 'soma_block_content' )['data'][ ( $i - 1 ) ]['label'] ); ?></div>
				</div>
			<?php endfor ?>
		</div>
	</div>
</section>
<?php endif; ?>
