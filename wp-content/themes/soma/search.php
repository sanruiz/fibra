<?php
/**
 * 
 * Default search page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$lang = $_GET['lang'];

$post_types_ref = [
    "page"              => $lang == 'es' ? "Página" : "Page",
    "portfolio"         => $lang == 'es' ? "Portafolio" : "Portfolio",
    "documents-reports" => $lang == 'es' ? "Documento" : "Document",
    "news"              => $lang == 'es' ? "Noticia" : "News",
    "team-members"      => $lang == 'es' ? "Equipo" : "Team",
    "events"            => $lang == 'es' ? "Evento" : "Event",
    "careers"           => $lang == 'es' ? "Carreras" : "Careers"
];

if (have_posts()):
    $formatedPosts = [];
    while ( have_posts() ) : the_post();
        $post_id = get_the_id();
        $post_type = get_post_type($post_id);
        $mainCategory = [];
        if ($post_type == 'portfolio') {
            $terms = get_the_terms( get_the_id(), 'portfolio-taxonomy' );
            $mainCategory = array_filter($terms, function ($var) {
                return ($var->slug == 'fibrasoma' || $var->slug == 'soma_real_estate' || $var->slug == 'soma_construction');
            });
        }
        $formatedPosts[] = [
            "ID"                => $post_id,
            "title"             => get_the_title($post_id),
            "permalink"         => get_the_permalink($post_id),
            "featured_image"    => get_the_post_thumbnail_url($post_id),
            "post_type"         => $post_types_ref[$post_type],
            "main_category"     => $mainCategory ? reset($mainCategory)->name : ''
        ];
    endwhile;
    $output = [
        "lang"          => $lang,
        "status"        => "success",
        "message"       => "Search results",
        "total"         => count($formatedPosts),
        "data"          => $formatedPosts
    ];
else: 
    $output = [
        "status"        => "failure",
        "message"       => "Sorry, we couldn't find what you are looking for.",
        "total"         => 0,
        "data"          => []
    ];
endif;

echo json_encode( $output );