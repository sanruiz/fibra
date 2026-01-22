<?php
/**
 * Portfolio Technical Specs Elementor Widget.
 *
 * Displays project technical specifications using an Elementor repeater.
 * Fully customizable via Elementor controls - no ACF dependency.
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.23
 */

declare(strict_types=1);

namespace Soma\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portfolio Technical Specs Widget.
 *
 * Simple repeater-based widget for displaying project specifications
 * as label/value pairs. Content is fully managed via Elementor.
 *
 * @since 3.1.23
 */
class PortfolioTechnicalSpecs extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'soma-portfolio-technical-specs';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'Portfolio Technical Specs', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-bullet-list';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories(): array {
		return array( 'soma' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords(): array {
		return array( 'portfolio', 'specs', 'technical', 'project', 'details', 'soma' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style handles.
	 */
	public function get_style_depends(): array {
		return array( 'soma-portfolio-technical-specs' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @return void
	 */
	protected function register_content_controls(): void {
		$this->start_controls_section(
			'section_specs',
			array(
				'label' => esc_html__( 'Specifications', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'spec_label',
			array(
				'label'       => esc_html__( 'Label', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Specification', 'soma' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'spec_value',
			array(
				'label'       => esc_html__( 'Value', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'specs_list',
			array(
				'label'       => esc_html__( 'Specifications List', 'soma' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'spec_label' => esc_html__( 'Year', 'soma' ),
						'spec_value' => '2024',
					),
					array(
						'spec_label' => esc_html__( 'Location', 'soma' ),
						'spec_value' => 'Ciudad de México',
					),
					array(
						'spec_label' => esc_html__( 'GLA', 'soma' ),
						'spec_value' => '90,000 m²',
					),
				),
				'title_field' => '{{{ spec_label }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @return void
	 */
	protected function register_style_controls(): void {
		// Container Style.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => esc_html__( 'Container', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .soma-portfolio-technical-specs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => esc_html__( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-technical-specs' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Item Style.
		$this->start_controls_section(
			'section_style_items',
			array(
				'label' => esc_html__( 'Items', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'item_spacing',
			array(
				'label'      => esc_html__( 'Spacing Between Items', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .spec-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .spec-item:not(:last-child)' => 'border-bottom: 1px solid {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_padding_bottom',
			array(
				'label'      => esc_html__( 'Padding Bottom', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .spec-item:not(:last-child)' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Label Style.
		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => esc_html__( 'Label', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .spec-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .spec-label',
			)
		);

		$this->end_controls_section();

		// Value Style.
		$this->start_controls_section(
			'section_style_value',
			array(
				'label' => esc_html__( 'Value', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .spec-value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'selector' => '{{WRAPPER}} .spec-value',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings   = $this->get_settings_for_display();
		$specs_list = $settings['specs_list'];

		if ( empty( $specs_list ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soma-portfolio-technical-specs soma-portfolio-technical-specs--empty">';
				echo '<p>' . esc_html__( 'Add specifications using the repeater control.', 'soma' ) . '</p>';
				echo '</div>';
			}
			return;
		}
		?>
		<div class="soma-portfolio-technical-specs">
			<?php foreach ( $specs_list as $spec ) : ?>
				<?php if ( ! empty( $spec['spec_label'] ) || ! empty( $spec['spec_value'] ) ) : ?>
					<div class="spec-item">
						<div class="spec-label"><?php echo esc_html( $spec['spec_label'] ); ?></div>
						<div class="spec-value"><?php echo esc_html( $spec['spec_value'] ); ?></div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
