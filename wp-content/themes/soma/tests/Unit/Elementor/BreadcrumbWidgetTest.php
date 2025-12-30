<?php
/**
 * Breadcrumb Widget Unit Tests
 *
 * @package Soma
 * @subpackage Tests\Unit\Elementor
 * @since 3.1.7
 */

namespace Soma\Tests\Unit\Elementor;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Test Breadcrumb widget structure
 *
 * @group unit
 * @group elementor
 * @group widgets
 * @group breadcrumb
 */
class BreadcrumbWidgetTest extends TestCase {

	/**
	 * Widget class name
	 *
	 * @var string
	 */
	private string $widget_class = \Soma\Elementor\Widgets\Breadcrumb::class;

	/**
	 * Test class exists
	 *
	 * @return void
	 */
	public function test_class_exists(): void {
		$this->assertTrue( class_exists( $this->widget_class ) );
	}

	/**
	 * Test extends WidgetBase
	 *
	 * @return void
	 */
	public function test_extends_widget_base(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$parent     = $reflection->getParentClass();

		$this->assertNotFalse( $parent );
		$this->assertSame( 'Elementor\Widget_Base', $parent->getName() );
	}

	/**
	 * Test has required methods
	 *
	 * @return void
	 */
	public function test_has_required_methods(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$methods    = array(
			'get_name',
			'get_title',
			'get_icon',
			'get_categories',
			'get_keywords',
			'get_style_depends',
			'register_controls',
			'render',
			'content_template',
		);

		foreach ( $methods as $method ) {
			$this->assertTrue(
				$reflection->hasMethod( $method ),
				"Widget should have method: {$method}"
			);
		}
	}

	/**
	 * Test get_name method exists and is public
	 *
	 * @return void
	 */
	public function test_get_name_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_name' );

		$this->assertTrue( $method->isPublic() );
		$this->assertSame( 'string', $method->getReturnType()->getName() );
	}

	/**
	 * Test get_title method exists and is public
	 *
	 * @return void
	 */
	public function test_get_title_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_title' );

		$this->assertTrue( $method->isPublic() );
		$this->assertSame( 'string', $method->getReturnType()->getName() );
	}

	/**
	 * Test get_icon method exists and is public
	 *
	 * @return void
	 */
	public function test_get_icon_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_icon' );

		$this->assertTrue( $method->isPublic() );
		$this->assertSame( 'string', $method->getReturnType()->getName() );
	}

	/**
	 * Test get_categories method exists and returns array
	 *
	 * @return void
	 */
	public function test_get_categories_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_categories' );

		$this->assertTrue( $method->isPublic() );
		$this->assertSame( 'array', $method->getReturnType()->getName() );
	}

	/**
	 * Test register_controls method exists and is protected
	 *
	 * @return void
	 */
	public function test_register_controls_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'register_controls' );

		$this->assertTrue( $method->isProtected() );
		$this->assertSame( 'void', $method->getReturnType()->getName() );
	}

	/**
	 * Test render method exists and is protected
	 *
	 * @return void
	 */
	public function test_render_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'render' );

		$this->assertTrue( $method->isProtected() );
		$this->assertSame( 'void', $method->getReturnType()->getName() );
	}

	/**
	 * Test content_template method exists and is protected
	 *
	 * @return void
	 */
	public function test_content_template_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'content_template' );

		$this->assertTrue( $method->isProtected() );
		$this->assertSame( 'void', $method->getReturnType()->getName() );
	}

	/**
	 * Test has get_breadcrumb_items private method
	 *
	 * @return void
	 */
	public function test_has_get_breadcrumb_items_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_breadcrumb_items' );

		$this->assertTrue( $method->isPrivate() );
		$this->assertSame( 'array', $method->getReturnType()->getName() );
	}
}
