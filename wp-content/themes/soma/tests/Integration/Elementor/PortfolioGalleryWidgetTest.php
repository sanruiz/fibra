<?php
/**
 * Portfolio Gallery Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.17
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\PortfolioGallery;

/**
 * Test Portfolio Gallery widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class PortfolioGalleryWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var PortfolioGallery|null
	 */
	private ?PortfolioGallery $widget = null;

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

		$this->widget = new PortfolioGallery();
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
			\Soma\Elementor\Base\WidgetBase::class,
			$this->widget
		);
	}

	/**
	 * Test widget name
	 */
	public function test_get_name(): void {
		$this->assertSame( 'soma-portfolio-gallery', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Portfolio Gallery', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-slider-push', $this->widget->get_icon() );
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
		$this->assertContains( 'soma-portfolio-gallery', $styles );
	}

	/**
	 * Test widget script dependencies
	 */
	public function test_get_script_depends(): void {
		$scripts = $this->widget->get_script_depends();

		$this->assertIsArray( $scripts );
		$this->assertContains( 'slick', $scripts );
		$this->assertContains( 'soma-portfolio-gallery', $scripts );
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
	 * Test widget has slider height control
	 */
	public function test_has_slider_height_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'slider_height', $controls );
	}

	/**
	 * Test widget has navigation controls
	 */
	public function test_has_navigation_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_navigation', $controls );
		$this->assertArrayHasKey( 'show_dots', $controls );
	}

	/**
	 * Test widget has autoplay control
	 */
	public function test_has_autoplay_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'autoplay', $controls );
	}

	/**
	 * Test widget has video URL control
	 */
	public function test_has_video_url_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'video_url', $controls );
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
	 * Test get_gallery_images method exists
	 */
	public function test_get_gallery_images_method_exists(): void {
		$reflection = new \ReflectionClass( $this->widget );

		$this->assertTrue( $reflection->hasMethod( 'get_gallery_images' ) );
	}

	/**
	 * Test get_portfolio_id method exists
	 */
	public function test_get_portfolio_id_method_exists(): void {
		$reflection = new \ReflectionClass( $this->widget );

		$this->assertTrue( $reflection->hasMethod( 'get_portfolio_id' ) );
	}

	/**
	 * Test render method handles empty gallery gracefully
	 */
	public function test_render_handles_empty_gallery(): void {
		// Create a test portfolio post without gallery.
		$post_id            = $this->factory->post->create(
			array(
				'post_type'  => 'portfolio',
				'post_title' => 'Test Portfolio',
			)
		);
		$this->test_posts[] = $post_id;

		// Set as current query.
		$GLOBALS['post'] = get_post( $post_id );
		setup_postdata( $GLOBALS['post'] );

		// Capture output.
		ob_start();
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Should either show empty notice or fallback to featured image.
		$this->assertIsString( $output );

		wp_reset_postdata();
	}
}
