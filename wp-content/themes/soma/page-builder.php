<?php
/**
 * Page Builder Template
 *
 * Renders ACF flexible content blocks using the PSR-4 PageBuilder system.
 *
 * @package    Soma
 * @subpackage Templates
 * @since      3.0.0
 *
 * Block data is passed to partials via WordPress query vars:
 * - get_query_var('soma_block_counter') - Block index
 * - get_query_var('soma_block_content') - Block content array
 * - get_query_var('soma_block_layout')  - Block layout name
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get blocks from ACF.
$soma_blocks = get_field( 'soma_blocks' );

// Render using PSR-4 system.
if ( class_exists( '\Soma\PageBuilder\BlockRenderer' ) ) {
	$renderer = \Soma\PageBuilder\BlockRenderer::instance();
	$renderer->render( $soma_blocks );
} else {
	// PSR-4 system not loaded - check theme initialization.
	if ( function_exists( 'soma_log_error' ) ) {
		soma_log_error( 'PageBuilder: BlockRenderer class not found. Check theme initialization.' );
	}
	echo '<!-- PageBuilder Error: PSR-4 system not loaded -->';
}
