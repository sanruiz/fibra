<?php
/**
 * Events Endpoint
 *
 * REST API endpoint for events.
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
 * Events Endpoint class.
 *
 * Handles /soma/events REST API endpoint.
 *
 * @since 3.0.0
 */
final class EventsEndpoint {
	/**
	 * Singleton instance.
	 *
	 * @var EventsEndpoint|null
	 */
	private static ?EventsEndpoint $instance = null;

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
	private const ROUTE = '/events';

	/**
	 * Get singleton instance.
	 *
	 * @return EventsEndpoint
	 */
	public static function instance(): EventsEndpoint {
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
			'post_type'   => 'events',
			'post_status' => array( 'publish' ),
			'order'       => $params['order'] ?? 'ASC',
		);

		if ( isset( $params['order_by'] ) && $params['order_by'] === 'custom_date' ) {
			$args['meta_key'] = 'event_info_init_date';
			$args['orderby']  = 'meta_value';
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

		$posts            = get_posts( $args );
		$formatted_events = array();

		if ( $posts ) {
			foreach ( $posts as $item ) {
				$content             = get_field( 'event_info', $item->ID );
				$formatted_init_date = $content['end_date']
					? $this->translate_date( gmdate( 'M j', $content['init_date'] ), 'short' )
					: $this->translate_date( gmdate( 'M j, Y', $content['init_date'] ) );
				$formatted_end_date  = $content['end_date']
					? $this->translate_date( gmdate( 'M j', $content['end_date'] ), 'short' )
					: null;
				$year                = gmdate( 'Y', $content['init_date'] );
				$filter              = $this->translate_date( gmdate( 'M Y', $content['init_date'] ), 'short' );

				$main_file = $this->format_file( $content );

				$event = array(
					'ID'             => $item->ID,
					'title'          => get_the_title( $item->ID ),
					'featured_image' => get_the_post_thumbnail_url( $item->ID ),
					'label'          => $content['label'],
					'init_date'      => $content['init_date'],
					'end_date'       => $content['end_date'] ? $content['end_date'] : null,
					'formated_date'  => $formatted_end_date
						? $formatted_init_date . ' - ' . $formatted_end_date
						: $formatted_init_date,
					'year'           => $year,
					'description'    => $content['description'],
					'file_label'     => $content['file_label'],
					'file'           => $main_file,
					'filter'         => $filter,
				);

				// Filter by year if specified.
				if ( isset( $params['year'] ) && $params['year'] ) {
					if ( $params['year'] === $filter ) {
						$formatted_events[] = $event;
					}
				} else {
					$formatted_events[] = $event;
				}
			}
		}

		return array(
			'status' => 'success',
			'total'  => $total,
			'count'  => \count( $formatted_events ),
			'data'   => $formatted_events,
		);
	}

	/**
	 * Format file based on current language.
	 *
	 * @param array $content Content field data.
	 * @return array|null Formatted file.
	 */
	private function format_file( array $content ): ?array {
		$current_lang = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'en';
		$main_file    = ( $current_lang === 'en' ) ? $content['file'] : $content['file_es'];

		if ( ! $main_file ) {
			return null;
		}

		return array(
			'title'    => $main_file['title'],
			'filename' => $main_file['filename'],
			'filesize' => $main_file['filesize'],
			'url'      => $main_file['url'],
			'type'     => $main_file['subtype'],
		);
	}

	/**
	 * Translate date string.
	 *
	 * @param string $date_string Date string to translate.
	 * @param string $format      Optional format ('short' or default).
	 * @return string Translated date.
	 */
	private function translate_date( string $date_string, string $format = '' ): string {
		if ( function_exists( 'soma_translate_date' ) ) {
			return soma_translate_date( $date_string, $format );
		}
		return $date_string;
	}
}
