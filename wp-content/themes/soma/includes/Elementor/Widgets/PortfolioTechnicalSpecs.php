<?php
/**
 * PortfolioTechnicalSpecs Elementor Widget.
 *
 * Display project technical specifications from ACF fields
 * (year, GLA, occupancy rate, designed by, project type).
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.17
 */

declare(strict_types=1);

namespace Soma\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PortfolioTechnicalSpecs widget for displaying project specifications.
 *
 * Displays project data from ACF fields in a vertical list format
 * suitable for the project detail page sidebar.
 *
 * @since 3.1.17
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
		return esc_html__( 'SOMA Portfolio Technical Specs', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-info-box';
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
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends(): array {
		return array( 'soma-portfolio-technical-specs' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends(): array {
		return array();
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
		// Data Source Section.
		$this->start_controls_section(
			'section_data_source',
			array(
				'label' => esc_html__( 'Data Source', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'data_source',
			array(
				'label'   => esc_html__( 'Source', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => esc_html__( 'Auto-detect from URL', 'soma' ),
					'manual' => esc_html__( 'Select Portfolio Item', 'soma' ),
				),
			)
		);

		$this->add_control(
			'portfolio_id',
			array(
				'label'     => esc_html__( 'Portfolio Item', 'soma' ),
				'type'      => Controls_Manager::SELECT2,
				'options'   => $this->get_portfolio_options(),
				'default'   => '',
				'condition' => array(
					'data_source' => 'manual',
				),
			)
		);

		$this->end_controls_section();

		// Fields to Display Section.
		$this->start_controls_section(
			'section_fields',
			array(
				'label' => esc_html__( 'Fields to Display', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_year',
			array(
				'label'        => esc_html__( 'Show Year', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'year_label',
			array(
				'label'     => esc_html__( 'Year Label', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Year', 'soma' ),
				'condition' => array(
					'show_year' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_designed_by',
			array(
				'label'        => esc_html__( 'Show Designed By', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'designed_by_label',
			array(
				'label'     => esc_html__( 'Designed By Label', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Designed by', 'soma' ),
				'condition' => array(
					'show_designed_by' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_gla',
			array(
				'label'        => esc_html__( 'Show GLA', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'gla_label',
			array(
				'label'     => esc_html__( 'GLA Label', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'GLA', 'soma' ),
				'condition' => array(
					'show_gla' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_occupancy',
			array(
				'label'        => esc_html__( 'Show Occupancy Rate', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'occupancy_label',
			array(
				'label'     => esc_html__( 'Occupancy Label', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Occupancy Rate', 'soma' ),
				'condition' => array(
					'show_occupancy' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_project_type',
			array(
				'label'        => esc_html__( 'Show Project Type', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'project_type_label',
			array(
				'label'     => esc_html__( 'Project Type Label', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Type', 'soma' ),
				'condition' => array(
					'show_project_type' => 'yes',
				),
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
		// Container Style Section.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => esc_html__( 'Container', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-technical-specs' => 'background-color: {{VALUE}};',
				),
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
				'default'    => array(
					'top'    => '30',
					'right'  => '30',
					'bottom' => '30',
					'left'   => '30',
					'unit'   => 'px',
				),
			)
		);

		$this->end_controls_section();

		// Label Style Section.
		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => esc_html__( 'Labels', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
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
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
			)
		);

		$this->add_responsive_control(
			'label_margin',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .spec-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Value Style Section.
		$this->start_controls_section(
			'section_style_value',
			array(
				'label' => esc_html__( 'Values', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
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
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->end_controls_section();

		// Spec Item Style Section.
		$this->start_controls_section(
			'section_style_item',
			array(
				'label' => esc_html__( 'Spec Items', 'soma' ),
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
					'size' => 20,
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
					'{{WRAPPER}} .spec-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
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
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .spec-item:not(:last-child)' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get portfolio options for select control.
	 *
	 * @return array Array of portfolio_id => title.
	 */
	protected function get_portfolio_options(): array {
		$options = array( '' => esc_html__( 'Auto-detect', 'soma' ) );

		$portfolios = get_posts(
			array(
				'post_type'      => 'portfolio',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		foreach ( $portfolios as $portfolio ) {
			$options[ $portfolio->ID ] = get_the_title( $portfolio->ID );
		}

		return $options;
	}

	/**
	 * Get portfolio ID based on settings.
	 *
	 * @return int|null Portfolio ID or null if not found.
	 */
	protected function get_portfolio_id(): ?int {
		$settings = $this->get_settings_for_display();

		if ( 'manual' === $settings['data_source'] && ! empty( $settings['portfolio_id'] ) ) {
			return (int) $settings['portfolio_id'];
		}

		// Auto-detect from URL.
		global $post;
		if ( $post && 'portfolio' === $post->post_type ) {
			return $post->ID;
		}

		return null;
	}

	/**
	 * Get technical specs from ACF fields.
	 *
	 * @param int $post_id Portfolio post ID.
	 * @return array Array of spec data.
	 */
	protected function get_technical_specs( int $post_id ): array {
		$specs = array();

		if ( ! function_exists( 'get_field' ) ) {
			return $specs;
		}

		// Year - from project_info group or direct field.
		$project_info = get_field( 'project_info', $post_id );
		if ( $project_info && ! empty( $project_info['year'] ) ) {
			$specs['year'] = $project_info['year'];
		}

		// Designed By.
		$designed_by = get_field( 'designed_by', $post_id );
		if ( $designed_by ) {
			$specs['designed_by'] = $designed_by;
		}

		// GLA (Gross Leasable Area).
		$gla = get_field( 'gla', $post_id );
		if ( $gla ) {
			$specs['gla'] = $gla;
		}

		// Occupancy Rate.
		$occupancy = get_field( 'occupancy_rate', $post_id );
		if ( $occupancy ) {
			$specs['occupancy'] = $occupancy;
		}

		// Project Type.
		$project_type = get_field( 'project_type', $post_id );
		if ( $project_type ) {
			$specs['project_type'] = $project_type;
		}

		return $specs;
	}

	/**
	 * Render the widget output.
	 *
	 * @return void
	 */
	protected function render(): void {
		$portfolio_id = $this->get_portfolio_id();

		if ( ! $portfolio_id ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soma-portfolio-technical-specs soma-portfolio-technical-specs--empty">';
				echo '<p>' . esc_html__( 'No portfolio item found. Please select a portfolio item or use this widget on a portfolio single page.', 'soma' ) . '</p>';
				echo '</div>';
			}
			return;
		}

		$settings = $this->get_settings_for_display();
		$specs    = $this->get_technical_specs( $portfolio_id );

		// Build specs array for display.
		$display_specs = array();

		if ( 'yes' === $settings['show_year'] && ! empty( $specs['year'] ) ) {
			$display_specs[] = array(
				'label' => $settings['year_label'],
				'value' => $specs['year'],
			);
		}

		if ( 'yes' === $settings['show_designed_by'] && ! empty( $specs['designed_by'] ) ) {
			$display_specs[] = array(
				'label' => $settings['designed_by_label'],
				'value' => $specs['designed_by'],
			);
		}

		if ( 'yes' === $settings['show_gla'] && ! empty( $specs['gla'] ) ) {
			$display_specs[] = array(
				'label' => $settings['gla_label'],
				'value' => $specs['gla'],
			);
		}

		if ( 'yes' === $settings['show_occupancy'] && ! empty( $specs['occupancy'] ) ) {
			$display_specs[] = array(
				'label' => $settings['occupancy_label'],
				'value' => $specs['occupancy'],
			);
		}

		if ( 'yes' === $settings['show_project_type'] && ! empty( $specs['project_type'] ) ) {
			$display_specs[] = array(
				'label' => $settings['project_type_label'],
				'value' => $specs['project_type'],
			);
		}

		if ( empty( $display_specs ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soma-portfolio-technical-specs soma-portfolio-technical-specs--empty">';
				echo '<p>' . esc_html__( 'No technical specifications found for this portfolio item.', 'soma' ) . '</p>';
				echo '</div>';
			}
			return;
		}
		?>
		<div class="soma-portfolio-technical-specs">
			<?php foreach ( $display_specs as $spec ) : ?>
				<div class="spec-item">
					<div class="spec-label"><?php echo esc_html( $spec['label'] ); ?></div>
					<div class="spec-value"><?php echo esc_html( $spec['value'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
