<?php
/**
 * Integration tests for the PortfolioCityType Elementor widget.
 *
 * @package Soma\Tests\Integration\Elementor
 * @since   3.1.24
 */

namespace Soma\Tests\Integration\Elementor;

use Soma\Elementor\Widgets\PortfolioCityType;
use WP_UnitTestCase;

/**
 * Class PortfolioCityTypeWidgetTest
 *
 * Tests for the Portfolio City Type Elementor widget.
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class PortfolioCityTypeWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance.
	 *
	 * @var PortfolioCityType|null
	 */
	private ?PortfolioCityType $widget = null;

	/**
	 * Test portfolio post ID.
	 *
	 * @var int
	 */
	private int $test_post_id = 0;

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		// Skip if Elementor not loaded.
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor not loaded' );
			return;
		}

		$this->widget = new PortfolioCityType();
	}

	/**
	 * Tear down test fixtures.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		if ( $this->test_post_id > 0 ) {
			wp_delete_post( $this->test_post_id, true );
		}

		$this->widget = null;
		parent::tearDown();
	}

	/**
	 * Test widget extends Widget_Base.
	 *
	 * @return void
	 */
	public function test_extends_widget_base(): void {
		$this->assertInstanceOf(
			\Elementor\Widget_Base::class,
			$this->widget,
			'Widget should extend Elementor Widget_Base'
		);
	}

	/**
	 * Test widget name.
	 *
	 * @return void
	 */
	public function test_get_name(): void {
		$this->assertSame(
			'soma-portfolio-city-type',
			$this->widget->get_name(),
			'Widget name should be soma-portfolio-city-type'
		);
	}

	/**
	 * Test widget title.
	 *
	 * @return void
	 */
	public function test_get_title(): void {
		$this->assertSame(
			'Portfolio City & Type',
			$this->widget->get_title(),
			'Widget title should be "Portfolio City & Type"'
		);
	}

	/**
	 * Test widget icon.
	 *
	 * @return void
	 */
	public function test_get_icon(): void {
		$this->assertSame(
			'eicon-map-pin',
			$this->widget->get_icon(),
			'Widget icon should be eicon-map-pin'
		);
	}

	/**
	 * Test widget categories.
	 *
	 * @return void
	 */
	public function test_get_categories(): void {
		$categories = $this->widget->get_categories();

		$this->assertIsArray( $categories );
		$this->assertContains(
			'soma',
			$categories,
			'Widget should be in soma category'
		);
	}

	/**
	 * Test widget style dependencies.
	 *
	 * @return void
	 */
	public function test_get_style_depends(): void {
		$styles = $this->widget->get_style_depends();

		$this->assertIsArray( $styles );
		$this->assertContains(
			'soma-portfolio-city-type',
			$styles,
			'Widget should depend on soma-portfolio-city-type style'
		);
	}

	/**
	 * Test widget keywords.
	 *
	 * @return void
	 */
	public function test_get_keywords(): void {
		$keywords = $this->widget->get_keywords();

		$this->assertIsArray( $keywords );
		$this->assertContains( 'portfolio', $keywords );
		$this->assertContains( 'city', $keywords );
		$this->assertContains( 'type', $keywords );
		$this->assertContains( 'soma', $keywords );
	}

	/**
	 * Test widget has required methods.
	 *
	 * @return void
	 */
	public function test_has_required_methods(): void {
		$this->assertTrue(
			method_exists( $this->widget, 'get_name' ),
			'Widget should have get_name method'
		);
		$this->assertTrue(
			method_exists( $this->widget, 'get_title' ),
			'Widget should have get_title method'
		);
		$this->assertTrue(
			method_exists( $this->widget, 'get_icon' ),
			'Widget should have get_icon method'
		);
		$this->assertTrue(
			method_exists( $this->widget, 'get_categories' ),
			'Widget should have get_categories method'
		);
		$this->assertTrue(
			method_exists( $this->widget, 'get_style_depends' ),
			'Widget should have get_style_depends method'
		);
	}
}
