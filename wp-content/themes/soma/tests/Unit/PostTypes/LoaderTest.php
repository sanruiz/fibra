<?php
/**
 * PostTypes Loader Test
 *
 * @package Soma\Tests\Unit\PostTypes
 */

namespace Soma\Tests\Unit\PostTypes;

use PHPUnit\Framework\TestCase;
use Soma\PostTypes\Loader;
use Soma\Core\Interfaces\LoadableInterface;

/**
 * Test PostTypes Loader.
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
	public function test_get_priority_returns_20(): void {
		$instance = Loader::instance();
		$this->assertEquals( 20, $instance->get_priority() );
	}

	/**
	 * Test should_load returns true.
	 */
	public function test_should_load_returns_true(): void {
		$instance = Loader::instance();
		$this->assertTrue( $instance->should_load() );
	}
}
