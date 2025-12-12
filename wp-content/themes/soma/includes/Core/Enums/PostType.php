<?php
/**
 * Post Type Enum
 *
 * Defines all custom post types used in the theme.
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
 * Post Type Enum
 *
 * Backed enum for all custom post types in the theme.
 * Provides type-safe post type identifiers and helper methods.
 *
 * @since 3.0.0
 */
enum PostType: string {
	case PORTFOLIO    = 'portfolio';
	case NEWS         = 'news';
	case CAREERS      = 'careers';
	case TEAM_MEMBERS = 'team_members';

	/**
	 * Get the post type value
	 *
	 * @return string
	 */
	public function value(): string {
		return $this->value;
	}

	/**
	 * Get human-readable label for the post type
	 *
	 * @return string
	 */
	public function label(): string {
		return match ( $this ) {
			self::PORTFOLIO    => __( 'Portfolio', 'soma' ),
			self::NEWS         => __( 'News', 'soma' ),
			self::CAREERS      => __( 'Careers', 'soma' ),
			self::TEAM_MEMBERS => __( 'Team Members', 'soma' ),
		};
	}

	/**
	 * Get plural label for the post type
	 *
	 * @return string
	 *
	 * @phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public function pluralLabel(): string {
		return match ( $this ) {
			self::PORTFOLIO    => __( 'Portfolio Items', 'soma' ),
			self::NEWS         => __( 'News', 'soma' ),
			self::CAREERS      => __( 'Careers', 'soma' ),
			self::TEAM_MEMBERS => __( 'Team Members', 'soma' ),
		};
	}

	/**
	 * Get all post type values
	 *
	 * @return array<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}

	/**
	 * Get all post type labels
	 *
	 * @return array<string, string>
	 */
	public static function labels(): array {
		$labels = [];
		foreach ( self::cases() as $case ) {
			$labels[ $case->value ] = $case->label();
		}
		return $labels;
	}


}
