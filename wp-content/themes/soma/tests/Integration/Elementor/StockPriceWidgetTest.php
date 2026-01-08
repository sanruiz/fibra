<?php
/**
 * StockPrice Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\StockPrice;

/**
 * Test StockPrice widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class StockPriceWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var StockPrice
	 */
	private StockPrice $widget;

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}

		$this->widget = new StockPrice();
	}

	/**
	 * Test widget extends correct base class
	 */
	public function test_extends_widget_base(): void {
		$this->assertInstanceOf(
			\Soma\Elementor\Base\WidgetBase::class,
			$this->widget
		);
	}

	/**
	 * Test widget name
	 */
	public function test_get_name(): void {
		$this->assertSame( 'soma-stock-price', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Stock Price', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-price-list', $this->widget->get_icon() );
	}

	/**
	 * Test widget categories
	 */
	public function test_get_categories(): void {
		$categories = $this->widget->get_categories();

		$this->assertIsArray( $categories );
		$this->assertContains( 'soma', $categories );
	}

	/**
	 * Test style dependencies
	 */
	public function test_get_style_depends(): void {
		$styles = $this->widget->get_style_depends();

		$this->assertIsArray( $styles );
		$this->assertContains( 'soma-stock-price', $styles );
	}

	/**
	 * Test widget has controls registered
	 */
	public function test_has_controls(): void {
		// Use reflection to call protected register_controls method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertNotEmpty( $controls, 'Widget should have controls registered' );

		// Verify specific controls exist.
		$this->assertArrayHasKey( 'layout', $controls, 'Widget should have layout control' );
		$this->assertArrayHasKey( 'alignment', $controls, 'Widget should have alignment control' );
		$this->assertArrayHasKey( 'gap', $controls, 'Widget should have gap control' );
	}

	/**
	 * Test widget renders without errors
	 */
	public function test_renders_without_errors(): void {
		// Use reflection to call protected render method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertNotEmpty( $output, 'Widget should render output' );
		$this->assertStringContainsString( 'soma-stock-price', $output );
	}

	/**
	 * Test widget renders label
	 */
	public function test_renders_label(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'soma-stock-price__label', $output );
	}

	/**
	 * Test widget renders price value
	 */
	public function test_renders_price_value(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'soma-stock-price__value', $output );
	}

	/**
	 * Test widget renders with stock data option
	 */
	public function test_renders_with_stock_data(): void {
		// Set up test stock data.
		update_option(
			'stock_data',
			array(
				'price'    => 55.50,
				'currency' => 'MXN',
				'symbol'   => 'SOMA21.MX',
			)
		);

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Should contain the price value.
		$this->assertStringContainsString( '55.50', $output );

		// Clean up.
		delete_option( 'stock_data' );
	}

	/**
	 * Test widget handles missing stock data gracefully
	 */
	public function test_handles_missing_stock_data(): void {
		// Ensure no stock data exists.
		delete_option( 'stock_data' );

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Should still render without errors.
		$this->assertStringContainsString( 'soma-stock-price', $output );
		// Should show 0.00 as default.
		$this->assertStringContainsString( '0.00', $output );
	}

	/**
	 * Test format_price method
	 */
	public function test_format_price_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'format_price' );

		// Test MXN currency.
		$formatted = $method->invoke( $this->widget, 55.50, 'MXN' );
		$this->assertSame( '$55.50 MXN', $formatted );

		// Test USD currency.
		$formatted = $method->invoke( $this->widget, 100.00, 'USD' );
		$this->assertSame( '$100.00 USD', $formatted );

		// Test with zero.
		$formatted = $method->invoke( $this->widget, 0, 'MXN' );
		$this->assertSame( '$0.00 MXN', $formatted );
	}

	/**
	 * Test horizontal layout class
	 */
	public function test_horizontal_layout(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Default is horizontal.
		$this->assertStringContainsString( 'soma-stock-price--horizontal', $output );
	}
}
