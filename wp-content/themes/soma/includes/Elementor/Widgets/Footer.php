<?php
/**
 * Footer Elementor Widget
 *
 * @package Soma\Elementor\Widgets
 */

namespace Soma\Elementor\Widgets;

use Elementor\Controls_Manager;
use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Footer Widget
 *
 * Displays site footer with logo, newsletter, navigation menus, and copyright.
 * Supports two style variants: default (social + business units) and fibrasoma (single menu).
 */
class Footer extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-footer';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Footer', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-footer';
	}

	/**
	 * Register widget controls
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls
	 *
	 * @return void
	 */
	private function register_content_controls(): void {
		// Style variant.
		$this->start_controls_section(
			'section_variant',
			[
				'label' => __( 'Style Variant', 'soma' ),
			]
		);

		$this->add_control(
			'style_variant',
			[
				'label'   => __( 'Footer Style', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'   => __( 'Default (Social + Business Units)', 'soma' ),
					'fibrasoma' => __( 'Fibrasoma (Single Menu)', 'soma' ),
				],
				'default' => 'default',
			]
		);

		$this->end_controls_section();

		// Logo section.
		$this->start_controls_section(
			'section_logo',
			[
				'label' => __( 'Logo', 'soma' ),
			]
		);

		$this->add_control(
			'use_acf_logo',
			[
				'label'   => __( 'Use ACF Logo', 'soma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'custom_logo',
			[
				'label'     => __( 'Custom Logo', 'soma' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'use_acf_logo' => '',
				],
			]
		);

		$this->add_control(
			'logo_subtext',
			[
				'label'       => __( 'Logo Subtext', 'soma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'placeholder' => __( 'Enter subtext below logo', 'soma' ),
			]
		);

		$this->end_controls_section();

		// Newsletter section.
		$this->start_controls_section(
			'section_newsletter',
			[
				'label' => __( 'Newsletter', 'soma' ),
			]
		);

		$this->add_control(
			'newsletter_form',
			[
				'label'       => __( 'Contact Form 7 Shortcode', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => '[contact-form-7 id="123"]',
				'description' => __( 'Enter the CF7 shortcode for newsletter subscription', 'soma' ),
			]
		);

		$this->add_control(
			'success_message',
			[
				'label'   => __( 'Success Message', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Thank you for subscribing.', 'soma' ),
			]
		);

		$this->end_controls_section();

		// Location section.
		$this->start_controls_section(
			'section_location',
			[
				'label' => __( 'Location', 'soma' ),
			]
		);

		$this->add_control(
			'location_text',
			[
				'label'   => __( 'Location Text', 'soma' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => '',
			]
		);

		$this->end_controls_section();

		// Menus section.
		$this->start_controls_section(
			'section_menus',
			[
				'label' => __( 'Navigation Menus', 'soma' ),
			]
		);

		$this->add_control(
			'fibrasoma_menu',
			[
				'label'     => __( 'Fibrasoma Menu', 'soma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_menu_choices(),
				'default'   => 'fibrasoma_footer',
				'condition' => [
					'style_variant' => 'fibrasoma',
				],
			]
		);

		$this->add_control(
			'social_menu',
			[
				'label'     => __( 'Social Menu', 'soma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_menu_choices(),
				'default'   => 'social',
				'condition' => [
					'style_variant' => 'default',
				],
			]
		);

		$this->add_control(
			'business_units_menu',
			[
				'label'     => __( 'Business Units Menu', 'soma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_menu_choices(),
				'default'   => 'business_units',
				'condition' => [
					'style_variant' => 'default',
				],
			]
		);

		$this->end_controls_section();

		// Footer bottom section.
		$this->start_controls_section(
			'section_bottom',
			[
				'label' => __( 'Footer Bottom', 'soma' ),
			]
		);

		$this->add_control(
			'copyright',
			[
				'label'   => __( 'Copyright Text', 'soma' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '© ' . current_time( 'Y' ) . ' ' . get_bloginfo( 'name' ),
			]
		);

		$this->add_control(
			'credits_link',
			[
				'label' => __( 'Credits Link', 'soma' ),
				'type'  => Controls_Manager::URL,
			]
		);

		$this->add_control(
			'privacy_link',
			[
				'label' => __( 'Privacy Policy Link', 'soma' ),
				'type'  => Controls_Manager::URL,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 *
	 * @return void
	 */
	private function register_style_controls(): void {
		// Container styles.
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => __( 'Container', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_background_control( 'container_background', __( 'Background', 'soma' ), '{{WRAPPER}} .footer-partial-c90350' );
		$this->add_spacing_control( 'container_padding', __( 'Padding', 'soma' ), '{{WRAPPER}} .footer-partial-c90350 .container' );

		$this->end_controls_section();

		// Logo styles.
		$this->start_controls_section(
			'section_style_logo',
			[
				'label' => __( 'Logo', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label'      => __( 'Width', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => 200,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_typography_control(
			'logo_subtext_typography',
			__( 'Subtext Typography', 'soma' ),
			'{{WRAPPER}} .logo_subtext'
		);

		$this->add_color_control( 'logo_subtext_color', __( 'Subtext Color', 'soma' ), '{{WRAPPER}} .logo_subtext', '--soma-text-light' );

		$this->end_controls_section();

		// Typography.
		$this->start_controls_section(
			'section_style_typography',
			[
				'label' => __( 'Typography', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_typography_control(
			'menu_title_typography',
			__( 'Menu Title Typography', 'soma' ),
			'{{WRAPPER}} .nav-list .title'
		);

		$this->add_typography_control(
			'menu_item_typography',
			__( 'Menu Item Typography', 'soma' ),
			'{{WRAPPER}} .nav-list a'
		);

		$this->add_typography_control(
			'copyright_typography',
			__( 'Copyright Typography', 'soma' ),
			'{{WRAPPER}} .copyright'
		);

		$this->end_controls_section();

		// Colors.
		$this->start_controls_section(
			'section_style_colors',
			[
				'label' => __( 'Colors', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_color_control( 'text_color', __( 'Text Color', 'soma' ), '{{WRAPPER}}', '--soma-text-light' );
		$this->add_color_control( 'link_color', __( 'Link Color', 'soma' ), '{{WRAPPER}} a', '--soma-text-primary' );
		$this->add_color_control( 'link_hover_color', __( 'Link Hover Color', 'soma' ), '{{WRAPPER}} a:hover', '--soma-primary' );

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		// Get ACF data if enabled.
		$acf_data = $settings['use_acf_logo'] === 'yes' ? $this->get_acf_option( 'footer_content', [] ) : [];

		$style_variant = $settings['style_variant'];
		$logo          = $settings['use_acf_logo'] === 'yes' && ! empty( $acf_data['logo'] ) ? $acf_data['logo'] : $settings['custom_logo'];
		$logo_subtext  = ! empty( $settings['logo_subtext'] ) ? $settings['logo_subtext'] : ( $acf_data['logo_subtext'] ?? '' );
		$location_text = ! empty( $settings['location_text'] ) ? $settings['location_text'] : ( $acf_data['location_text'] ?? '' );
		?>
		<section class="footer-partial-c90350 style-<?php echo esc_attr( $style_variant ); ?>">
			<div class="container">
				<div class="content">
					<!-- Row 1: Logo, Location (mobile), Newsletter -->
					<div class="row">
						<div class="logo">
							<?php if ( ! empty( $logo ) ) : ?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<?php if ( is_array( $logo ) ) : ?>
										<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ?? '' ); ?>">
									<?php else : ?>
										<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
									<?php endif; ?>
								</a>
							<?php endif; ?>
							<?php if ( $logo_subtext ) : ?>
								<div class="logo_subtext">
									<?php echo wp_kses_post( $logo_subtext ); ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="location mobile-copy">
							<?php echo wp_kses_post( $location_text ); ?>
						</div>
						<div class="newsletter">
							<?php if ( ! empty( $settings['newsletter_form'] ) ) : ?>
								<?php echo do_shortcode( $settings['newsletter_form'] ); ?>
							<?php endif; ?>
							<div class="success-form" style="display: none;">
								<?php echo esc_html( $settings['success_message'] ); ?>
							</div>
						</div>
					</div>

					<!-- Row 2: Location (desktop), Navigation -->
					<div class="row">
						<div class="location">
							<?php echo wp_kses_post( $location_text ); ?>
						</div>
						<div class="nav">
							<div class="nav-container">
								<?php if ( $style_variant === 'fibrasoma' ) : ?>
									<?php
									wp_nav_menu(
										[
											'menu'        => $settings['fibrasoma_menu'],
											'theme_location' => 'fibrasoma_footer',
											'container'   => 'div',
											'menu_class'  => 'fibrasoma-list',
											'fallback_cb' => false,
										]
									);
									?>
								<?php else : ?>
									<div class="nav-list">
										<div class="title"><?php echo function_exists( 'wpm_get_language' ) && wpm_get_language() === 'es' ? 'Redes' : 'Social'; ?></div>
										<?php
										wp_nav_menu(
											[
												'menu' => $settings['social_menu'],
												'theme_location' => 'social',
												'container' => 'div',
												'menu_class' => 'social-list',
												'fallback_cb' => false,
											]
										);
										?>
									</div>
									<div class="nav-list">
										<div class="title"><?php echo function_exists( 'wpm_get_language' ) && wpm_get_language() === 'es' ? 'Unidades de Negocio' : 'Business Units'; ?></div>
										<?php
										wp_nav_menu(
											[
												'menu' => $settings['business_units_menu'],
												'theme_location' => 'business_units',
												'container' => 'div',
												'menu_class' => 'business-list',
												'fallback_cb' => false,
											]
										);
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<!-- Row 3: Copyright, Credits/Privacy -->
					<div class="row">
						<div class="copyright">
							<?php echo wp_kses_post( $settings['copyright'] ); ?>
						</div>
						<div class="credits">
							<?php if ( ! empty( $settings['credits_link']['url'] ) ) : ?>
								<a href="<?php echo esc_url( $settings['credits_link']['url'] ); ?>" 
									target="<?php echo esc_attr( $settings['credits_link']['is_external'] ? '_blank' : '_self' ); ?>">
									<?php echo esc_html( $settings['credits_link']['custom_attributes'] ?? __( 'Credits', 'soma' ) ); ?>
								</a>
							<?php endif; ?>
							<?php if ( ! empty( $settings['privacy_link']['url'] ) ) : ?>
								<a href="<?php echo esc_url( $settings['privacy_link']['url'] ); ?>" 
									target="<?php echo esc_attr( $settings['privacy_link']['is_external'] ? '_blank' : '_self' ); ?>">
									<?php echo esc_html( $settings['privacy_link']['custom_attributes'] ?? __( 'Privacy Policy', 'soma' ) ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<script>
		jQuery(function($) {
			var footerElm = $('.footer-partial-c90350');
			if (footerElm.length) {
				footerElm[0].addEventListener('wpcf7invalid', function(event) {
					$('.wpcf7-not-valid-tip').each(function() {
						$('#btn-arrow').addClass('noempty');
						var errorText = $(this).text();
						if (errorText.includes('not valid') || errorText.includes('invalid')) {
							$(this).show().text('Invalid email');
						}
					});
				}, false);

				footerElm[0].addEventListener('wpcf7mailsent', function(event) {
					$('form').hide(200);
					$('.success-form').show(200);
				}, false);

				$(document).ajaxStart(function() {
					$("#wait").css("display", "block");
					$(".ajax-loader-name").text("Submitting...");
					$('#btn-arrow').hide();
					$('#input-email').css("font-size", "0px");
				});

				$(document).ajaxComplete(function() {
					$("#wait").css("display", "none");
					$(".ajax-loader-name").text("");
					$('#btn-arrow').show();
					$('#input-email').css("font-size", "16px");
				});
			}
		});
		</script>
		<?php
	}
}
