<?php
/**
 * Block Partial: Navbar
 *
 * Main site navigation bar with logo, menu, and language switcher.
 * Supports two styles: 'fibrasoma' and 'default'.
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data (usually empty for Navbar)
 * @uses get_query_var('soma_block_layout')  string Layout name ('Navbar')
 *
 * Note: Navbar typically uses ACF options page ('header_content', 'options')
 * rather than block-specific content.
 *
 * @see \Soma\PageBuilder\BlockRenderer
 * @see \Soma\PageBuilder\BlockRegistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$logo           = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];
$header_options = get_field( 'header_content', 'options' );
?>

<section class="navbar-partial-df27ae style-<?php echo esc_attr( $header_options['style'] ); ?>">
	<?php if ( $header_options['style'] === 'fibrasoma' ) : ?>
		<div class="fibrasoma-top-bar-container">
			<div class="container">
				<div class="top-bar fibrasoma-top-bar">
					<?php if ( $header_options['top_bar_link'] ) : ?>
						<div class="top-bar-link">
							<a href="<?php echo esc_url( $header_options['top_bar_link']['url'] ); ?>" target="<?php echo esc_attr( $header_options['top_bar_link']['target'] ); ?>">
								<svg width="6px" height="9px" viewBox="0 0 6 9" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<g transform="translate(-63.000000, -13.000000)">
											<g transform="translate(63.000000, -1.500000)" fill="#FFFFFF" fill-rule="nonzero" stroke="#FFFFFF" stroke-width="0.8">
												<g transform="translate(0.163281, 0.000000)">
													<polygon transform="translate(2.515600, 19.000000) rotate(-180.000000) translate(-2.515600, -19.000000) " points="0.599777147 23.0363029 0.395119533 22.8316453 4.22676486 19 0.395119533 15.1683547 0.599777147 14.9636971 4.63608008 19"></polygon>
												</g>
											</g>
										</g>
									</g>
								</svg>
								<?php echo esc_html( $header_options['top_bar_link']['title'] ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="lang-switcher" onClick="$(this).toggleClass('active')">
						<?php
						if ( function_exists( 'wpm_language_switcher' ) ) {
							wpm_language_switcher( 'dropdown', 'name' );}
						?>
					</div>
					<button class="search-trigger">
						<svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-1342.000000, -26.000000)" fill="#ffffff" fill-rule="nonzero" stroke="#ffffff" stroke-width="0.5">
									<g transform="translate(1273.000000, 15.500000)">
										<g transform="translate(69.821974, 10.800000)">
											<g transform="translate(0.000000, 0.200000)">
												<path d="M3.01193973,6.35675534 C3.03493447,2.82875496 5.90967068,-0.0147240176 9.43771128,0.000816577115 C12.9657519,0.0164850115 15.8152227,2.88528215 15.8069972,6.41334763 C15.798736,9.94141312 12.9358905,12.7968634 9.40781544,12.7959814 C5.86409957,12.7825882 3.0014081,9.90048078 3.01193973,6.35675534 Z M3.75460065,6.35675534 C3.77853702,9.47316857 6.32004886,11.9824073 9.43651323,11.9665703 C12.5529776,11.9505827 15.0687338,9.41552203 15.0608421,6.29902707 C15.0529131,3.18253212 12.5243204,0.660183348 9.40781544,0.660183348 C6.27505523,0.675481354 3.74597358,3.22396971 3.75460065,6.35675534 Z"></path>
												<polygon points="0.242478977 14.9617565 4.86178249 10.3601559 5.38590896 10.8862987 0.766605443 15.4878993"></polygon>
											</g>
										</g>
									</g>
								</g>
							</g>
						</svg>
					</button>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="container">
		<div class="content">
			<div class="logo">
				<a href="<?php echo esc_url( get_site_url() ); ?>">
					<?php if ( has_custom_logo() ) : ?>
						<img src="<?php echo esc_url( $logo ); ?>" alt="SOMA Logo">
					<?php else : ?>
						<?php bloginfo( 'name' ); ?>
					<?php endif; ?>
				</a>
			</div>
			<div class="hamburger">
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="nav">
				<div class="top-bar">
					<div class="lang-switcher" onClick="$(this).toggleClass('active')">
						<?php
						if ( function_exists( 'wpm_language_switcher' ) ) {
							wpm_language_switcher( 'dropdown', 'name' );}
						?>
					</div>
					<button class="search-trigger">
						<svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-1342.000000, -26.000000)" fill="#7E7E87" fill-rule="nonzero" stroke="#7E7E87" stroke-width="0.5">
									<g transform="translate(1273.000000, 15.500000)">
										<g transform="translate(69.821974, 10.800000)">
											<g transform="translate(0.000000, 0.200000)">
												<path d="M3.01193973,6.35675534 C3.03493447,2.82875496 5.90967068,-0.0147240176 9.43771128,0.000816577115 C12.9657519,0.0164850115 15.8152227,2.88528215 15.8069972,6.41334763 C15.798736,9.94141312 12.9358905,12.7968634 9.40781544,12.7959814 C5.86409957,12.7825882 3.0014081,9.90048078 3.01193973,6.35675534 Z M3.75460065,6.35675534 C3.77853702,9.47316857 6.32004886,11.9824073 9.43651323,11.9665703 C12.5529776,11.9505827 15.0687338,9.41552203 15.0608421,6.29902707 C15.0529131,3.18253212 12.5243204,0.660183348 9.40781544,0.660183348 C6.27505523,0.675481354 3.74597358,3.22396971 3.75460065,6.35675534 Z"></path>
												<polygon points="0.242478977 14.9617565 4.86178249 10.3601559 5.38590896 10.8862987 0.766605443 15.4878993"></polygon>
											</g>
										</g>
									</g>
								</g>
							</g>
						</svg>
					</button>
				</div>
				<div class="main-menu-container">
					<?php
						wp_nav_menu(
							array(
								'menu'           => 'main_menu',
								'theme_location' => 'main_menu',
								'container'      => 'div',
								'menu_class'     => 'main-menu-list',
							)
						);
						?>
				</div>
			</div>
		</div>
	</div>
</section>
