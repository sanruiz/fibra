<?php
/**
 * CF7 Loader Test
 *
 * @package Soma\Tests\Unit\CF7
 */

namespace Soma\Tests\Unit\CF7;

use PHPUnit\Framework\TestCase;
use Soma\CF7\Loader;
use Soma\Core\Interfaces\LoadableInterface;

/**
 * Test CF7 Loader.
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
	public function test_get_priority_returns_30(): void {
		$instance = Loader::instance();
		$this->assertEquals( 30, $instance->get_priority() );
	}

	/**
	 * Test should_load when WPCF7 not available.
	 */
	public function test_should_load_returns_false_when_wpcf7_missing(): void {
		$instance = Loader::instance();
		// By default in tests, WPCF7 classes won't exist.
		$this->assertFalse( $instance->should_load() );
	}
}
