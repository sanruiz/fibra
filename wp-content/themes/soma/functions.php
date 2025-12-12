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
 * Initialize Theme (PSR-4 Architecture)
 *
 * Load the main theme class if autoloader is available.
 * This will replace the legacy includes below in future versions.
 */
if ( class_exists( 'Soma\Core\Theme' ) ) {
	\Soma\Core\Theme::instance();
}

/**
 * Legacy Theme Includes (Temporary - Pending Migration)
 *
 * These files are being phased out as components are migrated to PSR-4.
 * Migrated to PSR-4 in previous phases:
 * - theme-config.php → Migrated in Phase 5 (\Soma\Assets, \Soma\Navigation, \Soma\Admin)
 * - post-types.php → Migrated in Phase 2 (\Soma\PostTypes\Loader)
 * - endpoints.php → Migrated in Phase 2 (\Soma\API\Loader)
 * - cf7-validations.php → Migrated in Phase 2 (\Soma\CF7\Loader)
 */
$soma_theme = array(
	'/taxonomies.php',      // TODO: Migrate to \Soma\Taxonomies\Loader in future phase.
);
foreach ( $soma_theme as $file ) {
	require_once __DIR__ . '/inc' . $file;
}

// // Clear any existing scheduled events
// wp_clear_scheduled_hook('update_stock_data_event');

// // Schedule the event again
// if (!wp_next_scheduled('update_stock_data_event')) {
//     wp_schedule_event(time(), 'three_hours', 'update_stock_data_event');
// }
