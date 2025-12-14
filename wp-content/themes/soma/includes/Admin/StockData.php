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
	private string $symbol = 'SOMA21.MX';

	/**
	 * API endpoint
	 *
	 * @var string
	 */
	private string $api_endpoint = 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes';

	/**
	 * API key
	 *
	 * @var string
	 */
	private string $api_key = 'fcea2b884fmshd2599170d0a0089p1d0897jsnd0b613f3fb0f';

	/**
	 * API host
	 *
	 * @var string
	 */
	private string $api_host = 'apidojo-yahoo-finance-v1.p.rapidapi.com';

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
		// Schedule cron event
		if ( ! wp_next_scheduled( 'update_stock_data_event' ) ) {
			wp_schedule_event( time(), 'three_hours', 'update_stock_data_event' );
		}

		add_action( 'update_stock_data_event', $this->fetch_stock_data( ... ) );
		add_filter( 'cron_schedules', $this->custom_cron_schedules( ... ) );
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
