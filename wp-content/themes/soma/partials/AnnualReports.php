<?php
/**
 * Block Partial: AnnualReports
 *
 * Annual reports listing
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'annual_reports_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('AnnualReports')
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

<?php if ( get_query_var( 'soma_block_content' )['category'] ) : ?>
<section class="annualreports-partial-5d3457 <?php echo esc_attr( get_query_var( 'soma_block_content' )['style'] ); ?>" 
	data-last-year="<?php echo esc_attr( get_query_var( 'soma_block_content' )['latest_year_preselect'] ? '1' : '0' ); ?>" 
	data-category="<?php echo esc_attr( get_query_var( 'soma_block_content' )['category'] ); ?>" 
	data-lang="<?php echo esc_attr( wpm_get_language() ); ?>">
	<div class="container">
		<div class="content">
			<div class="year-list">
				<div class="mobile-title"><?php echo ( wpm_get_language() === 'en' ) ? 'Filter by Year' : 'Filtrar por año'; ?> <span></span></div>
				<div class="years">
					<!-- Ajax -->
				</div>
				<div class="all">
					<a><?php echo ( wpm_get_language() === 'en' ) ? 'See All' : 'Ver Todos'; ?></a>
				</div>
			</div>
			<div class="documents">
				<div class="document-list">
					<!-- Ajax -->
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
