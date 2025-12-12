<?php
/**
 * Navigation Menus
 *
 * @package Soma\Core
 */

namespace Soma\Core;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Navigation class - manages WordPress navigation menus
 */
class Navigation implements LoadableInterface {

	/**
	 * Singleton instance
	 *
	 * @var Navigation|null
	 */
	private static ?Navigation $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Navigation
	 */
	public static function instance(): Navigation {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization
	 *
	 * @throws \Exception When trying to unserialize.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize the component
	 */
	public function init(): void {
		add_action( 'init', $this->register_menus(...) );
	}

	/**
	 * Get component loading priority
	 *
	 * @return int Priority (lower = earlier)
	 */
	public function get_priority(): int {
		return 8; // Load early, before most components
	}

	/**
	 * Check if component should load
	 *
	 * @return bool Always true - menus always needed
	 */
	public function should_load(): bool {
		return true;
	}

	/**
	 * Register navigation menus
	 */
	public function register_menus(): void {
		$locations = array(
			'main_menu'                   => __( 'Main Menu', 'soma' ),
			'social'                      => __( 'Social', 'soma' ),
			'business_units'              => __( 'Business Units', 'soma' ),
			'fibrasoma_footer'            => __( 'Fibrasoma Footer', 'soma' ),
			'navigation_sidebar_template' => __( 'Navigation Sidebar Template', 'soma' ),
		);

		register_nav_menus( $locations );
	}
}
