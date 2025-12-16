<?php
/**
 * Page Template.
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

global $post;
global $page_builder;
$page_builder   = get_field( 'soma_blocks' );
$header_options = get_field( 'header_content', 'options' );
?>

<?php if ( ! is_front_page() ) : ?>
	<?php get_template_part( 'partials/BreadCrumb' ); ?>
<?php endif; ?>

<main id="ditto-page" page-slug="<?php echo esc_attr( $post->post_name ); ?>" data-header-style="<?php echo esc_attr( $header_options['style'] ); ?>">
	<?php
	// Elementor support - required for Elementor editor to work
	the_content();
	
	// ACF Flexible Content - only render if not using Elementor
	if ( ! did_action( 'elementor/loaded' ) || ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		get_template_part( 'page-builder' );
	}
	?>
</main>

<?php get_footer(); ?>