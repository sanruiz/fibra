<?php
/**
 * Portfolio Endpoint
 *
 * REST API endpoint for portfolio posts.
 *
 * @package    Soma
 * @subpackage API\Endpoints
 * @since      3.0.0
 */

namespace Soma\API\Endpoints;

use WP_REST_Server;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Portfolio Endpoint class.
 *
 * Handles /soma/portfolio REST API endpoint.
 *
 * @since 3.0.0
 */
final class PortfolioEndpoint {
	/**
	 * Singleton instance.
	 *
	 * @var PortfolioEndpoint|null
	 */
	private static ?PortfolioEndpoint $instance = null;

	/**
	 * API namespace.
	 *
	 * @var string
	 */
	private const NAMESPACE = 'soma';

	/**
	 * Endpoint route.
	 *
	 * @var string
	 */
	private const ROUTE = '/portfolio';

	/**
	 * Get singleton instance.
	 *
	 * @return PortfolioEndpoint
	 */
	public static function instance(): PortfolioEndpoint {
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
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization.
	 *
	 * @throws \Exception When attempting to unserialize.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize the endpoint.
	 *
	 * @return void
	 */
	private function init(): void {
		add_action( 'rest_api_init', $this->register( ... ) );
	}

	/**
	 * Register the REST route.
	 *
	 * @return void
	 */
	private function register(): void {
		register_rest_route(
			self::NAMESPACE,
			self::ROUTE,
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => $this->handle( ... ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Handle the request.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return array Response data.
	 */
	private function handle( WP_REST_Request $request ): array {
		$params = $request->get_params();

		$args = array(
			'numberposts' => -1,
			'post_type'   => 'portfolio',
			'post_status' => array( 'publish' ),
			'orderby'     => 'menu_order',
			'order'       => $params['order'] ?? 'DESC',
		);

		// Filter by category slug if provided.
		if ( isset( $params['category'] ) && $params['category'] && 'all' !== $params['category'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio-taxonomy',
					'field'    => 'slug',
					'terms'    => sanitize_text_field( $params['category'] ),
				),
			);
		} elseif ( isset( $params['categories'] ) && $params['categories'] ) {
			// Legacy support for category IDs.
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio-taxonomy',
					'field'    => 'id',
					'terms'    => array_map( 'intval', explode( ',', $params['categories'] ) ),
					'operator' => 'AND',
				),
			);
		}

		$total = \count( get_posts( $args ) );

		if ( isset( $params['id'] ) && $params['id'] ) {
			$args['p'] = $params['id'];
		}
		if ( isset( $params['posts_per_page'] ) && $params['posts_per_page'] ) {
			$args['posts_per_page'] = $params['posts_per_page'];
		}
		if ( isset( $params['offset'] ) && $params['offset'] ) {
			$args['offset'] = $params['offset'];
		}

		$posts           = get_posts( $args );
		$formatted_posts = array();

		if ( $posts ) {
			foreach ( $posts as $item ) {
				$info = soma_get_portfolio_project_info( $item->ID );

				$formatted_posts[] = array(
					'ID'             => $item->ID,
					'title'          => get_the_title( $item->ID ),
					'permalink'      => get_the_permalink( $item->ID ),
					'featured_image' => get_the_post_thumbnail_url( $item->ID, 'large' ),
					'city'           => $info['city'],
					'year'           => $info['year'],
				);
			}
		}

		// Sort by year.
		$years = array_column( $formatted_posts, 'year' );
		array_multisort( $years, SORT_DESC, $formatted_posts );

		// Get available categories (child categories only, exclude parent "Fibrasoma").
		$categories         = $this->get_available_categories();
		$include_categories = isset( $params['include_categories'] ) && 'true' === $params['include_categories'];

		$response = array(
			'status' => 'success',
			'total'  => $total,
			'count'  => \count( $formatted_posts ),
			'data'   => $formatted_posts,
		);

		// Include categories only if requested or on initial load.
		if ( $include_categories ) {
			$response['categories'] = $categories;
		}

		return $response;
	}

	/**
	 * Get available portfolio categories (excludes main "Fibrasoma" category).
	 *
	 * @return array Array of category data.
	 */
	private function get_available_categories(): array {
		$categories = array();

		// Get all portfolio taxonomy terms that have posts.
		$terms = get_terms(
			array(
				'taxonomy'   => 'portfolio-taxonomy',
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $categories;
		}

		foreach ( $terms as $term ) {
			// Skip the main "Fibrasoma" category (it represents "All" projects).
			if ( 'fibrasoma' === $term->slug ) {
				continue;
			}

			$categories[] = array(
				'id'    => $term->term_id,
				'name'  => $term->name,
				'slug'  => $term->slug,
				'count' => $term->count,
			);
		}

		return $categories;
	}
}
