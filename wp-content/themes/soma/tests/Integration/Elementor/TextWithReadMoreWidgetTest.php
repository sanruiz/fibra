<?php
/**
 * Integration tests for the TextWithReadMore Elementor widget.
 *
 * @package Soma\Tests\Integration\Elementor
 * @since   3.1.24
 */

namespace Soma\Tests\Integration\Elementor;

use Soma\Elementor\Widgets\TextWithReadMore;
use WP_UnitTestCase;

/**
 * Class TextWithReadMoreWidgetTest
 *
 * Tests for the Text With Read More Elementor widget.
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class TextWithReadMoreWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance.
	 *
	 * @var TextWithReadMore|null
	 */
	private ?TextWithReadMore $widget = null;

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

		$this->widget = new TextWithReadMore();
	}

	/**
	 * Tear down test fixtures.
	 *
	 * @return void
	 */
	public function tearDown(): void {
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
			'soma-text-with-read-more',
			$this->widget->get_name(),
			'Widget name should be soma-text-with-read-more'
		);
	}

	/**
	 * Test widget title.
	 *
	 * @return void
	 */
	public function test_get_title(): void {
		$this->assertSame(
			'SOMA Text with Read More',
			$this->widget->get_title(),
			'Widget title should be "SOMA Text with Read More"'
		);
	}

	/**
	 * Test widget icon.
	 *
	 * @return void
	 */
	public function test_get_icon(): void {
		$this->assertSame(
			'eicon-post-content',
			$this->widget->get_icon(),
			'Widget icon should be eicon-post-content'
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
			'soma-text-with-read-more',
			$styles,
			'Widget should depend on soma-text-with-read-more style'
		);
	}

	/**
	 * Test widget script dependencies.
	 *
	 * @return void
	 */
	public function test_get_script_depends(): void {
		$scripts = $this->widget->get_script_depends();

		$this->assertIsArray( $scripts );
		$this->assertContains(
			'soma-text-with-read-more',
			$scripts,
			'Widget should depend on soma-text-with-read-more script'
		);
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
		$this->assertTrue(
			method_exists( $this->widget, 'get_script_depends' ),
			'Widget should have get_script_depends method'
		);
	}
}
