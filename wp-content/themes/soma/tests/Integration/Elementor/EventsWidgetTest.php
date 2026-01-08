<?php
/**
 * Events Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.13
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\Events;

/**
 * Test Events widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class EventsWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var Events|null
	 */
	private ?Events $widget = null;

	/**
	 * Test event post IDs
	 *
	 * @var array<int>
	 */
	private array $test_posts = array();

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
			return;
		}

		$this->widget = new Events();
	}

	/**
	 * Tear down test
	 */
	public function tearDown(): void {
		// Clean up test posts.
		foreach ( $this->test_posts as $post_id ) {
			wp_delete_post( $post_id, true );
		}
		$this->test_posts = array();

		parent::tearDown();
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
		$this->assertSame( 'soma-events', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Events', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-calendar', $this->widget->get_icon() );
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
		$this->assertContains( 'soma-events', $styles );
	}

	/**
	 * Test script dependencies
	 */
	public function test_get_script_depends(): void {
		$scripts = $this->widget->get_script_depends();

		$this->assertIsArray( $scripts );
		$this->assertContains( 'soma-events', $scripts );
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
		$this->assertArrayHasKey( 'order', $controls, 'Widget should have order control' );
		$this->assertArrayHasKey( 'order_by', $controls, 'Widget should have order_by control' );
		$this->assertArrayHasKey( 'show_filters', $controls, 'Widget should have show_filters control' );
	}

	/**
	 * Test widget has label controls
	 */
	public function test_has_label_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Verify label controls exist.
		$this->assertArrayHasKey( 'filter_title', $controls, 'Widget should have filter_title control' );
		$this->assertArrayHasKey( 'see_all_text', $controls, 'Widget should have see_all_text control' );
	}

	/**
	 * Test widget has style controls
	 */
	public function test_has_style_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Verify style controls exist.
		$this->assertArrayHasKey( 'container_padding', $controls, 'Widget should have container_padding control' );
		$this->assertArrayHasKey( 'container_background', $controls, 'Widget should have container_background control' );
		$this->assertArrayHasKey( 'filter_color', $controls, 'Widget should have filter_color control' );
		$this->assertArrayHasKey( 'filter_active_color', $controls, 'Widget should have filter_active_color control' );
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
		$this->assertStringContainsString( 'soma-events-widget', $output );
	}

	/**
	 * Test widget renders expected container classes
	 */
	public function test_renders_container_classes(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'events-partial-e5e1bb', $output );
	}

	/**
	 * Test widget renders data attributes
	 */
	public function test_renders_data_attributes(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'data-endpoint', $output );
		$this->assertStringContainsString( 'data-order', $output );
		$this->assertStringContainsString( 'data-order-by', $output );
		$this->assertStringContainsString( 'data-lang', $output );
	}

	/**
	 * Test widget renders filters section
	 */
	public function test_renders_filters_section(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'class="filters"', $output );
	}

	/**
	 * Test widget renders events container
	 */
	public function test_renders_events_container(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'class="events"', $output );
	}

	/**
	 * Test default control values
	 */
	public function test_default_control_values(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Check order default is ASC.
		$this->assertSame( 'ASC', $controls['order']['default'] );

		// Check order_by default is custom_date.
		$this->assertSame( 'custom_date', $controls['order_by']['default'] );

		// Check show_filters default is yes.
		$this->assertSame( 'yes', $controls['show_filters']['default'] );
	}

	/**
	 * Test order control options
	 */
	public function test_order_control_options(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'options', $controls['order'] );
		$this->assertArrayHasKey( 'ASC', $controls['order']['options'] );
		$this->assertArrayHasKey( 'DESC', $controls['order']['options'] );
	}

	/**
	 * Test order_by control options
	 */
	public function test_order_by_control_options(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'options', $controls['order_by'] );
		$this->assertArrayHasKey( 'custom_date', $controls['order_by']['options'] );
		$this->assertArrayHasKey( 'title', $controls['order_by']['options'] );
		$this->assertArrayHasKey( 'date', $controls['order_by']['options'] );
	}

	/**
	 * Test widget renders REST API endpoint URL
	 */
	public function test_renders_rest_api_endpoint(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( '/wp-json/soma/events', $output );
	}
}
