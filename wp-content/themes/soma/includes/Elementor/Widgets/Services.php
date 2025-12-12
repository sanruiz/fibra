<?php
/**
 * Services Elementor Widget
 *
 * @package Soma\Elementor\Widgets
 */

namespace Soma\Elementor\Widgets;

use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Services Widget
 *
 * Displays services/features in a grid layout using Elementor repeater.
 */
class Services extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-services';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Services', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-info-box';
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
		// Services section.
		$this->start_controls_section(
			'section_services',
			[
				'label' => __( 'Services', 'soma' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'service_icon',
			[
				'label' => __( 'Icon', 'soma' ),
				'type'  => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'service_title',
			[
				'label'   => __( 'Title', 'soma' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Service Title', 'soma' ),
			]
		);

		$repeater->add_control(
			'service_description',
			[
				'label'   => __( 'Description', 'soma' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Service description goes here.', 'soma' ),
			]
		);

		$repeater->add_control(
			'service_link',
			[
				'label' => __( 'Link', 'soma' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);

		$this->add_control(
			'services_list',
			[
				'label'       => __( 'Services List', 'soma' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'service_title'       => __( 'Service 1', 'soma' ),
						'service_description' => __( 'Description for service 1', 'soma' ),
					],
					[
						'service_title'       => __( 'Service 2', 'soma' ),
						'service_description' => __( 'Description for service 2', 'soma' ),
					],
					[
						'service_title'       => __( 'Service 3', 'soma' ),
						'service_description' => __( 'Description for service 3', 'soma' ),
					],
				],
				'title_field' => '{{{ service_title }}}',
			]
		);

		$this->end_controls_section();

		// Layout section.
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'soma' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'           => __( 'Columns', 'soma' ),
				'type'            => \Elementor\Controls_Manager::SELECT,
				'desktop_default' => '3',
				'tablet_default'  => '2',
				'mobile_default'  => '1',
				'options'         => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors'       => [
					'{{WRAPPER}} .services-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_responsive_control(
			'grid_gap',
			[
				'label'      => __( 'Gap', 'soma' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default'    => [
					'size' => 30,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .services-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
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
		// Card styles.
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => __( 'Card', 'soma' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_background_control( 'card_background', __( 'Background', 'soma' ), '--soma-bg-white' );
		$this->add_spacing_control( 'card_padding', __( 'Padding', 'soma' ), '30px' );
		$this->add_border_control( 'card_border', __( 'Border', 'soma' ) );
		$this->add_shadow_control( 'card_shadow', __( 'Box Shadow', 'soma' ) );

		$this->add_control(
			'card_hover_transition',
			[
				'label'     => __( 'Hover Transition', 'soma' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					],
				],
				'default'   => [
					'size' => 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .service-item' => 'transition: all {{SIZE}}s ease;',
				],
			]
		);

		$this->end_controls_section();

		// Icon styles.
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'soma' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Size', 'soma' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
					'rem' => [
						'min' => 1,
						'max' => 12,
					],
				],
				'default'    => [
					'size' => 60,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .service-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'      => __( 'Spacing', 'soma' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 6,
					],
				],
				'default'    => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .service-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Title styles.
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'soma' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_typography_control(
			'title_typography',
			__( 'Typography', 'soma' ),
			'{{WRAPPER}} .service-title'
		);

		$this->add_color_control( 'title_color', __( 'Color', 'soma' ), '{{WRAPPER}} .service-title', '--soma-text-primary' );

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'      => __( 'Spacing', 'soma' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'rem' => [
						'min' => 0,
						'max' => 3,
					],
				],
				'default'    => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .service-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Description styles.
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => __( 'Description', 'soma' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_typography_control(
			'description_typography',
			__( 'Typography', 'soma' ),
			'{{WRAPPER}} .service-description'
		);

		$this->add_color_control( 'description_color', __( 'Color', 'soma' ), '{{WRAPPER}} .service-description', '--soma-text-secondary' );

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['services_list'] ) ) {
			return;
		}
		?>
		<div class="soma-services">
			<div class="services-grid">
				<?php foreach ( $settings['services_list'] as $index => $service ) : ?>
					<?php
					$has_link = ! empty( $service['service_link']['url'] );
					$tag      = $has_link ? 'a' : 'div';
					?>
					<<?php echo esc_html( $tag ); ?> class="service-item"
						<?php if ( $has_link ) : ?>
							href="<?php echo esc_url( $service['service_link']['url'] ); ?>"
							<?php if ( ! empty( $service['service_link']['is_external'] ) ) : ?>
								target="_blank"
							<?php endif; ?>
							<?php if ( ! empty( $service['service_link']['nofollow'] ) ) : ?>
								rel="nofollow"
							<?php endif; ?>
						<?php endif; ?>
					>
						<?php if ( ! empty( $service['service_icon']['url'] ) ) : ?>
							<div class="service-icon">
								<img src="<?php echo esc_url( $service['service_icon']['url'] ); ?>" alt="<?php echo esc_attr( $service['service_title'] ); ?>">
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $service['service_title'] ) ) : ?>
							<h3 class="service-title"><?php echo esc_html( $service['service_title'] ); ?></h3>
						<?php endif; ?>

						<?php if ( ! empty( $service['service_description'] ) ) : ?>
							<div class="service-description"><?php echo wp_kses_post( $service['service_description'] ); ?></div>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
