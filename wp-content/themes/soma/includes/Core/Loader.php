<?php
/**
 * Component Loader
 *
 * Manages the loading of all theme components with priority-based ordering.
 * Components implementing LoadableInterface are automatically registered and
 * initialized in the correct sequence.
 *
 * @package Soma\Core
 * @since 3.0.0
 */

namespace Soma\Core;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Loader
 *
 * Singleton class that manages component loading with priority queue.
 *
 * @package Soma\Core
 */
class Loader {

	/**
	 * Singleton instance
	 *
	 * @var Loader|null
	 */
	private static ?Loader $instance = null;

	/**
	 * Registered components
	 *
	 * @var array<int, LoadableInterface[]>
	 */
	private array $components = array();

	/**
	 * Whether components have been loaded
	 *
	 * @var bool
	 */
	private bool $loaded = false;

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
	 * Register a component for loading.
	 *
	 * @param LoadableInterface $component The component to register.
	 * @return self For method chaining.
	 */
	public function register( LoadableInterface $component ): self {
		if ( $this->loaded ) {
			// If already loaded, initialize immediately.
			if ( $component->should_load() ) {
				$component->init();
			}
			return $this;
		}

		$priority = $component->get_priority();

		if ( ! isset( $this->components[ $priority ] ) ) {
			$this->components[ $priority ] = array();
		}

		$this->components[ $priority ][] = $component;

		return $this;
	}

	/**
	 * Load all registered components in priority order.
	 *
	 * @return void
	 */
	public function load(): void {
		if ( $this->loaded ) {
			return; // Already loaded.
		}

		// Sort by priority (ascending).
		ksort( $this->components );

		// Load each component.
		foreach ( $this->components as $priority_components ) {
			foreach ( $priority_components as $component ) {
				if ( $component->should_load() ) {
					$component->init();
				}
			}
		}

		$this->loaded = true;
	}

	/**
	 * Check if components have been loaded.
	 *
	 * @return bool True if loaded, false otherwise.
	 */
	public function is_loaded(): bool {
		return $this->loaded;
	}

	/**
	 * Get all registered components (for debugging).
	 *
	 * @return array<int, LoadableInterface[]>
	 */
	public function get_components(): array {
		return $this->components;
	}
}
