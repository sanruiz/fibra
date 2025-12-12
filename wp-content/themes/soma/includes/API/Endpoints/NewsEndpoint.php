<?php
/**
 * News Endpoint
 *
 * REST API endpoint for news and events posts.
 *
 * @package    Soma
 * @subpackage API\Endpoints
 * @since      3.0.0
 */

namespace Soma\API\Endpoints;

use WP_REST_Server;
use WP_REST_Request;
use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * News Endpoint class.
 *
 * Handles /soma/news REST API endpoint.
 *
 * @since 3.0.0
 */
final class NewsEndpoint {
	/**
	 * Singleton instance.
	 *
	 * @var NewsEndpoint|null
	 */
	private static ?NewsEndpoint $instance = null;

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
	private const ROUTE = '/news';

	/**
	 * Get singleton instance.
	 *
	 * @return NewsEndpoint
	 */
	public static function instance(): NewsEndpoint {
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
		add_action( 'rest_api_init', $this->register(...) );
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
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => $this->handle(...),
				'permission_callback' => '__return_true',
			]
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

		$args = [
			'numberposts' => -1,
			'post_type'   => [ 'news', 'events' ],
			'post_status' => [ 'publish' ],
			'meta_query'  => [
				'relation'    => 'OR',
				'new_date'    => [
					'key' => 'news_content_date',
				],
				'events_date' => [
					'key' => 'event_info_init_date',
				],
			],
			'orderby'     => [
				'new_date'    => 'DESC',
				'events_date' => 'DESC',
			],
			'order'       => $params['order'] ?? 'DESC',
		];

		if ( isset( $params['id'] ) ) {
			$args['p'] = $params['id'];
		}
		if ( isset( $params['posts_per_page'] ) ) {
			$args['posts_per_page'] = $params['posts_per_page'];
		}
		if ( isset( $params['offset'] ) ) {
			$args['offset'] = $params['offset'];
		}

		$posts           = get_posts( $args );
		$formatted_posts = [];

		if ( $posts ) {
			foreach ( $posts as $item ) {
				$info      = null;
				$date      = null;
				$timestamp = null;
				$file      = [];
				$label     = null;

				if ( $item->post_type === 'news' ) {
					$info      = get_field( 'news_content', $item->ID );
					$date      = DateTime::createFromFormat( 'U', $info['date'] );
					$timestamp = $info['date'];
				} elseif ( $item->post_type === 'events' ) {
					$info      = get_field( 'event_info', $item->ID );
					$date      = DateTime::createFromFormat( 'U', $info['init_date'] );
					$timestamp = $info['init_date'];
					$label     = $info['label'];
					$file      = [
						'filelabel' => $info['file_label'],
						'filedata'  => $info['file'],
					];
				}

				$formatted_posts[] = [
					'ID'             => $item->ID,
					'title'          => get_the_title( $item->ID ),
					'permalink'      => get_the_permalink( $item->ID ),
					'featured_image' => get_the_post_thumbnail_url( $item->ID ),
					'date'           => $date ? $date->format( 'F j, Y' ) : null,
					'timestamp'      => $timestamp,
					'label'          => $label,
					'file'           => $file,
					'post_type'      => $item->post_type,
				];
			}
		}

		// Sort by timestamp.
		$timestamps = array_column( $formatted_posts, 'timestamp' );
		array_multisort( $timestamps, SORT_DESC, $formatted_posts );

		return [
			'status' => 'success',
			'total'  => (int) wp_count_posts( 'news' )->publish,
			'count'  => \count( $formatted_posts ),
			'data'   => $formatted_posts,
		];
	}
}
