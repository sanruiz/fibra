<?php
/**
 * Cache Invalidation Manager
 *
 * Automatic cache invalidation on WordPress events.
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
 * Cache Invalidation Manager
 *
 * Automatically invalidates cache on post/term/option changes.
 *
 * @since 3.0.0
 */
class CacheInvalidationManager {

	/**
	 * Singleton instance
	 *
	 * @var CacheInvalidationManager|null
	 */
	private static ?CacheInvalidationManager $instance = null;

	/**
	 * Cache instance
	 *
	 * @var Cache
	 */
	private Cache $cache;

	/**
	 * Get singleton instance
	 *
	 * @return CacheInvalidationManager
	 */
	public static function instance(): CacheInvalidationManager {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {
		$this->cache = Cache::instance();
		$this->register_hooks();
	}

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
	 * Register WordPress hooks
	 *
	 * @return void
	 */
	private function register_hooks(): void {
		// Post hooks.
		add_action( 'save_post', $this->on_post_save(...), 10, 3 );
		add_action( 'delete_post', $this->on_post_delete(...) );
		add_action( 'trash_post', $this->on_post_delete(...) );

		// Term hooks.
		add_action( 'created_term', $this->on_term_change(...), 10, 3 );
		add_action( 'edited_term', $this->on_term_change(...), 10, 3 );
		add_action( 'delete_term', $this->on_term_change(...), 10, 3 );

		// Option hooks.
		add_action( 'update_option', $this->on_option_change(...), 10, 3 );

		// Navigation menu hooks.
		add_action( 'wp_update_nav_menu', $this->on_menu_update(...) );

		// Widget hooks.
		add_action( 'update_option_sidebars_widgets', $this->on_widgets_update(...) );

		// Theme customizer hooks.
		add_action( 'customize_save_after', $this->on_customizer_save(...) );
	}

	/**
	 * Handle post save event
	 *
	 * @param int $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @param bool $update Whether this is an update.
	 * @return void
	 */
	public function on_post_save( int $post_id, \WP_Post $post, bool $update ): void {
		// Skip autosaves and revisions.
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		$tags = CacheTag::forPostType( $post->post_type );
		$this->cache->invalidate_tags( $tags );

		// Log invalidation.
		soma_log_debug(
			'Cache invalidated for post type: {post_type}',
			[
				'post_type' => $post->post_type,
				'post_id'   => $post_id,
				'tags'      => array_map( fn( $tag ) => $tag->value(), $tags ),
			]
		);
	}

	/**
	 * Handle post delete event
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function on_post_delete( int $post_id ): void {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return;
		}

		$tags = CacheTag::forPostType( $post->post_type );
		$this->cache->invalidate_tags( $tags );
	}

	/**
	 * Handle term change event
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 * @return void
	 */
	public function on_term_change( int $term_id, int $tt_id, string $taxonomy ): void {
		// Invalidate post type caches for taxonomies.
		$this->cache->invalidate_tags( [ CacheTag::POST_TYPES ] );
	}

	/**
	 * Handle option change event
	 *
	 * @param string $option Option name.
	 * @param mixed $old_value Old value.
	 * @param mixed $new_value New value.
	 * @return void
	 */
	public function on_option_change( string $option, $old_value, $new_value ): void {
		// Check if it's an ACF options page.
		if ( str_starts_with( $option, 'options_' ) ) {
			$this->cache->invalidate_tags( [ CacheTag::OPTIONS ] );
		}
	}

	/**
	 * Handle menu update event
	 *
	 * @param int $menu_id Menu ID.
	 * @return void
	 */
	public function on_menu_update( int $menu_id ): void {
		$this->cache->invalidate_tags( [ CacheTag::NAVIGATION ] );
	}

	/**
	 * Handle widgets update event
	 *
	 * @param mixed $old_value Old sidebars value.
	 * @return void
	 */
	public function on_widgets_update( $old_value ): void {
		$this->cache->invalidate_tags( [ CacheTag::WIDGETS ] );
	}

	/**
	 * Handle customizer save event
	 *
	 * @param \WP_Customize_Manager $manager Customizer manager.
	 * @return void
	 */
	public function on_customizer_save( \WP_Customize_Manager $manager ): void {
		// Invalidate all cache on customizer save.
		$this->cache->flush();

		soma_log_info( 'Cache flushed after customizer save' );
	}

	/**
	 * Manually invalidate cache for specific tags
	 *
	 * @param array<CacheTag> $tags Tags to invalidate.
	 * @return int Number of entries invalidated.
	 */
	public function invalidate( array $tags ): int {
		$count = $this->cache->invalidate_tags( $tags );

		soma_log_debug(
			'Manual cache invalidation: {count} entries',
			[
				'count' => $count,
				'tags'  => array_map( fn( $tag ) => $tag->value(), $tags ),
			]
		);

		return $count;
	}
}
