<?php
/**
 * Project Type Taxonomy
 *
 * Registers the 'project-type' taxonomy for portfolio items.
 * This taxonomy functions like WordPress tags (non-hierarchical)
 * and replaces the old ACF 'project_type' field.
 *
 * Examples: "Office Building", "Shopping Center", "Residential Complex"
 *
 * @package    Soma
 * @subpackage Taxonomies
 * @since      3.1.23
 */

namespace Soma\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Soma\Core\Enums\PostType;

/**
 * Project Type Taxonomy class
 *
 * Registers the project-type taxonomy for categorizing portfolio items
 * by building/project type. Works like WordPress tags (flat, non-hierarchical).
 *
 * @since 3.1.23
 */
class ProjectTypeTaxonomy {

	/**
	 * Singleton instance
	 *
	 * @var ProjectTypeTaxonomy|null
	 */
	private static ?ProjectTypeTaxonomy $instance = null;

	/**
	 * Taxonomy slug
	 *
	 * @var string
	 */
	public const TAXONOMY = 'project-type';

	/**
	 * Get singleton instance
	 *
	 * @return ProjectTypeTaxonomy
	 */
	public static function instance(): ProjectTypeTaxonomy {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->init();
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
	 * Initialize the taxonomy
	 *
	 * @return void
	 */
	private function init(): void {
		add_action( 'init', $this->register( ... ), 0 );
	}

	/**
	 * Register the taxonomy
	 *
	 * @return void
	 */
	public function register(): void {
		$labels = array(
			'name'                       => _x( 'Project Types', 'taxonomy general name', 'soma' ),
			'singular_name'              => _x( 'Project Type', 'taxonomy singular name', 'soma' ),
			'search_items'               => __( 'Search Project Types', 'soma' ),
			'popular_items'              => __( 'Popular Project Types', 'soma' ),
			'all_items'                  => __( 'All Project Types', 'soma' ),
			'edit_item'                  => __( 'Edit Project Type', 'soma' ),
			'update_item'                => __( 'Update Project Type', 'soma' ),
			'add_new_item'               => __( 'Add New Project Type', 'soma' ),
			'new_item_name'              => __( 'New Project Type Name', 'soma' ),
			'separate_items_with_commas' => __( 'Separate project types with commas', 'soma' ),
			'add_or_remove_items'        => __( 'Add or remove project types', 'soma' ),
			'choose_from_most_used'      => __( 'Choose from the most used project types', 'soma' ),
			'not_found'                  => __( 'No project types found.', 'soma' ),
			'menu_name'                  => __( 'Project Types', 'soma' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => false, // Like tags, NOT categories.
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true, // Enable Gutenberg & REST API.
			'query_var'          => true,
			'rewrite'            => array(
				'slug'         => 'project-type',
				'with_front'   => false,
				'hierarchical' => false,
			),
		);

		register_taxonomy(
			self::TAXONOMY,
			array( PostType::PORTFOLIO->value() ),
			$args
		);
	}

	/**
	 * Get the taxonomy slug
	 *
	 * @return string
	 */
	public function get_taxonomy(): string {
		return self::TAXONOMY;
	}
}
