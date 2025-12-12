<?php
/**
 * NewsEndpoint Test
 *
 * @package Soma\Tests\Unit\API\Endpoints
 */

namespace Soma\Tests\Unit\API\Endpoints;

use PHPUnit\Framework\TestCase;
use Soma\API\Endpoints\NewsEndpoint;

/**
 * Test NewsEndpoint.
 */
class NewsEndpointTest extends TestCase {
	/**
	 * Test singleton pattern.
	 */
	public function test_singleton_returns_same_instance(): void {
		$instance1 = NewsEndpoint::instance();
		$instance2 = NewsEndpoint::instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertInstanceOf( NewsEndpoint::class, $instance1 );
	}

	/**
	 * Test namespace constant.
	 */
	public function test_namespace_constant_is_defined(): void {
		$reflection = new \ReflectionClass( NewsEndpoint::class );
		$this->assertTrue( $reflection->hasConstant( 'NAMESPACE' ) );
		$this->assertEquals( 'soma', $reflection->getConstant( 'NAMESPACE' ) );
	}

	/**
	 * Test route constant.
	 */
	public function test_route_constant_is_defined(): void {
		$reflection = new \ReflectionClass( NewsEndpoint::class );
		$this->assertTrue( $reflection->hasConstant( 'ROUTE' ) );
		$this->assertEquals( '/news', $reflection->getConstant( 'ROUTE' ) );
	}
}
