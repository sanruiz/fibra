<?php
/**
 * Template Name: Navigation Sidebar
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

$arrow = '
<svg width="16px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" fill="none" fill-rule="evenodd">
        <g transform="translate(1.000000, 0.000000)" stroke="#171717">
            <g transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="3" stroke-linecap="square"></line>
                <polygon stroke-width="2" fill="#171717" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
            </g>
        </g>
    </g>
</svg>
';
?>

<?php if ( ! is_front_page() ) : ?>
	<?php get_template_part( 'partials/BreadCrumb' ); ?>
<?php endif; ?>

<main id="navigationsidebar-template-207713" page-slug="<?php echo esc_attr( $post->post_name ); ?>" data-header-style="<?php echo esc_attr( $header_options['style'] ); ?>">
	<div class="container">
		<div class="navigationsidebar-template-content">
			<div class="template-menu">
				<?php
					wp_nav_menu(
						[
							'menu'           => 'navigation_sidebar_template',
							'theme_location' => 'navigation_sidebar_template',
							'container'      => 'div',
							'menu_class'     => 'navigation-sidebar-list',
						]
					);
					?>
				<?php if ( $header_options['navigation_sidebar_template_file']['label'] && $header_options['navigation_sidebar_template_file']['file'] ) : ?>
					<a class="extra-file" href="<?php echo esc_url( $header_options['navigation_sidebar_template_file']['file']['url'] ); ?>" target="_blank">
						<?php echo esc_html( $header_options['navigation_sidebar_template_file']['label'] ) . wp_kses_post( $arrow ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="template-content">
				<?php
				// Elementor support - required for Elementor editor to work.
				the_content();

				// ACF Flexible Content - only render if not using Elementor.
				if ( ! did_action( 'elementor/loaded' ) && ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
					get_template_part( 'page-builder' );
				}
				?>
			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>
