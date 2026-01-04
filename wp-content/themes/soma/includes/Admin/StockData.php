<?php
/**
 * Stock Data Fetcher (Yahoo Finance API)
 *
 * @package Soma\Admin
 */

namespace Soma\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stock Data class - fetches and caches stock market data
 */
class StockData {

	/**
	 * Singleton instance
	 *
	 * @var StockData|null
	 */
	private static ?StockData $instance = null;

	/**
	 * Stock symbol
	 *
	 * @var string
	 */
	private string $symbol = '';

	/**
	 * API endpoint
	 *
	 * @var string
	 */
	private string $api_endpoint = '';

	/**
	 * API key
	 *
	 * @var string
	 */
	private string $api_key = '';

	/**
	 * API host
	 *
	 * @var string
	 */
	private string $api_host = '';

	/**
	 * Update interval in hours
	 *
	 * @var int
	 */
	private int $update_interval = 3;

	/**
	 * Option key for sync status
	 *
	 * @var string
	 */
	private const SYNC_STATUS_OPTION = 'soma_stock_sync_status';

	/**
	 * Get singleton instance
	 *
	 * @return StockData
	 */
	public static function instance(): StockData {
		if ( self::$instance === null ) {
			self::$instance = new self();
			self::$instance->init();
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
	 * Initialize stock data fetcher
	 */
	private function init(): void {
		// Load configuration from ACF options.
		$this->load_config();

		// Register custom cron schedule FIRST (before using it).
		add_filter( 'cron_schedules', array( $this, 'custom_cron_schedules' ) );

		// Register the cron action handler.
		add_action( 'update_stock_data_event', array( $this, 'fetch_stock_data' ) );

		// Schedule cron event (only if not already scheduled).
		$this->maybe_schedule_cron();

		// Register AJAX handlers for admin.
		add_action( 'wp_ajax_soma_test_stock_api', array( $this, 'ajax_test_api' ) );
		add_action( 'wp_ajax_soma_get_stock_status', array( $this, 'ajax_get_status' ) );

		// Enqueue admin scripts on stock data options page.
		add_action( 'acf/input/admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Enqueue admin scripts for stock data settings page.
	 */
	public function enqueue_admin_scripts(): void {
		$screen = get_current_screen();
		if ( ! $screen || strpos( $screen->id, 'acf-options-stock-data' ) === false ) {
			return;
		}

		// Enqueue external styles.
		wp_enqueue_style(
			'soma-stock-data-admin',
			get_template_directory_uri() . '/assets/css/admin/stock-data.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		// Enqueue the external script.
		wp_enqueue_script(
			'soma-stock-data-admin',
			get_template_directory_uri() . '/assets/js/admin/stock-data.js',
			array( 'jquery' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		// Localize script with data and translations.
		wp_localize_script(
			'soma-stock-data-admin',
			'somaStockData',
			array(
				'nonce' => wp_create_nonce( 'soma_stock_api_nonce' ),
				'i18n'  => array(
					'testButton'    => __( 'Test API Connection', 'soma' ),
					'testing'       => __( 'Testing...', 'soma' ),
					'requestFailed' => __( 'Request failed', 'soma' ),
					'lastSync'      => __( 'Last Sync:', 'soma' ),
					'noSync'        => __( 'No sync has been performed yet.', 'soma' ),
					'currentData'   => __( 'Current Data:', 'soma' ),
					'marketTime'    => __( 'Market Time:', 'soma' ),
					'nextSync'      => __( 'Next Scheduled Sync:', 'soma' ),
					'every'         => __( 'every', 'soma' ),
				),
			)
		);
	}

	/**
	 * Schedule cron if not already scheduled or if interval changed.
	 */
	private function maybe_schedule_cron(): void {
		$next_scheduled = wp_next_scheduled( 'update_stock_data_event' );
		$schedule_name  = $this->get_schedule_name();

		// Check if we need to reschedule (interval changed).
		if ( $next_scheduled ) {
			$current_schedule = wp_get_schedule( 'update_stock_data_event' );
			if ( $current_schedule !== $schedule_name ) {
				wp_clear_scheduled_hook( 'update_stock_data_event' );
				$next_scheduled = false;
			}
		}

		if ( ! $next_scheduled ) {
			// Use deterministic start time (next interval boundary) for consistent execution.
			// wp_schedule_event expects UTC timestamps, so we use time() not current_time().
			$now       = time();
			$interval  = $this->update_interval * HOUR_IN_SECONDS;
			$first_run = $now - ( $now % $interval ) + $interval;
			wp_schedule_event( $first_run, $schedule_name, 'update_stock_data_event' );
		}
	}

	/**
	 * Get the schedule name based on configured interval.
	 *
	 * @return string Schedule name.
	 */
	private function get_schedule_name(): string {
		return 'soma_every_' . $this->update_interval . '_hours';
	}

	/**
	 * Load configuration from ACF options or WordPress options fallback.
	 *
	 * Uses ACF get_field() when available, with fallback to direct WordPress
	 * options (options_* prefix) for testing environments without ACF.
	 */
	private function load_config(): void {
		// Try ACF first, then fall back to WordPress options.
		$symbol          = $this->get_option_value( 'stock_symbol' );
		$api_endpoint    = $this->get_option_value( 'stock_api_endpoint' );
		$api_key         = $this->get_option_value( 'stock_api_key' );
		$api_host        = $this->get_option_value( 'stock_api_host' );
		$update_interval = $this->get_option_value( 'stock_update_interval' );

		$this->symbol          = ! empty( $symbol ) ? $symbol : 'SOMA21.MX';
		$this->api_endpoint    = ! empty( $api_endpoint ) ? $api_endpoint : 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes';
		$this->api_key         = ! empty( $api_key ) ? $api_key : '';
		$this->api_host        = ! empty( $api_host ) ? $api_host : 'apidojo-yahoo-finance-v1.p.rapidapi.com';
		$this->update_interval = ! empty( $update_interval ) ? (int) $update_interval : 3;
	}

	/**
	 * Get option value from ACF or WordPress options fallback.
	 *
	 * @param string $field_name The field name without prefix.
	 * @return string The option value or empty string.
	 */
	private function get_option_value( string $field_name ): string {
		$value = '';

		// Try ACF get_field first (production environment).
		if ( function_exists( 'get_field' ) ) {
			$acf_value = get_field( $field_name, 'option' );
			if ( ! empty( $acf_value ) ) {
				$value = (string) $acf_value;
			}
		}

		// Fall back to WordPress options (testing environment).
		if ( empty( $value ) ) {
			// ACF stores options with 'options_' prefix.
			$option_value = get_option( 'options_' . $field_name );
			$value        = ! empty( $option_value ) ? (string) $option_value : '';
		}

		// Clean WP Multilang language tags from the value.
		// Format: [:en]value[:] or [:en]value_en[:es]value_es[:].
		return $this->strip_multilang_tags( $value );
	}

	/**
	 * Strip WP Multilang language tags from a string.
	 *
	 * Removes tags like [:en], [:es], [:] from values.
	 * Returns the first language variant found.
	 *
	 * @param string $value Value potentially containing multilang tags.
	 * @return string Clean value without language tags.
	 */
	private function strip_multilang_tags( string $value ): string {
		if ( empty( $value ) ) {
			return $value;
		}

		// Check if value contains multilang tags.
		if ( strpos( $value, '[:' ) === false ) {
			return $value;
		}

		// Extract the first language variant.
		// Pattern matches [:xx] where xx is language code, then captures content until next tag.
		if ( preg_match( '/\[:[a-z]{2}\]([^\[]*)/i', $value, $matches ) ) {
			return trim( $matches[1] );
		}

		// Fallback: just remove all tags.
		$clean = preg_replace( '/\[:[a-z]{0,2}\]/i', '', $value );
		return is_string( $clean ) ? trim( $clean ) : $value;
	}

	/**
	 * Check if API is configured
	 *
	 * @return bool True if API key is configured.
	 */
	public function is_configured(): bool {
		return ! empty( $this->api_key );
	}

	/**
	 * Add custom cron schedules based on configured interval.
	 *
	 * WordPress PHPCS cannot detect interval values when they are computed dynamically.
	 * All intervals here are >= 1 hour (3600 seconds), which is well above the
	 * recommended minimum of 15 minutes (900 seconds) for cron schedules.
	 *
	 * @param array $schedules Existing schedules.
	 * @return array Modified schedules.
	 */
	public function custom_cron_schedules( array $schedules ): array {
		// All intervals are >= 1 hour (3600s), well above the 15-minute minimum.
		$schedules['soma_every_1_hours']  = array(
			'interval' => 1 * HOUR_IN_SECONDS,
			'display'  => __( 'Every 1 Hour(s)', 'soma' ),
		);
		$schedules['soma_every_2_hours']  = array(
			'interval' => 2 * HOUR_IN_SECONDS,
			'display'  => __( 'Every 2 Hour(s)', 'soma' ),
		);
		$schedules['soma_every_3_hours']  = array(
			'interval' => 3 * HOUR_IN_SECONDS,
			'display'  => __( 'Every 3 Hour(s)', 'soma' ),
		);
		$schedules['soma_every_6_hours']  = array(
			'interval' => 6 * HOUR_IN_SECONDS,
			'display'  => __( 'Every 6 Hour(s)', 'soma' ),
		);
		$schedules['soma_every_12_hours'] = array(
			'interval' => 12 * HOUR_IN_SECONDS,
			'display'  => __( 'Every 12 Hour(s)', 'soma' ),
		);
		$schedules['soma_every_24_hours'] = array(
			'interval' => 24 * HOUR_IN_SECONDS,
			'display'  => __( 'Every 24 Hour(s)', 'soma' ),
		);
		return $schedules;
	}

	/**
	 * Fetch stock data from Yahoo Finance API
	 *
	 * @return array{success: bool, message: string, data?: array} Result of the fetch operation.
	 */
	public function fetch_stock_data(): array {
		// Reload config to ensure we have latest values.
		$this->load_config();

		// Skip if API is not configured.
		if ( ! $this->is_configured() ) {
			$result = array(
				'success' => false,
				'message' => __( 'Stock data API not configured. Please add API key in Theme Settings > Stock Data.', 'soma' ),
			);
			$this->save_sync_status( $result );
			soma_log_warning( $result['message'] );
			return $result;
		}

		$request_url = add_query_arg(
			array(
				'region'  => 'US',
				'symbols' => $this->symbol,
			),
			$this->api_endpoint
		);

		// Log the request details for debugging.
		soma_log_debug(
			'Stock API request',
			array(
				'url'      => $request_url,
				'symbol'   => $this->symbol,
				'endpoint' => $this->api_endpoint,
				'host'     => $this->api_host,
			)
		);

		$response = wp_remote_get(
			$request_url,
			array(
				'headers' => array(
					'x-rapidapi-key'  => $this->api_key,
					'x-rapidapi-host' => $this->api_host,
				),
				'timeout' => 30,
			)
		);

		if ( is_wp_error( $response ) ) {
			$result = array(
				'success' => false,
				'message' => sprintf(
					/* translators: %s: error message */
					__( 'Failed to fetch stock data: %s', 'soma' ),
					$response->get_error_message()
				),
			);
			$this->save_sync_status( $result );
			soma_log_error( 'Failed to fetch stock data', array( 'error' => $response->get_error_message() ) );
			return $result;
		}

		$http_code = wp_remote_retrieve_response_code( $response );
		if ( $http_code !== 200 ) {
			$result = array(
				'success' => false,
				'message' => sprintf(
					/* translators: 1: HTTP status code, 2: API endpoint URL */
					__( 'API returned HTTP %1$d error. Endpoint: %2$s', 'soma' ),
					$http_code,
					$request_url
				),
			);
			$this->save_sync_status( $result );
			soma_log_error(
				'Stock API HTTP error',
				array(
					'http_code' => $http_code,
					'url'       => $request_url,
					'endpoint'  => $this->api_endpoint,
				)
			);
			return $result;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $body['quoteResponse']['result'][0] ) ) {
			$result = array(
				'success' => false,
				'message' => __( 'Stock data response is empty or invalid', 'soma' ),
			);
			$this->save_sync_status( $result );
			soma_log_warning( $result['message'] );
			return $result;
		}

		$data = $body['quoteResponse']['result'][0];

		$stock_data = array(
			'price'                  => $data['regularMarketPrice'] ?? 0,
			'volume'                 => $data['regularMarketVolume'] ?? 0,
			'change'                 => $data['regularMarketChange'] ?? 0,
			'percent'                => $data['regularMarketChangePercent'] ?? 0,
			'exchangeTimezoneName'   => $data['exchangeTimezoneName'] ?? '',
			'exchangeTimezoneOffset' => $data['gmtOffSetMilliseconds'] ?? 0,
			'symbol'                 => $data['symbol'] ?? $this->symbol,
			'timestamp'              => $data['regularMarketTime'] ?? time(),
			'currency'               => $data['currency'] ?? 'MXN',
			'shortName'              => $data['shortName'] ?? '',
			'longName'               => $data['longName'] ?? '',
			'marketState'            => $data['marketState'] ?? 'CLOSED',
		);

		update_option( 'stock_data', $stock_data );

		$result = array(
			'success' => true,
			'message' => sprintf(
				/* translators: 1: stock symbol, 2: stock price */
				__( 'Stock data updated: %1$s = %2$s', 'soma' ),
				$stock_data['symbol'],
				number_format( (float) $stock_data['price'], 2 )
			),
			'data'    => $stock_data,
		);
		$this->save_sync_status( $result );

		soma_log_info(
			'Stock data updated successfully',
			array(
				'symbol' => $stock_data['symbol'],
				'price'  => $stock_data['price'],
			)
		);

		return $result;
	}

	/**
	 * Save sync status to options.
	 *
	 * @param array $result Sync result with success and message keys.
	 */
	private function save_sync_status( array $result ): void {
		$status = array(
			'success'   => $result['success'],
			'message'   => $result['message'],
			'timestamp' => time(),
			'datetime'  => wp_date( 'Y-m-d H:i:s' ),
		);
		update_option( self::SYNC_STATUS_OPTION, $status );
	}

	/**
	 * Get last sync status.
	 *
	 * @return array|null Status array or null if never synced.
	 */
	public static function get_sync_status(): ?array {
		$status = get_option( self::SYNC_STATUS_OPTION, null );
		return is_array( $status ) ? $status : null;
	}

	/**
	 * AJAX handler for testing the API connection.
	 */
	public function ajax_test_api(): void {
		// Verify user capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized', 'soma' ) ), 403 );
		}

		// Verify nonce.
		check_ajax_referer( 'soma_stock_api_nonce', 'nonce' );

		// Force fetch.
		$result = $this->fetch_stock_data();

		if ( $result['success'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}

	/**
	 * AJAX handler for getting current sync status.
	 */
	public function ajax_get_status(): void {
		// Verify user capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized', 'soma' ) ), 403 );
		}

		$sync_status = self::get_sync_status();
		$stock_data  = self::get_stock_data();
		$next_run    = wp_next_scheduled( 'update_stock_data_event' );

		wp_send_json_success(
			array(
				'sync_status' => $sync_status,
				'stock_data'  => $stock_data,
				'next_run'    => $next_run ? gmdate( 'Y-m-d H:i:s', $next_run ) : null,
				'interval'    => $this->update_interval,
			)
		);
	}

	/**
	 * Get cached stock data
	 *
	 * @return array|null Stock data or null if not available.
	 */
	public static function get_stock_data(): ?array {
		$data = get_option( 'stock_data', null );
		return is_array( $data ) ? $data : null;
	}
}
