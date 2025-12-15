<?php
/**
 * Stock Data Endpoint
 *
 * REST API endpoint for stock market data.
 *
 * @package    Soma
 * @subpackage API\Endpoints
 * @since      3.0.0
 */

namespace Soma\API\Endpoints;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Stock Data Endpoint class.
 *
 * Handles /soma/stock-data REST API endpoint.
 *
 * @since 3.0.0
 */
final class StockDataEndpoint {
	/**
	 * Singleton instance.
	 *
	 * @var StockDataEndpoint|null
	 */
	private static ?StockDataEndpoint $instance = null;

	/**
	 * API namespace.
	 *
	 * @var string
	 */
	private const NAMESPACE = 'soma';

	/**
	 * Endpoint route.
	 *
	 * @var string
	 */
	private const ROUTE = '/stock-data';

	/**
	 * Get singleton instance.
	 *
	 * @return StockDataEndpoint
	 */
	public static function instance(): StockDataEndpoint {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->init();
	}

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
	 * Initialize the endpoint.
	 *
	 * @return void
	 */
	private function init(): void {
		add_action( 'rest_api_init', $this->register( ... ) );
	}

	/**
	 * Register the REST route.
	 *
	 * @return void
	 */
	private function register(): void {
		register_rest_route(
			self::NAMESPACE,
			self::ROUTE,
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => $this->handle( ... ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Handle the request.
	 *
	 * Fetches stored stock data from WordPress options.
	 *
	 * @param WP_REST_Request $_request Request object (unused, required by signature).
	 * @return WP_REST_Response|WP_Error Response data or error.
	 */
	private function handle( WP_REST_Request $_request ) {
		$stock_data = get_option( 'stock_data' );

		if ( ! $stock_data ) {
			return new WP_Error(
				'no_data',
				__( 'No stock data available', 'soma' ),
				array( 'status' => 404 )
			);
		}

		return rest_ensure_response( $stock_data );
	}
}
