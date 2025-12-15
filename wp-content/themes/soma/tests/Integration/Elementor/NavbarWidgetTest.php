<?php
/**
 * Navbar Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\Navbar;

/**
 * Test Navbar widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class NavbarWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var Navbar
	 */
	private Navbar $widget;

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}

		$this->widget = new Navbar();
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
		$this->assertSame( 'soma-navbar', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Navbar', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-nav-menu', $this->widget->get_icon() );
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
		$this->assertContains( 'soma-navbar', $styles );
	}

	/**
	 * Test widget has controls registered
	 */
	public function test_has_controls(): void {
		// Use reflection to call protected register_controls method
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertNotEmpty( $controls, 'Widget should have controls registered' );

		// Verify specific controls exist
		$this->assertArrayHasKey( 'menu', $controls, 'Widget should have menu control' );
	}

	/**
	 * Test widget renders without errors
	 */
	public function test_renders_without_errors(): void {
		// Use reflection to call protected render method
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertNotEmpty( $output, 'Widget should render output' );
		$this->assertStringContainsString( 'soma-navbar', $output );
	}
}
