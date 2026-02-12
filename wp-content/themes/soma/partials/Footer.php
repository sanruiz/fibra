<?php
/**
 * Block Partial: Footer
 *
 * Site footer with logo, location info, navigation menus, and social links.
 * Supports two styles: 'fibrasoma' and 'default'.
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data (usually empty for Footer)
 * @uses get_query_var('soma_block_layout')  string Layout name ('Footer')
 *
 * Note: Footer typically uses ACF options page ('footer_content', 'options')
 * rather than block-specific content.
 *
 * @see \Soma\PageBuilder\BlockRenderer
 * @see \Soma\PageBuilder\BlockRegistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$data = get_field( 'footer_content', 'options' );
?>

<section class="footer-partial-c90350 style-<?php echo esc_attr( $data['style'] ); ?>">
	<div class="container">
		<div class="content">
			<div class="row">
				<div class="logo">
					<?php if ( $data['logo'] ) : ?>
						<a href="<?php echo esc_url( get_site_url() ); ?>">
							<img src="<?php echo esc_url( $data['logo']['url'] ); ?>" alt="<?php echo esc_attr( $data['logo']['alt'] ); ?>">
						</a>
					<?php endif; ?>
					<div class="logo_subtext">
						<?php echo wp_kses_post( $data['logo_subtext'] ); ?>
					</div>
				</div>
				<div class="location mobile-copy">
					<?php echo wp_kses_post( $data['location_text'] ); ?>
				</div>
				<div class="newsletter">
					<?php echo do_shortcode( $data['newsletter_form_shortcode'] ); ?>
					<div class="success-form">
							Thank you for subscribing.                 
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="location">
					<?php echo wp_kses_post( $data['location_text'] ); ?>
				</div>
				<div class="nav">
					<div class="nav-container">
						<?php if ( $data['style'] === 'fibrasoma' ) : ?>
							<?php
								wp_nav_menu(
									array(
										'menu'           => 'fibrasoma_footer',
										'theme_location' => 'fibrasoma_footer',
										'container'      => 'div',
										'menu_class'     => 'fibrasoma-list',
									)
								);
							?>
							<div class="nav-list">
								<div class="title"><?php esc_html_e( 'Social', 'soma' ); ?></div>
							<?php
								wp_nav_menu(
									array(
										'menu'           => 'social',
										'theme_location' => 'social',
										'container'      => 'div',
										'menu_class'     => 'social-list',
									)
								);
							?>
							</div>
						<?php else : ?>
							<div class="nav-list">
								<div class="title"><?php esc_html_e( 'Social', 'soma' ); ?></div>
								<?php
									wp_nav_menu(
										array(
											'menu'       => 'social',
											'theme_location' => 'social',
											'container'  => 'div',
											'menu_class' => 'social-list',
										)
									);
								?>
							</div>
							<div class="nav-list">
								<div class="title"><?php esc_html_e( 'Business Units', 'soma' ); ?></div>
								<?php
									wp_nav_menu(
										array(
											'menu'       => 'business_units',
											'theme_location' => 'business_units',
											'container'  => 'div',
											'menu_class' => 'business-list',
										)
									);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="copyright"><?php echo wp_kses_post( $data['copyright'] ); ?></div>
				<div class="credits">
					<?php if ( $data['credits'] ) : ?>
						<a href="<?php echo esc_url( $data['credits']['url'] ); ?>" target="<?php echo esc_attr( $data['credits']['target'] ); ?>"><?php echo esc_html( $data['credits']['title'] ); ?></a>
					<?php endif; ?>
					<?php if ( $data['privacy_policy'] ) : ?>
						<a href="<?php echo esc_url( $data['privacy_policy']['url'] ); ?>" target="<?php echo esc_attr( $data['privacy_policy']['target'] ); ?>"><?php echo esc_html( $data['privacy_policy']['title'] ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	jQuery(function(){
		var wpcf7Elm = document.querySelector( '.footer-partial-c90350' );
		wpcf7Elm.addEventListener( 'wpcf7invalid', function( event ) {
			var inputs = event.detail.inputs;
			for ( var i = 0; i < inputs.length; i++ ) {            
				
				$('.wpcf7-not-valid-tip').each(function() {
					jQuery('#btn-arrow').addClass('noempty');
				if ($(this).text() === "Email address entered is not valid, DNS resolution failed." ||  jQuery(this).text() === "The email value is not valid." ||  jQuery(this).text() === "The e-mail address entered is invalid." || jQuery(this).text() === "La dirección de correo electrónico que has introducido no es válida.") {
						$(this).show();
						$(this).text('Inavalid email');
					}
				})
			}
		}, false );
		wpcf7Elm.addEventListener( 'wpcf7mailsent', function( event ) {
			
			$('form').hide(200);
			$('.success-form').show(200);
		}, false );

		$(document).ajaxStart(function(){
			$("#wait").css("display", "block");
			$(".ajax-loader-name").text("Submitting...");
			$('#btn-arrow').hide();
			$('#input-email').css("font-size", "0px");
		});
		$(document).ajaxComplete(function(){
			$("#wait").css("display", "none");
			$(".ajax-loader-name").text("");
			$('#btn-arrow').show();
			$('#input-email').css("font-size", "16px");
		});
	});
</script>
