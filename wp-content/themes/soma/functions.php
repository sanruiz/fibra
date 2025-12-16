<?php
/**
 * Soma Theme - Main Functions File
 *
 * @package Soma
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Composer Autoloader
 *
 * Load PSR-4 autoloader for modern PHP classes.
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Load Theme Text Domain for Translations
 *
 * Enables internationalization support for the theme.
 * Translations should be placed in the /languages directory.
 *
 * @since 3.1.0
 */
function soma_load_textdomain(): void {
	load_theme_textdomain( 'soma', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'soma_load_textdomain' );

/**
 * Initialize Theme (PSR-4 Architecture)
 *
 * Load the main theme class if autoloader is available.
 * This will replace the legacy includes below in future versions.
 */
if ( class_exists( 'Soma\Core\Theme' ) ) {
	\Soma\Core\Theme::instance();
}

// // Clear any existing scheduled events
// wp_clear_scheduled_hook('update_stock_data_event');

// // Schedule the event again
// if (!wp_next_scheduled('update_stock_data_event')) {
// wp_schedule_event(time(), 'three_hours', 'update_stock_data_event');
// }
