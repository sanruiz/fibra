<?php
/**
 * Single Portfolio Template
 *
 * Displays project title, city and taxonomy terms.
 * Additional content (gallery, technical data, related projects)
 * is managed via Elementor widgets.
 *
 * @package Soma
 * @since   3.1.17
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$project_info = get_field( 'project_info' );
$city         = $project_info['city'] ?? '';
$terms        = get_the_terms( get_the_ID(), 'portfolio-taxonomy' );

// Filter out parent/umbrella taxonomy terms.
$excluded_slugs = array( 'soma_real_estate', 'soma_construction', 'fibrasoma' );
?>

<section class="single-portfolio-hero">
	<div class="container">
		<h1 class="project-title"><?php echo esc_html( get_the_title() ); ?></h1>
		<p class="project-meta">
			<?php if ( $city ) : ?>
				<span class="project-city"><?php echo esc_html( $city ); ?></span>
			<?php endif; ?>
			<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
				<?php foreach ( $terms as $taxonomy_term ) : ?>
					<?php if ( ! in_array( $taxonomy_term->slug, $excluded_slugs, true ) ) : ?>
						<span class="project-type"><?php echo esc_html( $taxonomy_term->name ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
	</div>
</section>

<?php
// Elementor content - gallery, technical data, sustainability, related projects.
the_content();
?>
