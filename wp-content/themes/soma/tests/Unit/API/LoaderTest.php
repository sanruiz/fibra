<?php
/**
 * API Loader Test
 *
 * @package Soma\Tests\Unit\API
 */

namespace Soma\Tests\Unit\API;

use PHPUnit\Framework\TestCase;
use Soma\API\Loader;
use Soma\Core\Interfaces\LoadableInterface;

/**
 * Test API Loader.
 */
class LoaderTest extends TestCase {
	/**
	 * Test singleton pattern.
	 */
	public function test_singleton_returns_same_instance(): void {
		$instance1 = Loader::instance();
		$instance2 = Loader::instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertInstanceOf( Loader::class, $instance1 );
	}

	/**
	 * Test implements LoadableInterface.
	 */
	public function test_implements_loadable_interface(): void {
		$instance = Loader::instance();
		$this->assertInstanceOf( LoadableInterface::class, $instance );
	}

	/**
	 * Test get_priority returns correct value.
	 */
	public function test_get_priority_returns_35(): void {
		$instance = Loader::instance();
		$this->assertEquals( 35, $instance->get_priority() );
	}

	/**
	 * Test should_load returns true.
	 */
	public function test_should_load_returns_true(): void {
		$instance = Loader::instance();
		$this->assertTrue( $instance->should_load() );
	}
}
