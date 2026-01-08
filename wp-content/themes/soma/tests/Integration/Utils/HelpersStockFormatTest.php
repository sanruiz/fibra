<?php
/**
 * Stock Formatting Helper Functions Integration Tests
 *
 * Tests for stock formatting helper functions.
 * These are integration tests because soma_format_stock_datetime() uses wp_date(),
 * which requires WordPress to be loaded, while the price and change helpers use native PHP.
 *
 * @package Soma\Tests\Integration\Utils
 */

namespace Soma\Tests\Integration\Utils;

use WP_UnitTestCase;

/**
 * Test stock formatting helper functions
 *
 * @group integration
 * @group utils
 * @group helpers
 */
class HelpersStockFormatTest extends WP_UnitTestCase {

	/**
	 * Test soma_format_stock_price_simple() with MXN currency.
	 */
	public function test_format_stock_price_simple_mxn(): void {
		$result = soma_format_stock_price_simple( 45.67, 'MXN' );
		$this->assertSame( '$45.67', $result );
	}

	/**
	 * Test soma_format_stock_price_simple() with MXN default.
	 */
	public function test_format_stock_price_simple_default_mxn(): void {
		$result = soma_format_stock_price_simple( 100.00 );
		$this->assertSame( '$100.00', $result );
	}

	/**
	 * Test soma_format_stock_price_simple() with USD currency.
	 */
	public function test_format_stock_price_simple_usd(): void {
		$result = soma_format_stock_price_simple( 100.00, 'USD' );
		$this->assertSame( '$100.00', $result );
	}

	/**
	 * Test soma_format_stock_price_simple() with EUR currency.
	 */
	public function test_format_stock_price_simple_eur(): void {
		$result = soma_format_stock_price_simple( 85.50, 'EUR' );
		$this->assertSame( '€85.50', $result );
	}

	/**
	 * Test soma_format_stock_price_simple() with zero value.
	 */
	public function test_format_stock_price_simple_zero(): void {
		$result = soma_format_stock_price_simple( 0.00, 'MXN' );
		$this->assertSame( '$0.00', $result );
	}

	/**
	 * Test soma_format_stock_price_simple() with negative value.
	 */
	public function test_format_stock_price_simple_negative(): void {
		$result = soma_format_stock_price_simple( -45.67, 'USD' );
		$this->assertSame( '$-45.67', $result );
	}

	/**
	 * Test soma_format_stock_price_simple() with large number.
	 */
	public function test_format_stock_price_simple_large(): void {
		$result = soma_format_stock_price_simple( 123456.78, 'MXN' );
		$this->assertSame( '$123,456.78', $result );
	}

	/**
	 * Test soma_format_stock_price_simple() with unknown currency fallback.
	 */
	public function test_format_stock_price_simple_unknown_currency(): void {
		$result = soma_format_stock_price_simple( 50.00, 'GBP' );
		$this->assertSame( '$50.00', $result, 'Unknown currency should fallback to $' );
	}

	/**
	 * Test soma_format_stock_change_combined() with positive values.
	 */
	public function test_format_stock_change_combined_positive(): void {
		$result = soma_format_stock_change_combined( 1.23, 2.50 );
		$this->assertSame( '$ 1.23  2.50 %', $result );
	}

	/**
	 * Test soma_format_stock_change_combined() with negative values.
	 */
	public function test_format_stock_change_combined_negative(): void {
		$result = soma_format_stock_change_combined( -0.45, -1.25 );
		$this->assertSame( '$ -0.45  -1.25 %', $result );
	}

	/**
	 * Test soma_format_stock_change_combined() with zero values.
	 */
	public function test_format_stock_change_combined_zero(): void {
		$result = soma_format_stock_change_combined( 0.00, 0.00 );
		$this->assertSame( '$ 0.00  0.00 %', $result );
	}

	/**
	 * Test soma_format_stock_change_combined() with mixed values.
	 */
	public function test_format_stock_change_combined_mixed(): void {
		$result = soma_format_stock_change_combined( 5.67, -2.34 );
		$this->assertSame( '$ 5.67  -2.34 %', $result );
	}

	/**
	 * Test soma_format_stock_change_combined() with large values.
	 */
	public function test_format_stock_change_combined_large(): void {
		$result = soma_format_stock_change_combined( 1234.56, 789.12 );
		$this->assertSame( '$ 1,234.56  789.12 %', $result );
	}

	/**
	 * Test soma_format_stock_datetime() basic format.
	 */
	public function test_format_stock_datetime_basic(): void {
		// Use a specific timestamp: January 7, 2026 11:10:00 AM UTC.
		$timestamp = strtotime( '2026-01-07 11:10:00 UTC' );
		$result    = soma_format_stock_datetime( $timestamp );

		// Verify the result starts with "As of " prefix.
		$this->assertStringStartsWith( 'As of ', $result );

		// Verify format contains time (with AM/PM).
		$this->assertMatchesRegularExpression( '/\d{1,2}:\d{2} (AM|PM)/', $result );

		// Verify format contains timezone abbreviation.
		$this->assertMatchesRegularExpression( '/[A-Z]{3,5}/', $result );

		// Verify format contains date in n/j/Y format (no leading zeros).
		$this->assertMatchesRegularExpression( '/\d{1,2}\/\d{1,2}\/\d{4}/', $result );
	}

	/**
	 * Test soma_format_stock_datetime() with midnight timestamp.
	 */
	public function test_format_stock_datetime_midnight(): void {
		// January 1, 2026 12:00:00 AM UTC.
		$timestamp = strtotime( '2026-01-01 00:00:00 UTC' );
		$result    = soma_format_stock_datetime( $timestamp );

		$this->assertStringStartsWith( 'As of ', $result );
		$this->assertMatchesRegularExpression( '/12:\d{2} AM/', $result );
	}

	/**
	 * Test soma_format_stock_datetime() with noon timestamp.
	 */
	public function test_format_stock_datetime_noon(): void {
		// January 1, 2026 12:00:00 PM UTC.
		$timestamp = strtotime( '2026-01-01 12:00:00 UTC' );
		$result    = soma_format_stock_datetime( $timestamp );

		$this->assertStringStartsWith( 'As of ', $result );
		$this->assertMatchesRegularExpression( '/12:\d{2} PM/', $result );
	}

	/**
	 * Test soma_format_stock_datetime() format structure.
	 */
	public function test_format_stock_datetime_structure(): void {
		$timestamp = time();
		$result    = soma_format_stock_datetime( $timestamp );

		// Verify complete structure: "As of [time] [AM/PM] [TZ] [date]".
		$pattern = '/^As of \d{1,2}:\d{2} (AM|PM) [A-Z]{3,5} \d{1,2}\/\d{1,2}\/\d{4}$/';
		$this->assertMatchesRegularExpression(
			$pattern,
			$result,
			'Format should be: As of [h:i] [AM/PM] [TZ] [n/j/Y]'
		);
	}

	/**
	 * Test soma_format_stock_datetime() uses site timezone.
	 */
	public function test_format_stock_datetime_uses_site_timezone(): void {
		// Save current timezone.
		$original_timezone = get_option( 'timezone_string' );

		// Set timezone to America/Chicago (CST/CDT).
		update_option( 'timezone_string', 'America/Chicago' );

		// January 7, 2026 11:10:00 AM America/Chicago = 17:10:00 UTC.
		$timestamp = strtotime( '2026-01-07 17:10:00 UTC' );
		$result    = soma_format_stock_datetime( $timestamp );

		// Should show CST (Central Standard Time) or CDT depending on DST.
		$this->assertStringContainsString( 'CST', $result );

		// Restore original timezone.
		if ( $original_timezone ) {
			update_option( 'timezone_string', $original_timezone );
		}
	}
}
