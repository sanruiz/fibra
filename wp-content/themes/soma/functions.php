<?php
/**
 * Soma Theme - Main Functions File
 *
 * @package Soma
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Composer Autoloader
 *
 * Load PSR-4 autoloader for modern PHP classes.
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! function_exists( 'soma_load_textdomain' ) ) {
	/**
	 * Load Theme Text Domain
	 *
	 * Enables internationalization support for the theme.
	 * Translations should be placed in the /languages directory.
	 *
	 * @since 3.1.0
	 */
	function soma_load_textdomain(): void {
		load_theme_textdomain( 'soma', get_template_directory() . '/languages' );
	}
	add_action( 'after_setup_theme', 'soma_load_textdomain' );
}

/**
 * Initialize Theme (PSR-4 Architecture)
 *
 * Load the main theme class if autoloader is available.
 * This will replace the legacy includes below in future versions.
 */
if ( class_exists( 'Soma\Core\Theme' ) ) {
	\Soma\Core\Theme::instance();
}

if ( ! function_exists( 'soma_should_normalize_imported_upload_urls' ) ) {
	/**
	 * Check whether imported upload URLs should be normalized.
	 *
	 * @since 3.1.28
	 *
	 * @return bool
	 */
	function soma_should_normalize_imported_upload_urls(): bool {
		return false === strpos( home_url(), 'fibrasoma.group' );
	}
}

if ( ! function_exists( 'soma_normalize_imported_upload_urls' ) ) {
	/**
	 * Rewrite production upload URLs to the current site's uploads base URL.
	 *
	 * @since 3.1.28
	 *
	 * @param string $content Content to normalize.
	 * @return string
	 */
	function soma_normalize_imported_upload_urls( string $content ): string {
		if ( ! soma_should_normalize_imported_upload_urls() || '' === $content ) {
			return $content;
		}

		$uploads_base_url = trailingslashit( content_url( '/uploads' ) );

		return str_replace(
			array(
				'https://fibrasoma.group/wp-content/uploads/',
				'http://fibrasoma.group/wp-content/uploads/',
			),
			$uploads_base_url,
			$content
		);
	}
}

if ( ! function_exists( 'soma_get_referenced_elementor_template_ids' ) ) {
	/**
	 * Get Elementor template IDs referenced by the current queried post.
	 *
	 * @since 3.1.28
	 *
	 * @return int[]
	 */
	function soma_get_referenced_elementor_template_ids(): array {
		$post_id = get_queried_object_id();

		if ( ! $post_id ) {
			return array();
		}

		$elementor_data = (string) get_post_meta( $post_id, '_elementor_data', true );

		if ( '' === $elementor_data || ! preg_match_all( '/"template_id":"?(\d+)"?/', $elementor_data, $matches ) ) {
			return array();
		}

		return array_unique( array_map( 'absint', $matches[1] ) );
	}
}

if ( ! function_exists( 'soma_get_elementor_css_post_ids_to_normalize' ) ) {
	/**
	 * Get Elementor CSS post IDs that should be normalized for the current request.
	 *
	 * @since 3.1.28
	 *
	 * @return int[]
	 */
	function soma_get_elementor_css_post_ids_to_normalize(): array {
		$post_ids = array_filter(
			array(
				absint( get_queried_object_id() ),
				class_exists( '\Elementor\Plugin' ) ? absint( \Elementor\Plugin::$instance->kits_manager->get_active_id() ) : 0,
			)
		);

		return array_values(
			array_unique(
				array_merge(
					$post_ids,
					soma_get_referenced_elementor_template_ids()
				)
			)
		);
	}
}

if ( ! function_exists( 'soma_normalize_elementor_generated_css_files' ) ) {
	/**
	 * Normalize imported production upload URLs inside Elementor generated CSS files.
	 *
	 * @since 3.1.28
	 *
	 * @return void
	 */
	function soma_normalize_elementor_generated_css_files(): void {
		if ( is_admin() || ! soma_should_normalize_imported_upload_urls() ) {
			return;
		}

		foreach ( soma_get_elementor_css_post_ids_to_normalize() as $post_id ) {
			$file_path = WP_CONTENT_DIR . '/uploads/elementor/css/post-' . $post_id . '.css';

			if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) || ! is_writable( $file_path ) ) {
				continue;
			}

			$css = (string) file_get_contents( $file_path );

			if ( '' === $css ) {
				continue;
			}

			$normalized_css = soma_normalize_imported_upload_urls( $css );

			if ( $normalized_css === $css ) {
				continue;
			}

			file_put_contents( $file_path, $normalized_css );
		}
	}
	add_action( 'wp_enqueue_scripts', 'soma_normalize_elementor_generated_css_files', 5 );
}

if ( ! function_exists( 'soma_normalize_frontend_imported_upload_urls' ) ) {
	/**
	 * Normalize imported production upload URLs inside frontend HTML output.
	 *
	 * @since 3.1.28
	 *
	 * @return void
	 */
	function soma_normalize_frontend_imported_upload_urls(): void {
		if ( is_admin() || is_feed() || wp_doing_ajax() || ! soma_should_normalize_imported_upload_urls() ) {
			return;
		}

		ob_start( 'soma_normalize_imported_upload_urls' );
	}
	add_action( 'template_redirect', 'soma_normalize_frontend_imported_upload_urls', 0 );
}

if ( ! function_exists( 'soma_enqueue_elementor_template_css' ) ) {
	/**
	 * Enqueue generated Elementor template CSS referenced inside page data.
	 *
	 * Some imported local environments keep embedded Elementor template markup
	 * but miss Elementor's automatic enqueue for the generated post-{ID}.css
	 * asset. This keeps nested templates styled without changing page content.
	 *
	 * @since 3.1.28
	 *
	 * @return void
	 */
	function soma_enqueue_elementor_template_css(): void {
		if ( is_admin() ) {
			return;
		}

		$template_ids = soma_get_referenced_elementor_template_ids();

		foreach ( $template_ids as $template_id ) {
			if ( ! $template_id ) {
				continue;
			}

			$relative_path = '/uploads/elementor/css/post-' . $template_id . '.css';
			$file_path     = WP_CONTENT_DIR . $relative_path;

			if ( ! file_exists( $file_path ) ) {
				continue;
			}

			wp_enqueue_style(
				'soma-elementor-template-' . $template_id,
				content_url( $relative_path ),
				array(),
				(string) filemtime( $file_path )
			);
		}
	}
	add_action( 'wp_enqueue_scripts', 'soma_enqueue_elementor_template_css', 30 );
}

if ( ! function_exists( 'soma_print_elementor_template_css_fallback' ) ) {
	/**
	 * Print referenced Elementor template CSS as a frontend fallback.
	 *
	 * When the generated post-{ID}.css file exists, print a direct link to it.
	 * When the file is missing but the referenced template exists, ask Elementor
	 * to print the generated CSS inline so loop templates keep their styling.
	 *
	 * @since 3.1.28
	 *
	 * @return void
	 */
	function soma_print_elementor_template_css_fallback(): void {
		if ( is_admin() ) {
			return;
		}

		$template_ids = soma_get_referenced_elementor_template_ids();

		foreach ( $template_ids as $template_id ) {
			if ( ! $template_id ) {
				continue;
			}

			$relative_path = '/uploads/elementor/css/post-' . $template_id . '.css';
			$file_path     = WP_CONTENT_DIR . $relative_path;

			if ( file_exists( $file_path ) ) {
				// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Fallback for imported Elementor nested template CSS that is not enqueued by Elementor on this request.
				echo '<link rel="stylesheet" id="soma-elementor-template-' . esc_attr( (string) $template_id ) . '-css" href="' . esc_url( content_url( $relative_path ) ) . '?ver=' . esc_attr( (string) filemtime( $file_path ) ) . '" media="all">';
				continue;
			}

			if ( ! class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				continue;
			}

			$css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
			$css_file->print_css();
		}
	}
	add_action( 'wp_head', 'soma_print_elementor_template_css_fallback', 31 );
}

// // Clear any existing scheduled events
// wp_clear_scheduled_hook('update_stock_data_event');

// // Schedule the event again
// if (!wp_next_scheduled('update_stock_data_event')) {
// wp_schedule_event(time(), 'three_hours', 'update_stock_data_event');
// }
