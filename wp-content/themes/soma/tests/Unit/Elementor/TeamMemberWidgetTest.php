<?php
/**
 * TeamMember Widget Unit Tests
 *
 * Tests for TeamMember Elementor widget (singular profile view).
 *
 * @package Soma
 * @subpackage Tests\Unit\Elementor
 * @since 3.1.12
 */

namespace Soma\Tests\Unit\Elementor;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * TeamMember widget unit tests
 *
 * @group unit
 * @group elementor
 * @group widgets
 */
class TeamMemberWidgetTest extends TestCase {

	/**
	 * Widget class to test
	 *
	 * @var string
	 */
	private string $widget_class = \Soma\Elementor\Widgets\TeamMember::class;

	/**
	 * Test that class exists
	 */
	public function test_class_exists(): void {
		$this->assertTrue( class_exists( $this->widget_class ) );
	}

	/**
	 * Test that widget extends base widget class
	 */
	public function test_extends_widget_base(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$parent     = $reflection->getParentClass();

		$this->assertNotFalse( $parent, 'TeamMember widget should extend a base class' );
		$this->assertSame(
			'Soma\Elementor\Base\WidgetBase',
			$parent->getName(),
			'TeamMember widget should extend WidgetBase'
		);
	}

	/**
	 * Test that widget has required methods
	 */
	public function test_has_required_methods(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$methods    = array(
			'get_name',
			'get_title',
			'get_icon',
			'get_style_depends',
			'register_controls',
			'render',
		);

		foreach ( $methods as $method ) {
			$this->assertTrue(
				$reflection->hasMethod( $method ),
				"TeamMember widget should have {$method} method"
			);
		}
	}

	/**
	 * Test that get_name returns correct string
	 */
	public function test_get_name_returns_string(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_name' );

		$this->assertTrue( $method->isPublic(), 'get_name should be public' );
		$this->assertTrue( $method->hasReturnType(), 'get_name should have return type' );
		$this->assertSame( 'string', $method->getReturnType()->getName(), 'get_name should return string' );
	}

	/**
	 * Test that get_title returns correct string
	 */
	public function test_get_title_returns_string(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_title' );

		$this->assertTrue( $method->isPublic(), 'get_title should be public' );
		$this->assertTrue( $method->hasReturnType(), 'get_title should have return type' );
		$this->assertSame( 'string', $method->getReturnType()->getName(), 'get_title should return string' );
	}

	/**
	 * Test that get_icon returns correct string
	 */
	public function test_get_icon_returns_string(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_icon' );

		$this->assertTrue( $method->isPublic(), 'get_icon should be public' );
		$this->assertTrue( $method->hasReturnType(), 'get_icon should have return type' );
		$this->assertSame( 'string', $method->getReturnType()->getName(), 'get_icon should return string' );
	}

	/**
	 * Test that get_style_depends returns array
	 */
	public function test_get_style_depends_returns_array(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_style_depends' );

		$this->assertTrue( $method->isPublic(), 'get_style_depends should be public' );
		$this->assertTrue( $method->hasReturnType(), 'get_style_depends should have return type' );
		$this->assertSame( 'array', $method->getReturnType()->getName(), 'get_style_depends should return array' );
	}

	/**
	 * Test that register_controls method exists and is protected
	 */
	public function test_register_controls_is_protected(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'register_controls' );

		$this->assertTrue( $method->isProtected(), 'register_controls should be protected' );
	}

	/**
	 * Test that render method exists and is protected
	 */
	public function test_render_is_protected(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'render' );

		$this->assertTrue( $method->isProtected(), 'render should be protected' );
	}

	/**
	 * Test that get_team_members_options method exists
	 */
	public function test_has_get_team_members_options_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertTrue(
			$reflection->hasMethod( 'get_team_members_options' ),
			'TeamMember widget should have get_team_members_options method'
		);
	}

	/**
	 * Test that get_team_members_options is private
	 */
	public function test_get_team_members_options_is_private(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_team_members_options' );

		$this->assertTrue(
			$method->isPrivate(),
			'get_team_members_options should be private'
		);
	}

	/**
	 * Test that get_team_members_options returns array
	 */
	public function test_get_team_members_options_returns_array(): void {
		$reflection = new ReflectionClass( $this->widget_class );
		$method     = $reflection->getMethod( 'get_team_members_options' );

		$this->assertTrue(
			$method->hasReturnType(),
			'get_team_members_options should have return type'
		);
		$this->assertSame(
			'array',
			$method->getReturnType()->getName(),
			'get_team_members_options should return array'
		);
	}

	/**
	 * Test that register_content_controls method exists
	 */
	public function test_has_register_content_controls_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertTrue(
			$reflection->hasMethod( 'register_content_controls' ),
			'TeamMember widget should have register_content_controls method'
		);
	}

	/**
	 * Test that register_style_controls method exists
	 */
	public function test_has_register_style_controls_method(): void {
		$reflection = new ReflectionClass( $this->widget_class );

		$this->assertTrue(
			$reflection->hasMethod( 'register_style_controls' ),
			'TeamMember widget should have register_style_controls method'
		);
	}
}
