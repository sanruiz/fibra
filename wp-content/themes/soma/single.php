<?php
/**
 * Single Post Template.
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

global $post;
$header_options = get_field( 'header_content', 'options' );
?>

<?php get_template_part( 'partials/BreadCrumb' ); ?>

<main id="ditto-single" page-slug="<?php echo $post->post_name; ?>" data-header-style="<?php echo $header_options['style']; ?>">

	<?php if ( get_post_type() == 'news' ) : ?>

		<?php get_template_part( 'singles/news' ); ?>
	
	<?php elseif ( get_post_type() == 'careers' ) : ?>

		<?php get_template_part( 'singles/careers' ); ?>
	
	<?php elseif ( get_post_type() == 'team-members' ) : ?>

		<?php get_template_part( 'singles/team-members' ); ?>

	<?php elseif ( get_post_type() == 'portfolio' ) : ?>

		<?php
			global $pageBuilder;
			$pageBuilder = get_field( 'soma_blocks' );
			$city        = get_field( 'project_info_city' );
			$terms       = get_the_terms( get_the_id(), 'portfolio-taxonomy' );
		?>

		<section class="project-title-section">
			<div class="container">
				<h2><?php echo get_the_title(); ?></h2>
				<p>
					<?php echo $city ? $city . '. ' : ''; ?>
					<?php if ( $terms ) : ?>
						<?php foreach ( $terms as $key => $term ) : ?>
							<?php if ( $term->slug != 'soma_real_estate' && $term->slug != 'soma_construction' && $term->slug != 'fibrasoma' ) : ?>
								<?php echo $term->name . '. '; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</p>
			</div>
		</section>

		<?php get_template_part( 'page-builder' ); ?>

	<?php else : ?>

		<section>
			<div class="container">
				<p><?php echo '[' . get_the_title() . ']'; ?></p>
			</div>
		</section>

	<?php endif; ?>

</main>

<script>
<?php if ( get_post_type() == 'news' ) : ?>
	$('#menu-main-menu .menu-item.news').addClass('current-menu-item');
<?php endif; ?>
<?php if ( get_post_type() == 'careers' ) : ?>
	$('#menu-main-menu .menu-item.careers').addClass('current-menu-item');
<?php endif; ?>
<?php if ( get_post_type() == 'portfolio' ) : ?>
	$('#menu-main-menu .menu-item.portfolio').addClass('current-menu-item');
<?php endif; ?>
<?php if ( get_post_type() == 'team-members' ) : ?>
	$('#menu-main-menu .menu-item.team-members').addClass('current-menu-item');
<?php endif; ?>
</script>


<?php get_footer(); ?>