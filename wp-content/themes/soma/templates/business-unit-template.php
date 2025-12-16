<?php
/**
 * Template Name: Business Unit
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
global $page_builder;
$page_builder = get_field( 'soma_blocks' );

$bussiness_unit_data = get_field( 'business_unit_data' );
?>

<?php get_template_part( 'partials/BreadCrumb' ); ?>

<section class="bussiness-units-header">
	<div class="container">
		<div class="content" style="background-color: <?php echo esc_attr( $bussiness_unit_data['color'] ); ?>">
			<svg width="148px" height="150px" viewBox="0 0 148 150" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<g transform="translate(-1153.000000, -453.000000)">
						<g transform="translate(80.000000, 260.000000)">
							<g transform="translate(1073.000000, 193.000000)">
								<polygon fill="#FFFFFF" points="147.317073 125.614493 125.207484 60.7438592 104.503565 0 42.4222393 0 0 124.468328 0 149.752066 27.7279199 149.752066 66.6519257 34.2646893 80.2724295 34.2646893 119.194986 149.752066 147.317073 149.752066"></polygon>
							</g>
						</g>
					</g>
				</g>
			</svg>
		</div>
	</div>
</section>

<main id="business-unit-template-332fce">
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
