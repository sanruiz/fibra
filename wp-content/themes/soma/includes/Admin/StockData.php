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
		if ( ! wp_next_scheduled( 'update_stock_data_event' ) ) {
			// Use deterministic start time (next 3-hour boundary) for consistent execution.
			$now       = current_time( 'timestamp' );
			$interval  = 3 * HOUR_IN_SECONDS;
			$first_run = $now - ( $now % $interval ) + $interval;
			wp_schedule_event( $first_run, 'three_hours', 'update_stock_data_event' );
		}
	}

	/**
	 * Load configuration from ACF options or WordPress options fallback.
	 *
	 * Uses ACF get_field() when available, with fallback to direct WordPress
	 * options (options_* prefix) for testing environments without ACF.
	 */
	private function load_config(): void {
		// Try ACF first, then fall back to WordPress options.
		$symbol       = $this->get_option_value( 'stock_symbol' );
		$api_endpoint = $this->get_option_value( 'stock_api_endpoint' );
		$api_key      = $this->get_option_value( 'stock_api_key' );
		$api_host     = $this->get_option_value( 'stock_api_host' );

		$this->symbol       = ! empty( $symbol ) ? $symbol : 'SOMA21.MX';
		$this->api_endpoint = ! empty( $api_endpoint ) ? $api_endpoint : 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes';
		$this->api_key      = ! empty( $api_key ) ? $api_key : '';
		$this->api_host     = ! empty( $api_host ) ? $api_host : 'apidojo-yahoo-finance-v1.p.rapidapi.com';
	}

	/**
	 * Get option value from ACF or WordPress options fallback.
	 *
	 * @param string $field_name The field name without prefix.
	 * @return string The option value or empty string.
	 */
	private function get_option_value( string $field_name ): string {
		// Try ACF get_field first (production environment).
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name, 'option' );
			if ( ! empty( $value ) ) {
				return (string) $value;
			}
		}

		// Fall back to WordPress options (testing environment).
		// ACF stores options with 'options_' prefix.
		$option_value = get_option( 'options_' . $field_name );
		return ! empty( $option_value ) ? (string) $option_value : '';
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
	 * Add custom cron schedule (every 3 hours)
	 *
	 * @param array $schedules Existing schedules.
	 * @return array Modified schedules.
	 */
	public function custom_cron_schedules( array $schedules ): array {
		$schedules['three_hours'] = array(
			'interval' => 3 * HOUR_IN_SECONDS,
			'display'  => __( 'Every 3 Hours', 'soma' ),
		);
		return $schedules;
	}

	/**
	 * Fetch stock data from Yahoo Finance API
	 */
	public function fetch_stock_data(): void {
		// Reload config to ensure we have latest values.
		$this->load_config();

		// Skip if API is not configured.
		if ( ! $this->is_configured() ) {
			soma_log_warning( 'Stock data API not configured. Please add API key in Theme Settings > Stock Data.' );
			return;
		}

		$response = wp_remote_get(
			add_query_arg(
				array(
					'region'  => 'US',
					'symbols' => $this->symbol,
				),
				$this->api_endpoint
			),
			array(
				'headers' => array(
					'x-rapidapi-key'  => $this->api_key,
					'x-rapidapi-host' => $this->api_host,
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			soma_log_error(
				'Failed to fetch stock data',
				array(
					'error' => $response->get_error_message(),
				)
			);
			return;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $body['quoteResponse']['result'][0] ) ) {
			soma_log_warning( 'Stock data response is empty or invalid' );
			return;
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

		soma_log_info(
			'Stock data updated successfully',
			array(
				'symbol' => $stock_data['symbol'],
				'price'  => $stock_data['price'],
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
