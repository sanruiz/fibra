<?php
/**
 * Portfolio Technical Specs Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.17
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\PortfolioTechnicalSpecs;

/**
 * Test Portfolio Technical Specs widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class PortfolioTechnicalSpecsWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var PortfolioTechnicalSpecs|null
	 */
	private ?PortfolioTechnicalSpecs $widget = null;

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
			return;
		}

		$this->widget = new PortfolioTechnicalSpecs();
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
		$this->widget     = null;

		parent::tearDown();
	}

	/**
	 * Test widget extends correct base class
	 */
	public function test_extends_widget_base(): void {
		$this->assertInstanceOf(
			\Elementor\Widget_Base::class,
			$this->widget
		);
	}

	/**
	 * Test widget name
	 */
	public function test_get_name(): void {
		$this->assertSame( 'soma-portfolio-technical-specs', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'SOMA Portfolio Technical Specs', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-info-box', $this->widget->get_icon() );
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
		$this->assertContains( 'soma-portfolio-technical-specs', $styles );
	}

	/**
	 * Test widget script dependencies returns empty array
	 */
	public function test_get_script_depends(): void {
		$scripts = $this->widget->get_script_depends();

		$this->assertIsArray( $scripts );
		$this->assertEmpty( $scripts );
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

		// Should not throw an exception or fatal error.
		$this->assertIsString( $output );
	}

	/**
	 * Test widget has data source control
	 */
	public function test_has_data_source_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'data_source', $controls );
	}

	/**
	 * Test widget has portfolio_id control
	 */
	public function test_has_portfolio_id_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'portfolio_id', $controls );
	}

	/**
	 * Test widget has year visibility controls
	 */
	public function test_has_year_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_year', $controls );
		$this->assertArrayHasKey( 'year_label', $controls );
	}

	/**
	 * Test widget has designed by visibility controls
	 */
	public function test_has_designed_by_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_designed_by', $controls );
		$this->assertArrayHasKey( 'designed_by_label', $controls );
	}

	/**
	 * Test widget has GLA visibility controls
	 */
	public function test_has_gla_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_gla', $controls );
		$this->assertArrayHasKey( 'gla_label', $controls );
	}

	/**
	 * Test widget has occupancy visibility controls
	 */
	public function test_has_occupancy_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_occupancy', $controls );
		$this->assertArrayHasKey( 'occupancy_label', $controls );
	}

	/**
	 * Test widget has project type visibility controls
	 */
	public function test_has_project_type_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_project_type', $controls );
		$this->assertArrayHasKey( 'project_type_label', $controls );
	}

	/**
	 * Test widget has background color control
	 */
	public function test_has_background_color_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'background_color', $controls );
	}

	/**
	 * Test widget has container padding control
	 */
	public function test_has_container_padding_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'container_padding', $controls );
	}

	/**
	 * Test widget has label color control
	 */
	public function test_has_label_color_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'label_color', $controls );
	}

	/**
	 * Test get_portfolio_options method exists and returns array
	 */
	public function test_get_portfolio_options(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'get_portfolio_options' );
		$method->setAccessible( true );

		$options = $method->invoke( $this->widget );

		$this->assertIsArray( $options );
	}

	/**
	 * Test get_portfolio_id method exists
	 */
	public function test_get_portfolio_id_method_exists(): void {
		$reflection = new \ReflectionClass( $this->widget );

		$this->assertTrue( $reflection->hasMethod( 'get_portfolio_id' ) );
	}

	/**
	 * Test render method handles empty data gracefully
	 */
	public function test_render_handles_empty_data(): void {
		// Create a test portfolio post without ACF fields.
		$post_id            = $this->factory->post->create(
			array(
				'post_type'  => 'portfolio',
				'post_title' => 'Test Portfolio',
			)
		);
		$this->test_posts[] = $post_id;

		// Set as current query.
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Required for testing post context.
		$GLOBALS['post'] = get_post( $post_id );
		setup_postdata( $GLOBALS['post'] );

		// Capture output.
		ob_start();
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Should either show empty state or nothing.
		$this->assertIsString( $output );

		wp_reset_postdata();
	}
}
