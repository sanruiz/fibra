<?php
/**
 * Navbar Elementor Widget
 *
 * Elementor widget for site navigation bar.
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.0.0
 */

namespace Soma\Elementor\Widgets;

use Soma\Elementor\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Navbar widget class
 *
 * Renders a customizable navigation bar with logo, menu, search, and language switcher.
 */
class Navbar extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-navbar';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Navbar', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-nav-menu';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return array( 'soma-navbar' );
	}

	/**
	 * Register widget controls
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content tab controls
	 */
	private function register_content_controls(): void {
		// Menu Section.
		$this->start_controls_section(
			'section_menu',
			array(
				'label' => __( 'Menu', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'   => __( 'Select Menu', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_menu_choices(),
				'default' => '',
			)
		);

		$this->add_control(
			'menu_location',
			array(
				'label'   => __( 'Theme Location', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''                            => __( 'Select Location', 'soma' ),
					'main_menu'                   => __( 'Main Menu', 'soma' ),
					'social'                      => __( 'Social', 'soma' ),
					'business_units'              => __( 'Business Units', 'soma' ),
					'fibrasoma_footer'            => __( 'Fibrasoma Footer', 'soma' ),
					'navigation_sidebar_template' => __( 'Navigation Sidebar', 'soma' ),
				),
				'default' => 'main_menu',
			)
		);

		$this->end_controls_section();

		// Logo Section.
		$this->start_controls_section(
			'section_logo',
			array(
				'label' => __( 'Logo', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_logo',
			array(
				'label'        => __( 'Show Logo', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'soma' ),
				'label_off'    => __( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'use_custom_logo',
			array(
				'label'        => __( 'Use Theme Customizer Logo', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_logo' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_logo',
			array(
				'label'     => __( 'Custom Logo', 'soma' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => '',
					'id'  => 0,
				),
				'condition' => array(
					'show_logo'        => 'yes',
					'use_custom_logo!' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Features Section.
		$this->start_controls_section(
			'section_features',
			array(
				'label' => __( 'Features', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_search',
			array(
				'label'        => __( 'Show Search Button', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'soma' ),
				'label_off'    => __( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_language_switcher',
			array(
				'label'        => __( 'Show Language Switcher', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'soma' ),
				'label_off'    => __( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'style_variant',
			array(
				'label'   => __( 'Style Variant', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default'   => __( 'Default', 'soma' ),
					'fibrasoma' => __( 'Fibrasoma', 'soma' ),
				),
				'default' => 'default',
			)
		);

		$this->add_control(
			'top_bar_link_text',
			array(
				'label'       => __( 'Top Bar Link Text', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Link text', 'soma' ),
				'condition'   => array(
					'style_variant' => 'fibrasoma',
				),
			)
		);

		$this->add_control(
			'top_bar_link_url',
			array(
				'label'       => __( 'Top Bar Link URL', 'soma' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soma' ),
				'default'     => array(
					'url'         => '',
					'is_external' => true,
				),
				'condition'   => array(
					'style_variant' => 'fibrasoma',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style tab controls
	 */
	private function register_style_controls(): void {
		// Navbar Style.
		$this->start_controls_section(
			'section_navbar_style',
			array(
				'label' => __( 'Navbar', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_background_control( 'navbar_background', __( 'Background', 'soma' ), '{{WRAPPER}} .soma-navbar' );

		$this->add_spacing_control(
			'navbar_padding',
			__( 'Padding', 'soma' ),
			'{{WRAPPER}} .soma-navbar .content',
			array(
				'top'    => '1',
				'right'  => '0',
				'bottom' => '1',
				'left'   => '0',
				'unit'   => 'rem',
			)
		);

		$this->add_control(
			'navbar_height',
			array(
				'label'      => __( 'Height', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 50,
						'max' => 200,
					),
					'rem' => array(
						'min' => 3,
						'max' => 12,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-navbar .content' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Menu Items Style.
		$this->start_controls_section(
			'section_menu_style',
			array(
				'label' => __( 'Menu Items', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_typography_control(
			'menu_typography',
			__( 'Typography', 'soma' ),
			'{{WRAPPER}} .main-menu-list > li > a'
		);

		$this->add_control(
			'menu_item_spacing',
			array(
				'label'      => __( 'Item Spacing', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 100,
					),
					'rem' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'default'    => array(
					'unit' => 'rem',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .main-menu-list > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'menu_items_tabs' );

		// Normal State.
		$this->start_controls_tab(
			'menu_items_normal',
			array(
				'label' => __( 'Normal', 'soma' ),
			)
		);

		$this->add_color_control(
			'menu_item_color',
			__( 'Text Color', 'soma' ),
			'{{WRAPPER}} .main-menu-list > li > a',
			'color',
			'var(--soma-text-primary)'
		);

		$this->end_controls_tab();

		// Hover State.
		$this->start_controls_tab(
			'menu_items_hover',
			array(
				'label' => __( 'Hover', 'soma' ),
			)
		);

		$this->add_color_control(
			'menu_item_hover_color',
			__( 'Text Color', 'soma' ),
			'{{WRAPPER}} .main-menu-list > li > a:hover',
			'color',
			'var(--soma-primary)'
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Logo Style.
		$this->start_controls_section(
			'section_logo_style',
			array(
				'label'     => __( 'Logo', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_logo' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'logo_width',
			array(
				'label'      => __( 'Width', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		// Get logo URL.
		$logo_url = '';
		if ( 'yes' === $settings['show_logo'] ) {
			if ( 'yes' === $settings['use_custom_logo'] && has_custom_logo() ) {
				$logo_id    = get_theme_mod( 'custom_logo' );
				$logo_image = wp_get_attachment_image_src( $logo_id, 'full' );
				$logo_url   = is_array( $logo_image ) ? $logo_image[0] : '';
			} elseif ( ! empty( $settings['custom_logo']['url'] ) ) {
				$logo_url = $settings['custom_logo']['url'];
			}
		}

		$classes = array( 'soma-navbar', 'navbar-partial-df27ae', 'style-' . $settings['style_variant'] );
		?>
		<section<?php $this->render_classes( $classes ); ?>>
			<?php if ( 'fibrasoma' === $settings['style_variant'] ) : ?>
				<div class="fibrasoma-top-bar-container">
					<div class="container">
						<div class="top-bar fibrasoma-top-bar">
							<?php if ( ! empty( $settings['top_bar_link_text'] ) ) : ?>
								<div class="top-bar-link">
									<a href="<?php echo esc_url( $settings['top_bar_link_url']['url'] ?? '#' ); ?>"
										target="<?php echo $settings['top_bar_link_url']['is_external'] ? '_blank' : '_self'; ?>">
										<svg width="6px" height="9px" viewBox="0 0 6 9" version="1.1">
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
										<?php echo esc_html( $settings['top_bar_link_text'] ); ?>
									</a>
								</div>
							<?php endif; ?>
							<?php if ( 'yes' === $settings['show_language_switcher'] ) : ?>
								<div class="lang-switcher" onClick="$(this).toggleClass('active')">
									<?php
									if ( function_exists( 'wpm_language_switcher' ) ) {
										wpm_language_switcher( 'dropdown', 'name' );
									}
									?>
								</div>
							<?php endif; ?>
							<?php if ( 'yes' === $settings['show_search'] ) : ?>
								<?php $this->render_search_button( '#ffffff' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="container">
				<div class="content">
					<?php if ( 'yes' === $settings['show_logo'] && $logo_url ) : ?>
						<div class="logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							</a>
						</div>
					<?php elseif ( 'yes' === $settings['show_logo'] ) : ?>
						<div class="logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php bloginfo( 'name' ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="hamburger">
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
					<div class="nav">
						<div class="top-bar">
							<?php if ( 'yes' === $settings['show_language_switcher'] ) : ?>
								<div class="lang-switcher" onClick="$(this).toggleClass('active')">
									<?php
									if ( function_exists( 'wpm_language_switcher' ) ) {
										wpm_language_switcher( 'dropdown', 'name' );
									}
									?>
								</div>
							<?php endif; ?>
							<?php if ( 'yes' === $settings['show_search'] ) : ?>
								<?php $this->render_search_button( '#7E7E87' ); ?>
							<?php endif; ?>
						</div>
						<div class="main-menu-container">
							<?php
							$menu_args = array(
								'container'  => 'div',
								'menu_class' => 'main-menu-list',
							);

							if ( ! empty( $settings['menu'] ) ) {
								$menu_args['menu'] = $settings['menu'];
							} elseif ( ! empty( $settings['menu_location'] ) ) {
								$menu_args['theme_location'] = $settings['menu_location'];
							}

							wp_nav_menu( $menu_args );
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Render search button SVG
	 *
	 * @param string $color Fill color for SVG.
	 */
	private function render_search_button( string $color ): void {
		?>
		<button class="search-trigger">
			<svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<g transform="translate(-1342.000000, -26.000000)" fill="<?php echo esc_attr( $color ); ?>" fill-rule="nonzero" stroke="<?php echo esc_attr( $color ); ?>" stroke-width="0.5">
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
		<?php
	}
}

