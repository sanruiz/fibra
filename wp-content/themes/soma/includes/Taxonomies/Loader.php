<?php
/**
 * Taxonomies Loader
 *
 * Manages registration and initialization of all custom taxonomies.
 * Implements LoadableInterface for priority-based loading.
 *
 * @package Soma\Taxonomies
 * @since 3.0.0
 */

namespace Soma\Taxonomies;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Loader
 *
 * Singleton loader for all taxonomy registrations.
 *
 * @package Soma\Taxonomies
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
	 * Initialize all taxonomies.
	 *
	 * @return void
	 */
	public function init(): void {
		// Initialize all taxonomy classes.
		TeamMembersTaxonomy::instance();
		PortfolioTaxonomy::instance();
		DocumentsTaxonomy::instance();
	}

	/**
	 * Get component loading priority.
	 *
	 * Taxonomies load at priority 15 (after Utilities at 10, before PostTypes at 20).
	 *
	 * @return int The priority number.
	 */
	public function get_priority(): int {
		return 15;
	}

	/**
	 * Check if taxonomies should be loaded.
	 *
	 * @return bool Always true - taxonomies always load.
	 */
	public function should_load(): bool {
		return true;
	}
}
