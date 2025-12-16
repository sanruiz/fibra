<?php
/**
 * Template Name: Elementor Page
 * Template Post Type: page, post
 *
 * Template for pages designed with Elementor page builder.
 * This template displays Elementor content without ACF Flexible Content.
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main class="elementor-page">
	<?php the_content(); ?>
</main>

<?php get_footer(); ?>
