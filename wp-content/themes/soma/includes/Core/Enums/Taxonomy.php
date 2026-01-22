<?php
/**
 * Taxonomy Enum
 *
 * Defines all custom taxonomies used in the theme.
 *
 * @package Soma
 * @subpackage Core\Enums
 * @since 3.0.0
 */

namespace Soma\Core\Enums;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Taxonomy Enum
 *
 * Backed enum for all custom taxonomies in the theme.
 * Provides type-safe taxonomy identifiers and helper methods.
 *
 * @since 3.0.0
 */
enum Taxonomy: string {
	case TEAM_MEMBERS = 'team-members-taxonomy';
	case PORTFOLIO    = 'portfolio-taxonomy';
	case DOCUMENTS    = 'documents-taxonomy';
	case PROJECT_TYPE = 'project-type';

	/**
	 * Get the taxonomy slug
	 *
	 * @return string
	 */
	public function value(): string {
		return $this->value;
	}

	/**
	 * Get human-readable label for the taxonomy
	 *
	 * @return string
	 */
	public function label(): string {
		return match ( $this ) {
			self::TEAM_MEMBERS => __( 'Team Member Categories', 'soma' ),
			self::PORTFOLIO    => __( 'Portfolio Categories', 'soma' ),
			self::DOCUMENTS    => __( 'Document Categories', 'soma' ),
			self::PROJECT_TYPE => __( 'Project Types', 'soma' ),
		};
	}

	/**
	 * Get singular label for the taxonomy
	 *
	 * @return string
	 *
	 * @phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public function singularLabel(): string {
		return match ( $this ) {
			self::TEAM_MEMBERS => __( 'Team Member Category', 'soma' ),
			self::PORTFOLIO    => __( 'Portfolio Category', 'soma' ),
			self::DOCUMENTS    => __( 'Document Category', 'soma' ),
			self::PROJECT_TYPE => __( 'Project Type', 'soma' ),
		};
	}

	/**
	 * Get associated post type for the taxonomy
	 *
	 * @return string
	 *
	 * @phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public function postType(): string {
		return match ( $this ) {
			self::TEAM_MEMBERS => 'team-members',
			self::PORTFOLIO    => 'portfolio',
			self::DOCUMENTS    => 'documents-reports',
			self::PROJECT_TYPE => 'portfolio',
		};
	}

	/**
	 * Get all taxonomy values
	 *
	 * @return array<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}

	/**
	 * Get all taxonomy labels
	 *
	 * @return array<string, string>
	 */
	public static function labels(): array {
		$labels = array();
		foreach ( self::cases() as $case ) {
			$labels[ $case->value ] = $case->label();
		}
		return $labels;
	}

	/**
	 * Check if taxonomy is hierarchical
	 *
	 * @return bool True for category-like, false for tag-like
	 *
	 * @phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public function isHierarchical(): bool {
		return match ( $this ) {
			self::TEAM_MEMBERS => true,  // Categories (hierarchical).
			self::PORTFOLIO    => true,  // Categories (hierarchical).
			self::DOCUMENTS    => true,  // Categories (hierarchical).
			self::PROJECT_TYPE => false, // Tags (non-hierarchical, like WordPress tags).
		};
	}

	/**
	 * Get taxonomy configuration arguments
	 *
	 * @return array<string, mixed>
	 *
	 * @phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public function getArgs(): array {
		$is_hierarchical = $this->isHierarchical();

		return array(
			'labels'            => array(
				'name'                       => $this->label(),
				'singular_name'              => $this->singularLabel(),
				'search_items'               => $is_hierarchical
					? sprintf( /* translators: %s: taxonomy label */ __( 'Search %s', 'soma' ), $this->label() )
					: sprintf( /* translators: %s: taxonomy label */ __( 'Search %s', 'soma' ), $this->label() ),
				'popular_items'              => $is_hierarchical
					? null
					: sprintf( /* translators: %s: taxonomy label */ __( 'Popular %s', 'soma' ), $this->label() ),
				'all_items'                  => sprintf( /* translators: %s: taxonomy label */ __( 'All %s', 'soma' ), $this->label() ),
				'edit_item'                  => sprintf( /* translators: %s: taxonomy singular label */ __( 'Edit %s', 'soma' ), $this->singularLabel() ),
				'update_item'                => sprintf( /* translators: %s: taxonomy singular label */ __( 'Update %s', 'soma' ), $this->singularLabel() ),
				'add_new_item'               => sprintf( /* translators: %s: taxonomy singular label */ __( 'Add New %s', 'soma' ), $this->singularLabel() ),
				'new_item_name'              => sprintf( /* translators: %s: taxonomy singular label */ __( 'New %s Name', 'soma' ), $this->singularLabel() ),
				'separate_items_with_commas' => $is_hierarchical
					? null
					: sprintf( /* translators: %s: taxonomy label */ __( 'Separate %s with commas', 'soma' ), strtolower( $this->label() ) ),
				'add_or_remove_items'        => $is_hierarchical
					? null
					: sprintf( /* translators: %s: taxonomy label */ __( 'Add or remove %s', 'soma' ), strtolower( $this->label() ) ),
				'choose_from_most_used'      => $is_hierarchical
					? null
					: sprintf( /* translators: %s: taxonomy label */ __( 'Choose from the most used %s', 'soma' ), strtolower( $this->label() ) ),
				'not_found'                  => sprintf( /* translators: %s: taxonomy label */ __( 'No %s found', 'soma' ), strtolower( $this->label() ) ),
				'menu_name'                  => $this->label(),
			),
			'hierarchical'      => $is_hierarchical,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true, // Enable Gutenberg/REST API support.
			'rewrite'           => array( 'with_front' => false ),
		);
	}
}
