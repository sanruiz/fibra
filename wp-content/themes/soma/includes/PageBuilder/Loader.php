<?php
/**
 * Page Builder Loader
 *
 * Initializes the page builder system with block registry and renderer.
 *
 * @package    Soma
 * @subpackage PageBuilder
 * @since      3.0.0
 */

namespace Soma\PageBuilder;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Page Builder Loader class
 *
 * Manages the initialization of the page builder system.
 * Loads at priority 25 (after PostTypes, before Elementor/CF7).
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
	 * Block registry instance
	 *
	 * @var BlockRegistry
	 */
	private BlockRegistry $registry;

	/**
	 * Block renderer instance
	 *
	 * @var BlockRenderer
	 */
	private BlockRenderer $renderer;

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
	private function __construct() {
		$this->registry = BlockRegistry::instance();
		$this->renderer = BlockRenderer::instance();
	}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization
	 *
	 * @throws \Exception Always throws exception.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize the page builder system
	 *
	 * @return void
	 */
	public function init(): void {
		// Register hooks for cache invalidation
		add_action( 'save_post', $this->invalidate_cache_on_save( ... ) );
		add_action( 'acf/save_post', $this->invalidate_cache_on_acf_save( ... ), 20 );

		// Optional: Enable block caching (disabled by default)
		// $this->renderer->enable_caching( true, 3600 );

		if ( function_exists( 'soma_log_info' ) ) {
			soma_log_info(
				'PageBuilder: System initialized',
				array(
					'registered_blocks' => $this->registry->count(),
					'caching_enabled'   => false,
				)
			);
		}
	}

	/**
	 * Get component loading priority
	 *
	 * Loads after PostTypes (20), before CF7/Elementor (30).
	 *
	 * @return int Priority value.
	 */
	public function get_priority(): int {
		return 25;
	}

	/**
	 * Check if component should load
	 *
	 * Page builder should always load.
	 *
	 * @return bool Always returns true.
	 */
	public function should_load(): bool {
		return true;
	}

	/**
	 * Invalidate block cache on post save
	 *
	 * @param int $post_id Post ID being saved.
	 * @return void
	 */
	private function invalidate_cache_on_save( int $post_id ): void {
		// Skip autosaves
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Skip revisions
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Invalidate all page builder caches for this post
		$this->renderer->invalidate_cache();

		if ( function_exists( 'soma_log_debug' ) ) {
			soma_log_debug(
				'PageBuilder: Cache invalidated on post save',
				array( 'post_id' => $post_id )
			);
		}
	}

	/**
	 * Invalidate block cache on ACF save
	 *
	 * @param int|string $post_id Post ID or options page ID.
	 * @return void
	 */
	private function invalidate_cache_on_acf_save( $post_id ): void {
		// Only process numeric post IDs (not options pages)
		if ( ! is_numeric( $post_id ) ) {
			return;
		}

		$this->renderer->invalidate_cache();

		if ( function_exists( 'soma_log_debug' ) ) {
			soma_log_debug(
				'PageBuilder: Cache invalidated on ACF save',
				array( 'post_id' => $post_id )
			);
		}
	}

	/**
	 * Get block registry instance
	 *
	 * @return BlockRegistry
	 */
	public function get_registry(): BlockRegistry {
		return $this->registry;
	}

	/**
	 * Get block renderer instance
	 *
	 * @return BlockRenderer
	 */
	public function get_renderer(): BlockRenderer {
		return $this->renderer;
	}

	/**
	 * Enable block caching
	 *
	 * @param bool $enabled Whether to enable caching.
	 * @param int  $ttl     Cache TTL in seconds (default: 1 hour).
	 * @return void
	 */
	public function enable_caching( bool $enabled = true, int $ttl = 3600 ): void {
		$this->renderer->enable_caching( $enabled, $ttl );

		if ( function_exists( 'soma_log_info' ) ) {
			soma_log_info(
				'PageBuilder: Caching ' . ( $enabled ? 'enabled' : 'disabled' ),
				array( 'ttl' => $ttl )
			);
		}
	}
}
