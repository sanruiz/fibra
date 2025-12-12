<?php
/**
 * Block Renderer
 *
 * Renders ACF flexible content blocks with validation, error handling, and optional caching.
 *
 * @package    Soma
 * @subpackage PageBuilder
 * @since      3.0.0
 */

namespace Soma\PageBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * BlockRenderer class
 *
 * Responsible for rendering ACF flexible content blocks through theme partials.
 * Includes validation, error handling, and PSR-3 logging integration.
 *
 * @since 3.0.0
 */
class BlockRenderer {
	/**
	 * Singleton instance
	 *
	 * @var BlockRenderer|null
	 */
	private static ?BlockRenderer $instance = null;

	/**
	 * Block registry
	 *
	 * @var BlockRegistry
	 */
	private BlockRegistry $registry;

	/**
	 * Enable caching for blocks
	 *
	 * @var bool
	 */
	private bool $caching_enabled = false;

	/**
	 * Cache TTL in seconds (default: 1 hour)
	 *
	 * @var int
	 */
	private int $cache_ttl = 3600;

	/**
	 * Get singleton instance
	 *
	 * @return BlockRenderer
	 */
	public static function instance(): BlockRenderer {
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
	 * Enable block caching
	 *
	 * @param bool $enabled Whether to enable caching.
	 * @param int  $ttl     Cache TTL in seconds.
	 * @return void
	 */
	public function enable_caching( bool $enabled = true, int $ttl = 3600 ): void {
		$this->caching_enabled = $enabled;
		$this->cache_ttl       = $ttl;
	}

	/**
	 * Render all blocks from ACF flexible content
	 *
	 * Main entry point for rendering page builder blocks.
	 * Maintains backward compatibility with global $pageBuilder variable.
	 *
	 * @param array|null $blocks Array of ACF blocks or null.
	 * @return void
	 */
	public function render( ?array $blocks ): void {
		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			if ( function_exists( 'soma_log_debug' ) ) {
				soma_log_debug( 'BlockRenderer: No blocks to render' );
			}
			return;
		}

		foreach ( $blocks as $index => $block ) {
			$this->render_block( $block, $index );
		}
	}

	/**
	 * Render a single block
	 *
	 * @param array $block   ACF block data.
	 * @param int   $counter Block index/counter.
	 * @return void
	 */
	public function render_block( array $block, int $counter ): void {
		// Validate block structure
		if ( ! $this->validate_block( $block ) ) {
			$this->handle_error( $block['acf_fc_layout'] ?? 'unknown', 'Invalid block structure' );
			return;
		}

		$layout = $block['acf_fc_layout'];

		// Check if block is registered
		if ( ! $this->registry->is_registered( $layout ) ) {
			$this->handle_error( $layout, 'Block not registered in BlockRegistry' );
			return;
		}

		// Get field group and partial path
		$field_group = $this->registry->get_field_group( $layout );
		$partial     = $this->registry->get_partial_path( $layout );

		if ( $field_group === null || $partial === null ) {
			$this->handle_error( $layout, 'Missing field group or partial path' );
			return;
		}

		// Check if partial file exists
		$partial_file = $this->registry->get_partial_file_path( $layout );
		if ( $partial_file === null || ! file_exists( $partial_file ) ) {
			$this->handle_error( $layout, sprintf( 'Partial file not found: %s', $partial_file ?? 'unknown' ) );
			return;
		}

		// Pass block data to partial via WordPress query vars
		set_query_var( 'soma_block_counter', $counter );
		set_query_var( 'soma_block_content', $block[ $field_group ] ?? array() );
		set_query_var( 'soma_block_layout', $layout );

		// Render with optional caching
		if ( $this->caching_enabled ) {
			$block_data = array(
				'block_counter' => $counter,
				'block_content' => $block[ $field_group ] ?? array(),
			);
			$this->render_with_cache( $layout, $partial, $block_data );
		} else {
			$this->render_partial( $partial );
		}
	}

	/**
	 * Validate block structure
	 *
	 * @param array $block ACF block data.
	 * @return bool True if valid, false otherwise.
	 */
	private function validate_block( array $block ): bool {
		// Must have acf_fc_layout key
		if ( ! isset( $block['acf_fc_layout'] ) ) {
			if ( function_exists( 'soma_log_warning' ) ) {
				soma_log_warning( 'BlockRenderer: Block missing acf_fc_layout key', array( 'block' => $block ) );
			}
			return false;
		}

		// Layout must be non-empty string
		if ( ! is_string( $block['acf_fc_layout'] ) || empty( $block['acf_fc_layout'] ) ) {
			if ( function_exists( 'soma_log_warning' ) ) {
				soma_log_warning( 'BlockRenderer: Invalid acf_fc_layout value', array( 'layout' => $block['acf_fc_layout'] ) );
			}
			return false;
		}

		return true;
	}

	/**
	 * Handle rendering errors
	 *
	 * Logs errors and displays message in development environment.
	 *
	 * @param string $layout Layout name.
	 * @param string $error  Error message.
	 * @return void
	 */
	private function handle_error( string $layout, string $error ): void {
		$message = sprintf( 'BlockRenderer Error [%s]: %s', $layout, $error );

		// Log error via PSR-3 logger
		if ( function_exists( 'soma_log_error' ) ) {
			soma_log_error(
				$message,
				array(
					'layout' => $layout,
					'error'  => $error,
				)
			);
		}

		// Display error in development (WP_DEBUG)
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			echo '<div style="background:#ffebee;color:#c62828;padding:1rem;margin:1rem 0;border-left:4px solid #c62828;font-family:monospace;">';
			echo '<strong>Block Rendering Error:</strong><br>';
			echo esc_html( $message );
			echo '</div>';
		}
	}

	/**
	 * Render partial template
	 *
	 * @param string $partial Partial name without extension.
	 * @return void
	 */
	private function render_partial( string $partial ): void {
		get_template_part( 'partials/' . $partial );
	}

	/**
	 * Render with caching
	 *
	 * @param string $layout    Layout name.
	 * @param string $partial   Partial name.
	 * @param array  $pageBlock Page block data.
	 * @return void
	 */
	private function render_with_cache( string $layout, string $partial, array $pageBlock ): void {
		if ( ! function_exists( 'soma_cache_remember' ) ) {
			// Fallback to non-cached rendering
			$this->render_partial( $partial );
			return;
		}

		$cache_key  = $this->get_cache_key( $layout, $pageBlock );
		$cache_tags = array( 'page_builder', 'block_' . $layout );

		// Get or generate cached output
		$output = soma_cache_remember(
			$cache_key,
			function () use ( $partial ) {
				ob_start();
				$this->render_partial( $partial );
				return ob_get_clean();
			},
			$this->cache_ttl,
			$cache_tags
		);

		// Output cached content
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $output;
	}

	/**
	 * Generate cache key for block
	 *
	 * @param string $layout    Layout name.
	 * @param array  $pageBlock Page block data.
	 * @return string Cache key.
	 */
	private function get_cache_key( string $layout, array $pageBlock ): string {
		// Include block counter and content hash in key
		$content_hash = md5( serialize( $pageBlock['block_content'] ) );
		return sprintf(
			'block_%s_%d_%s',
			$layout,
			$pageBlock['block_counter'],
			$content_hash
		);
	}

	/**
	 * Invalidate block cache
	 *
	 * Can be called on post save to clear cached blocks.
	 *
	 * @param string|null $layout Optional layout name to invalidate specific block type.
	 * @return void
	 */
	public function invalidate_cache( ?string $layout = null ): void {
		if ( ! function_exists( 'soma_cache_invalidate_tags' ) ) {
			return;
		}

		if ( $layout !== null ) {
			// Invalidate specific block type
			soma_cache_invalidate_tags( array( 'block_' . $layout ) );
		} else {
			// Invalidate all page builder blocks
			soma_cache_invalidate_tags( array( 'page_builder' ) );
		}

		if ( function_exists( 'soma_log_debug' ) ) {
			soma_log_debug(
				'BlockRenderer: Cache invalidated',
				array( 'layout' => $layout ?? 'all' )
			);
		}
	}

	/**
	 * Get rendering statistics
	 *
	 * Useful for debugging and performance monitoring.
	 *
	 * @return array{registered_blocks: int, caching_enabled: bool, cache_ttl: int}
	 */
	public function get_stats(): array {
		return array(
			'registered_blocks' => $this->registry->count(),
			'caching_enabled'   => $this->caching_enabled,
			'cache_ttl'         => $this->cache_ttl,
		);
	}
}
