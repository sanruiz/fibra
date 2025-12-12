<?php
/**
 * PHPUnit Bootstrap for Soma Theme Tests
 */

// Set up WordPress tests environment.
$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run tests/bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Load Simple Mocks FIRST before anything else.
$mocks_file = __DIR__ . '/Mocks/SimpleMocks.php';
if ( file_exists( $mocks_file ) ) {
	require_once $mocks_file;
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

if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
	class WPCF7_ContactForm {
		private $id = 0;
		private $title = 'Test Form';

		public function __construct( $id = 0, $title = 'Test Form' ) {
			$this->id = $id;
			$this->title = $title;
		}

		public function id() {
			return $this->id;
		}

		public function title() {
			return $this->title;
		}
	}
}

if ( ! class_exists( 'WPCF7_Submission' ) ) {
	class WPCF7_Submission {
		private static $instance = null;
		private $posted_data = [];

		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function get_posted_data() {
			return $this->posted_data;
		}

		public function set_posted_data( $data ) {
			$this->posted_data = $data;
		}
	}
}

if ( ! class_exists( 'WPCF7_Validation' ) ) {
	class WPCF7_Validation {
		private $invalid_fields = [];

		public function invalidate( $tag, $message ) {
			$name = is_object( $tag ) ? $tag->name : $tag;
			$this->invalid_fields[ $name ] = $message;
		}

		public function is_valid( $name = null ) {
			if ( $name === null ) {
				return empty( $this->invalid_fields );
			}
			return ! isset( $this->invalid_fields[ $name ] );
		}
	}
}

if ( ! class_exists( 'WPCF7_FormTag' ) ) {
	class WPCF7_FormTag {
		public $name = '';
		public $type = '';

		public function __construct( $name = '', $type = '' ) {
			$this->name = $name;
			$this->type = $type;
		}
	}
}

if ( ! class_exists( 'WPCF7' ) ) {
	class WPCF7 {
		// Mock WPCF7 main class for should_load() checks.
	}
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

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
	
	if ( file_exists( $elementor_plugin ) ) {
		require_once $elementor_plugin;
		
		// Trigger Elementor loaded action
		if ( did_action( 'plugins_loaded' ) && ! did_action( 'elementor/loaded' ) ) {
			do_action( 'elementor/loaded' );
		}
	}
}

tests_add_filter( 'muplugins_loaded', '_load_elementor_for_tests' );

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
