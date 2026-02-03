<?php
/**
 * Helpers Unit Test
 *
 * @package Soma\Tests\Unit\Utils
 */

namespace Soma\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;

/**
 * Test Helper functions.
 *
 * @group unit
 * @group utils
 * @group helpers
 */
class HelpersTest extends TestCase {

	/**
	 * Test soma_get_portfolio_project_info function exists.
	 */
	public function test_soma_get_portfolio_project_info_function_exists(): void {
		$this->assertTrue(
			function_exists( 'soma_get_portfolio_project_info' ),
			'Function soma_get_portfolio_project_info should exist'
		);
	}

	/**
	 * Test soma_get_portfolio_project_info returns array.
	 */
	public function test_soma_get_portfolio_project_info_returns_array(): void {
		$result = soma_get_portfolio_project_info();
		$this->assertIsArray( $result );
	}

	/**
	 * Test soma_get_portfolio_project_info returns expected keys.
	 */
	public function test_soma_get_portfolio_project_info_returns_expected_keys(): void {
		$result = soma_get_portfolio_project_info();

		$this->assertArrayHasKey( 'city', $result );
		$this->assertArrayHasKey( 'year', $result );
	}

	/**
	 * Test soma_get_portfolio_project_info returns string values.
	 */
	public function test_soma_get_portfolio_project_info_returns_string_values(): void {
		$result = soma_get_portfolio_project_info();

		$this->assertIsString( $result['city'] );
		$this->assertIsString( $result['year'] );
	}

	/**
	 * Test soma_get_portfolio_project_info returns empty strings as defaults.
	 */
	public function test_soma_get_portfolio_project_info_returns_empty_defaults(): void {
		// When no post context and ACF not available, should return empty strings.
		$result = soma_get_portfolio_project_info( 0 );

		$this->assertSame( '', $result['city'] );
		$this->assertSame( '', $result['year'] );
	}

	/**
	 * Test soma_get_portfolio_project_info accepts null post_id.
	 */
	public function test_soma_get_portfolio_project_info_accepts_null_post_id(): void {
		// Should not throw when passing null (uses current post).
		$result = soma_get_portfolio_project_info( null );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'city', $result );
		$this->assertArrayHasKey( 'year', $result );
	}

	/**
	 * Test soma_get_portfolio_project_info accepts integer post_id.
	 */
	public function test_soma_get_portfolio_project_info_accepts_integer_post_id(): void {
		// Should not throw when passing integer.
		$result = soma_get_portfolio_project_info( 999999 );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'city', $result );
		$this->assertArrayHasKey( 'year', $result );
	}
}
