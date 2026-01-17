<?php
/**
 * Team Members Post Type Test
 *
 * @package Soma\Tests\Unit\PostTypes
 */

namespace Soma\Tests\Unit\PostTypes;

use PHPUnit\Framework\TestCase;
use Soma\PostTypes\Types\TeamMembers;

/**
 * Test TeamMembers post type.
 */
class TeamMembersTest extends TestCase {
	/**
	 * Test singleton pattern.
	 */
	public function test_singleton_returns_same_instance(): void {
		$instance1 = TeamMembers::instance();
		$instance2 = TeamMembers::instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertInstanceOf( TeamMembers::class, $instance1 );
	}

	/**
	 * Test post type constant.
	 */
	public function test_post_type_constant_is_defined(): void {
		$reflection = new \ReflectionClass( TeamMembers::class );
		$this->assertTrue( $reflection->hasConstant( 'POST_TYPE' ) );
		$post_type = $reflection->getConstant( 'POST_TYPE' );
		$this->assertInstanceOf( \Soma\Core\Enums\PostType::class, $post_type );
		$this->assertEquals( 'team-members', $post_type->value );
	}

	/**
	 * Test that clone is prevented.
	 */
	public function test_clone_is_prevented(): void {
		$this->expectException( \Error::class );
		$instance = TeamMembers::instance();
		$clone    = clone $instance;
	}
}
