<?php
/**
 * Helper Functions
 *
 * Global soma_* helper functions for theme functionality.
 *
 * @package Soma
 * @subpackage Utils
 * @since 3.0.0
 */

use Soma\Utils\Logger;
use Soma\Utils\Cache;
use Soma\Core\Enums\PostType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =============================================================================
// Logger Helpers
// =============================================================================

/**
 * Get logger instance
 *
 * @return Logger
 */
function soma_get_logger(): Logger {
	return Logger::instance();
}

/**
 * Log emergency message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_emergency( string $message, array $context = array() ): void {
	soma_get_logger()->emergency( $message, $context );
}

/**
 * Log alert message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_alert( string $message, array $context = array() ): void {
	soma_get_logger()->alert( $message, $context );
}

/**
 * Log critical message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_critical( string $message, array $context = array() ): void {
	soma_get_logger()->critical( $message, $context );
}

/**
 * Log error message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_error( string $message, array $context = array() ): void {
	soma_get_logger()->error( $message, $context );
}

/**
 * Log warning message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_warning( string $message, array $context = array() ): void {
	soma_get_logger()->warning( $message, $context );
}

/**
 * Log notice message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_notice( string $message, array $context = array() ): void {
	soma_get_logger()->notice( $message, $context );
}

/**
 * Log info message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_info( string $message, array $context = array() ): void {
	soma_get_logger()->info( $message, $context );
}

/**
 * Log debug message
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Context data.
 * @return void
 */
function soma_log_debug( string $message, array $context = array() ): void {
	soma_get_logger()->debug( $message, $context );
}

// =============================================================================
// Cache Helpers
// =============================================================================

/**
 * Get cache instance
 *
 * @return Cache
 */
function soma_get_cache(): Cache {
	return Cache::instance();
}

/**
 * Get cached value
 *
 * @param string $key Cache key.
 * @param mixed  $default_value Default value if not found.
 * @return mixed
 */
function soma_cache_get( string $key, $default_value = null ) {
	return soma_get_cache()->get( $key, $default_value );
}

/**
 * Set cached value
 *
 * @param string                            $key Cache key.
 * @param mixed                             $value Value to cache.
 * @param int                               $ttl Time to live in seconds (default: 1 hour).
 * @param array<\Soma\Utils\Enums\CacheTag> $tags Cache tags for invalidation.
 * @return bool
 */
function soma_cache_set( string $key, $value, int $ttl = 3600, array $tags = array() ): bool {
	return soma_get_cache()->set( $key, $value, $ttl, $tags );
}

/**
 * Remember: Get from cache or execute callback and cache result
 *
 * @param string                            $key Cache key.
 * @param callable                          $callback Callback to execute if cache miss.
 * @param int                               $ttl Time to live in seconds (default: 1 hour).
 * @param array<\Soma\Utils\Enums\CacheTag> $tags Cache tags for invalidation.
 * @return mixed
 */
function soma_cache_remember( string $key, callable $callback, int $ttl = 3600, array $tags = array() ) {
	return soma_get_cache()->remember( $key, $callback, $ttl, $tags );
}

/**
 * Invalidate cache by tags
 *
 * @param array<\Soma\Utils\Enums\CacheTag> $tags Tags to invalidate.
 * @return int Number of entries invalidated.
 */
function soma_cache_invalidate_tags( array $tags ): int {
	return soma_get_cache()->invalidate_tags( $tags );
}

/**
 * Flush all cache
 *
 * @return bool
 */
function soma_cache_flush(): bool {
	return soma_get_cache()->flush();
}

// =============================================================================
// Post Type Helpers
// =============================================================================

/**
 * Get portfolio items
 *
 * @param array<string, mixed> $args WP_Query arguments.
 * @return WP_Query
 */
function soma_get_portfolio_items( array $args = array() ): WP_Query {
	$defaults = array(
		'post_type'      => PostType::PORTFOLIO->value(),
		'posts_per_page' => 10,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * Get news items
 *
 * @param array<string, mixed> $args WP_Query arguments.
 * @return WP_Query
 */
function soma_get_news_items( array $args = array() ): WP_Query {
	$defaults = array(
		'post_type'      => PostType::NEWS->value(),
		'posts_per_page' => 10,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * Get careers items
 *
 * @param array<string, mixed> $args WP_Query arguments.
 * @return WP_Query
 */
function soma_get_careers_items( array $args = array() ): WP_Query {
	$defaults = array(
		'post_type'      => PostType::CAREERS->value(),
		'posts_per_page' => 10,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * Get team members
 *
 * @param array<string, mixed> $args WP_Query arguments.
 * @return WP_Query
 */
function soma_get_team_members( array $args = array() ): WP_Query {
	$defaults = array(
		'post_type'      => PostType::TEAM_MEMBERS->value(),
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

// =============================================================================
// Template Helpers
// =============================================================================

/**
 * Load template part (wrapper for get_template_part)
 *
 * @param string               $slug Template slug.
 * @param string|null          $name Template name.
 * @param array<string, mixed> $args Arguments to pass to template.
 * @return void
 */
function soma_get_template_part( string $slug, ?string $name = null, array $args = array() ): void {
	if ( ! empty( $args ) ) {
		set_query_var( 'template_args', $args );
	}

	get_template_part( $slug, $name, $args );
}

/**
 * Load partial with data
 *
 * @param string               $partial_name Partial name (without .php extension).
 * @param array<string, mixed> $data Data to pass to partial.
 * @return void
 */
function soma_load_partial( string $partial_name, array $data = array() ): void {
	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- Legacy global variable.
	global $pageBlock;

	// Set data as global for partial access.
	if ( ! empty( $data ) ) {
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- Legacy global variable.
		$pageBlock = $data;
	}

	soma_get_template_part( 'partials/' . $partial_name );
}

// =============================================================================
// ACF Helpers
// =============================================================================

/**
 * Get flexible content blocks
 *
 * @param string   $field_name ACF field name (default: 'soma_blocks').
 * @param int|null $post_id Post ID (null for current post).
 * @return array<array<string, mixed>>|false
 */
function soma_get_flexible_content( string $field_name = 'soma_blocks', ?int $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		soma_log_error( 'ACF get_field function not available' );
		return false;
	}

	return get_field( $field_name, $post_id );
}

/**
 * Render flexible content blocks
 *
 * @param array<array<string, mixed>>|false $blocks Flexible content blocks.
 * @return void
 */
function soma_render_flexible_content( $blocks ): void {
	if ( ! $blocks || ! is_array( $blocks ) ) {
		return;
	}

	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- Legacy global variable.
	global $pageBuilder;
	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- Legacy global variable.
	$pageBuilder = $blocks;

	get_template_part( 'page-builder' );
}

// =============================================================================
// Utility Helpers
// =============================================================================

/**
 * Check if we're in development mode
 *
 * @return bool
 */
function soma_is_dev(): bool {
	return defined( 'WP_DEBUG' ) && WP_DEBUG;
}

/**
 * Get theme version
 *
 * @return string
 */
function soma_get_version(): string {
	$theme = wp_get_theme();
	return $theme->get( 'Version' );
}

/**
 * Sanitize CSS class name
 *
 * @param string $class_name Class name to sanitize.
 * @return string
 */
function soma_sanitize_class( string $class_name ): string {
	return sanitize_html_class( $class_name );
}

/**
 * Get asset URL with versioning
 *
 * @param string $path Asset path relative to theme root.
 * @return string
 */
function soma_asset_url( string $path ): string {
	$url = get_template_directory_uri() . '/' . ltrim( $path, '/' );
	return add_query_arg( 'ver', soma_get_version(), $url );
}

// =============================================================================
// Translation Helpers
// =============================================================================

/**
 * Translate date strings to Spanish (WP Multilang integration)
 *
 * @param string      $str_date Date string to translate.
 * @param string|null $format   Format type ('short' for abbreviated months).
 * @return string Translated date string.
 */
function soma_translate_date( string $str_date, ?string $format = null ): string {
	if ( ! function_exists( 'wpm_get_language' ) || 'es' !== wpm_get_language() ) {
		return $str_date;
	}

	if ( 'short' === $format ) {
		// Short month names.
		$translations = array(
			'Jan' => 'Ene',
			'Apr' => 'Abr',
			'Aug' => 'Ago',
			'Dec' => 'Dic',
		);
	} else {
		// Full month names.
		$translations = array(
			'January'   => 'Enero',
			'February'  => 'Febrero',
			'March'     => 'Marzo',
			'April'     => 'Abril',
			'May'       => 'Mayo',
			'June'      => 'Junio',
			'July'      => 'Julio',
			'August'    => 'Agosto',
			'September' => 'Septiembre',
			'October'   => 'Octubre',
			'November'  => 'Noviembre',
			'December'  => 'Diciembre',
		);
	}

	return str_replace( array_keys( $translations ), array_values( $translations ), $str_date );
}

/**
 * Translate date (backward compatibility alias)
 *
 * @deprecated 3.0.0 Use soma_translate_date() instead.
 * @param string      $str_date Date string to translate.
 * @param string|null $format   Optional. Format type ('short' for abbreviated months).
 * @return string Translated date string.
 */
function translateDate( string $str_date, ?string $format = null ): string {
	return soma_translate_date( $str_date, $format );
}

// =============================================================================
// Stock Data Helpers
// =============================================================================

/**
 * Get current stock data
 *
 * @return array|null Stock data or null if not available.
 */
function soma_get_stock_data(): ?array {
	return \Soma\Admin\StockData::get_stock_data();
}

// =============================================================================
// i18n Helpers
// =============================================================================

/**
 * Get ACF field value with language-specific fallback.
 *
 * Automatically selects language-specific field variant based on current language.
 * Falls back to default field if language variant doesn't exist.
 *
 * @since 3.1.0
 *
 * @param array  $content ACF content array.
 * @param string $field   Base field name (without language suffix).
 * @return mixed Field value in current language, or default field value, or empty string.
 *
 * @example
 * // ACF fields: 'file' (English) and 'file_es' (Spanish)
 * $file = soma_get_i18n_field( $content, 'file' );
 * // Returns $content['file'] if English, $content['file_es'] if Spanish
 *
 * @example
 * // With events array
 * $events = soma_get_i18n_field( get_query_var('soma_block_content'), 'events' );
 * // Returns events_es array if Spanish, events array if English
 */
function soma_get_i18n_field( array $content, string $field ) {
	// Get current language (default to 'en' if WP Multilang not active).
	$lang = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'en';

	// Build language-specific field name (e.g., 'file_es').
	$lang_field = 'es' === $lang ? "{$field}_es" : $field;

	// Return language-specific field, fall back to default field, or empty string.
	return $content[ $lang_field ] ?? $content[ $field ] ?? '';
}

// =============================================================================
// Breadcrumb Helpers
// =============================================================================

/**
 * Get breadcrumb navigation items
 *
 * Generates breadcrumb trail for current page context including:
 * - Home page link
 * - Single posts with post type archive parent
 * - Post type archives
 * - Pages with parent hierarchy
 * - Taxonomy terms with parent hierarchy
 * - Search results
 * - 404 pages
 *
 * @since 3.1.7
 *
 * @return array<int, array<string, mixed>> Array of breadcrumb items with keys:
 *                                           - 'name' (string): Display text
 *                                           - 'url' (string): Link URL
 *                                           - 'is_current' (bool): Whether this is the current page
 *
 * @example
 * $breadcrumbs = soma_get_breadcrumb_items();
 * foreach ( $breadcrumbs as $item ) {
 *     if ( $item['is_current'] ) {
 *         echo esc_html( $item['name'] );
 *     } else {
 *         echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a>';
 *     }
 * }
 */
function soma_get_breadcrumb_items(): array {
	$breadcrumbs = array();

	// Always start with Home.
	$breadcrumbs[] = array(
		'name'       => __( 'Home', 'soma' ),
		'url'        => home_url( '/' ),
		'is_current' => is_front_page(),
	);

	// Return early if we're on the home page.
	if ( is_front_page() ) {
		return $breadcrumbs;
	}

	// Single Post (any post type).
	if ( is_singular() ) {
		$post = get_queried_object();

		// Add post type archive as parent for non-page post types.
		if ( 'post' !== $post->post_type && 'page' !== $post->post_type ) {
			$post_type_obj = get_post_type_object( $post->post_type );

			if ( $post_type_obj && $post_type_obj->has_archive ) {
				$breadcrumbs[] = array(
					'name'       => $post_type_obj->labels->name,
					'url'        => get_post_type_archive_link( $post->post_type ),
					'is_current' => false,
				);
			}
		}

		// Add page hierarchy for pages.
		if ( 'page' === $post->post_type ) {
			if ( $post->post_parent ) {
				$parent_ids = array_reverse( get_post_ancestors( $post->ID ) );
				foreach ( $parent_ids as $parent_id ) {
					$breadcrumbs[] = array(
						'name'       => get_the_title( $parent_id ),
						'url'        => get_permalink( $parent_id ),
						'is_current' => false,
					);
				}
			}
		}

		// Add current post/page.
		$breadcrumbs[] = array(
			'name'       => get_the_title( $post->ID ),
			'url'        => get_permalink( $post->ID ),
			'is_current' => true,
		);

		return $breadcrumbs;
	}

	// Post Type Archive.
	if ( is_post_type_archive() ) {
		$post_type_obj = get_queried_object();

		if ( $post_type_obj ) {
			$breadcrumbs[] = array(
				'name'       => $post_type_obj->labels->name,
				'url'        => get_post_type_archive_link( $post_type_obj->name ),
				'is_current' => true,
			);
		}

		return $breadcrumbs;
	}

	// Taxonomy (Category, Tag, Custom Taxonomy).
	if ( is_tax() || is_category() || is_tag() ) {
		$term = get_queried_object();

		// Add parent terms for hierarchical taxonomies.
		if ( $term && $term->parent && is_taxonomy_hierarchical( $term->taxonomy ) ) {
			$parent_ids = array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) );
			foreach ( $parent_ids as $parent_id ) {
				$parent_term = get_term( $parent_id, $term->taxonomy );
				if ( $parent_term && ! is_wp_error( $parent_term ) ) {
					$breadcrumbs[] = array(
						'name'       => $parent_term->name,
						'url'        => get_term_link( $parent_term ),
						'is_current' => false,
					);
				}
			}
		}

		// Add current term.
		if ( $term ) {
			$breadcrumbs[] = array(
				'name'       => $term->name,
				'url'        => get_term_link( $term ),
				'is_current' => true,
			);
		}

		return $breadcrumbs;
	}

	// Search Results.
	if ( is_search() ) {
		$breadcrumbs[] = array(
			'name'       => sprintf(
				/* translators: %s: search query */
				__( 'Search results for: %s', 'soma' ),
				get_search_query()
			),
			'url'        => get_search_link(),
			'is_current' => true,
		);

		return $breadcrumbs;
	}

	// 404 Page.
	if ( is_404() ) {
		$breadcrumbs[] = array(
			'name'       => __( 'Page Not Found', 'soma' ),
			'url'        => '',
			'is_current' => true,
		);

		return $breadcrumbs;
	}

	// Default (Blog page, date archives, author archives).
	if ( is_home() ) {
		$page_for_posts = get_option( 'page_for_posts' );
		if ( $page_for_posts ) {
			$breadcrumbs[] = array(
				'name'       => get_the_title( $page_for_posts ),
				'url'        => get_permalink( $page_for_posts ),
				'is_current' => true,
			);
		} else {
			$breadcrumbs[] = array(
				'name'       => __( 'Blog', 'soma' ),
				'url'        => home_url( '/blog/' ),
				'is_current' => true,
			);
		}
	}

	return $breadcrumbs;
}
