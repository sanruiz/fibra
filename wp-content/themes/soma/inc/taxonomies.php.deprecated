<?php 
/**
 * Register taxonomies
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Team Members Taxonomy
 */
function team_members_taxonomy_handler() {
    $labels = array(
      'name'            => 'Categories', 'Taxonomy General Name', 'text_domain',
      'singular_name'   => 'Category', 'Taxonomy Singular Name', 'text_domain',
    );
    $args = array(
      'labels'              => $labels,
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_admin_column'   => true,
      'rewrite'             => array('with_front' => false),
    );
    register_taxonomy( 'team-members-taxonomy', array( 'team-members' ), $args );
  }
add_action( 'init', 'team_members_taxonomy_handler', 0 );


/**
 * Portfolio Taxonomy
 */
function portfolio_taxonomy_handler() {
  $labels = array(
    'name'            => 'Categories', 'Taxonomy General Name', 'text_domain',
    'singular_name'   => 'Category', 'Taxonomy Singular Name', 'text_domain',
  );
  $args = array(
    'labels'              => $labels,
    'hierarchical'        => true,
    'public'              => true,
    'show_ui'             => true,
    'show_admin_column'   => true,
    'rewrite'             => array('with_front' => false),
  );
  register_taxonomy( 'portfolio-taxonomy', array( 'portfolio' ), $args );
}
add_action( 'init', 'portfolio_taxonomy_handler', 0 );

/**
 * Documents and Reports Taxonomy
 */
function documents_and_reports_taxonomy_handler() {
  $labels = array(
    'name'            => 'Categories', 'Taxonomy General Name', 'text_domain',
    'singular_name'   => 'Category', 'Taxonomy Singular Name', 'text_domain',
  );
  $args = array(
    'labels'              => $labels,
    'hierarchical'        => true,
    'public'              => true,
    'show_ui'             => true,
    'show_admin_column'   => true,
    'rewrite'             => array('with_front' => false),
  );
  register_taxonomy( 'documents-taxonomy', array( 'documents-reports' ), $args );
}
add_action( 'init', 'documents_and_reports_taxonomy_handler', 0 );