<?php
/**
 * News Custom Post Type
 *
 * Registers and manages the News post type.
 *
 * @package Soma\PostTypes\Types
 * @since 3.0.0
 */

namespace Soma\PostTypes\Types;
use Soma\Core\Enums\PostType;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class News
 *
 * Singleton class for News post type registration.
 *
 * @package Soma\PostTypes\Types
 */
class News {

	/**
	 * Post type enum
	 *
	 * @var PostType
	 */
	private const POST_TYPE = PostType::NEWS;

	/**
	 * Singleton instance
	 *
	 * @var News|null
	 */
	private static ?News $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return News The singleton instance.
	 */
	public static function instance(): News {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Prevent cloning of the instance.
	 *
	 * @return void
	 */
	private function __clone() {
		// Singleton pattern - no cloning.
	}

	/**
	 * Prevent unserialization of the instance.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Singleton pattern - no unserialization.
	}

	/**
	 * Initialize the post type.
	 *
	 * @return void
	 */
	private function init(): void {
		add_action( 'init', $this->register( ... ) );
	}

	/**
	 * Register the News post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE->value(),
			[
				'labels'             => [
					'name'          => __( 'News', 'soma' ),
					'singular_name' => __( 'News Article', 'soma' ),
				],
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'show_in_rest'       => false,
				'show_in_menu'       => true,
				'rewrite'            => [ 'slug' => self::POST_TYPE->value() ],
				'menu_icon'          => 'dashicons-media-document',
				'supports'           => [ 'title', 'thumbnail' ],
			]
		);
	}

	/**
	 * Get the post type slug.
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE->value();
	}
}
