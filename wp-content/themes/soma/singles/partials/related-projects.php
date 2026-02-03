<?php
/**
 * Related Projects Template Part
 *
 * Shows portfolio projects that share the same taxonomy term as the current project.
 * Can be included at the end of portfolio singles, regardless of Elementor/legacy mode.
 *
 * @package Soma
 * @since   3.1.17
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$current_post_id = get_the_ID();

// Parent/umbrella taxonomy terms to exclude from filtering and display.
$excluded_slugs = array( 'soma_real_estate', 'soma_construction', 'fibrasoma' );

// Get current project's taxonomy terms (excluding parent/umbrella terms).
$current_terms = get_the_terms( $current_post_id, 'portfolio-taxonomy' );
$term_slugs    = array();

if ( $current_terms && ! is_wp_error( $current_terms ) ) {
	foreach ( $current_terms as $current_term ) {
		if ( ! in_array( $current_term->slug, $excluded_slugs, true ) ) {
			$term_slugs[] = $current_term->slug;
		}
	}
}

// Only query related projects if we have valid terms.
if ( empty( $term_slugs ) ) {
	return;
}

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

if ( ! $related_projects->have_posts() ) {
	return;
}
?>
<section class="related-projects-section dark-style">
	<div class="container">
		<h2 class="related-projects-title"><?php echo esc_html__( 'Related Projects', 'soma' ); ?></h2>
		<div class="related-projects-grid">
			<?php
			while ( $related_projects->have_posts() ) :
				$related_projects->the_post();
				$related_info  = soma_get_portfolio_project_info();
				$related_city  = $related_info['city'];
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
