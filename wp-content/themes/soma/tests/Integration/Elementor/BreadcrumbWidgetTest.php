<?php
/**
 * Breadcrumb Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.7
 */

namespace Soma\Tests\Integration\Elementor;

use Soma\Elementor\Widgets\Breadcrumb;
use WP_UnitTestCase;

/**
 * Test Breadcrumb widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 * @group breadcrumb
 */
class BreadcrumbWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var Breadcrumb|null
	 */
	private ?Breadcrumb $widget = null;

	/**
	 * Set up before each test
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}

		$this->widget = new Breadcrumb();
	}

	/**
	 * Tear down after each test
	 *
	 * @return void
	 */
	public function tear_down(): void {
		$this->widget = null;
		parent::tear_down();
	}

	/**
	 * Test widget name
	 *
	 * @return void
	 */
	public function test_widget_name(): void {
		$this->assertSame( 'soma-breadcrumb', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 *
	 * @return void
	 */
	public function test_widget_title(): void {
		$this->assertSame( 'SOMA Breadcrumb', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 *
	 * @return void
	 */
	public function test_widget_icon(): void {
		$icon = $this->widget->get_icon();
		$this->assertNotEmpty( $icon );
		$this->assertStringStartsWith( 'eicon-', $icon );
	}

	/**
	 * Test widget categories
	 *
	 * @return void
	 */
	public function test_widget_categories(): void {
		$categories = $this->widget->get_categories();
		$this->assertIsArray( $categories );
		$this->assertContains( 'soma', $categories );
	}

	/**
	 * Test widget keywords
	 *
	 * @return void
	 */
	public function test_widget_keywords(): void {
		$keywords = $this->widget->get_keywords();
		$this->assertIsArray( $keywords );
		$this->assertContains( 'breadcrumb', $keywords );
		$this->assertContains( 'navigation', $keywords );
	}

	/**
	 * Test style dependencies
	 *
	 * @return void
	 */
	public function test_style_depends(): void {
		$styles = $this->widget->get_style_depends();
		$this->assertIsArray( $styles );
		$this->assertContains( 'soma-breadcrumb', $styles );
	}

	/**
	 * Test controls are registered
	 *
	 * @return void
	 */
	public function test_controls_registered(): void {
		// Get widget controls - this will trigger register_controls().
		$controls = $this->widget->get_controls();

		// Check that key controls exist.
		$this->assertArrayHasKey( 'separator', $controls );
		$this->assertArrayHasKey( 'show_home', $controls );
		$this->assertArrayHasKey( 'show_current', $controls );
		$this->assertArrayHasKey( 'text_color', $controls );
		$this->assertArrayHasKey( 'link_color', $controls );
	}

	/**
	 * Test separator control default value
	 *
	 * @return void
	 */
	public function test_separator_default(): void {
		$controls = $this->widget->get_controls();
		$this->assertEquals( '/', $controls['separator']['default'] );
	}

	/**
	 * Test show_home control default value
	 *
	 * @return void
	 */
	public function test_show_home_default(): void {
		$controls = $this->widget->get_controls();
		$this->assertEquals( 'yes', $controls['show_home']['default'] );
	}

	/**
	 * Test show_current control default value
	 *
	 * @return void
	 */
	public function test_show_current_default(): void {
		$controls = $this->widget->get_controls();
		$this->assertEquals( 'yes', $controls['show_current']['default'] );
	}

	/**
	 * Test breadcrumb helper function exists
	 *
	 * @return void
	 */
	public function test_breadcrumb_helper_exists(): void {
		$this->assertTrue( function_exists( 'soma_get_breadcrumb_items' ) );
	}

	/**
	 * Test breadcrumb items on front page
	 *
	 * @return void
	 */
	public function test_breadcrumb_items_front_page(): void {
		$this->go_to( home_url( '/' ) );

		$items = soma_get_breadcrumb_items();

		$this->assertIsArray( $items );
		$this->assertCount( 1, $items );
		$this->assertEquals( 'Home', $items[0]['name'] );
		$this->assertTrue( $items[0]['is_current'] );
	}

	/**
	 * Test breadcrumb items on single page
	 *
	 * @return void
	 */
	public function test_breadcrumb_items_single_page(): void {
		$page_id = $this->factory->post->create(
			array(
				'post_type'  => 'page',
				'post_title' => 'Test Page',
			)
		);

		$this->go_to( get_permalink( $page_id ) );

		$items = soma_get_breadcrumb_items();

		$this->assertIsArray( $items );
		$this->assertGreaterThanOrEqual( 2, count( $items ) );
		$this->assertEquals( 'Home', $items[0]['name'] );
		$this->assertEquals( 'Test Page', $items[ count( $items ) - 1 ]['name'] );
		$this->assertTrue( $items[ count( $items ) - 1 ]['is_current'] );
	}

	/**
	 * Test breadcrumb items with page hierarchy
	 *
	 * @return void
	 */
	public function test_breadcrumb_items_page_hierarchy(): void {
		$parent_id = $this->factory->post->create(
			array(
				'post_type'  => 'page',
				'post_title' => 'Parent Page',
			)
		);

		$child_id = $this->factory->post->create(
			array(
				'post_type'   => 'page',
				'post_title'  => 'Child Page',
				'post_parent' => $parent_id,
			)
		);

		$this->go_to( get_permalink( $child_id ) );

		$items = soma_get_breadcrumb_items();

		$this->assertIsArray( $items );
		$this->assertCount( 3, $items );
		$this->assertEquals( 'Home', $items[0]['name'] );
		$this->assertEquals( 'Parent Page', $items[1]['name'] );
		$this->assertEquals( 'Child Page', $items[2]['name'] );
		$this->assertTrue( $items[2]['is_current'] );
	}

	/**
	 * Test breadcrumb items structure
	 *
	 * @return void
	 */
	public function test_breadcrumb_items_structure(): void {
		$items = soma_get_breadcrumb_items();

		foreach ( $items as $item ) {
			$this->assertIsArray( $item );
			$this->assertArrayHasKey( 'name', $item );
			$this->assertArrayHasKey( 'url', $item );
			$this->assertArrayHasKey( 'is_current', $item );
			$this->assertIsString( $item['name'] );
			$this->assertIsString( $item['url'] );
			$this->assertIsBool( $item['is_current'] );
		}
	}
}
