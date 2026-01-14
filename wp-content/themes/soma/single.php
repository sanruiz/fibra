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

<main id="ditto-single" page-slug="<?php echo esc_attr( $post->post_name ); ?>" data-header-style="<?php echo esc_attr( $header_options['style'] ); ?>">

	<?php if ( get_post_type() === 'news' ) : ?>

		<?php get_template_part( 'singles/news' ); ?>
	
	<?php elseif ( get_post_type() === 'careers' ) : ?>

		<?php get_template_part( 'singles/careers' ); ?>
	
	<?php elseif ( get_post_type() === 'team-members' ) : ?>

		<?php get_template_part( 'singles/team-members' ); ?>

	<?php elseif ( get_post_type() === 'portfolio' ) : ?>

		<?php get_template_part( 'singles/portfolio' ); ?>

	<?php else : ?>

		<section>
			<div class="container">
				<p><?php echo '[' . esc_html( get_the_title() ) . ']'; ?></p>
			</div>
		</section>

	<?php endif; ?>

</main>

<script>
<?php if ( get_post_type() === 'news' ) : ?>
	$('#menu-main-menu .menu-item.news').addClass('current-menu-item');
<?php endif; ?>
<?php if ( get_post_type() === 'careers' ) : ?>
	$('#menu-main-menu .menu-item.careers').addClass('current-menu-item');
<?php endif; ?>
<?php if ( get_post_type() === 'portfolio' ) : ?>
	$('#menu-main-menu .menu-item.portfolio').addClass('current-menu-item');
<?php endif; ?>
<?php if ( get_post_type() === 'team-members' ) : ?>
	$('#menu-main-menu .menu-item.team-members').addClass('current-menu-item');
<?php endif; ?>
</script>


<?php get_footer(); ?>