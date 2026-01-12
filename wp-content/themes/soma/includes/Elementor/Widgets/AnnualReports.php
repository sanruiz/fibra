<?php
/**
 * Annual Reports Elementor Widget
 *
 * Displays annual reports/documents with year filtering in a 2-column layout.
 * Uses AJAX to load content from the documents REST API endpoint.
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.13
 */

namespace Soma\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Annual Reports widget class
 *
 * Creates a 2-column layout with year filter sidebar and documents display area.
 * Content is loaded via AJAX from /wp-json/soma/documents endpoint.
 */
class AnnualReports extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-annual-reports';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Reports', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-document-file';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array<int, string>
	 */
	public function get_style_depends(): array {
		return array( 'soma-annual-reports' );
	}

	/**
	 * Get script dependencies
	 *
	 * @return array<int, string>
	 */
	public function get_script_depends(): array {
		return array( 'soma-annual-reports' );
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
	 * Get documents taxonomy terms as options
	 *
	 * @return array<int|string, string>
	 */
	private function get_document_categories(): array {
		$terms   = get_terms(
			array(
				'taxonomy'   => 'documents-taxonomy',
				'hide_empty' => false,
			)
		);
		$options = array();

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

	/**
	 * Register content controls
	 *
	 * @return void
	 */
	private function register_content_controls(): void {
		// Query section.
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'soma' ),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => __( 'Category', 'soma' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_document_categories(),
				'label_block' => true,
				'description' => __( 'Select a category to filter annual reports.', 'soma' ),
			)
		);

		$this->add_control(
			'latest_year_preselect',
			array(
				'label'        => __( 'Preselect Latest Year', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Automatically select the most recent year when loading.', 'soma' ),
			)
		);

		$this->end_controls_section();

		// Layout section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'soma' ),
			)
		);

		$this->add_control(
			'style_variant',
			array(
				'label'   => __( 'Style Variant', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'full-width',
				'options' => array(
					'full-width'    => __( 'Full Width', 'soma' ),
					'three-columns' => __( 'Three Columns', 'soma' ),
				),
			)
		);

		$this->end_controls_section();

		// Labels section.
		$this->start_controls_section(
			'section_labels',
			array(
				'label' => __( 'Labels', 'soma' ),
			)
		);

		$this->add_control(
			'filter_title',
			array(
				'label'       => __( 'Filter Title (Mobile)', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Filter by Year', 'soma' ),
				'placeholder' => __( 'Filter by Year', 'soma' ),
			)
		);

		$this->add_control(
			'see_all_text',
			array(
				'label'       => __( 'See All Text', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'See All', 'soma' ),
				'placeholder' => __( 'See All', 'soma' ),
			)
		);

		$this->add_control(
			'download_text',
			array(
				'label'       => __( 'Download Link Text', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Download', 'soma' ),
				'placeholder' => __( 'Download', 'soma' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 *
	 * @return void
	 */
	private function register_style_controls(): void {
		// Image styles.
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Image', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_height_full_width',
			array(
				'label'      => __( 'Image Height', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 600,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 307,
				),
				'selectors'  => array(
					'{{WRAPPER}} .full-width .document .image' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'style_variant' => 'full-width',
				),
			)
		);

		$this->add_responsive_control(
			'image_height_three_columns',
			array(
				'label'      => __( 'Image Height', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 600,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 348,
				),
				'selectors'  => array(
					'{{WRAPPER}} .three-columns .document-list:not(.filtered) .document .image' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'style_variant' => 'three-columns',
				),
			)
		);

		$this->end_controls_section();

		// Year list styles.
		$this->start_controls_section(
			'section_style_year_list',
			array(
				'label' => __( 'Year Filter', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'year_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .year-list .years h3',
			)
		);

		$this->add_control(
			'year_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .year-list .years h3' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'year_active_color',
			array(
				'label'     => __( 'Active Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .year-list .years h3.active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'year_hover_color',
			array(
				'label'     => __( 'Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .year-list .years h3:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Document card styles.
		$this->start_controls_section(
			'section_style_document',
			array(
				'label' => __( 'Document Card', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'document_title_typography',
				'label'    => __( 'Title Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .document .text h3',
			)
		);

		$this->add_control(
			'document_title_color',
			array(
				'label'     => __( 'Title Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .document .text h3' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'document_description_typography',
				'label'    => __( 'Description Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .document .text p',
			)
		);

		$this->add_control(
			'document_description_color',
			array(
				'label'     => __( 'Description Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .document .text p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'document_link_color',
			array(
				'label'     => __( 'Link Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .document .text a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'document_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .document .text a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * Outputs HTML structure that works with the annualReports.js handler.
	 * The JS fetches data from /wp-json/soma/documents and populates the DOM.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$category      = ! empty( $settings['category'] ) ? (int) $settings['category'] : 0;
		$latest_year   = 'yes' === $settings['latest_year_preselect'] ? '1' : '0';
		$style_variant = $settings['style_variant'] ?? 'full-width';
		$filter_title  = $settings['filter_title'] ?? __( 'Filter by Year', 'soma' );
		$see_all_text  = $settings['see_all_text'] ?? __( 'See All', 'soma' );
		$download_text = $settings['download_text'] ?? __( 'Download', 'soma' );
		$current_lang  = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'en';

		// Build widget classes.
		$widget_classes = array(
			'soma-annual-reports-widget',
			'annualreports-partial-5d3457', // Keep for JS handler compatibility.
			esc_attr( $style_variant ),
		);

		// Don't render if no category selected.
		if ( 0 === $category ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soma-annual-reports-widget soma-widget-placeholder">';
				echo '<p>' . esc_html__( 'Please select a document category to display annual reports.', 'soma' ) . '</p>';
				echo '</div>';
			}
			return;
		}
		?>
		<section class="<?php echo esc_attr( implode( ' ', $widget_classes ) ); ?>"
			data-last-year="<?php echo esc_attr( $latest_year ); ?>"
			data-category="<?php echo esc_attr( (string) $category ); ?>"
			data-lang="<?php echo esc_attr( $current_lang ); ?>"
			data-download-text="<?php echo esc_attr( $download_text ); ?>"
			data-endpoint="<?php echo esc_url( rest_url( 'soma/documents' ) ); ?>">
			<div class="loading">
				<span class="spinner"></span>
			</div>
			<div class="content">
				<div class="year-list">
					<div class="mobile-title">
						<?php echo esc_html( $filter_title ); ?>
						<span></span>
					</div>
					<div class="years">
						<!-- AJAX: Year filter buttons rendered by JS -->
					</div>
					<div class="all">
						<a><?php echo esc_html( $see_all_text ); ?></a>
					</div>
				</div>
				<div class="documents">
					<div class="document-list">
						<!-- AJAX: Documents rendered by JS -->
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
