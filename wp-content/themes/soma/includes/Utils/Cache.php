<?php
/**
 * Cache Class
 *
 * Tag-based caching system for improved performance.
 *
 * @package Soma
 * @subpackage Utils
 * @since 3.0.0
 */

namespace Soma\Utils;

use Soma\Utils\Enums\CacheTag;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cache Class
 *
 * Singleton cache manager with tag-based invalidation.
 * Uses WordPress object cache when available, falls back to transients.
 *
 * @since 3.0.0
 */
class Cache {

	/**
	 * Singleton instance
	 *
	 * @var Cache|null
	 */
	private static ?Cache $instance = null;

	/**
	 * Cache prefix
	 *
	 * @var string
	 */
	private string $prefix = 'soma_cache_';

	/**
	 * Tag index option name
	 *
	 * @var string
	 */
	private string $tag_index = 'soma_cache_tags';

	/**
	 * Get singleton instance
	 *
	 * @return Cache
	 */
	public static function instance(): Cache {
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
	 * Get cached value
	 *
	 * @param string $key Cache key.
	 * @param mixed  $default Default value if not found.
	 * @return mixed
	 */
	public function get( string $key, $default = null ) {
		$full_key = $this->prefix . $key;

		// Try object cache first.
		$value = wp_cache_get( $full_key, 'soma' );
		if ( false !== $value ) {
			return $value;
		}

		// Fallback to transient.
		$value = get_transient( $full_key );
		if ( false !== $value ) {
			// Store in object cache for this request.
			wp_cache_set( $full_key, $value, 'soma' );
			return $value;
		}

		return $default;
	}

	/**
	 * Set cached value
	 *
	 * @param string          $key Cache key.
	 * @param mixed           $value Value to cache.
	 * @param int             $ttl Time to live in seconds (default: 1 hour).
	 * @param array<CacheTag> $tags Cache tags for invalidation.
	 * @return bool
	 */
	public function set( string $key, $value, int $ttl = 3600, array $tags = array() ): bool {
		$full_key = $this->prefix . $key;

		// Store in object cache.
		wp_cache_set( $full_key, $value, 'soma', $ttl );

		// Store in transient for persistence.
		$result = set_transient( $full_key, $value, $ttl );

		// Register tags.
		if ( ! empty( $tags ) ) {
			$this->register_tags( $key, $tags );
		}

		return $result;
	}

	/**
	 * Remember: Get from cache or execute callback and cache result
	 *
	 * @param string          $key Cache key.
	 * @param callable        $callback Callback to execute if cache miss.
	 * @param int             $ttl Time to live in seconds (default: 1 hour).
	 * @param array<CacheTag> $tags Cache tags for invalidation.
	 * @return mixed
	 */
	public function remember( string $key, callable $callback, int $ttl = 3600, array $tags = array() ) {
		$value = $this->get( $key );

		if ( null !== $value ) {
			return $value;
		}

		$value = $callback();
		$this->set( $key, $value, $ttl, $tags );

		return $value;
	}

	/**
	 * Delete cached value
	 *
	 * @param string $key Cache key.
	 * @return bool
	 */
	public function delete( string $key ): bool {
		$full_key = $this->prefix . $key;

		// Remove from object cache.
		wp_cache_delete( $full_key, 'soma' );

		// Remove from transient.
		return delete_transient( $full_key );
	}

	/**
	 * Invalidate all cache entries with given tags
	 *
	 * @param array<CacheTag> $tags Tags to invalidate.
	 * @return int Number of entries invalidated.
	 */
	public function invalidate_tags( array $tags ): int {
		$tag_index   = $this->get_tag_index();
		$invalidated = 0;

		foreach ( $tags as $tag ) {
			// Support both CacheTag enums and string tags.
			$tag_value = \is_string( $tag ) ? $tag : $tag->value();
			if ( ! isset( $tag_index[ $tag_value ] ) ) {
				continue;
			}

			foreach ( $tag_index[ $tag_value ] as $key ) {
				if ( $this->delete( $key ) ) {
					++$invalidated;
				}
			}

			// Remove tag from index.
			unset( $tag_index[ $tag_value ] );
		}

		$this->save_tag_index( $tag_index );

		return $invalidated;
	}

	/**
	 * Flush all cache
	 *
	 * @return bool
	 */
	public function flush(): bool {
		global $wpdb;

		// Clear object cache.
		wp_cache_flush();

		// Delete all transients with our prefix.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_' . $this->prefix ) . '%'
			)
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_timeout_' . $this->prefix ) . '%'
			)
		);

		// Clear tag index.
		delete_option( $this->tag_index );

		return true;
	}

	/**
	 * Register tags for a cache key
	 *
	 * @param string          $key Cache key.
	 * @param array<CacheTag> $tags Tags to register.
	 * @return void
	 */
	private function register_tags( string $key, array $tags ): void {
		$tag_index = $this->get_tag_index();

		foreach ( $tags as $tag ) {
			$tag_value = $tag->value();
			if ( ! isset( $tag_index[ $tag_value ] ) ) {
				$tag_index[ $tag_value ] = array();
			}
			if ( ! in_array( $key, $tag_index[ $tag_value ], true ) ) {
				$tag_index[ $tag_value ][] = $key;
			}
		}

		$this->save_tag_index( $tag_index );
	}

	/**
	 * Get tag index
	 *
	 * @return array<string, array<string>>
	 */
	private function get_tag_index(): array {
		$index = get_option( $this->tag_index, array() );
		return is_array( $index ) ? $index : array();
	}

	/**
	 * Save tag index
	 *
	 * @param array<string, array<string>> $index Tag index.
	 * @return void
	 */
	private function save_tag_index( array $index ): void {
		update_option( $this->tag_index, $index, false );
	}

	/**
	 * Get cache statistics
	 *
	 * @return array<string, mixed>
	 */
	public function get_stats(): array {
		$tag_index  = $this->get_tag_index();
		$total_keys = 0;

		foreach ( $tag_index as $keys ) {
			$total_keys += count( $keys );
		}

		return array(
			'total_tags' => count( $tag_index ),
			'total_keys' => $total_keys,
			'tags'       => array_map( 'count', $tag_index ),
		);
	}

	/**
	 * Clean up expired cache entries
	 *
	 * @return int Number of entries cleaned.
	 */
	public function cleanup(): int {
		global $wpdb;

		// Get expired transients.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$expired = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT REPLACE(option_name, '_transient_timeout_', '') FROM {$wpdb->options}
				WHERE option_name LIKE %s
				AND option_value < %d",
				$wpdb->esc_like( '_transient_timeout_' . $this->prefix ) . '%',
				time()
			)
		);

		$cleaned = 0;
		foreach ( $expired as $key ) {
			if ( delete_transient( $key ) ) {
				++$cleaned;
			}
		}

		return $cleaned;
	}
}
