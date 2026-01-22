<?php
/**
 * Portfolio City Type Elementor Widget.
 *
 * Displays the city (from ACF) and project type (from taxonomy) for portfolio items.
 * Output format: "Ciudad de México. Usos Mixtos"
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.24
 */

declare(strict_types=1);

namespace Soma\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portfolio City Type Widget.
 *
 * Displays city and project type in a single line format.
 *
 * @since 3.1.24
 */
class PortfolioCityType extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'soma-portfolio-city-type';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'Portfolio City & Type', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-map-pin';
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
		return array( 'portfolio', 'city', 'type', 'project', 'location', 'soma' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style handles.
	 */
	public function get_style_depends(): array {
		return array( 'soma-portfolio-city-type' );
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
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'       => esc_html__( 'Separator', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '.',
				'description' => esc_html__( 'Character(s) between city and project type.', 'soma' ),
			)
		);

		$this->add_control(
			'show_city',
			array(
				'label'        => esc_html__( 'Show City', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			'fallback_city',
			array(
				'label'       => esc_html__( 'Fallback City', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Text to display if city is empty.', 'soma' ),
				'condition'   => array(
					'show_city' => 'yes',
				),
			)
		);

		$this->add_control(
			'fallback_project_type',
			array(
				'label'       => esc_html__( 'Fallback Project Type', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Text to display if project type is empty.', 'soma' ),
				'condition'   => array(
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
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Typography', 'soma' ),
				'selector' => '{{WRAPPER}} .soma-portfolio-city-type__text',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-city-type__text' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => esc_html__( 'Separator Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-city-type__separator' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'soma' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'soma' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'soma' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'soma' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-city-type' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get city from ACF field.
	 *
	 * @return string City name or empty string.
	 */
	private function get_city(): string {
		if ( ! function_exists( 'get_field' ) ) {
			return '';
		}

		$post_id      = get_the_ID();
		$project_info = get_field( 'project_info', $post_id );

		if ( is_array( $project_info ) && ! empty( $project_info['city'] ) ) {
			return (string) $project_info['city'];
		}

		return '';
	}

	/**
	 * Get project type from taxonomy.
	 *
	 * @return string Project type name or empty string.
	 */
	private function get_project_type(): string {
		$post_id = get_the_ID();
		$terms   = get_the_terms( $post_id, 'project-type' );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return '';
		}

		// Return the first term name.
		return $terms[0]->name;
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$show_city         = 'yes' === $settings['show_city'];
		$show_project_type = 'yes' === $settings['show_project_type'];
		$separator         = $settings['separator'];
		$fallback_city     = $settings['fallback_city'] ?? '';
		$fallback_type     = $settings['fallback_project_type'] ?? '';

		// Get values.
		$city         = $show_city ? $this->get_city() : '';
		$project_type = $show_project_type ? $this->get_project_type() : '';

		// Apply fallbacks.
		if ( $show_city && empty( $city ) && ! empty( $fallback_city ) ) {
			$city = $fallback_city;
		}

		if ( $show_project_type && empty( $project_type ) && ! empty( $fallback_type ) ) {
			$project_type = $fallback_type;
		}

		// Build output parts.
		$parts = array();

		if ( $show_city && ! empty( $city ) ) {
			$parts[] = '<span class="soma-portfolio-city-type__city">' . esc_html( $city ) . '</span>';
		}

		if ( $show_project_type && ! empty( $project_type ) ) {
			$parts[] = '<span class="soma-portfolio-city-type__type">' . esc_html( $project_type ) . '</span>';
		}

		// Nothing to display.
		if ( empty( $parts ) ) {
			// Show placeholder in editor.
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soma-portfolio-city-type">';
				echo '<span class="soma-portfolio-city-type__text">';
				echo esc_html__( 'City & Project Type will appear here', 'soma' );
				echo '</span>';
				echo '</div>';
			}
			return;
		}

		// Build separator HTML.
		$separator_html = '';
		if ( count( $parts ) > 1 && ! empty( $separator ) ) {
			$separator_html = '<span class="soma-portfolio-city-type__separator">' . esc_html( $separator ) . ' </span>';
		}

		// Render output.
		echo '<div class="soma-portfolio-city-type">';
		echo '<span class="soma-portfolio-city-type__text">';
		echo wp_kses_post( implode( $separator_html, $parts ) );
		echo '</span>';
		echo '</div>';
	}
}
