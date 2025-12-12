<?php
/**
 * PHPUnit Bootstrap File
 *
 * @package Soma
 */

// Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Define test constants
define('SOMA_TESTS_DIR', __DIR__);
define('SOMA_THEME_DIR', dirname(__DIR__));

// Mock WordPress functions if WP test suite is not available
if (!function_exists('add_action')) {
    require_once __DIR__ . '/Mocks/SimpleMocks.php';
}
