<?php
/**
 * Related Projects Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.23
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\RelatedProjects;

/**
 * Test Related Projects widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class RelatedProjectsWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var RelatedProjects|null
	 */
	private ?RelatedProjects $widget = null;

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

		$this->widget = new RelatedProjects();
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
		$this->assertSame( 'soma-related-projects', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'SOMA Related Projects', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-posts-grid', $this->widget->get_icon() );
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
		$this->assertContains( 'soma-related-projects', $styles );
	}

	/**
	 * Test widget has keywords
	 */
	public function test_get_keywords(): void {
		$keywords = $this->widget->get_keywords();

		$this->assertIsArray( $keywords );
		$this->assertContains( 'related', $keywords );
		$this->assertContains( 'projects', $keywords );
		$this->assertContains( 'portfolio', $keywords );
		$this->assertContains( 'grid', $keywords );
		$this->assertContains( 'soma', $keywords );
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
	 * Test widget has section_title control
	 */
	public function test_has_section_title_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'section_title', $controls );
	}

	/**
	 * Test widget has posts_per_page control
	 */
	public function test_has_posts_per_page_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'posts_per_page', $controls );
	}

	/**
	 * Test widget has columns control
	 */
	public function test_has_columns_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'columns', $controls );
	}

	/**
	 * Test widget has show_city control
	 */
	public function test_has_show_city_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_city', $controls );
	}

	/**
	 * Test widget has show_category control
	 */
	public function test_has_show_category_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'show_category', $controls );
	}

	/**
	 * Test widget has orderby control
	 */
	public function test_has_orderby_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'orderby', $controls );
	}

	/**
	 * Test widget has style_variant control
	 */
	public function test_has_style_variant_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'style_variant', $controls );
	}

	/**
	 * Test widget has title_color control
	 */
	public function test_has_title_color_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'title_color', $controls );
	}

	/**
	 * Test widget has grid_gap control
	 */
	public function test_has_grid_gap_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'grid_gap', $controls );
	}

	/**
	 * Test widget has image_height control
	 */
	public function test_has_image_height_control(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		// Get controls stack.
		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'image_height', $controls );
	}
}
