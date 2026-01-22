<?php
/**
 * Single Portfolio Template
 *
 * Elementor-first approach for portfolio single pages.
 * All content (hero, gallery, technical specs, sustainability, related projects)
 * is managed via Elementor widgets for maximum flexibility. This template does
 * not use ACF flexible content or specific ACF fields; the content structure is
 * controlled entirely through Elementor.
 *
 * @package Soma
 * @since   3.1.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

the_content();
