<?php
/**
 * Post Types Loader
 *
 * Manages registration and initialization of all custom post types.
 * Implements LoadableInterface for priority-based loading.
 *
 * @package Soma\PostTypes
 * @since 3.0.0
 */

namespace Soma\PostTypes;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Loader
 *
 * Singleton loader for all post type registrations.
 *
 * @package Soma\PostTypes
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
	 * Initialize all post types.
	 *
	 * @return void
	 */
	public function init(): void {
		// Initialize all post type classes.
		Types\Portfolio::instance();
		Types\News::instance();
		Types\TeamMembers::instance();
		Types\Documents::instance();
		Types\Events::instance();
		Types\Careers::instance();
	}

	/**
	 * Get component loading priority.
	 *
	 * Post types load at priority 20 (after Custom Fields at 15).
	 *
	 * @return int The priority number.
	 */
	public function get_priority(): int {
		return 20;
	}

	/**
	 * Check if post types should be loaded.
	 *
	 * @return bool Always true - post types always load.
	 */
	public function should_load(): bool {
		return true;
	}
}
