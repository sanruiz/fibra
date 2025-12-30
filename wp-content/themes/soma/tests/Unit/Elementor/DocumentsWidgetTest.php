<?php
/**
 * Documents Widget Unit Tests
 *
 * Unit tests for the Documents Elementor widget class structure
 * and method signatures without WordPress dependencies.
 *
 * @package Soma
 * @subpackage Tests\Unit\Elementor
 * @since 3.1.5
 */

namespace Soma\Tests\Unit\Elementor;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Test Documents widget class structure
 *
 * @group unit
 * @group elementor
 * @group widgets
 */
class DocumentsWidgetTest extends TestCase {

	/**
	 * Widget class name
	 *
	 * @var string
	 */
	private string $widget_class = \Soma\Elementor\Widgets\Documents::class;

	/**
	 * Check if Elementor is available before running tests
	 *
	 * Since the Documents widget extends Elementor\Widget_Base,
	 * we need Elementor to be available to even load the class.
	 */
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		// Check if Elementor Widget_Base class exists
		// This is required because our widget extends it via WidgetBase.
		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			self::markTestSkipped( 'Elementor Widget_Base class is not available. Skipping unit tests.' );
		}
	}

	/**
	 * Test widget class exists
	 */
	public function test_class_exists(): void {
		$this->assertTrue(
			class_exists( $this->widget_class ),
			'Documents widget class should exist'
		);
	}

	/**
	 * Test widget extends WidgetBase
	 */
	public function test_extends_widget_base(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$parent     = $reflection->getParentClass();

		$this->assertNotFalse( $parent, 'Widget should have a parent class' );
		$this->assertSame(
			'Soma\Elementor\Base\WidgetBase',
			$parent->getName(),
			'Widget should extend WidgetBase'
		);
	}

	/**
	 * Test widget has required methods
	 */
	public function test_has_required_methods(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$required_methods = array(
			'get_name',
			'get_title',
			'get_icon',
			'get_style_depends',
			'register_controls',
			'render',
		);

		foreach ( $required_methods as $method ) {
			$this->assertTrue(
				$reflection->hasMethod( $method ),
				"Widget should have '$method' method"
			);
		}
	}

	/**
	 * Test get_name returns string
	 */
	public function test_get_name_returns_string(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_name' );

		$this->assertTrue(
			$method->hasReturnType(),
			'get_name should have return type'
		);
		$this->assertSame(
			'string',
			$method->getReturnType()->getName(),
			'get_name should return string'
		);
	}

	/**
	 * Test get_title returns string
	 */
	public function test_get_title_returns_string(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_title' );

		$this->assertTrue(
			$method->hasReturnType(),
			'get_title should have return type'
		);
		$this->assertSame(
			'string',
			$method->getReturnType()->getName(),
			'get_title should return string'
		);
	}

	/**
	 * Test get_icon returns string
	 */
	public function test_get_icon_returns_string(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_icon' );

		$this->assertTrue(
			$method->hasReturnType(),
			'get_icon should have return type'
		);
		$this->assertSame(
			'string',
			$method->getReturnType()->getName(),
			'get_icon should return string'
		);
	}

	/**
	 * Test get_style_depends returns array
	 */
	public function test_get_style_depends_returns_array(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_style_depends' );

		$this->assertTrue(
			$method->hasReturnType(),
			'get_style_depends should have return type'
		);
		$this->assertSame(
			'array',
			$method->getReturnType()->getName(),
			'get_style_depends should return array'
		);
	}

	/**
	 * Test register_controls is protected
	 */
	public function test_register_controls_is_protected(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'register_controls' );

		$this->assertTrue(
			$method->isProtected(),
			'register_controls should be protected'
		);
	}

	/**
	 * Test render is protected
	 */
	public function test_render_is_protected(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'render' );

		$this->assertTrue(
			$method->isProtected(),
			'render should be protected'
		);
	}

	/**
	 * Test render returns void
	 */
	public function test_render_returns_void(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'render' );

		$this->assertTrue(
			$method->hasReturnType(),
			'render should have return type'
		);
		$this->assertSame(
			'void',
			$method->getReturnType()->getName(),
			'render should return void'
		);
	}

	/**
	 * Test has private register_content_controls method
	 */
	public function test_has_register_content_controls_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertTrue(
			$reflection->hasMethod( 'register_content_controls' ),
			'Widget should have register_content_controls method'
		);

		$method = $reflection->getMethod( 'register_content_controls' );
		$this->assertTrue(
			$method->isPrivate(),
			'register_content_controls should be private'
		);
	}

	/**
	 * Test has private register_style_controls method
	 */
	public function test_has_register_style_controls_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertTrue(
			$reflection->hasMethod( 'register_style_controls' ),
			'Widget should have register_style_controls method'
		);

		$method = $reflection->getMethod( 'register_style_controls' );
		$this->assertTrue(
			$method->isPrivate(),
			'register_style_controls should be private'
		);
	}

	/**
	 * Test class namespace is correct
	 */
	public function test_class_namespace(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertSame(
			'Soma\Elementor\Widgets',
			$reflection->getNamespaceName(),
			'Widget should be in Soma\Elementor\Widgets namespace'
		);
	}

	/**
	 * Test class is not abstract
	 */
	public function test_class_is_not_abstract(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertFalse(
			$reflection->isAbstract(),
			'Documents widget should not be abstract'
		);
	}

	/**
	 * Test class is not final
	 */
	public function test_class_is_not_final(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertFalse(
			$reflection->isFinal(),
			'Documents widget should not be final to allow extension'
		);
	}
}
