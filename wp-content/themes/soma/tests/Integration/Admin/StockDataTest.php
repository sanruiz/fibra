<?php
/**
 * StockData Integration Tests
 *
 * Tests the StockData class functionality with real WordPress environment
 * and real Yahoo Finance API calls.
 *
 * @package Soma
 * @subpackage Tests\Integration\Admin
 */

namespace Soma\Tests\Integration\Admin;

use WP_UnitTestCase;
use Soma\Admin\StockData;

/**
 * Test StockData class integration with WordPress and Yahoo Finance API
 *
 * @group integration
 * @group admin
 * @group stock-data
 * @group api
 */
class StockDataTest extends WP_UnitTestCase {

	/**
	 * Indicates if a real API key is available for external API tests.
	 *
	 * Set via SOMA_STOCK_API_KEY environment variable for CI/CD.
	 *
	 * @var bool
	 */
	private bool $has_real_api_key = false;

	/**
	 * Clean up stock data option before each test
	 */
	public function setUp(): void {
		parent::setUp();
		delete_option( 'stock_data' );

		// Check if real API key is available via environment variable.
		$env_api_key = getenv( 'SOMA_STOCK_API_KEY' );

		if ( ! empty( $env_api_key ) && 'test-api-key-for-testing' !== $env_api_key ) {
			$this->has_real_api_key = true;
			$api_key                = $env_api_key;
		} else {
			$this->has_real_api_key = false;
			$api_key                = 'test-api-key-for-testing';
		}

		// Mock ACF options for Stock Data configuration.
		update_option( 'options_stock_symbol', 'SOMA21.MX' );
		update_option( 'options_stock_api_endpoint', 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes' );
		update_option( 'options_stock_api_key', $api_key );
		update_option( 'options_stock_api_host', 'apidojo-yahoo-finance-v1.p.rapidapi.com' );

		// Reset singleton instance for fresh tests.
		// Note: In PHP 8.1+, setAccessible() is no longer needed for ReflectionProperty.
		$reflection = new \ReflectionClass( StockData::class );
		$property   = $reflection->getProperty( 'instance' );
		$property->setValue( null, null );
	}

	/**
	 * Skip test if no real API key is available.
	 *
	 * Call this at the start of tests that require real API calls.
	 */
	private function skip_without_real_api_key(): void {
		if ( ! $this->has_real_api_key ) {
			$this->markTestSkipped( 'Real API key not available. Set SOMA_STOCK_API_KEY environment variable to run external API tests.' );
			return; // Prevent further execution when skipped.
		}
	}

	/**
	 * Skip test if API didn't return valid stock data.
	 *
	 * Validates that stock_data option contains expected structure.
	 * External APIs may be rate-limited, unavailable, or return errors.
	 *
	 * @param mixed $data The stock data from get_option( 'stock_data' ).
	 */
	private function skip_without_valid_stock_data( $data ): void {
		if ( ! is_array( $data ) || empty( $data ) || ! isset( $data['symbol'] ) ) {
			$this->markTestSkipped( 'API did not return valid stock data. External API may be unavailable or rate limited.' );
		}
	}

	/**
	 * Clean up after each test
	 */
	public function tearDown(): void {
		delete_option( 'stock_data' );

		// Clean up ACF mock options.
		delete_option( 'options_stock_symbol' );
		delete_option( 'options_stock_api_endpoint' );
		delete_option( 'options_stock_api_key' );
		delete_option( 'options_stock_api_host' );

		// Unschedule cron event.
		$timestamp = wp_next_scheduled( 'update_stock_data_event' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'update_stock_data_event' );
		}

		parent::tearDown();
	}

	/**
	 * Test singleton returns same instance
	 */
	public function test_singleton_returns_same_instance(): void {
		$instance1 = StockData::instance();
		$instance2 = StockData::instance();

		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test cron schedules are registered
	 */
	public function test_cron_schedule_is_registered(): void {
		StockData::instance();

		$schedules = wp_get_schedules();

		// Default interval is 3 hours.
		$this->assertArrayHasKey( 'soma_every_3_hours', $schedules );
		$this->assertSame( 3 * HOUR_IN_SECONDS, $schedules['soma_every_3_hours']['interval'] );
	}

	/**
	 * Test cron event is scheduled
	 */
	public function test_cron_event_is_scheduled(): void {
		StockData::instance();

		$next_scheduled = wp_next_scheduled( 'update_stock_data_event' );

		$this->assertNotFalse( $next_scheduled );
		$this->assertGreaterThan( 0, $next_scheduled );
	}

	/**
	 * Test custom_cron_schedules adds configurable intervals
	 */
	public function test_custom_cron_schedules_adds_interval(): void {
		$instance  = StockData::instance();
		$schedules = $instance->custom_cron_schedules( array() );

		// All configurable intervals should be registered.
		$this->assertArrayHasKey( 'soma_every_1_hours', $schedules );
		$this->assertArrayHasKey( 'soma_every_3_hours', $schedules );
		$this->assertArrayHasKey( 'soma_every_6_hours', $schedules );
		$this->assertArrayHasKey( 'soma_every_12_hours', $schedules );
		$this->assertArrayHasKey( 'soma_every_24_hours', $schedules );
		$this->assertSame( 3 * HOUR_IN_SECONDS, $schedules['soma_every_3_hours']['interval'] );
		$this->assertArrayHasKey( 'display', $schedules['soma_every_3_hours'] );
	}

	/**
	 * Test custom_cron_schedules preserves existing schedules
	 */
	public function test_custom_cron_schedules_preserves_existing(): void {
		$instance  = StockData::instance();
		$existing  = array(
			'daily' => array(
				'interval' => DAY_IN_SECONDS,
				'display'  => 'Once Daily',
			),
		);
		$schedules = $instance->custom_cron_schedules( $existing );

		$this->assertArrayHasKey( 'daily', $schedules );
		$this->assertArrayHasKey( 'soma_every_3_hours', $schedules );
	}

	/**
	 * Test get_stock_data returns null when no data exists
	 */
	public function test_get_stock_data_returns_null_when_empty(): void {
		delete_option( 'stock_data' );

		$data = StockData::get_stock_data();

		$this->assertNull( $data );
	}

	/**
	 * Test get_stock_data returns array when data exists
	 */
	public function test_get_stock_data_returns_array_when_exists(): void {
		$test_data = array(
			'price'    => 50.0,
			'currency' => 'MXN',
			'symbol'   => 'SOMA21.MX',
		);
		update_option( 'stock_data', $test_data );

		$data = StockData::get_stock_data();

		$this->assertIsArray( $data );
		$this->assertSame( 50.0, $data['price'] );
		$this->assertSame( 'MXN', $data['currency'] );
		$this->assertSame( 'SOMA21.MX', $data['symbol'] );
	}

	/**
	 * Test get_stock_data returns null for non-array option
	 */
	public function test_get_stock_data_returns_null_for_non_array(): void {
		update_option( 'stock_data', 'invalid string' );

		$data = StockData::get_stock_data();

		$this->assertNull( $data );
	}

	/**
	 * Test fetch_stock_data makes real API call and stores data
	 *
	 * This test uses the real Yahoo Finance API with SOMA21.MX symbol.
	 * Expected data structure based on actual API response:
	 * - price: ~50.0 MXN
	 * - symbol: SOMA21.MX
	 * - currency: MXN
	 * - marketState: CLOSED or REGULAR
	 * - exchangeTimezoneName: America/Mexico_City
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_fetch_stock_data_stores_real_data(): void {
		$this->skip_without_real_api_key();

		$instance = StockData::instance();

		// Execute the fetch.
		$instance->fetch_stock_data();

		// Get the stored data.
		$data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		// Verify data was stored.
		$this->assertIsArray( $data, 'Stock data should be stored as array' );

		// Verify required fields exist.
		$required_fields = array(
			'price',
			'volume',
			'change',
			'percent',
			'symbol',
			'timestamp',
			'currency',
			'marketState',
		);

		foreach ( $required_fields as $field ) {
			$this->assertArrayHasKey( $field, $data, "Stock data should have '{$field}' field" );
		}

		// Verify symbol is correct.
		$this->assertSame( 'SOMA21.MX', $data['symbol'] );

		// Verify currency is MXN.
		$this->assertSame( 'MXN', $data['currency'] );

		// Verify price is a valid number.
		$this->assertIsNumeric( $data['price'] );
		$this->assertGreaterThan( 0, $data['price'], 'Price should be positive' );

		// Verify timestamp is a valid Unix timestamp.
		$this->assertIsInt( $data['timestamp'] );
		$this->assertGreaterThan( 0, $data['timestamp'] );

		// Verify market state is valid.
		$valid_states = array( 'CLOSED', 'REGULAR', 'PRE', 'POST', 'PREPRE', 'POSTPOST' );
		$this->assertContains( $data['marketState'], $valid_states );
	}

	/**
	 * Test fetch_stock_data price is within expected range
	 *
	 * Based on actual 52-week range: $44.00 - $55.00 MXN.
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_fetch_stock_data_price_in_expected_range(): void {
		$this->skip_without_real_api_key();

		$instance = StockData::instance();
		$instance->fetch_stock_data();

		$data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		// Price should be within 52-week range (with some buffer).
		$this->assertGreaterThanOrEqual( 40.0, $data['price'], 'Price should be >= $40 MXN' );
		$this->assertLessThanOrEqual( 60.0, $data['price'], 'Price should be <= $60 MXN' );
	}

	/**
	 * Test fetch_stock_data includes exchange timezone info
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_fetch_stock_data_includes_timezone_info(): void {
		$this->skip_without_real_api_key();

		$instance = StockData::instance();
		$instance->fetch_stock_data();

		$data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		// Verify timezone fields exist.
		$this->assertArrayHasKey( 'exchangeTimezoneName', $data );
		$this->assertArrayHasKey( 'exchangeTimezoneOffset', $data );

		// Verify Mexico City timezone.
		$this->assertSame( 'America/Mexico_City', $data['exchangeTimezoneName'] );
	}

	/**
	 * Test fetch_stock_data includes company name info
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_fetch_stock_data_includes_company_info(): void {
		$this->skip_without_real_api_key();

		$instance = StockData::instance();
		$instance->fetch_stock_data();

		$data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		// Verify company name fields exist.
		$this->assertArrayHasKey( 'shortName', $data );
		$this->assertArrayHasKey( 'longName', $data );

		// longName should be "Fibra SOMA".
		$this->assertSame( 'Fibra SOMA', $data['longName'] );
	}

	/**
	 * Test soma_get_stock_data helper function works
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_helper_function_returns_data(): void {
		$this->skip_without_real_api_key();

		$instance = StockData::instance();
		$instance->fetch_stock_data();

		// Use the helper function.
		$data = soma_get_stock_data();

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		$this->assertIsArray( $data );
		$this->assertSame( 'SOMA21.MX', $data['symbol'] );
		$this->assertSame( 'MXN', $data['currency'] );
	}

	/**
	 * Test soma_get_stock_data returns null when no data
	 */
	public function test_helper_function_returns_null_when_empty(): void {
		delete_option( 'stock_data' );

		$data = soma_get_stock_data();

		$this->assertNull( $data );
	}

	/**
	 * Test fetch does not schedule duplicate cron events
	 */
	public function test_does_not_duplicate_cron_events(): void {
		// Initialize twice.
		StockData::instance();

		// Reset and initialize again.
		// Note: In PHP 8.1+, setAccessible() is no longer needed for ReflectionProperty.
		$reflection = new \ReflectionClass( StockData::class );
		$property   = $reflection->getProperty( 'instance' );
		$property->setValue( null, null );

		StockData::instance();

		// Get all scheduled events for this hook.
		$cron = _get_cron_array();
		$count = 0;

		foreach ( $cron as $timestamp => $hooks ) {
			if ( isset( $hooks['update_stock_data_event'] ) ) {
				++$count;
			}
		}

		$this->assertSame( 1, $count, 'Should only have one scheduled event' );
	}

	/**
	 * Test cron interval is correct for 3-hour schedule (10800 seconds)
	 */
	public function test_cron_interval_is_three_hours(): void {
		StockData::instance();

		$schedules = wp_get_schedules();

		$this->assertSame( 10800, $schedules['soma_every_3_hours']['interval'] );
	}

	/**
	 * Test cron event uses correct recurrence schedule
	 */
	public function test_cron_event_uses_configured_schedule(): void {
		StockData::instance();

		$cron       = _get_cron_array();
		$recurrence = null;

		foreach ( $cron as $timestamp => $hooks ) {
			if ( isset( $hooks['update_stock_data_event'] ) ) {
				$recurrence = $hooks['update_stock_data_event'][ key( $hooks['update_stock_data_event'] ) ]['schedule'];
				break;
			}
		}

		// Default interval is 3 hours.
		$this->assertSame( 'soma_every_3_hours', $recurrence );
	}

	/**
	 * Test cron event first run is in the future
	 */
	public function test_cron_first_run_is_in_future(): void {
		StockData::instance();

		$next_scheduled = wp_next_scheduled( 'update_stock_data_event' );
		$now            = current_time( 'timestamp' );

		$this->assertGreaterThanOrEqual( $now, $next_scheduled );
	}

	/**
	 * Test cron event first run is within 3 hours
	 */
	public function test_cron_first_run_within_three_hours(): void {
		StockData::instance();

		$next_scheduled = wp_next_scheduled( 'update_stock_data_event' );
		$now            = current_time( 'timestamp' );
		$three_hours    = 3 * HOUR_IN_SECONDS;

		$this->assertLessThanOrEqual( $now + $three_hours, $next_scheduled );
	}

	/**
	 * Test cron schedule filter is properly hooked
	 */
	public function test_cron_schedule_filter_is_hooked(): void {
		StockData::instance();

		$this->assertGreaterThan(
			0,
			has_filter( 'cron_schedules', array( StockData::instance(), 'custom_cron_schedules' ) )
		);
	}

	/**
	 * Test cron action is properly hooked
	 */
	public function test_cron_action_is_hooked(): void {
		StockData::instance();

		$this->assertGreaterThan(
			0,
			has_action( 'update_stock_data_event', array( StockData::instance(), 'fetch_stock_data' ) )
		);
	}

	/**
	 * Test manual cron execution fetches data
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_manual_cron_execution_fetches_data(): void {
		$this->skip_without_real_api_key();

		StockData::instance();

		// Manually trigger the cron action.
		do_action( 'update_stock_data_event' );

		// Verify data was fetched.
		$data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		$this->assertIsArray( $data );
		$this->assertSame( 'SOMA21.MX', $data['symbol'] );
		$this->assertSame( 'MXN', $data['currency'] );
	}

	/**
	 * Test cron schedule registered before event
	 *
	 * This test validates the fix from commit 30911d8.
	 * The cron schedule must be registered BEFORE wp_schedule_event() is called.
	 */
	public function test_cron_schedule_registered_before_event(): void {
		// Reset singleton.
		// Note: In PHP 8.1+, setAccessible() is no longer needed for ReflectionProperty.
		$reflection = new \ReflectionClass( StockData::class );
		$property   = $reflection->getProperty( 'instance' );
		$property->setValue( null, null );

		// Remove all existing schedules and events.
		remove_all_filters( 'cron_schedules' );
		$timestamp = wp_next_scheduled( 'update_stock_data_event' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'update_stock_data_event' );
		}

		// Initialize the singleton (this should register schedule first, then event).
		StockData::instance();

		// Verify schedule exists (default is 3 hours).
		$schedules = wp_get_schedules();
		$this->assertArrayHasKey( 'soma_every_3_hours', $schedules );

		// Verify event is scheduled with the correct recurrence.
		$cron       = _get_cron_array();
		$found      = false;
		$recurrence = null;

		foreach ( $cron as $ts => $hooks ) {
			if ( isset( $hooks['update_stock_data_event'] ) ) {
				$found      = true;
				$recurrence = $hooks['update_stock_data_event'][ key( $hooks['update_stock_data_event'] ) ]['schedule'];
				break;
			}
		}

		$this->assertTrue( $found, 'Cron event should be scheduled' );
		$this->assertSame( 'soma_every_3_hours', $recurrence, 'Cron should use soma_every_3_hours schedule' );
	}

	/**
	 * Test fetch_stock_data updates existing data
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_fetch_updates_existing_data(): void {
		$this->skip_without_real_api_key();

		// Set initial data.
		$initial_data = array(
			'price'     => 0,
			'symbol'    => 'OLD',
			'currency'  => 'USD',
			'timestamp' => 0,
		);
		update_option( 'stock_data', $initial_data );

		// Fetch new data.
		$instance = StockData::instance();
		$instance->fetch_stock_data();

		// Get updated data.
		$data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		// Verify data was updated.
		$this->assertSame( 'SOMA21.MX', $data['symbol'] );
		$this->assertSame( 'MXN', $data['currency'] );
		$this->assertGreaterThan( 0, $data['price'] );
		$this->assertGreaterThan( 0, $data['timestamp'] );
	}

	/**
	 * Test multiple fetch calls update data correctly
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_multiple_fetches_update_correctly(): void {
		$this->skip_without_real_api_key();

		$instance = StockData::instance();

		// First fetch.
		$instance->fetch_stock_data();
		$first_data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $first_data );

		// Second fetch.
		$instance->fetch_stock_data();
		$second_data = get_option( 'stock_data' );

		// Both should have valid data.
		$this->assertSame( 'SOMA21.MX', $first_data['symbol'] );
		$this->assertSame( 'SOMA21.MX', $second_data['symbol'] );
		$this->assertSame( $first_data['currency'], $second_data['currency'] );
	}

	/**
	 * Test stored data matches expected API response format
	 *
	 * Based on real Yahoo Finance API response for SOMA21.MX.
	 *
	 * @group slow
	 * @group external-api
	 */
	public function test_stored_data_matches_api_format(): void {
		$this->skip_without_real_api_key();

		$instance = StockData::instance();
		$instance->fetch_stock_data();

		$data = get_option( 'stock_data' );

		// Skip if API didn't return valid data.
		$this->skip_without_valid_stock_data( $data );

		// Verify exact field mapping from API response.
		$expected_fields = array(
			'price'                  => 'regularMarketPrice',
			'volume'                 => 'regularMarketVolume',
			'change'                 => 'regularMarketChange',
			'percent'                => 'regularMarketChangePercent',
			'exchangeTimezoneName'   => 'exchangeTimezoneName',
			'exchangeTimezoneOffset' => 'gmtOffSetMilliseconds',
			'symbol'                 => 'symbol',
			'timestamp'              => 'regularMarketTime',
			'currency'               => 'currency',
			'shortName'              => 'shortName',
			'longName'               => 'longName',
			'marketState'            => 'marketState',
		);

		foreach ( array_keys( $expected_fields ) as $field ) {
			$this->assertArrayHasKey( $field, $data, "Missing field: {$field}" );
		}

		// Verify count matches.
		$this->assertCount( 12, $data, 'Data should have exactly 12 fields' );
	}
}
