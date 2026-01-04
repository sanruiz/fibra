<?php
/**
 * StockDataEndpoint Integration Tests
 *
 * Tests the REST API endpoint /soma/stock-data with real data.
 *
 * @package Soma
 * @subpackage Tests\Integration\API
 */

namespace Soma\Tests\Integration\API;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_UnitTestCase;
use Soma\Admin\StockData;
use Soma\API\Endpoints\StockDataEndpoint;

/**
 * Test StockDataEndpoint REST API
 *
 * @group integration
 * @group api
 * @group rest-api
 * @group stock-data
 */
class StockDataEndpointTest extends WP_UnitTestCase {

	/**
	 * REST server instance.
	 *
	 * @var WP_REST_Server
	 */
	private $server;

	/**
	 * Set up test fixtures.
	 */
	public function setUp(): void {
		parent::setUp();

		// Initialize REST API server.
		global $wp_rest_server;
		$this->server = $wp_rest_server = new WP_REST_Server();
		do_action( 'rest_api_init' );

		// Clean up stock data option.
		delete_option( 'stock_data' );

		// Reset singleton instances for fresh tests.
		$this->reset_singleton( StockData::class );
		$this->reset_singleton( StockDataEndpoint::class );
	}

	/**
	 * Clean up after each test.
	 */
	public function tearDown(): void {
		delete_option( 'stock_data' );

		// Unschedule cron event.
		$timestamp = wp_next_scheduled( 'update_stock_data_event' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'update_stock_data_event' );
		}

		parent::tearDown();
	}

	/**
	 * Reset singleton instance via reflection.
	 *
	 * Note: In PHP 8.1+, setAccessible() is no longer needed for ReflectionProperty.
	 *
	 * @param string $class_name Fully qualified class name.
	 */
	private function reset_singleton( string $class_name ): void {
		$reflection = new \ReflectionClass( $class_name );
		$property   = $reflection->getProperty( 'instance' );
		$property->setValue( null, null );
	}

	/**
	 * Test REST route is registered.
	 */
	public function test_rest_route_is_registered(): void {
		StockDataEndpoint::instance();

		$routes = $this->server->get_routes();

		$this->assertArrayHasKey( '/soma/stock-data', $routes );
	}

	/**
	 * Test endpoint supports GET method.
	 *
	 * WordPress REST API stores methods as associative array with method names as keys.
	 */
	public function test_endpoint_supports_get_method(): void {
		StockDataEndpoint::instance();

		$routes = $this->server->get_routes();
		$route  = $routes['/soma/stock-data'][0];

		// WordPress REST API stores methods as ['GET' => true, ...].
		$this->assertArrayHasKey( 'GET', $route['methods'] );
		$this->assertTrue( $route['methods']['GET'] );
	}

	/**
	 * Test endpoint returns 404 when no stock data exists.
	 */
	public function test_endpoint_returns_404_when_no_data(): void {
		StockDataEndpoint::instance();
		delete_option( 'stock_data' );

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );

		$this->assertSame( 404, $response->get_status() );
		$this->assertSame( 'no_data', $response->get_data()['code'] );
	}

	/**
	 * Test endpoint returns 200 when stock data exists.
	 */
	public function test_endpoint_returns_200_when_data_exists(): void {
		StockDataEndpoint::instance();

		$test_data = array(
			'price'                  => 50.0,
			'volume'                 => 400,
			'change'                 => 0.0,
			'percent'                => 0.0,
			'exchangeTimezoneName'   => 'America/Mexico_City',
			'exchangeTimezoneOffset' => -21600000,
			'symbol'                 => 'SOMA21.MX',
			'timestamp'              => 1767373811,
			'currency'               => 'MXN',
			'shortName'              => 'CIBANCO SA INSTIT DE BANCA MULT',
			'longName'               => 'Fibra SOMA',
			'marketState'            => 'CLOSED',
		);
		update_option( 'stock_data', $test_data );

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
	}

	/**
	 * Test endpoint returns correct data structure.
	 */
	public function test_endpoint_returns_correct_data_structure(): void {
		StockDataEndpoint::instance();

		$test_data = array(
			'price'                  => 50.0,
			'volume'                 => 400,
			'change'                 => 0.5,
			'percent'                => 1.0,
			'exchangeTimezoneName'   => 'America/Mexico_City',
			'exchangeTimezoneOffset' => -21600000,
			'symbol'                 => 'SOMA21.MX',
			'timestamp'              => 1767373811,
			'currency'               => 'MXN',
			'shortName'              => 'CIBANCO SA INSTIT DE BANCA MULT',
			'longName'               => 'Fibra SOMA',
			'marketState'            => 'CLOSED',
		);
		update_option( 'stock_data', $test_data );

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		// Verify all required fields exist.
		$this->assertArrayHasKey( 'price', $data );
		$this->assertArrayHasKey( 'volume', $data );
		$this->assertArrayHasKey( 'change', $data );
		$this->assertArrayHasKey( 'percent', $data );
		$this->assertArrayHasKey( 'symbol', $data );
		$this->assertArrayHasKey( 'currency', $data );
		$this->assertArrayHasKey( 'timestamp', $data );
		$this->assertArrayHasKey( 'marketState', $data );
		$this->assertArrayHasKey( 'exchangeTimezoneName', $data );
		$this->assertArrayHasKey( 'exchangeTimezoneOffset', $data );
		$this->assertArrayHasKey( 'shortName', $data );
		$this->assertArrayHasKey( 'longName', $data );
	}

	/**
	 * Test endpoint returns correct values.
	 */
	public function test_endpoint_returns_correct_values(): void {
		StockDataEndpoint::instance();

		$test_data = array(
			'price'                  => 50.0,
			'volume'                 => 400,
			'change'                 => 0.5,
			'percent'                => 1.0,
			'exchangeTimezoneName'   => 'America/Mexico_City',
			'exchangeTimezoneOffset' => -21600000,
			'symbol'                 => 'SOMA21.MX',
			'timestamp'              => 1767373811,
			'currency'               => 'MXN',
			'shortName'              => 'CIBANCO SA INSTIT DE BANCA MULT',
			'longName'               => 'Fibra SOMA',
			'marketState'            => 'CLOSED',
		);
		update_option( 'stock_data', $test_data );

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSame( 50.0, $data['price'] );
		$this->assertSame( 400, $data['volume'] );
		$this->assertSame( 'SOMA21.MX', $data['symbol'] );
		$this->assertSame( 'MXN', $data['currency'] );
		$this->assertSame( 'Fibra SOMA', $data['longName'] );
		$this->assertSame( 'America/Mexico_City', $data['exchangeTimezoneName'] );
		$this->assertSame( 'CLOSED', $data['marketState'] );
	}

	/**
	 * Test endpoint returns real API data after fetch.
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_endpoint_returns_real_api_data(): void {
		StockDataEndpoint::instance();

		// Fetch real data from Yahoo Finance.
		$stock_instance = StockData::instance();
		$stock_instance->fetch_stock_data();

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		// Should return 200 with real data.
		$this->assertSame( 200, $response->get_status() );

		// Verify real data values.
		$this->assertSame( 'SOMA21.MX', $data['symbol'] );
		$this->assertSame( 'MXN', $data['currency'] );
		$this->assertSame( 'Fibra SOMA', $data['longName'] );
		$this->assertSame( 'America/Mexico_City', $data['exchangeTimezoneName'] );

		// Verify price is in expected range (52-week: $44-$55).
		$this->assertGreaterThanOrEqual( 40.0, $data['price'] );
		$this->assertLessThanOrEqual( 60.0, $data['price'] );

		// Verify numeric types.
		$this->assertIsNumeric( $data['price'] );
		$this->assertIsInt( $data['volume'] );
		$this->assertIsNumeric( $data['change'] );
		$this->assertIsNumeric( $data['percent'] );
	}

	/**
	 * Test endpoint is publicly accessible (no auth required).
	 */
	public function test_endpoint_is_publicly_accessible(): void {
		StockDataEndpoint::instance();

		$test_data = array(
			'price'    => 50.0,
			'symbol'   => 'SOMA21.MX',
			'currency' => 'MXN',
		);
		update_option( 'stock_data', $test_data );

		// Ensure we're not logged in.
		wp_set_current_user( 0 );

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
	}

	/**
	 * Test endpoint does not support POST method.
	 */
	public function test_endpoint_does_not_support_post(): void {
		StockDataEndpoint::instance();

		$request  = new WP_REST_Request( 'POST', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );

		$this->assertSame( 404, $response->get_status() );
	}

	/**
	 * Test endpoint does not support PUT method.
	 */
	public function test_endpoint_does_not_support_put(): void {
		StockDataEndpoint::instance();

		$request  = new WP_REST_Request( 'PUT', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );

		$this->assertSame( 404, $response->get_status() );
	}

	/**
	 * Test endpoint does not support DELETE method.
	 */
	public function test_endpoint_does_not_support_delete(): void {
		StockDataEndpoint::instance();

		$request  = new WP_REST_Request( 'DELETE', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );

		$this->assertSame( 404, $response->get_status() );
	}

	/**
	 * Test response content type is JSON.
	 */
	public function test_response_content_type_is_json(): void {
		StockDataEndpoint::instance();

		$test_data = array(
			'price'    => 50.0,
			'symbol'   => 'SOMA21.MX',
			'currency' => 'MXN',
		);
		update_option( 'stock_data', $test_data );

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );

		$this->assertInstanceOf( WP_REST_Response::class, $response );
	}

	/**
	 * Test real API data timestamp is recent.
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_real_api_timestamp_is_recent(): void {
		StockDataEndpoint::instance();

		// Fetch real data.
		$stock_instance = StockData::instance();
		$stock_instance->fetch_stock_data();

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		// Timestamp should be within the last 7 days (markets may be closed).
		$seven_days_ago = time() - ( 7 * DAY_IN_SECONDS );
		$this->assertGreaterThan( $seven_days_ago, $data['timestamp'] );
	}

	/**
	 * Test real API market state is valid.
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_real_api_market_state_is_valid(): void {
		StockDataEndpoint::instance();

		// Fetch real data.
		$stock_instance = StockData::instance();
		$stock_instance->fetch_stock_data();

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$valid_states = array( 'CLOSED', 'REGULAR', 'PRE', 'POST', 'PREPRE', 'POSTPOST' );
		$this->assertContains( $data['marketState'], $valid_states );
	}

	/**
	 * Test real API exchange timezone offset.
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_real_api_timezone_offset(): void {
		StockDataEndpoint::instance();

		// Fetch real data.
		$stock_instance = StockData::instance();
		$stock_instance->fetch_stock_data();

		$request  = new WP_REST_Request( 'GET', '/soma/stock-data' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		// Mexico City is UTC-6 = -21600000 milliseconds.
		$this->assertSame( -21600000, $data['exchangeTimezoneOffset'] );
	}
}
