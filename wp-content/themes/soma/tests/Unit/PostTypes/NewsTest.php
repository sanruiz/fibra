<?php
/**
 * News Post Type Test
 *
 * @package Soma\Tests\Unit\PostTypes
 */

namespace Soma\Tests\Unit\PostTypes;

use PHPUnit\Framework\TestCase;
use Soma\PostTypes\Types\News;

/**
 * Test News post type.
 */
class NewsTest extends TestCase {
	/**
	 * Test singleton pattern.
	 */
	public function test_singleton_returns_same_instance(): void {
		$instance1 = News::instance();
		$instance2 = News::instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertInstanceOf( News::class, $instance1 );
	}

	/**
	 * Test post type constant.
	 */
	public function test_post_type_constant_is_defined(): void {
		$reflection = new \ReflectionClass( News::class );
		$this->assertTrue( $reflection->hasConstant( 'POST_TYPE' ) );
		$post_type = $reflection->getConstant( 'POST_TYPE' );
		$this->assertInstanceOf( \Soma\Core\Enums\PostType::class, $post_type );
		$this->assertEquals( 'news', $post_type->value );
	}

	/**
	 * Test that clone is prevented.
	 */
	public function test_clone_is_prevented(): void {
		$this->expectException( \Error::class );
		$instance = News::instance();
		$clone    = clone $instance;
	}
}
