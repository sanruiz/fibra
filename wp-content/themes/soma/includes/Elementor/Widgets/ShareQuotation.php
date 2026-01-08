<?php
/**
 * ShareQuotation Elementor widget.
 *
 * Displays stock market quotation data including price, change,
 * percent change, volume, and date in a 3-column layout.
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
	exit; // Exit if accessed directly.
}

/**
 * ShareQuotation widget class.
 *
 * @since 3.1.13
 */
class ShareQuotation extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 3.1.13
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'soma-share-quotation';
	}

	/**
	 * Get widget title.
	 *
	 * @since 3.1.13
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'Share Quotation', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 3.1.13
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-price-table';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 3.1.13
	 * @return array<string> Widget categories.
	 */
	public function get_categories(): array {
		return array( 'soma' );
	}

	/**
	 * Get widget style dependencies.
	 *
	 * @since 3.1.13
	 * @return array<string> Style dependencies.
	 */
	public function get_style_depends(): array {
		return array( 'soma-share-quotation' );
	}

	/**
	 * Get widget script dependencies.
	 *
	 * @since 3.1.13
	 * @return array<string> Script dependencies.
	 */
	public function get_script_depends(): array {
		return array();
	}

	/**
	 * Register widget controls.
	 *
	 * @since 3.1.13
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @since 3.1.13
	 * @return void
	 */
	protected function register_content_controls(): void {
		// Content Section.
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'SOMA21',
				'placeholder' => esc_html__( 'Enter title', 'soma' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'symbol',
			array(
				'label'       => esc_html__( 'Subtitle', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Share Quotation', 'soma' ),
				'placeholder' => esc_html__( 'e.g., Share Quotation', 'soma' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		// Labels Section.
		$this->start_controls_section(
			'section_labels',
			array(
				'label' => esc_html__( 'Labels', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'price_label',
			array(
				'label'   => esc_html__( 'Price Label', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Actual Price', 'soma' ),
			)
		);

		$this->add_control(
			'change_label',
			array(
				'label'   => esc_html__( 'Change Label', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Change', 'soma' ),
			)
		);

		$this->add_control(
			'percent_label',
			array(
				'label'   => esc_html__( 'Percent Label', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '%',
			)
		);

		$this->add_control(
			'volume_label',
			array(
				'label'   => esc_html__( 'Volume Label', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Volume', 'soma' ),
			)
		);

		$this->add_control(
			'date_label',
			array(
				'label'   => esc_html__( 'Date Label', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Date', 'soma' ),
			)
		);

		$this->end_controls_section();

		// Display Options Section.
		$this->start_controls_section(
			'section_display',
			array(
				'label' => esc_html__( 'Display Options', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'dark_background',
			array(
				'label'        => esc_html__( 'Dark Background', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soma' ),
				'label_off'    => esc_html__( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'Enable for dark backgrounds (white text)', 'soma' ),
			)
		);

		$this->add_control(
			'show_download',
			array(
				'label'        => esc_html__( 'Show Download Link', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soma' ),
				'label_off'    => esc_html__( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'download_url',
			array(
				'label'       => esc_html__( 'Download URL', 'soma' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://example.com/file.pdf', 'soma' ),
				'default'     => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => false,
				),
				'condition'   => array(
					'show_download' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 3.1.13
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

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'top'    => '60',
					'right'  => '30',
					'bottom' => '60',
					'left'   => '30',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-share-quotation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Title Style Section.
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => esc_html__( 'Title', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .soma-share-quotation__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-share-quotation__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Symbol Style Section.
		$this->start_controls_section(
			'section_style_symbol',
			array(
				'label' => esc_html__( 'Symbol', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'symbol_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .soma-share-quotation__symbol',
			)
		);

		$this->add_control(
			'symbol_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation__symbol' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Price Style Section.
		$this->start_controls_section(
			'section_style_price',
			array(
				'label' => esc_html__( 'Price', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .soma-share-quotation__price-value',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation__price-value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Label Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .soma-share-quotation__label',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Change Indicators Style Section.
		$this->start_controls_section(
			'section_style_change',
			array(
				'label' => esc_html__( 'Change Indicators', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'positive_color',
			array(
				'label'     => esc_html__( 'Positive Change Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#22c55e',
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation__change--positive' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'negative_color',
			array(
				'label'     => esc_html__( 'Negative Change Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ef4444',
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation__change--negative' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Download Link Style Section.
		$this->start_controls_section(
			'section_style_download',
			array(
				'label'     => esc_html__( 'Download Link', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_download' => 'yes',
				),
			)
		);

		$this->add_control(
			'download_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .soma-share-quotation__download-icon svg' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'download_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 35,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-share-quotation__download-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get download arrow SVG icon.
	 *
	 * @since 3.1.13
	 * @return string SVG markup.
	 */
	protected function get_download_icon(): string {
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 35" fill="none">
			<path d="M18 6.5625V24.0625" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M9.5625 15.625L18 24.0625L26.4375 15.625" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M9.5625 28.4375H26.4375" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>';
	}

	/**
	 * Render widget output.
	 *
	 * @since 3.1.13
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		// Get stock data.
		$stock_data = soma_get_stock_data();

		if ( empty( $stock_data ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				echo '<div class="soma-share-quotation soma-share-quotation--no-data">';
				echo '<p>' . esc_html__( 'Stock data not available. Please configure stock data in Settings.', 'soma' ) . '</p>';
				echo '</div>';
			}
			return;
		}

		// Extract data with defaults.
		$price     = (float) ( $stock_data['price'] ?? 0 );
		$change    = (float) ( $stock_data['change'] ?? 0 );
		$percent   = (float) ( $stock_data['percent'] ?? 0 );
		$volume    = (int) ( $stock_data['volume'] ?? 0 );
		$timestamp = (int) ( $stock_data['timestamp'] ?? time() );
		$currency  = $stock_data['currency'] ?? 'MXN';

		// Determine change class.
		$change_class = $change >= 0 ? 'positive' : 'negative';

		// Background class.
		$bg_class = 'yes' === $settings['dark_background'] ? 'black-bg' : 'white-bg';

		// Get current language for data attribute.
		$lang = function_exists( 'wpm_get_language' ) ? wpm_get_language() : 'es';

		// Build wrapper classes.
		$wrapper_classes = array(
			'soma-share-quotation',
			'soma-share-quotation--' . $bg_class,
		);
		?>
		<section class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
			data-symbol="<?php echo esc_attr( $settings['symbol'] ); ?>"
			data-price="<?php echo esc_attr( soma_format_stock_price( $price, $currency ) ); ?>"
			data-change="<?php echo esc_attr( soma_format_stock_change( $change ) ); ?>"
			data-percent="<?php echo esc_attr( soma_format_stock_percent( $percent ) ); ?>"
			data-volume="<?php echo esc_attr( soma_format_stock_volume( $volume ) ); ?>"
			data-lang="<?php echo esc_attr( $lang ); ?>">

			<div class="soma-share-quotation__container">
				<!-- Column 1: Title and Download -->
				<div class="soma-share-quotation__column soma-share-quotation__column--info">
					<h2 class="soma-share-quotation__title">
						<?php echo esc_html( $settings['title'] ); ?>
					</h2>
					<span class="soma-share-quotation__symbol">
						<?php echo esc_html( $settings['symbol'] ); ?>
					</span>
					<?php if ( 'yes' === $settings['show_download'] && ! empty( $settings['download_url']['url'] ) ) : ?>
						<a href="<?php echo esc_url( $settings['download_url']['url'] ); ?>"
							class="soma-share-quotation__download"
							<?php echo ! empty( $settings['download_url']['is_external'] ) ? 'target="_blank"' : ''; ?>
							<?php echo ! empty( $settings['download_url']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>>
							<span class="soma-share-quotation__download-icon">
								<?php echo $this->get_download_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</a>
					<?php endif; ?>
				</div>

				<!-- Column 2: Price, Change/Percent, Date -->
				<div class="soma-share-quotation__column soma-share-quotation__column--price">
					<div class="soma-share-quotation__data-row">
						<span class="soma-share-quotation__label"><?php echo esc_html( $settings['price_label'] ); ?></span>
						<span class="soma-share-quotation__price-value"><?php echo esc_html( soma_format_stock_price_simple( $price, $currency ) ); ?></span>
					</div>
					<div class="soma-share-quotation__data-row soma-share-quotation__data-row--change-combined">
						<span class="soma-share-quotation__change soma-share-quotation__change--<?php echo esc_attr( $change_class ); ?>">
							<?php echo esc_html( soma_format_stock_change_combined( $change, $percent ) ); ?>
						</span>
					</div>
					<div class="soma-share-quotation__data-row">
						<span class="soma-share-quotation__date"><?php echo esc_html( soma_format_stock_datetime( $timestamp ) ); ?></span>
					</div>
				</div>

				<!-- Column 3: Volume -->
				<div class="soma-share-quotation__column soma-share-quotation__column--volume">
					<div class="soma-share-quotation__data-row">
						<span class="soma-share-quotation__label"><?php echo esc_html( $settings['volume_label'] ); ?></span>
						<span class="soma-share-quotation__volume-value"><?php echo esc_html( soma_format_stock_volume( $volume ) ); ?></span>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
