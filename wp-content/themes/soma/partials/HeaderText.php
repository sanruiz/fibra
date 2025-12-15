<?php
/**
 * Block Partial: HeaderText
 *
 * Header text block
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'header_text_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('HeaderText')
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

<section class="headertext-partial-40964d">
	<div class="container">
		<div class="content">
			<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
				<h2><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h2>
			<?php endif; ?>
			<?php if ( get_query_var( 'soma_block_content' )['subtitle'] ) : ?>
				<h4><?php echo esc_html( get_query_var( 'soma_block_content' )['subtitle'] ); ?></h4>
			<?php endif; ?>
			<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
				<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
