<?php
/**
 * Search Template.
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$lang = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : 'en';

$post_types_ref = [
	'page'              => $lang === 'es' ? 'Página' : 'Page',
	'portfolio'         => $lang === 'es' ? 'Portafolio' : 'Portfolio',
	'documents-reports' => $lang === 'es' ? 'Documento' : 'Document',
	'news'              => $lang === 'es' ? 'Noticia' : 'News',
	'team-members'      => $lang === 'es' ? 'Equipo' : 'Team',
	'events'            => $lang === 'es' ? 'Evento' : 'Event',
	'careers'           => $lang === 'es' ? 'Carreras' : 'Careers',
];

if ( have_posts() ) :
	$formated_posts = [];
	while ( have_posts() ) :
		the_post();
		$current_post_id   = get_the_id();
		$current_post_type = get_post_type( $current_post_id );
		$main_category     = [];
		if ( $current_post_type === 'portfolio' ) {
			$terms         = get_the_terms( get_the_id(), 'portfolio-taxonomy' );
			$main_category = array_filter(
				$terms,
				function ( $term ) {
					return ( $term->slug === 'fibrasoma' || $term->slug === 'soma_real_estate' || $term->slug === 'soma_construction' );
				}
			);
		}
		$formated_posts[] = [
			'ID'             => $current_post_id,
			'title'          => get_the_title( $current_post_id ),
			'permalink'      => get_the_permalink( $current_post_id ),
			'featured_image' => get_the_post_thumbnail_url( $current_post_id ),
			'post_type'      => $post_types_ref[ $current_post_type ],
			'main_category'  => $main_category ? reset( $main_category )->name : '',
		];
	endwhile;
	$output = [
		'lang'    => $lang,
		'status'  => 'success',
		'message' => 'Search results',
		'total'   => count( $formated_posts ),
		'data'    => $formated_posts,
	];
else :
	$output = [
		'status'  => 'failure',
		'message' => "Sorry, we couldn't find what you are looking for.",
		'total'   => 0,
		'data'    => [],
	];
endif;

echo wp_json_encode( $output );
