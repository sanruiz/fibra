<?php
/**
 * Block Partial: Documents
 *
 * Documents list display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'documents_list_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Documents')
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
<section class="documents-partial-15af9d style-<?php echo esc_attr( get_query_var( 'soma_block_content' )['style'] ); ?>" 
	data-order-by-date="<?php echo esc_attr( get_query_var( 'soma_block_content' )['order_by_custom_date'] ? 1 : 0 ); ?>" 
	data-category="<?php echo esc_attr( get_query_var( 'soma_block_content' )['category'] ); ?>" 
	data-posts-per-page="<?php echo esc_attr( get_query_var( 'soma_block_content' )['posts_per_page'] ); ?>" 
	data-lang="<?php echo esc_attr( wpm_get_language() ); ?>"
	>
	<div class="container">
		<div class="content">
			<!-- Ajax -->
		</div>
		<div class="loader-container">
			<!-- <div class="loader"><div></div><div></div><div></div></div> -->
			<span class="loading">Loading more</span>
		</div>
	</div>
</section>
