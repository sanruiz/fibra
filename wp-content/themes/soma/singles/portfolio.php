<?php
/**
 * Single Portfolio Template
 *
 * Displays project title, city and taxonomy terms.
 * Supports both Elementor-based layouts (new) and ACF Flexible Content (legacy).
 *
 * - Elementor: Gallery, technical data, sustainability, and related projects
 *   are managed via custom Elementor widgets.
 * - Legacy: Falls back to ACF page-builder for existing projects not yet
 *   migrated to Elementor.
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
$excluded_slugs    = array( 'soma_real_estate', 'soma_construction', 'fibrasoma' );
$uses_elementor    = soma_is_built_with_elementor();
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

<?php if ( $uses_elementor ) : ?>
	<?php
	// Elementor content - gallery, technical data, sustainability, related projects.
	the_content();
	?>
<?php else : ?>
	<?php
	// Legacy: ACF Flexible Content blocks for projects not migrated to Elementor.
	get_template_part( 'page-builder' );
	?>
<?php endif; ?>
