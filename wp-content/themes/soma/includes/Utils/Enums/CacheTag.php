<?php
/**
 * Cache Tag Enum
 *
 * Defines cache tags for organized invalidation.
 *
 * @package Soma
 * @subpackage Utils\Enums
 * @since 3.0.0
 */

namespace Soma\Utils\Enums;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cache Tag Enum
 *
 * Provides cache tags for tag-based invalidation strategy.
 * Tags allow invalidating groups of related cache entries.
 *
 * @since 3.0.0
 */
enum CacheTag: string {
	case POST_TYPES    = 'post_types';
	case CUSTOM_FIELDS = 'custom_fields';
	case API           = 'api';
	case WIDGETS       = 'widgets';
	case PORTFOLIO     = 'portfolio';
	case NEWS          = 'news';
	case CAREERS       = 'careers';
	case TEAM_MEMBERS  = 'team_members';
	case NAVIGATION    = 'navigation';
	case OPTIONS       = 'options';
	case TEMPLATES     = 'templates';

	/**
	 * Get the cache tag value
	 *
	 * @return string
	 */
	public function value(): string {
		return $this->value;
	}

	/**
	 * Get human-readable label for the cache tag
	 *
	 * @return string
	 */
	public function label(): string {
		return match ( $this ) {
			self::POST_TYPES    => __( 'Post Types', 'soma' ),
			self::CUSTOM_FIELDS => __( 'Custom Fields', 'soma' ),
			self::API           => __( 'REST API', 'soma' ),
			self::WIDGETS       => __( 'Widgets', 'soma' ),
			self::PORTFOLIO     => __( 'Portfolio', 'soma' ),
			self::NEWS          => __( 'News', 'soma' ),
			self::CAREERS       => __( 'Careers', 'soma' ),
			self::TEAM_MEMBERS  => __( 'Team Members', 'soma' ),
			self::NAVIGATION    => __( 'Navigation', 'soma' ),
			self::OPTIONS       => __( 'Options', 'soma' ),
			self::TEMPLATES     => __( 'Templates', 'soma' ),
		};
	}

	/**
	 * Get cache key prefix for this tag
	 *
	 * @return string
	 */
	public function prefix(): string {
		return 'soma_' . $this->value . '_';
	}

	/**
	 * Get all cache tag values
	 *
	 * @return array<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}

	/**
	 * Get tags for a post type
	 *
	 * @param string $post_type Post type name.
	 * @return array<self>
	 *
	 * @phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public static function forPostType( string $post_type ): array {
		$tags = array( self::POST_TYPES );

		$type_tags = match ( $post_type ) {
			'portfolio'    => array( self::PORTFOLIO, self::API ),
			'news'         => array( self::NEWS, self::API ),
			'careers'      => array( self::CAREERS, self::API ),
			'team_members' => array( self::TEAM_MEMBERS, self::API ),
			default        => array(),
		};

		return array_merge( $tags, $type_tags );
	}
}
