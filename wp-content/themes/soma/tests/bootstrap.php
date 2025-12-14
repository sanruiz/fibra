<?php
/**
 * PHPUnit Bootstrap for Soma Theme Tests
 */

// Define testing environment constant.
if ( ! defined( 'SOMA_TESTING' ) ) {
	define( 'SOMA_TESTING', true );
}

// Set up WordPress tests environment.
$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run tests/bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Create mock classes for testing.
if ( ! class_exists( 'WP_Customize_Manager' ) ) {
	class WP_Customize_Manager {
		private $sections = [];
		private $settings = [];
		private $controls = [];

		public function add_section( $id, $args = [] ) {
			$this->sections[ $id ] = (object) array_merge(
				[
					'title'    => '',
					'priority' => 160,
				],
				$args
			);
			return true;
		}

		public function add_setting( $id, $args = [] ) {
			$this->settings[ $id ] = (object) array_merge(
				[
					'default' => '',
					'type'    => 'theme_mod',
				],
				$args
			);
			return true;
		}

		public function add_control( $id_or_control, $args = [] ) {
			return true;
		}

		public function get_section( $id ) {
			return $this->sections[ $id ] ?? (object) [
				'title'    => 'Test Section',
				'priority' => 160,
			];
		}

		public function get_setting( $id ) {
			return $this->settings[ $id ] ?? (object) [
				'default' => '',
				'type'    => 'theme_mod',
			];
		}
	}
}

// CF7 classes will be loaded from actual plugin in test environment

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Load Advanced Custom Fields PRO for testing.
 */
function _load_acf_for_tests() {
	$_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $_tests_dir ) {
		$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
	}
	
	$wp_core_dir = dirname( $_tests_dir ) . '/wordpress';
	$acf_plugin = $wp_core_dir . '/wp-content/plugins/advanced-custom-fields-pro/acf.php';
	$acf_helper = $wp_core_dir . '/wp-content/plugins/advanced-custom-fields-pro/acf-test-helper.php';
	
	if ( file_exists( $acf_plugin ) ) {
		require_once $acf_plugin;
		
		// Load test helper if available
		if ( file_exists( $acf_helper ) ) {
			require_once $acf_helper;
		}
		
		// Trigger ACF initialization
		if ( function_exists( 'acf' ) && ! did_action( 'acf/init' ) ) {
			do_action( 'acf/init' );
		}
	}
}

/**
 * Load Contact Form 7 for testing.
 */
function _load_cf7_for_tests() {
	$_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $_tests_dir ) {
		$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
	}
	
	$wp_core_dir = dirname( $_tests_dir ) . '/wordpress';
	$cf7_plugin = $wp_core_dir . '/wp-content/plugins/contact-form-7/wp-contact-form-7.php';
	$cf7_helper = $wp_core_dir . '/wp-content/plugins/contact-form-7/cf7-test-helper.php';
	
	if ( file_exists( $cf7_plugin ) ) {
		require_once $cf7_plugin;
		
		// Load test helper if available
		if ( file_exists( $cf7_helper ) ) {
			require_once $cf7_helper;
		}
		
		// Trigger CF7 initialization
		if ( ! did_action( 'wpcf7_init' ) ) {
			do_action( 'wpcf7_init' );
		}
	}
}

/**
 * Manually load the theme being tested.
 */
function _manually_load_theme() {
	// Define theme paths.
	$theme_dir = dirname( __DIR__ );

	// Set up theme directory constants.
	define( 'SOMA_THEME_DIR', $theme_dir );
	define( 'SOMA_THEME_URL', 'http://example.org/wp-content/themes/soma' );

	// Register the theme directory.
	register_theme_directory( dirname( $theme_dir ) );

	// Switch to the theme.
	switch_theme( 'soma' );

	// Load theme functions.
	require $theme_dir . '/functions.php';
}

// Load plugins before theme
tests_add_filter( 'muplugins_loaded', '_load_acf_for_tests', 5 );
tests_add_filter( 'muplugins_loaded', '_load_cf7_for_tests', 5 );
tests_add_filter( 'muplugins_loaded', '_manually_load_theme' );

/**
 * Set up global WordPress objects for testing.
 */
function _setup_test_globals() {
	global $wp_query, $wp_rewrite, $wp_the_query;

	// Create mock WP_Query if not exists.
	if ( ! $wp_query && class_exists( 'WP_Query' ) ) {
		$wp_query     = new WP_Query();
		$wp_the_query = $wp_query;
		$wp_query->query_vars = [];
		$wp_query->posts      = [];
		$wp_query->post_count = 0;
	}

	// Create mock WP_Rewrite if not exists.
	if ( ! $wp_rewrite && class_exists( 'WP_Rewrite' ) ) {
		$wp_rewrite = new WP_Rewrite();
	}
}

tests_add_filter( 'wp_loaded', '_setup_test_globals', 1 );
tests_add_filter( 'init', '_setup_test_globals', 1 );

/**
 * Load Elementor plugin for integration tests
 */
function _load_elementor_for_tests() {
	$_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $_tests_dir ) {
		$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
	}
	
	$wp_core_dir = dirname( $_tests_dir ) . '/wordpress';
	$elementor_plugin = $wp_core_dir . '/wp-content/plugins/elementor/elementor.php';
	$elementor_helper = $wp_core_dir . '/wp-content/plugins/elementor/elementor-test-helper.php';
	
	if ( file_exists( $elementor_plugin ) ) {
		require_once $elementor_plugin;
		
		// Load test helper if available
		if ( file_exists( $elementor_helper ) ) {
			require_once $elementor_helper;
		}
		
		// Trigger Elementor loaded action
		if ( did_action( 'plugins_loaded' ) && ! did_action( 'elementor/loaded' ) ) {
			do_action( 'elementor/loaded' );
		}
	}
}

// Load Elementor after ACF and CF7
tests_add_filter( 'muplugins_loaded', '_load_elementor_for_tests', 5 );

/**
 * Initialize theme after WordPress is loaded.
 */
function _init_theme_for_tests() {
	// Initialize theme instance to ensure it's loaded.
	if ( class_exists( 'Soma\\Core\\Theme' ) ) {
		\Soma\Core\Theme::instance();
	}
}

tests_add_filter( 'after_setup_theme', '_init_theme_for_tests', 1 );

// Load Yoast PHPUnit Polyfills before WordPress test bootstrap.
if ( file_exists( dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php' ) ) {
	require_once dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';
}

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

// Ensure global wp_query is available immediately.
global $wp_query, $wp_the_query;
if ( ! $wp_query && class_exists( 'WP_Query' ) ) {
	$wp_query     = new WP_Query();
	$wp_the_query = $wp_query;
}
