<?php
/**
 * Block Partial: ProjectContactInfo
 *
 * Project contact information
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'project_contact_info_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('ProjectContactInfo')
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
<section class="projectcontactinfo-partial-36c8d9 <?php echo esc_attr( get_query_var( 'soma_block_content' )['dark_style'] ? 'dark-style' : '' ); ?>">
	<div class="container">
		<div class="content">
			<div class="column">
				<?php echo wp_kses_post( get_query_var( 'soma_block_content' )['column_1'] ); ?>
			</div>
			<div class="column">
				<?php echo wp_kses_post( get_query_var( 'soma_block_content' )['column_2'] ); ?>
			</div>
			<div class="column">
				<?php echo wp_kses_post( get_query_var( 'soma_block_content' )['column_3'] ); ?>
			</div>
		</div>
	</div>
</section>
					
