<?php
/**
 *
 * Default page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

global $post;
global $pageBuilder;
$pageBuilder    = get_field( 'soma_blocks' );
$header_options = get_field( 'header_content', 'options' );
?>

<?php if ( ! is_front_page() ) : ?>
	<?php get_template_part( 'partials/BreadCrumb' ); ?>
<?php endif; ?>

<main id="ditto-page" page-slug="<?php echo $post->post_name; ?>" data-header-style="<?php echo $header_options['style']; ?>">
	<?php get_template_part( 'page-builder' ); ?>
</main>

<?php get_footer(); ?>