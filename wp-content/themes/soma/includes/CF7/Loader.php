<?php
/**
 * Contact Form 7 Loader
 *
 * Manages Contact Form 7 integration and custom validations.
 * Implements LoadableInterface for priority-based loading.
 *
 * @package Soma\CF7
 * @since 3.0.0
 */

namespace Soma\CF7;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Loader
 *
 * Singleton loader for CF7 integration.
 *
 * @package Soma\CF7
 */
class Loader implements LoadableInterface {

	/**
	 * Singleton instance
	 *
	 * @var Loader|null
	 */
	private static ?Loader $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Loader The singleton instance.
	 */
	public static function instance(): Loader {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		// Singleton pattern - no direct instantiation.
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
	 * Initialize CF7 integration.
	 *
	 * @return void
	 */
	public function init(): void {
		// Initialize validations.
		Validations::instance();
	}

	/**
	 * Get component loading priority.
	 *
	 * CF7 integration loads at priority 30 (after Post Types and Custom Fields).
	 *
	 * @return int The priority number.
	 */
	public function get_priority(): int {
		return 30;
	}

	/**
	 * Check if CF7 should be loaded.
	 *
	 * Only load if Contact Form 7 plugin is active.
	 *
	 * @return bool True if CF7 is active, false otherwise.
	 */
	public function should_load(): bool {
		return class_exists( 'WPCF7' );
	}
}
