<?php
/**
 * Portfolio Elementor Widget
 *
 * Displays portfolio items with filters, list/grid views, and AJAX loading.
 * Replicates the functionality of the existing Portfolio partial.
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.1.8
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
 * Portfolio widget class
 *
 * Queries portfolio post type and displays them with:
 * - Category filters (All, In Operation, In Construction, etc.)
 * - List view (default) and Grid view toggle
 * - AJAX-powered infinite scroll / load more
 * - Lazy loading images with hover zoom effect
 */
class Portfolio extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-portfolio';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Portfolio', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-gallery-masonry';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array<int, string>
	 */
	public function get_style_depends(): array {
		return array( 'soma-portfolio' );
	}

	/**
	 * Get script dependencies
	 *
	 * @return array<int, string>
	 */
	public function get_script_depends(): array {
		return array( 'soma-portfolio' );
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
	 * Get portfolio taxonomy terms as options
	 *
	 * @return array<int|string, string>
	 */
	private function get_portfolio_categories(): array {
		$terms   = get_terms(
			array(
				'taxonomy'   => 'portfolio-taxonomy',
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
			'main_category',
			array(
				'label'       => __( 'Main Category', 'soma' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_portfolio_categories(),
				'label_block' => true,
				'description' => __( 'Select the main category to display. All portfolio items in this category will be shown.', 'soma' ),
			)
		);

		$this->add_control(
			'filter_categories',
			array(
				'label'       => __( 'Filter Categories', 'soma' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_portfolio_categories(),
				'multiple'    => true,
				'label_block' => true,
				'description' => __( 'Select categories to show as filter buttons. Leave empty to hide filters.', 'soma' ),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'       => __( 'Initial Posts', 'soma' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 10,
				'min'         => 1,
				'max'         => 50,
				'description' => __( 'Number of posts to load initially and on each load more.', 'soma' ),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'year',
				'options' => array(
					'year'       => __( 'Year', 'soma' ),
					'menu_order' => __( 'Menu Order', 'soma' ),
					'date'       => __( 'Date', 'soma' ),
					'title'      => __( 'Title', 'soma' ),
					'modified'   => __( 'Modified Date', 'soma' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Descending', 'soma' ),
					'ASC'  => __( 'Ascending', 'soma' ),
				),
			)
		);

		$this->end_controls_section();

		// Display section.
		$this->start_controls_section(
			'section_display',
			array(
				'label' => __( 'Display', 'soma' ),
			)
		);

		$this->add_control(
			'default_view',
			array(
				'label'   => __( 'Default View', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'list',
				'options' => array(
					'list' => __( 'List View', 'soma' ),
					'grid' => __( 'Grid View', 'soma' ),
				),
			)
		);

		$this->add_control(
			'show_filters',
			array(
				'label'        => __( 'Show Filters', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_view_toggle',
			array(
				'label'        => __( 'Show View Toggle', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_year',
			array(
				'label'        => __( 'Show Year', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_city',
			array(
				'label'        => __( 'Show City', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'style_variant',
			array(
				'label'   => __( 'Style Variant', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fibrasoma',
				'options' => array(
					'fibrasoma' => __( 'FibraSOMA (Dark)', 'soma' ),
					'soma'      => __( 'SOMA (Light)', 'soma' ),
				),
			)
		);

		$this->add_control(
			'all_filter_text',
			array(
				'label'       => __( '"All" Filter Text', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'All', 'soma' ),
				'placeholder' => __( 'All', 'soma' ),
				'condition'   => array(
					'show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'list_view_text',
			array(
				'label'     => __( 'List View Text', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'List View', 'soma' ),
				'condition' => array(
					'show_view_toggle' => 'yes',
				),
			)
		);

		$this->add_control(
			'grid_view_text',
			array(
				'label'     => __( 'Grid View Text', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Grid View', 'soma' ),
				'condition' => array(
					'show_view_toggle' => 'yes',
				),
			)
		);

		$this->add_control(
			'loading_text',
			array(
				'label'   => __( 'Loading Text', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Loading more', 'soma' ),
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
		// Container styles.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => __( 'Container', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .soma-portfolio-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => __( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-widget' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Filter styles.
		$this->start_controls_section(
			'section_style_filters',
			array(
				'label'     => __( 'Filters', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_filters' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filters_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .portfolio-filters .filter',
			)
		);

		$this->add_control(
			'filter_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .portfolio-filters .filter' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_active_color',
			array(
				'label'     => __( 'Active Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .portfolio-filters .filter.active, {{WRAPPER}} .portfolio-filters .filter:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_spacing',
			array(
				'label'      => __( 'Spacing', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .portfolio-filters .filters-list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'filters_border_color',
			array(
				'label'     => __( 'Border Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .portfolio-filters' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Year styles.
		$this->start_controls_section(
			'section_style_year',
			array(
				'label'     => __( 'Year', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_year' => 'yes',
				),
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
				'selector' => '{{WRAPPER}} .project .year',
			)
		);

		$this->add_control(
			'year_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .project .year' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Title styles.
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .project .title h3',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .project .title h3' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => __( 'Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .project:hover .title h3' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// City styles.
		$this->start_controls_section(
			'section_style_city',
			array(
				'label'     => __( 'City', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_city' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'city_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .project .city',
			)
		);

		$this->add_control(
			'city_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .project .city' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Image styles.
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Image', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_zoom_scale',
			array(
				'label'      => __( 'Hover Zoom Scale', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '' ),
				'range'      => array(
					'' => array(
						'min'  => 1,
						'max'  => 1.5,
						'step' => 0.05,
					),
				),
				'default'    => array(
					'size' => 1.1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .project .image img:hover' => 'transform: scale({{SIZE}});',
				),
			)
		);

		$this->add_control(
			'image_transition',
			array(
				'label'      => __( 'Transition Duration', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's' ),
				'range'      => array(
					's' => array(
						'min'  => 0.1,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0.5,
					'unit' => 's',
				),
				'selectors'  => array(
					'{{WRAPPER}} .project .image img' => 'transition: transform {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// View toggle styles.
		$this->start_controls_section(
			'section_style_view_toggle',
			array(
				'label'     => __( 'View Toggle', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_view_toggle' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'view_toggle_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .view-mode span',
			)
		);

		$this->add_control(
			'view_toggle_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .view-mode > div' => 'color: {{VALUE}};',
					'{{WRAPPER}} .view-mode svg g, {{WRAPPER}} .view-mode svg path' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$main_category     = $settings['main_category'] ?? '';
		$filter_categories = $settings['filter_categories'] ?? array();
		$posts_per_page    = (int) ( $settings['posts_per_page'] ?? 10 );
		$orderby           = $settings['orderby'] ?? 'year';
		$order             = $settings['order'] ?? 'DESC';
		$default_view      = $settings['default_view'] ?? 'list';
		$show_filters      = 'yes' === $settings['show_filters'];
		$show_view_toggle  = 'yes' === $settings['show_view_toggle'];
		$show_year         = 'yes' === $settings['show_year'];
		$show_city         = 'yes' === $settings['show_city'];
		$style_variant     = $settings['style_variant'] ?? 'fibrasoma';
		$all_filter_text   = $settings['all_filter_text'] ?? __( 'All', 'soma' );
		$list_view_text    = $settings['list_view_text'] ?? __( 'List View', 'soma' );
		$grid_view_text    = $settings['grid_view_text'] ?? __( 'Grid View', 'soma' );
		$loading_text      = $settings['loading_text'] ?? __( 'Loading more', 'soma' );

		// Get current language.
		$lang = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'en';

		// Get pre-filter from URL.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter, no state change.
		$pre_filter = isset( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '';

		// Build widget classes.
		$widget_classes = array(
			'soma-portfolio-widget',
			'style-' . $style_variant,
		);
		if ( ! $show_year ) {
			$widget_classes[] = 'hide-year';
		}
		if ( ! $show_city ) {
			$widget_classes[] = 'hide-city';
		}

		// Encode categories for data attribute.
		$categories_data = ! empty( $main_category ) ? $main_category : '';
		?>
		<section 
			class="<?php echo esc_attr( implode( ' ', $widget_classes ) ); ?>"
			data-main-category="<?php echo esc_attr( $categories_data ); ?>"
			data-posts-per-page="<?php echo esc_attr( $posts_per_page ); ?>"
			data-lang="<?php echo esc_attr( $lang ); ?>"
			data-orderby="<?php echo esc_attr( $orderby ); ?>"
			data-order="<?php echo esc_attr( $order ); ?>"
			data-show-year="<?php echo esc_attr( $show_year ? 'true' : 'false' ); ?>"
			data-show-city="<?php echo esc_attr( $show_city ? 'true' : 'false' ); ?>"
			data-pre-filter="<?php echo esc_attr( $pre_filter ); ?>"
			data-all-text="<?php echo esc_attr( $all_filter_text ); ?>"
			data-show-filters="<?php echo esc_attr( $show_filters ? 'true' : 'false' ); ?>"
		>
			<div class="container">
				<?php if ( $show_filters || $show_view_toggle ) : ?>
				<!-- Desktop Filters -->
				<div class="portfolio-filters desk">
					<?php if ( $show_filters ) : ?>
					<div class="filters-list">
						<!-- Filters rendered dynamically by JS -->
					</div>
					<?php endif; ?>

					<?php if ( $show_view_toggle ) : ?>
					<div class="view-mode">
						<div class="list <?php echo 'list' === $default_view ? 'current-view' : ''; ?>">
							<svg width="20px" height="21px" viewBox="0 0 20 21" version="1.1" xmlns="http://www.w3.org/2000/svg">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<g transform="translate(-1244.000000, -2068.000000)">
										<g transform="translate(79.000000, 2062.031250)">
											<g transform="translate(1169.000000, 0.000000)" stroke="currentColor" stroke-linecap="square" stroke-width="2">
												<line x1="5.33333333" y1="16.5" x2="18.6666667" y2="16.5"></line>
												<line x1="5.33333333" y1="22.5" x2="18.6666667" y2="22.5"></line>
												<line x1="0" y1="17" x2="1" y2="17"></line>
												<line x1="5.33333333" y1="10.5" x2="18.6666667" y2="10.5"></line>
												<line x1="0" y1="11" x2="1" y2="11"></line>
												<line x1="0" y1="23" x2="1" y2="23"></line>
											</g>
										</g>
									</g>
								</g>
							</svg>
							<span><?php echo esc_html( $list_view_text ); ?></span>
						</div>
						<div class="grid <?php echo 'grid' === $default_view ? 'current-view' : ''; ?>">
							<svg width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<g transform="translate(-1244.000000, -2068.000000)" stroke="currentColor" stroke-width="1.28">
										<g transform="translate(79.000000, 2062.031250)">
											<g transform="translate(1165.000000, 0.000000)">
												<g transform="translate(0.000001, 6.400001)">
													<rect x="0.64" y="0.64" width="7.36" height="7.36"></rect>
													<rect x="10.64" y="0.64" width="7.36" height="7.36"></rect>
													<rect x="0.64" y="10.64" width="7.36" height="7.36"></rect>
													<rect x="10.64" y="10.64" width="7.36" height="7.36"></rect>
												</g>
											</g>
										</g>
									</g>
								</g>
							</svg>
							<span><?php echo esc_html( $grid_view_text ); ?></span>
						</div>
					</div>
					<?php endif; ?>
				</div>

				<!-- Mobile Filters -->
				<div class="portfolio-filters movil">
					<?php if ( $show_filters ) : ?>
					<div class="ViewAll">
						<div style="width: 100%;"><?php esc_html_e( 'View All', 'soma' ); ?></div>
						<svg class="ViewAllsvg" style="transition: 0.5s;" xmlns="http://www.w3.org/2000/svg" width="19.172" height="19.172" viewBox="0 0 19.172 19.172">
							<g transform="translate(9.548 1.77) rotate(45)">
								<path d="M0,0,10.729,10.729" transform="translate(0.19 0.136)" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"/>
								<path d="M0,10.729,10.729,0" transform="translate(0.19 0.136)" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"/>
							</g>
						</svg>
					</div>
					<div class="IteamView" style="display: none;">
						<!-- Filters rendered dynamically by JS -->
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Projects Container -->
				<div class="projects <?php echo esc_attr( $default_view ); ?>-view">
					<!-- AJAX Render -->
				</div>

				<!-- Loader -->
				<div class="loader-container">
					<span class="loading"><?php echo esc_html( $loading_text ); ?></span>
				</div>
			</div>
		</section>
		<?php
	}
}

