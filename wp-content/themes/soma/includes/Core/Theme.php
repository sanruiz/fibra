<?php
/**
 * Main Theme Class
 *
 * Central theme initialization and component management.
 * This class serves as the entry point for the Soma theme,
 * orchestrating the loading of all components through the Loader.
 *
 * @package Soma\Core
 * @since 3.0.0
 */

namespace Soma\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Theme
 *
 * Main theme singleton that initializes all components.
 *
 * @package Soma\Core
 */
class Theme {

	/**
	 * Singleton instance
	 *
	 * @var Theme|null
	 */
	private static ?Theme $instance = null;

	/**
	 * Component loader
	 *
	 * @var Loader
	 */
	private Loader $loader;

	/**
	 * Theme directory path
	 *
	 * @var string
	 */
	private string $theme_dir;

	/**
	 * Theme directory URI
	 *
	 * @var string
	 */
	private string $theme_uri;

	/**
	 * Get singleton instance.
	 *
	 * @return Theme The singleton instance.
	 */
	public static function instance(): Theme {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->theme_dir = get_template_directory();
		$this->theme_uri = get_template_directory_uri();
		$this->loader    = Loader::instance();

		$this->init();
	}

	/**
	 * Prevent cloning of the instance.
	 *
	 * @return void
	 */
	private function __clone() {
		// Singleton pattern - no cloning.
	}

	/**
	 * Prevent unserialization of the instance.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Singleton pattern - no unserialization.
	}

	/**
	 * Initialize the theme.
	 *
	 * @return void
	 */
	private function init(): void {
		// Register components.
		$this->register_components();

		// Load all components.
		add_action( 'after_setup_theme', array( $this, 'load_components' ), 5 );
	}

	/**
	 * Register all theme components.
	 *
	 * Components will be loaded in priority order by the Loader.
	 * Lower priority numbers load first (5-50 recommended range).
	 *
	 * @return void
	 */
	private function register_components(): void {
		// Register Assets FIRST (priority 5) - CSS/JS loading.
		$this->loader->register( \Soma\Core\Assets::instance() );

		// Register Navigation (priority 8) - WordPress menus.
		$this->loader->register( \Soma\Core\Navigation::instance() );

		// Register Utilities (priority 10) - provides helper functions for all components.
		$this->loader->register( \Soma\Utils\Loader::instance() );

		// Register Taxonomies (priority 15) - before Post Types to ensure taxonomies exist first.
		$this->loader->register( \Soma\Taxonomies\Loader::instance() );

		// Register Post Types (priority 20).
		$this->loader->register( \Soma\PostTypes\Loader::instance() );

		// Register Page Builder (priority 25) - ACF flexible content system.
		$this->loader->register( \Soma\PageBuilder\Loader::instance() );

		// Register REST API Endpoints (priority 35).
		$this->loader->register( \Soma\API\Loader::instance() );

		// Register Admin Components (priority 40).
		$this->loader->register( \Soma\Admin\Loader::instance() );
	}

	/**
	 * Load all registered components.
	 *
	 * Hooked to 'after_setup_theme' with priority 5.
	 *
	 * @return void
	 */
	public function load_components(): void {
		$this->loader->load();
	}

	/**
	 * Get theme version.
	 *
	 * @return string
	 */
	public function get_version(): string {
		return wp_get_theme()->get( 'Version' );
	}

	/**
	 * Get theme directory path.
	 *
	 * @return string
	 */
	public function get_theme_dir(): string {
		return $this->theme_dir;
	}

	/**
	 * Get theme directory URI.
	 *
	 * @return string
	 */
	public function get_theme_uri(): string {
		return $this->theme_uri;
	}

	/**
	 * Get component loader instance.
	 *
	 * @return Loader
	 */
	public function get_loader(): Loader {
		return $this->loader;
	}
}
