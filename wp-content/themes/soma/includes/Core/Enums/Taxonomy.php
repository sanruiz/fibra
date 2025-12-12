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
	 * Get taxonomy configuration arguments
	 *
	 * @return array<string, mixed>
	 *
	 * @phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public function getArgs(): array {
		return array(
			'labels'            => array(
				'name'          => $this->label(),
				'singular_name' => $this->singularLabel(),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'with_front' => false ),
		);
	}
}
