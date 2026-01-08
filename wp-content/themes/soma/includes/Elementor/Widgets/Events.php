<?php
/**
 * Events Elementor Widget
 *
 * Displays events from the events custom post type with AJAX filtering.
 * Replicates the functionality from partials/Events.php as an Elementor widget.
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.13
 */

namespace Soma\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Events Widget Class
 *
 * Displays upcoming and past events with month/type filtering.
 * Uses AJAX to load events from /wp-json/soma/events endpoint.
 *
 * Features:
 * - Month filter sidebar (collapsible on mobile)
 * - Event cards with date, description, and download links
 * - Responsive 3-column grid layout
 * - Supports both English and Spanish via WP Multilang
 *
 * @since 3.1.13
 */
class Events extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string Widget name used internally
	 */
	public function get_name(): string {
		return 'soma-events';
	}

	/**
	 * Get widget title
	 *
	 * @return string Widget title displayed in Elementor panel
	 */
	public function get_title(): string {
		return __( 'Events', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string Elementor icon class
	 */
	public function get_icon(): string {
		return 'eicon-calendar';
	}

	/**
	 * Get widget keywords
	 *
	 * @return array<int, string> Search keywords
	 */
	public function get_keywords(): array {
		return array( 'events', 'calendar', 'schedule', 'upcoming', 'soma' );
	}

	/**
	 * Get style dependencies
	 *
	 * Widget uses styles from main.bundle.css (sass/partials/_Events.scss)
	 * No custom widget CSS needed.
	 *
	 * @return array<int, string> Style handles
	 */
	public function get_style_depends(): array {
		return array();
	}

	/**
	 * Get script dependencies
	 *
	 * Widget uses eventsHandler from main.bundle.js (js/components/events.js)
	 * Auto-initialized via .events-partial-e5e1bb class detection in main.js
	 *
	 * @return array<int, string> Script handles
	 */
	public function get_script_depends(): array {
		return array();
	}

	/**
	 * Register widget controls
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_labels_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls
	 *
	 * @return void
	 */
	private function register_content_controls(): void {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => __( 'Ascending', 'soma' ),
					'DESC' => __( 'Descending', 'soma' ),
				),
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => __( 'Order By', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom_date',
				'options' => array(
					'custom_date' => __( 'Event Date', 'soma' ),
					'date'        => __( 'Published Date', 'soma' ),
					'title'       => __( 'Title', 'soma' ),
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

		$this->end_controls_section();
	}

	/**
	 * Register labels controls
	 *
	 * @return void
	 */
	private function register_labels_controls(): void {
		$this->start_controls_section(
			'section_labels',
			array(
				'label' => __( 'Labels', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'filter_title',
			array(
				'label'     => __( 'Filter Title (Mobile)', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Filter by Month', 'soma' ),
				'condition' => array(
					'show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'see_all_text',
			array(
				'label'     => __( 'See All Text', 'soma' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'See All', 'soma' ),
				'condition' => array(
					'show_filters' => 'yes',
				),
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
					'{{WRAPPER}} .soma-events-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => __( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-events-widget' => 'background-color: {{VALUE}};',
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
				'name'     => 'filter_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .filters li a',
			)
		);

		$this->add_control(
			'filter_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .filters li a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .filters li a.active, {{WRAPPER}} .filters li a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Event card styles.
		$this->start_controls_section(
			'section_style_event_card',
			array(
				'label' => __( 'Event Card', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'event_label_color',
			array(
				'label'     => __( 'Label Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .event .label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_label_typography',
				'label'    => __( 'Label Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .event .label',
			)
		);

		$this->add_control(
			'event_date_color',
			array(
				'label'     => __( 'Date Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .event h3' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_date_typography',
				'label'    => __( 'Date Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .event h3',
			)
		);

		$this->add_control(
			'event_description_color',
			array(
				'label'     => __( 'Description Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .event p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_description_typography',
				'label'    => __( 'Description Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .event p',
			)
		);

		$this->add_control(
			'event_link_color',
			array(
				'label'     => __( 'Link Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .event a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'event_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .event a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * Outputs HTML structure matching the original Events partial so that the
	 * existing eventsHandler from js/components/events.js works without modification.
	 * The JS fetches data from /wp-json/soma/events and populates the DOM.
	 *
	 * Structure matches partials/Events.php and sass/partials/_Events.scss:
	 * - .events-partial-e5e1bb > .container > .content
	 * - .content > .filters + .events (flex layout)
	 * - .filters > .mobile-title + .list > .item (not ul/li)
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$show_filters = 'yes' === ( $settings['show_filters'] ?? 'yes' );
		$filter_title = $settings['filter_title'] ?? __( 'Filter by Month', 'soma' );
		$see_all_text = $settings['see_all_text'] ?? __( 'See All', 'soma' );
		$current_lang = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'en';
		?>
		<section class="events-partial-e5e1bb" data-lang="<?php echo esc_attr( $current_lang ); ?>">
				<div class="content">
					<?php if ( $show_filters ) : ?>
					<div class="filters">
						<div class="mobile-title">
							<?php echo esc_html( $filter_title ); ?>
							<span></span>
						</div>
						<div class="list">
							<div class="item active" data-filter="all">
								<?php echo esc_html( $see_all_text ); ?>
							</div>
							<!-- JS adds more filter items dynamically -->
						</div>
					</div>
					<?php endif; ?>

					<div class="events">
						<div class="event-list">
							<!-- JS populates event cards dynamically -->
						</div>
					</div>
				</div>
		</section>
		<?php
	}
}
