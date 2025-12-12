<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme includes
 */
$soma_theme = array(
  '/theme-config.php',            // Theme general config
  '/post-types.php',              // Register post types
  '/taxonomies.php',              // Register taxonomies
  '/endpoints.php',               // Register endpoints
  '/cf7-validations.php',         // Contact Form 7 Custom Validations
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
