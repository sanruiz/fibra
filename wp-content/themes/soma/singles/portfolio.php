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
	// Elementor content - gallery, technical data, sustainability widgets.
	the_content();
	?>
<?php else : ?>
	<?php
	// Legacy: ACF Flexible Content blocks for projects not migrated to Elementor.
	get_template_part( 'page-builder' );
	?>
<?php endif; ?>

<?php
/**
 * Related Projects Section
 *
 * Shows portfolio projects that share the same taxonomy term as the current project.
 * Displayed at the end of all portfolio singles, regardless of Elementor/legacy mode.
 */
$current_post_id = get_the_ID();

// Get current project's taxonomy terms (excluding parent/umbrella terms).
$current_terms = get_the_terms( $current_post_id, 'portfolio-taxonomy' );
$term_slugs    = array();

if ( $current_terms && ! is_wp_error( $current_terms ) ) {
	foreach ( $current_terms as $term ) {
		if ( ! in_array( $term->slug, $excluded_slugs, true ) ) {
			$term_slugs[] = $term->slug;
		}
	}
}

// Query related projects if we have valid terms.
if ( ! empty( $term_slugs ) ) {
	$related_args = array(
		'post_type'      => 'portfolio',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'post__not_in'   => array( $current_post_id ),
		'orderby'        => 'rand',
		'tax_query'      => array(
			array(
				'taxonomy' => 'portfolio-taxonomy',
				'field'    => 'slug',
				'terms'    => $term_slugs,
			),
		),
	);

	$related_projects = new \WP_Query( $related_args );

	if ( $related_projects->have_posts() ) :
		?>
		<section class="related-projects-section">
			<div class="container">
				<h2 class="related-projects-title"><?php echo esc_html__( 'Related Projects', 'soma' ); ?></h2>
				<div class="related-projects-grid">
					<?php
					while ( $related_projects->have_posts() ) :
						$related_projects->the_post();
						$related_info  = get_field( 'project_info' );
						$related_city  = $related_info['city'] ?? '';
						$related_terms = get_the_terms( get_the_ID(), 'portfolio-taxonomy' );
						?>
						<a href="<?php the_permalink(); ?>" class="related-project-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="related-project-image">
									<?php the_post_thumbnail( 'medium_large' ); ?>
								</div>
							<?php endif; ?>
							<div class="related-project-info">
								<h3 class="related-project-name"><?php the_title(); ?></h3>
								<?php if ( $related_city ) : ?>
									<span class="related-project-city"><?php echo esc_html( $related_city ); ?></span>
								<?php endif; ?>
								<?php if ( $related_terms && ! is_wp_error( $related_terms ) ) : ?>
									<?php foreach ( $related_terms as $related_term ) : ?>
										<?php if ( ! in_array( $related_term->slug, $excluded_slugs, true ) ) : ?>
											<span class="related-project-type"><?php echo esc_html( $related_term->name ); ?></span>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
}
?>
