<?php
/**
 * Portfolio Custom Post Type
 *
 * Registers and manages the Portfolio post type for project showcases.
 *
 * @package Soma\PostTypes\Types
 * @since 3.0.0
 */

namespace Soma\PostTypes\Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Portfolio
 *
 * Singleton class for Portfolio post type registration.
 *
 * @package Soma\PostTypes\Types
 */
class Portfolio {

	/**
	 * Post type slug
	 *
	 * @var string
	 */
	private const POST_TYPE = 'portfolio';

	/**
	 * Singleton instance
	 *
	 * @var Portfolio|null
	 */
	private static ?Portfolio $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Portfolio The singleton instance.
	 */
	public static function instance(): Portfolio {
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
		add_action( 'init', $this->register(...) );
	}

	/**
	 * Register the Portfolio post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'             => [
					'name'          => __( 'Portfolio', 'soma' ),
					'singular_name' => __( 'Project', 'soma' ),
				],
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'show_in_rest'       => false,
				'show_in_menu'       => true,
				'rewrite'            => [ 'slug' => self::POST_TYPE ],
				'menu_icon'          => 'dashicons-portfolio',
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
		return self::POST_TYPE;
	}
}
