<?php
/**
 * StockData Unit Tests
 *
 * Tests the StockData class that fetches and caches stock market data
 * from Yahoo Finance API.
 *
 * @package Soma
 * @subpackage Tests\Unit\Admin
 */

namespace Soma\Tests\Unit\Admin;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Test StockData class structure and methods
 *
 * @group unit
 * @group admin
 * @group stock-data
 */
class StockDataTest extends TestCase {

	/**
	 * The StockData class name
	 *
	 * @var string
	 */
	private string $class_name = \Soma\Admin\StockData::class;

	/**
	 * Test class exists
	 */
	public function test_class_exists(): void {
		$this->assertTrue( class_exists( $this->class_name ) );
	}

	/**
	 * Test singleton pattern - instance method exists
	 */
	public function test_has_instance_method(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasMethod( 'instance' ) );
	}

	/**
	 * Test singleton pattern - instance method is static
	 */
	public function test_instance_method_is_static(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$method     = $reflection->getMethod( 'instance' );
		$this->assertTrue( $method->isStatic() );
	}

	/**
	 * Test singleton pattern - instance method is public
	 */
	public function test_instance_method_is_public(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$method     = $reflection->getMethod( 'instance' );
		$this->assertTrue( $method->isPublic() );
	}

	/**
	 * Test constructor is private (singleton pattern)
	 */
	public function test_constructor_is_private(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$constructor = $reflection->getConstructor();
		$this->assertTrue( $constructor->isPrivate() );
	}

	/**
	 * Test clone method is private (singleton pattern)
	 */
	public function test_clone_is_private(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$method     = $reflection->getMethod( '__clone' );
		$this->assertTrue( $method->isPrivate() );
	}

	/**
	 * Test has get_stock_data method
	 */
	public function test_has_get_stock_data_method(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasMethod( 'get_stock_data' ) );
	}

	/**
	 * Test get_stock_data is static
	 */
	public function test_get_stock_data_is_static(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$method     = $reflection->getMethod( 'get_stock_data' );
		$this->assertTrue( $method->isStatic() );
	}

	/**
	 * Test has fetch_stock_data method
	 */
	public function test_has_fetch_stock_data_method(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasMethod( 'fetch_stock_data' ) );
	}

	/**
	 * Test has custom_cron_schedules method
	 */
	public function test_has_custom_cron_schedules_method(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasMethod( 'custom_cron_schedules' ) );
	}

	/**
	 * Test has init method
	 */
	public function test_has_init_method(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasMethod( 'init' ) );
	}

	/**
	 * Test init method is private
	 */
	public function test_init_is_private(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$method     = $reflection->getMethod( 'init' );
		$this->assertTrue( $method->isPrivate() );
	}

	/**
	 * Test has symbol property
	 */
	public function test_has_symbol_property(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasProperty( 'symbol' ) );
	}

	/**
	 * Test symbol property default value.
	 *
	 * Note: In PHP 8.1+, setAccessible() is no longer needed for ReflectionProperty.
	 */
	public function test_symbol_default_value(): void {
		$reflection = new ReflectionClass( $this->class_name );

		// Get default value from property definition.
		$defaults = $reflection->getDefaultProperties();
		$this->assertSame( 'SOMA21.MX', $defaults['symbol'] );
	}

	/**
	 * Test has api_endpoint property
	 */
	public function test_has_api_endpoint_property(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasProperty( 'api_endpoint' ) );
	}

	/**
	 * Test api_endpoint uses Yahoo Finance RapidAPI
	 */
	public function test_api_endpoint_is_yahoo_finance(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$defaults   = $reflection->getDefaultProperties();
		$this->assertStringContainsString( 'yahoo-finance', $defaults['api_endpoint'] );
		$this->assertStringContainsString( 'rapidapi', $defaults['api_endpoint'] );
	}

	/**
	 * Test has api_key property
	 */
	public function test_has_api_key_property(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasProperty( 'api_key' ) );
	}

	/**
	 * Test has api_host property
	 */
	public function test_has_api_host_property(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$this->assertTrue( $reflection->hasProperty( 'api_host' ) );
	}

	/**
	 * Test api_host matches endpoint domain
	 */
	public function test_api_host_matches_endpoint(): void {
		$reflection = new ReflectionClass( $this->class_name );
		$defaults   = $reflection->getDefaultProperties();
		$this->assertStringContainsString( $defaults['api_host'], $defaults['api_endpoint'] );
	}
}
