<?php
/**
 * Utils Loader
 *
 * Loads utility classes and helper functions.
 *
 * @package Soma
 * @subpackage Utils
 * @since 3.0.0
 */

namespace Soma\Utils;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utils Loader Class
 *
 * Initializes utility components: Logger, Cache, CacheInvalidationManager.
 * Loads global helper functions.
 *
 * @since 3.0.0
 */
class Loader implements LoadableInterface {

	/**
	 * Singleton instance
	 *
	 * @var Loader|null
	 */
	private static ?Loader $instance = null;

	/**
	 * Get singleton instance
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
	 * @throws \Exception Cannot unserialize singleton.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize utilities
	 *
	 * @return void
	 */
	public function init(): void {
		// Load helper functions first (they depend on classes below).
		$this->load_helpers();

		// Initialize singletons.
		Logger::instance();
		Cache::instance();
		CacheInvalidationManager::instance();

		// Register cleanup cron job.
		$this->register_cleanup_cron();
	}

	/**
	 * Get component priority
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 10; // Load utilities FIRST so helper functions are available.
	}

	/**
	 * Check if component should load
	 *
	 * @return bool
	 */
	public function should_load(): bool {
		return true; // Always load utilities.
	}

	/**
	 * Load helper functions
	 *
	 * @return void
	 */
	private function load_helpers(): void {
		require_once get_template_directory() . '/includes/Utils/Helpers.php';
	}

	/**
	 * Register cleanup cron job
	 *
	 * @return void
	 */
	private function register_cleanup_cron(): void {
		// Register custom cron schedule.
		add_filter(
			'cron_schedules',
			function ( $schedules ) {
				$schedules['soma_daily'] = array(
					'interval' => DAY_IN_SECONDS,
					'display'  => __( 'Once Daily (Soma)', 'soma' ),
				);
				return $schedules;
			}
		);

		// Schedule cleanup if not already scheduled.
		if ( ! wp_next_scheduled( 'soma_cache_cleanup' ) ) {
			wp_schedule_event( time(), 'soma_daily', 'soma_cache_cleanup' );
		}

		// Register cleanup action.
		add_action( 'soma_cache_cleanup', $this->cleanup_cache( ... ) );
	}

	/**
	 * Cleanup expired cache entries
	 *
	 * @return void
	 */
	public function cleanup_cache(): void {
		$count = Cache::instance()->cleanup();

		if ( $count > 0 ) {
			soma_log_info( 'Cache cleanup: {count} expired entries removed', array( 'count' => $count ) );
		}
	}
}
