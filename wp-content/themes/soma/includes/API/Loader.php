<?php
/**
 * API Loader
 *
 * Registers all REST API endpoints for the theme.
 *
 * @package    Soma
 * @subpackage API
 * @since      3.0.0
 */

namespace Soma\API;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * API Loader class.
 *
 * Handles registration of all REST API endpoints.
 *
 * @since 3.0.0
 */
final class Loader implements LoadableInterface {
	/**
	 * Singleton instance.
	 *
	 * @var Loader|null
	 */
	private static ?Loader $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Loader
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
	private function __construct() {}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization.
	 *
	 * @throws \Exception When attempting to unserialize.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize the loader.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->load_endpoints();
	}

	/**
	 * Get component loading priority.
	 *
	 * @return int Priority (35 for API endpoints).
	 */
	public function get_priority(): int {
		return 35;
	}

	/**
	 * Check if component should load.
	 *
	 * @return bool Always true for REST API.
	 */
	public function should_load(): bool {
		return true;
	}

	/**
	 * Load all endpoint instances.
	 *
	 * @return void
	 */
	private function load_endpoints(): void {
		\Soma\API\Endpoints\NewsEndpoint::instance();
		\Soma\API\Endpoints\PortfolioEndpoint::instance();
		\Soma\API\Endpoints\DocumentsEndpoint::instance();
		\Soma\API\Endpoints\EventsEndpoint::instance();
		\Soma\API\Endpoints\CareersEndpoint::instance();
		\Soma\API\Endpoints\StockDataEndpoint::instance();
	}
}
