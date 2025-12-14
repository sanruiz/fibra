<?php
/**
 * Events Custom Post Type
 *
 * Registers and manages the Events post type.
 *
 * @package Soma\PostTypes\Types
 * @since 3.0.0
 */

namespace Soma\PostTypes\Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Events
 *
 * Singleton class for Events post type registration.
 *
 * @package Soma\PostTypes\Types
 */
class Events {

	/**
	 * Post type slug
	 *
	 * @var string
	 */
	private const POST_TYPE = 'events';

	/**
	 * Singleton instance
	 *
	 * @var Events|null
	 */
	private static ?Events $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Events The singleton instance.
	 */
	public static function instance(): Events {
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
	 * Register the Events post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'             => array(
					'name'          => __( 'Events', 'soma' ),
					'singular_name' => __( 'Event', 'soma' ),
				),
				'public'             => true,
				'publicly_queryable' => false,
				'has_archive'        => false,
				'show_in_rest'       => false,
				'show_in_menu'       => true,
				'rewrite'            => array( 'slug' => self::POST_TYPE ),
				'menu_icon'          => 'dashicons-clipboard',
				'supports'           => array( 'title', 'thumbnail' ),
			)
		);
	}

	/**
	 * Get the post type slug.
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
	}
}
