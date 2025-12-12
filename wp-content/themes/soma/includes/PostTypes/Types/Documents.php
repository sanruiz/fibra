<?php
/**
 * Documents Custom Post Type
 *
 * Registers and manages the Documents & Reports post type.
 *
 * @package Soma\PostTypes\Types
 * @since 3.0.0
 */

namespace Soma\PostTypes\Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Documents
 *
 * Singleton class for Documents post type registration.
 *
 * @package Soma\PostTypes\Types
 */
class Documents {

	/**
	 * Post type slug
	 *
	 * @var string
	 */
	private const POST_TYPE = 'documents-reports';

	/**
	 * Singleton instance
	 *
	 * @var Documents|null
	 */
	private static ?Documents $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Documents The singleton instance.
	 */
	public static function instance(): Documents {
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
	 * Register the Documents post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'             => [
					'name'          => __( 'Documents & Reports', 'soma' ),
					'singular_name' => __( 'Document', 'soma' ),
				],
				'public'             => true,
				'publicly_queryable' => false,
				'has_archive'        => false,
				'show_in_rest'       => false,
				'show_in_menu'       => true,
				'rewrite'            => [ 'slug' => self::POST_TYPE ],
				'menu_icon'          => 'dashicons-pdf',
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
