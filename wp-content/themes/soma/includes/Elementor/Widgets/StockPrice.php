<?php
/**
 * Stock Price Elementor Widget
 *
 * Displays current stock price from cached stock data.
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.0.0
 */

namespace Soma\Elementor\Widgets;

use Soma\Elementor\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stock Price widget class
 *
 * Renders the current stock price with label.
 * Data is fetched from the 'stock_data' WordPress option.
 */
class StockPrice extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-stock-price';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Stock Price', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-price-list';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return array( 'soma-stock-price' );
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
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => __( 'Horizontal', 'soma' ),
					'vertical'   => __( 'Vertical', 'soma' ),
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'soma' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'soma' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'soma' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'soma' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .soma-stock-price' => 'justify-content: {{VALUE}}; align-items: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gap',
			array(
				'label'      => __( 'Gap', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'em' => array(
						'min' => 0,
						'max' => 3,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-stock-price' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style tab controls
	 */
	private function register_style_controls(): void {
		// Label Style Section.
		$this->start_controls_section(
			'section_label_style',
			array(
				'label' => __( 'Label', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .soma-stock-price__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Typography', 'soma' ),
				'selector' => '{{WRAPPER}} .soma-stock-price__label',
			)
		);

		$this->end_controls_section();

		// Price Style Section.
		$this->start_controls_section(
			'section_price_style',
			array(
				'label' => __( 'Price', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .soma-stock-price__value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'label'    => __( 'Typography', 'soma' ),
				'selector' => '{{WRAPPER}} .soma-stock-price__value',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		// Get stock data using theme helper function.
		$stock_data = soma_get_stock_data();

		// Default values if no data available.
		$price    = 0;
		$currency = 'MXN';

		if ( is_array( $stock_data ) ) {
			$price    = isset( $stock_data['price'] ) ? floatval( $stock_data['price'] ) : 0;
			$currency = isset( $stock_data['currency'] ) ? sanitize_text_field( (string) $stock_data['currency'] ) : 'MXN';
		}

		// Format price with currency symbol using shared helper.
		$formatted_price = soma_format_stock_price( $price, $currency );

		// Layout class.
		$layout_class = 'horizontal' === $settings['layout'] ? 'soma-stock-price--horizontal' : 'soma-stock-price--vertical';

		?>
		<div class="soma-stock-price <?php echo esc_attr( $layout_class ); ?>">
			<span class="soma-stock-price__label">
				<?php esc_html_e( 'Current Price', 'soma' ); ?>
			</span>
			<span class="soma-stock-price__value">
				<?php echo esc_html( $formatted_price ); ?>
			</span>
		</div>
		<?php
	}
}
