<?php
/**
 * Team Members Taxonomy
 *
 * Registers and manages the taxonomy for Team Members post type categorization.
 *
 * @package Soma\Taxonomies
 * @since 3.0.0
 */

namespace Soma\Taxonomies;

use Soma\Core\Enums\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class TeamMembersTaxonomy
 *
 * Singleton class for Team Members taxonomy registration.
 *
 * @package Soma\Taxonomies
 */
class TeamMembersTaxonomy {

	/**
	 * Taxonomy enum
	 *
	 * @var Taxonomy
	 */
	private const TAXONOMY = Taxonomy::TEAM_MEMBERS;

	/**
	 * Singleton instance
	 *
	 * @var TeamMembersTaxonomy|null
	 */
	private static ?TeamMembersTaxonomy $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return TeamMembersTaxonomy The singleton instance.
	 */
	public static function instance(): TeamMembersTaxonomy {
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
	 * Initialize the taxonomy.
	 *
	 * @return void
	 */
	private function init(): void {
		add_action( 'init', $this->register( ... ), 0 );
	}

	/**
	 * Register the Team Members taxonomy.
	 *
	 * @return void
	 */
	public function register(): void {
		register_taxonomy(
			self::TAXONOMY->value(),
			array( self::TAXONOMY->postType() ),
			self::TAXONOMY->getArgs()
		);
	}

	/**
	 * Get the taxonomy slug.
	 *
	 * @return string
	 */
	public function get_taxonomy(): string {
		return self::TAXONOMY->value();
	}

	/**
	 * Get the associated post type.
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::TAXONOMY->postType();
	}
}
