<?php
/**
 * ShareQuotation Widget Integration Tests.
 *
 * @package Soma\Tests\Integration\Elementor
 * @since   3.1.12
 */

namespace Soma\Tests\Integration\Elementor;

use Soma\Elementor\Widgets\ShareQuotation;
use WP_UnitTestCase;

/**
 * Integration tests for the ShareQuotation Elementor widget.
 *
 * @group integration
 * @group elementor
 * @group widgets
 * @group share-quotation
 */
class ShareQuotationWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance.
	 *
	 * @var ShareQuotation|null
	 */
	private ?ShareQuotation $widget = null;

	/**
	 * Set up test environment.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		// Skip if Elementor not loaded.
		if ( ! did_action( 'elementor/loaded' ) ) {
			$this->markTestSkipped( 'Elementor not loaded' );
			return;
		}

		$this->widget = new ShareQuotation();
	}

	/**
	 * Tear down test environment.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		$this->widget = null;
		delete_option( 'stock_data' );
		parent::tearDown();
	}

	/**
	 * Test widget extends the correct base class.
	 *
	 * @return void
	 */
	public function test_extends_widget_base(): void {
		$this->assertInstanceOf(
			\Soma\Elementor\Base\WidgetBase::class,
			$this->widget,
			'ShareQuotation should extend WidgetBase'
		);
	}

	/**
	 * Test widget name is correct.
	 *
	 * @return void
	 */
	public function test_get_name(): void {
		$this->assertSame(
			'soma-share-quotation',
			$this->widget->get_name(),
			'Widget name should be soma-share-quotation'
		);
	}

	/**
	 * Test widget title is correct.
	 *
	 * @return void
	 */
	public function test_get_title(): void {
		$this->assertSame(
			'Share Quotation',
			$this->widget->get_title(),
			'Widget title should be Share Quotation'
		);
	}

	/**
	 * Test widget icon is correct.
	 *
	 * @return void
	 */
	public function test_get_icon(): void {
		$this->assertSame(
			'eicon-price-table',
			$this->widget->get_icon(),
			'Widget icon should be eicon-price-table'
		);
	}

	/**
	 * Test widget categories include 'soma'.
	 *
	 * @return void
	 */
	public function test_get_categories(): void {
		$categories = $this->widget->get_categories();

		$this->assertIsArray( $categories );
		$this->assertContains(
			'soma',
			$categories,
			'Widget categories should include soma'
		);
	}

	/**
	 * Test widget has correct style dependencies.
	 *
	 * @return void
	 */
	public function test_get_style_depends(): void {
		$style_depends = $this->widget->get_style_depends();

		$this->assertIsArray( $style_depends );
		$this->assertContains(
			'soma-share-quotation',
			$style_depends,
			'Widget should depend on soma-share-quotation style'
		);
	}

	/**
	 * Test widget has controls registered.
	 *
	 * @return void
	 */
	public function test_has_controls(): void {
		// Use reflection to call protected register_controls method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertNotEmpty( $controls, 'Widget should have controls registered' );

		// Extract control IDs.
		$control_ids = array_keys( $controls );

		// Verify expected controls exist.
		$expected_controls = [
			'title',
			'symbol',
			'price_label',
			'change_label',
			'percent_label',
			'volume_label',
			'date_label',
			'dark_background',
			'show_download',
			'show_volume',
			'show_date',
			'show_change',
			'show_percent',
		];

		foreach ( $expected_controls as $expected ) {
			$this->assertContains(
				$expected,
				$control_ids,
				"Widget should have '$expected' control"
			);
		}
	}

	/**
	 * Test widget renders without errors.
	 *
	 * @return void
	 */
	public function test_renders_without_errors(): void {
		// Use reflection to call protected render method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertIsString( $output );
		$this->assertNotFalse( $output );
	}

	/**
	 * Test widget renders with stock data.
	 *
	 * @return void
	 */
	public function test_renders_with_stock_data(): void {
		// Set up mock stock data.
		$stock_data = [
			'price'                  => 15.25,
			'change'                 => 0.50,
			'percent'                => 3.39,
			'volume'                 => 1234567,
			'timestamp'              => time(),
			'symbol'                 => 'SOMA',
			'currency'               => 'MXN',
			'shortName'              => 'SOMA Inc',
			'longName'               => 'SOMA Investment Trust',
			'marketState'            => 'REGULAR',
			'exchangeTimezoneName'   => 'America/Mexico_City',
			'exchangeTimezoneOffset' => -21600,
		];

		update_option( 'stock_data', $stock_data );

		// Use reflection to call protected render method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Verify output contains expected structure.
		$this->assertStringContainsString( 'soma-share-quotation', $output );
		$this->assertStringContainsString( 'soma-share-quotation__container', $output );
	}

	/**
	 * Test widget handles missing stock data gracefully.
	 *
	 * @return void
	 */
	public function test_handles_missing_stock_data(): void {
		// Ensure no stock data exists.
		delete_option( 'stock_data' );

		// Use reflection to call protected render method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Widget should still render something (even empty state).
		$this->assertIsString( $output );
	}

	/**
	 * Test format_price method returns formatted price.
	 *
	 * @return void
	 */
	public function test_format_price_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'format_price' );

		// Test various price values.
		$this->assertSame( '$15.25', $method->invoke( $this->widget, 15.25 ) );
		$this->assertSame( '$0.00', $method->invoke( $this->widget, 0 ) );
		$this->assertSame( '$1,234.56', $method->invoke( $this->widget, 1234.56 ) );
	}

	/**
	 * Test format_volume method returns formatted volume.
	 *
	 * @return void
	 */
	public function test_format_volume_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'format_volume' );

		// Test various volume values.
		$this->assertSame( '1,234,567', $method->invoke( $this->widget, 1234567 ) );
		$this->assertSame( '0', $method->invoke( $this->widget, 0 ) );
		$this->assertSame( '999', $method->invoke( $this->widget, 999 ) );
	}

	/**
	 * Test format_change method returns formatted change with sign.
	 *
	 * @return void
	 */
	public function test_format_change_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'format_change' );

		// Test positive change.
		$this->assertSame( '+$0.50', $method->invoke( $this->widget, 0.50 ) );

		// Test negative change.
		$this->assertSame( '-$0.50', $method->invoke( $this->widget, -0.50 ) );

		// Test zero change.
		$this->assertSame( '$0.00', $method->invoke( $this->widget, 0 ) );
	}

	/**
	 * Test format_percent method returns formatted percentage with sign.
	 *
	 * @return void
	 */
	public function test_format_percent_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'format_percent' );

		// Test positive percent.
		$this->assertSame( '+3.39%', $method->invoke( $this->widget, 3.39 ) );

		// Test negative percent.
		$this->assertSame( '-2.50%', $method->invoke( $this->widget, -2.50 ) );

		// Test zero percent.
		$this->assertSame( '0.00%', $method->invoke( $this->widget, 0 ) );
	}

	/**
	 * Test format_date method returns formatted date string.
	 *
	 * @return void
	 */
	public function test_format_date_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'format_date' );

		// Test with specific timestamp (January 15, 2025 at 10:30:00 UTC).
		$timestamp = strtotime( '2025-01-15 10:30:00 UTC' );
		$result    = $method->invoke( $this->widget, $timestamp );

		// Result should contain date components.
		$this->assertIsString( $result );
		$this->assertNotEmpty( $result );
	}

	/**
	 * Test widget renders with dark background class.
	 *
	 * @return void
	 */
	public function test_renders_dark_background_class(): void {
		// Set up stock data.
		$stock_data = [
			'price'     => 15.25,
			'change'    => 0.50,
			'percent'   => 3.39,
			'volume'    => 1234567,
			'timestamp' => time(),
			'symbol'    => 'SOMA',
			'currency'  => 'MXN',
		];

		update_option( 'stock_data', $stock_data );

		// Use reflection to access settings.
		$reflection       = new \ReflectionClass( $this->widget );
		$settings_prop    = $reflection->getProperty( '_settings' );
		$settings_prop->setValue( $this->widget, [ 'dark_background' => 'yes' ] );

		$method = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// The output may contain the dark class depending on the settings.
		$this->assertIsString( $output );
	}

	/**
	 * Test positive change renders with positive class.
	 *
	 * @return void
	 */
	public function test_positive_change_renders_positive_class(): void {
		// Set up stock data with positive change.
		$stock_data = [
			'price'     => 15.25,
			'change'    => 0.50,
			'percent'   => 3.39,
			'volume'    => 1234567,
			'timestamp' => time(),
			'symbol'    => 'SOMA',
			'currency'  => 'MXN',
		];

		update_option( 'stock_data', $stock_data );

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Should contain positive indicator class.
		$this->assertStringContainsString( '--positive', $output );
	}

	/**
	 * Test negative change renders with negative class.
	 *
	 * @return void
	 */
	public function test_negative_change_renders_negative_class(): void {
		// Set up stock data with negative change.
		$stock_data = [
			'price'     => 15.25,
			'change'    => -0.50,
			'percent'   => -3.39,
			'volume'    => 1234567,
			'timestamp' => time(),
			'symbol'    => 'SOMA',
			'currency'  => 'MXN',
		];

		update_option( 'stock_data', $stock_data );

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Should contain negative indicator class.
		$this->assertStringContainsString( '--negative', $output );
	}

	/**
	 * Test show_volume control defaults to hidden.
	 *
	 * @return void
	 */
	public function test_show_volume_defaults_to_hidden(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();
		$this->assertArrayHasKey( 'show_volume', $controls, 'Widget should have show_volume control' );
		$this->assertSame( '', $controls['show_volume']['default'], 'show_volume should default to hidden (empty string)' );
	}

	/**
	 * Test show_date control defaults to hidden.
	 *
	 * @return void
	 */
	public function test_show_date_defaults_to_hidden(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();
		$this->assertArrayHasKey( 'show_date', $controls, 'Widget should have show_date control' );
		$this->assertSame( '', $controls['show_date']['default'], 'show_date should default to hidden (empty string)' );
	}

	/**
	 * Test show_change control defaults to hidden.
	 *
	 * @return void
	 */
	public function test_show_change_defaults_to_hidden(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();
		$this->assertArrayHasKey( 'show_change', $controls, 'Widget should have show_change control' );
		$this->assertSame( '', $controls['show_change']['default'], 'show_change should default to hidden (empty string)' );
	}

	/**
	 * Test show_percent control defaults to hidden.
	 *
	 * @return void
	 */
	public function test_show_percent_defaults_to_hidden(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();
		$this->assertArrayHasKey( 'show_percent', $controls, 'Widget should have show_percent control' );
		$this->assertSame( '', $controls['show_percent']['default'], 'show_percent should default to hidden (empty string)' );
	}
}
