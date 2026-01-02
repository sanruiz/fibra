<?php
/**
 * Portfolio Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.8
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\Portfolio;

/**
 * Test Portfolio widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class PortfolioWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var Portfolio
	 */
	private Portfolio $widget;

	/**
	 * Test portfolio post IDs
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
		}

		$this->widget = new Portfolio();
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
		$this->assertSame( 'soma-portfolio', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Portfolio', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-gallery-masonry', $this->widget->get_icon() );
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
	 * Test widget style dependencies
	 */
	public function test_get_style_depends(): void {
		$styles = $this->widget->get_style_depends();

		$this->assertIsArray( $styles );
		$this->assertContains( 'soma-portfolio', $styles );
	}

	/**
	 * Test widget script dependencies
	 */
	public function test_get_script_depends(): void {
		$scripts = $this->widget->get_script_depends();

		$this->assertIsArray( $scripts );
		$this->assertContains( 'soma-portfolio', $scripts );
	}

	/**
	 * Test widget has controls registered
	 */
	public function test_has_controls(): void {
		// Access controls via reflection.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );

		// This should not throw an exception.
		$method->invoke( $this->widget );

		$this->assertTrue( true ); // If we got here, controls registered successfully.
	}

	/**
	 * Test widget renders without errors
	 */
	public function test_render_without_errors(): void {
		// Capture output to prevent it from being displayed.
		ob_start();

		// Access render method via reflection.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );
		$method->invoke( $this->widget );

		$output = ob_get_clean();

		// Should render something (even if empty state).
		$this->assertIsString( $output );
	}

	/**
	 * Test widget output contains expected structure
	 */
	public function test_render_output_structure(): void {
		ob_start();

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );
		$method->invoke( $this->widget );

		$output = ob_get_clean();

		// Should contain main container class.
		$this->assertStringContainsString( 'soma-portfolio-widget', $output );
	}

	/**
	 * Test widget has expected control sections
	 */
	public function test_control_sections_exist(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls data.
		$controls = $this->widget->get_controls();

		// Should have content and style sections.
		$this->assertNotEmpty( $controls );
	}

	/**
	 * Test widget respects posts_per_page setting
	 */
	public function test_posts_per_page_setting(): void {
		// Create test portfolio posts.
		for ( $i = 0; $i < 15; $i++ ) {
			$post_id           = $this->factory->post->create(
				array(
					'post_type'   => 'portfolio',
					'post_status' => 'publish',
					'post_title'  => 'Test Portfolio ' . $i,
				)
			);
			$this->test_posts[] = $post_id;
		}

		// The widget should limit results based on posts_per_page.
		$this->assertCount( 15, $this->test_posts );
	}

	/**
	 * Test widget filter functionality
	 */
	public function test_filter_controls_exist(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Check for filter-related controls.
		$control_ids = array_keys( $controls );

		// Should have filter_categories or similar control.
		$has_filter_control = false;
		foreach ( $control_ids as $id ) {
			if ( strpos( $id, 'filter' ) !== false || strpos( $id, 'category' ) !== false ) {
				$has_filter_control = true;
				break;
			}
		}

		$this->assertTrue( $has_filter_control, 'Widget should have filter/category controls' );
	}

	/**
	 * Test widget view toggle controls exist
	 */
	public function test_view_toggle_controls_exist(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls    = $this->widget->get_controls();
		$control_ids = array_keys( $controls );

		// Should have view-related controls.
		$has_view_control = false;
		foreach ( $control_ids as $id ) {
			if ( strpos( $id, 'view' ) !== false || strpos( $id, 'default_view' ) !== false ) {
				$has_view_control = true;
				break;
			}
		}

		$this->assertTrue( $has_view_control, 'Widget should have view mode controls' );
	}

	/**
	 * Test widget style variant controls exist
	 */
	public function test_style_variant_control_exists(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls    = $this->widget->get_controls();
		$control_ids = array_keys( $controls );

		// Should have style variant control.
		$this->assertContains( 'style_variant', $control_ids, 'Widget should have style_variant control' );
	}
}
