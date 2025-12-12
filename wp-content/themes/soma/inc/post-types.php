<?php 
/**
 * Register post types
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Post Type Portfolio
 */
function portfolio_post_type() {
    register_post_type('portfolio',
        array(
            'labels'      => array(
                'name'          => __("Portfolio"),
                'singular_name' => __("Project"),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'has_archive'        => false,
            'show_in_rest'       => false,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => 'portfolio' ),
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => array( 'title', 'thumbnail' ),
        )
    );
}
add_action('init', 'portfolio_post_type');

/**
 * Custom Post Type News
 */
function news_post_type() {
    register_post_type('news',
        array(
            'labels'      => array(
                'name'          => __("News"),
                'singular_name' => __("News"),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'has_archive'        => false,
            'show_in_rest'       => false,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => 'news' ),
            'menu_icon'          => 'dashicons-media-document',
            'supports'           => array( 'title', 'thumbnail' ),
        )
    );
}
add_action('init', 'news_post_type');

/**
 * Custom Post Type Team Members
 */
function team_members_post_type() {
    register_post_type('team-members',
        array(
            'labels'      => array(
                'name'          => __("Team Members"),
                'singular_name' => __("Team Member"),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'has_archive'        => false,
            'show_in_rest'       => false,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => 'team-members' ),
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array( 'title', 'thumbnail' ),
        )
    );
}
add_action('init', 'team_members_post_type');

/**
 * Custom Post Type Documents and Reports
 */
function documents_and_reports_post_type() {
    register_post_type('documents-reports',
        array(
            'labels'      => array(
                'name'          => __("Documents & Reports"),
                'singular_name' => __("Document"),
            ),
            'public'             => true,
            'publicly_queryable' => false,
            'has_archive'        => false,
            'show_in_rest'       => false,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => 'documents-reports' ),
            'menu_icon'          => 'dashicons-pdf',
            'supports'           => array( 'title', 'thumbnail' ),
        )
    );
}
add_action('init', 'documents_and_reports_post_type');

/**
 * Custom Post Type Events
 */
function events_post_type() {
    register_post_type('events',
        array(
            'labels'      => array(
                'name'          => __("Events"),
                'singular_name' => __("Event"),
            ),
            'public'             => true,
            'publicly_queryable' => false,
            'has_archive'        => false,
            'show_in_rest'       => false,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => 'events' ),
            'menu_icon'          => 'dashicons-clipboard',
            'supports'           => array( 'title', 'thumbnail'),
        )
    );
}
add_action('init', 'events_post_type');

/**
 * Custom Post Type Careers
 */
function careers_post_type() {
    register_post_type('careers',
        array(
            'labels'      => array(
                'name'          => __("Careers"),
                'singular_name' => __("Career"),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'has_archive'        => false,
            'show_in_rest'       => false,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => 'careers' ),
            'menu_icon'          => 'dashicons-universal-access',
            'supports'           => array( 'title', 'thumbnail'),
        )
    );
}
add_action('init', 'careers_post_type');