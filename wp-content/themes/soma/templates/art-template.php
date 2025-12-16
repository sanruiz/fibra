<?php
/**
 * Template Name: Art
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();

global $page_builder;
$page_builder = get_field( 'soma_blocks' );

$art_content = get_field( 'art_content' );
?>

<?php get_template_part( 'partials/BreadCrumb' ); ?>

<main id="art-template-d74087">

	<section class="art-title-section">
		<div class="container">
			<h2><?php echo esc_html( get_the_title() ); ?></h2>
			<p><?php echo esc_html( $art_content['subtitle'] ); ?></p>
		</div>
		<script>
			$('#menu-main-menu .menu-item.art').addClass('current-menu-item');
		</script>
	</section>

	<?php
	// Elementor support - required for Elementor editor to work.
	the_content();

	// ACF Flexible Content - only render if not using Elementor.
	if ( ! did_action( 'elementor/loaded' ) && ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		get_template_part( 'page-builder' );
	}
	?>
	
</main>

<?php get_footer(); ?>
