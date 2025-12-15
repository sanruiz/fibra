<?php
/**
 * Template Name: Soma Landing
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
$content = get_field( 'soma_landing_content' );

?>

<style>
	.navbar-partial-df27ae,
	.searchpanel-partial-1749fc,
	.footer-partial-c90350 {
		display: none !important;
	}
</style>

<main id="somalanding-template-11512d">
	<div class="container">
		<div class="landing-header">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/landing_logo.svg" alt="Landing Logo">
		</div>
		<div class="landing-body">
			<div class="logo">
				<svg width="193px" height="197px" viewBox="0 0 193 197">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<g transform="translate(-79.000000, -305.000000)">
							<g transform="translate(79.000000, 305.828125)">
								<polygon fill="#000000" points="192.672839 164.2885 163.756182 79.4455902 136.677971 0 55.4831366 0 0 162.789455 0 195.857514 36.2647515 195.857514 87.1726236 44.814052 104.986588 44.814052 155.892565 195.857514 192.672839 195.857514"></polygon>
							</g>
						</g>
					</g>
				</svg>
			</div>
			<div class="content">
				<?php if ( $content['title'] ) : ?>
					<div class="title">
					<h2><?php echo esc_html( $content['title'] ); ?></h2>
					</div>
				<?php endif; ?>
				<?php if ( $content['subtitle'] ) : ?>
					<div class="subtitle">
					<h3><?php echo esc_html( $content['subtitle'] ); ?></h3>
					</div>
				<?php endif; ?>
				<?php if ( $content['form_shortcode'] ) : ?>
					<div class="landing-form">
						<?php echo do_shortcode( $content['form_shortcode'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
