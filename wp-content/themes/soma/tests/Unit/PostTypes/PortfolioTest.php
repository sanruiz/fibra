<?php
/**
 * Portfolio Post Type Test
 *
 * @package Soma\Tests\Unit\PostTypes
 */

namespace Soma\Tests\Unit\PostTypes;

use PHPUnit\Framework\TestCase;
use Soma\PostTypes\Types\Portfolio;

/**
 * Test Portfolio post type.
 */
class PortfolioTest extends TestCase {
	/**
	 * Test singleton pattern.
	 */
	public function test_singleton_returns_same_instance(): void {
		$instance1 = Portfolio::instance();
		$instance2 = Portfolio::instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertInstanceOf( Portfolio::class, $instance1 );
	}

	/**
	 * Test post type constant.
	 */
	public function test_post_type_constant_is_defined(): void {
		$reflection = new \ReflectionClass( Portfolio::class );
		$this->assertTrue( $reflection->hasConstant( 'POST_TYPE' ) );
		$this->assertEquals( 'portfolio', $reflection->getConstant( 'POST_TYPE' ) );
	}

	/**
	 * Test that clone is prevented.
	 */
	public function test_clone_is_prevented(): void {
		$this->expectException( \Error::class );
		$instance = Portfolio::instance();
		$clone = clone $instance;
	}
}
