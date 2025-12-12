<?php
/**
 * Loadable Interface
 *
 * Defines the contract for all loadable components in the theme.
 * Each component that implements this interface can be loaded by the Loader class
 * with a specific priority order.
 *
 * @package Soma\Core\Interfaces
 * @since 3.0.0
 */

namespace Soma\Core\Interfaces;

/**
 * Interface LoadableInterface
 *
 * @package Soma\Core\Interfaces
 */
interface LoadableInterface {

	/**
	 * Initialize the component.
	 *
	 * This method is called by the Loader when the component is loaded.
	 * It should contain all the initialization logic, including registering
	 * hooks, filters, and any other setup required by the component.
	 *
	 * @return void
	 */
	public function init(): void;

	/**
	 * Get the component loading priority.
	 *
	 * Lower numbers mean earlier loading. Priority ranges:
	 * - 10: Core components (Theme, Loader)
	 * - 15: Custom Fields (ACF)
	 * - 20: Post Types
	 * - 25: Page Builder
	 * - 30: Integrations (Elementor, CF7)
	 * - 35: API Endpoints
	 * - 40: Admin customizations
	 * - 45: Utilities (Logger, Cache)
	 *
	 * @return int The priority number (10-50 recommended range).
	 */
	public function get_priority(): int;

	/**
	 * Check if the component should be loaded.
	 *
	 * This method allows conditional loading of components based on
	 * environment, user capabilities, or other criteria.
	 *
	 * @return bool True if the component should load, false otherwise.
	 */
	public function should_load(): bool;
}
