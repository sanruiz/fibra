<?php
/**
 * Documents Endpoint
 *
 * REST API endpoint for documents and reports.
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
 * Documents Endpoint class.
 *
 * Handles /soma/documents REST API endpoint.
 *
 * @since 3.0.0
 */
final class DocumentsEndpoint {
	/**
	 * Singleton instance.
	 *
	 * @var DocumentsEndpoint|null
	 */
	private static ?DocumentsEndpoint $instance = null;

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
	private const ROUTE = '/documents';

	/**
	 * Get singleton instance.
	 *
	 * @return DocumentsEndpoint
	 */
	public static function instance(): DocumentsEndpoint {
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
			'post_type'   => 'documents-reports',
			'post_status' => [ 'publish' ],
			'order'       => $params['order'] ?? 'ASC',
		];

		if ( isset( $params['order_by'] ) && $params['order_by'] === 'custom_date' ) {
			$args['meta_key'] = 'document_content_date';
			$args['orderby']  = 'meta_value';
		} else {
			$args['orderby'] = 'menu_order';
		}

		if ( isset( $params['categories'] ) && $params['categories'] ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'documents-taxonomy',
					'field'    => 'id',
					'terms'    => array_map( 'intval', explode( ',', $params['categories'] ) ),
					'operator' => 'AND',
				],
			];
		}

		$total = count( get_posts( $args ) );

		if ( isset( $params['id'] ) ) {
			$args['p'] = $params['id'];
		}
		if ( isset( $params['posts_per_page'] ) ) {
			$args['posts_per_page'] = $params['posts_per_page'];
		}
		if ( isset( $params['offset'] ) ) {
			$args['offset'] = $params['offset'];
		}

		$posts               = get_posts( $args );
		$formatted_documents = [];

		if ( $posts ) {
			foreach ( $posts as $item ) {
				$content        = get_field( 'document_content', $item->ID );
				$terms          = get_the_terms( $item->ID, 'documents-taxonomy' );
				$formatted_date = $content['date'] ? $this->translate_date( gmdate( 'F j, Y', $content['date'] ) ) : null;
				$year           = $content['date'] ? gmdate( 'Y', $content['date'] ) : null;

				$formatted_terms = $this->format_terms( $terms );
				$formatted_files = $this->format_additional_files( $content );
				$main_file       = $this->format_main_file( $content );

				$document = [
					'ID'                   => $item->ID,
					'title'                => get_the_title( $item->ID ),
					'featured_image'       => get_the_post_thumbnail_url( $item->ID ),
					'label'                => $content['label'],
					'date'                 => $content['date'],
					'formated_date'        => $formatted_date,
					'year'                 => $year,
					'description'          => $content['description'],
					'has_additional_files' => $content['has_additional_files'],
					'file'                 => $main_file,
					'additional_files'     => $formatted_files,
					'categories'           => $formatted_terms,
				];

				// Filter by year if specified.
				if ( isset( $params['year'] ) ) {
					if ( $params['year'] === $year ) {
						$formatted_documents[] = $document;
					}
				} else {
					$formatted_documents[] = $document;
				}
			}
		}

		return [
			'status' => 'success',
			'total'  => $total,
			'count'  => count( $formatted_documents ),
			'data'   => $formatted_documents,
		];
	}

	/**
	 * Format taxonomy terms.
	 *
	 * @param array|false $terms Taxonomy terms.
	 * @return array|null Formatted terms.
	 */
	private function format_terms( $terms ): ?array {
		if ( ! $terms ) {
			return null;
		}

		$formatted_terms = [];
		foreach ( $terms as $term ) {
			$formatted_terms[ $term->term_id ] = $term->name;
		}

		return $formatted_terms;
	}

	/**
	 * Format additional files based on current language.
	 *
	 * @param array $content Content field data.
	 * @return array|null Formatted files.
	 */
	private function format_additional_files( array $content ): ?array {
		if ( ! $content['has_additional_files'] || ! $content['additional_files'] ) {
			return null;
		}

		$formatted_files = [];
		$current_lang    = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'en';

		foreach ( $content['additional_files'] as $key => $file ) {
			$file_content = ( $current_lang === 'en' ) ? $file['file'] : $file['file_es'];

			$formatted_files[ $key ] = [
				'label' => $file['label'],
				'file'  => $file_content ? [
					'title'    => $file_content['title'],
					'filename' => $file_content['filename'],
					'filesize' => $file_content['filesize'],
					'url'      => $file_content['url'],
					'type'     => $file_content['subtype'],
				] : null,
			];
		}

		return $formatted_files;
	}

	/**
	 * Format main file based on current language.
	 *
	 * @param array $content Content field data.
	 * @return array|null Formatted file.
	 */
	private function format_main_file( array $content ): ?array {
		$current_lang = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'en';
		$main_file    = ( $current_lang === 'en' ) ? $content['file'] : $content['file_es'];

		if ( ! $main_file ) {
			return null;
		}

		return [
			'title'    => $main_file['title'],
			'filename' => $main_file['filename'],
			'filesize' => $main_file['filesize'],
			'url'      => $main_file['url'],
			'type'     => $main_file['subtype'],
		];
	}

	/**
	 * Translate date string.
	 *
	 * @param string $date_string Date string to translate.
	 * @return string Translated date.
	 */
	private function translate_date( string $date_string ): string {
		if ( function_exists( 'translateDate' ) ) {
			return translateDate( $date_string );
		}
		return $date_string;
	}
}
