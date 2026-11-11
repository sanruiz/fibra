<?php
/**
 * Integration tests for the TitleDescriptionAccordion Elementor widget.
 *
 * @package Soma\Tests\Integration\Elementor
 * @since   3.1.25
 */

namespace Soma\Tests\Integration\Elementor;

use Soma\Elementor\Widgets\TitleDescriptionAccordion;
use WP_UnitTestCase;

/**
 * Class TitleDescriptionAccordionWidgetTest.
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class TitleDescriptionAccordionWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance.
	 *
	 * @var TitleDescriptionAccordion|null
	 */
	private ?TitleDescriptionAccordion $widget = null;

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\\Elementor\\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor not loaded' );
			return;
		}

		$this->widget = new TitleDescriptionAccordion();
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
	 * Test widget name.
	 *
	 * @return void
	 */
	public function test_get_name(): void {
		$this->assertSame( 'soma-title-description-accordion', $this->widget->get_name() );
	}

	/**
	 * Test widget title.
	 *
	 * @return void
	 */
	public function test_get_title(): void {
		$this->assertSame( 'SOMA Title Description Accordion', $this->widget->get_title() );
	}

	/**
	 * Test widget category.
	 *
	 * @return void
	 */
	public function test_get_categories(): void {
		$this->assertContains( 'soma', $this->widget->get_categories() );
	}

	/**
	 * Test style dependency.
	 *
	 * @return void
	 */
	public function test_get_style_depends(): void {
		$this->assertContains( 'soma-title-description-accordion', $this->widget->get_style_depends() );
	}
}
