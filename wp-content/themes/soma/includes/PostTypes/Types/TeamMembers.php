<?php
/**
 * Team Members Custom Post Type
 *
 * Registers and manages the Team Members post type.
 *
 * @package Soma\PostTypes\Types
 * @since 3.0.0
 */

namespace Soma\PostTypes\Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class TeamMembers
 *
 * Singleton class for Team Members post type registration.
 *
 * @package Soma\PostTypes\Types
 */
class TeamMembers {

	/**
	 * Post type slug
	 *
	 * @var string
	 */
	private const POST_TYPE = 'team-members';

	/**
	 * Singleton instance
	 *
	 * @var TeamMembers|null
	 */
	private static ?TeamMembers $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return TeamMembers The singleton instance.
	 */
	public static function instance(): TeamMembers {
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
	 * Register the Team Members post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'             => [
					'name'          => __( 'Team Members', 'soma' ),
					'singular_name' => __( 'Team Member', 'soma' ),
				],
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'show_in_rest'       => false,
				'show_in_menu'       => true,
				'rewrite'            => [ 'slug' => self::POST_TYPE ],
				'menu_icon'          => 'dashicons-groups',
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
