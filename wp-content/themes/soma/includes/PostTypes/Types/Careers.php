<?php
/**
 * Careers Custom Post Type
 *
 * Registers and manages the Careers post type.
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
 * Class Careers
 *
 * Singleton class for Careers post type registration.
 *
 * @package Soma\PostTypes\Types
 */
class Careers {

	/**
	 * Post type enum
	 *
	 * @var PostType
	 */
	private const POST_TYPE = PostType::CAREERS;

	/**
	 * Singleton instance
	 *
	 * @var Careers|null
	 */
	private static ?Careers $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Careers The singleton instance.
	 */
	public static function instance(): Careers {
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
	 * Register the Careers post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE->value(),
			[
				'labels'             => [
					'name'          => __( 'Careers', 'soma' ),
					'singular_name' => __( 'Career', 'soma' ),
				],
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'show_in_rest'       => false,
				'show_in_menu'       => true,
				'rewrite'            => [ 'slug' => self::POST_TYPE->value() ],
				'menu_icon'          => 'dashicons-universal-access',
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
