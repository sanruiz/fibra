<?php
/**
 * Logger Integration Tests
 *
 * @package Soma\Tests\Integration\Utils
 */

namespace Soma\Tests\Integration\Utils;

use Soma\Utils\Logger;
use WP_UnitTestCase;

/**
 * Test the Logger directory protection and log file naming.
 *
 * @group integration
 * @group utils
 * @group logger
 */
class LoggerTest extends WP_UnitTestCase {

	/**
	 * Set up test fixtures.
	 */
	public function setUp(): void {
		parent::setUp();

		$this->reset_singleton();
	}

	/**
	 * Tear down test fixtures.
	 */
	public function tearDown(): void {
		$this->reset_singleton();

		parent::tearDown();
	}

	/**
	 * Reset the Logger singleton for a clean test state.
	 */
	private function reset_singleton(): void {
		$reflection = new \ReflectionClass( Logger::class );
		$property   = $reflection->getProperty( 'instance' );
		$property->setValue( null, null );
	}

	/**
	 * Test the log directory is created.
	 */
	public function test_log_directory_is_created(): void {
		Logger::instance();

		$log_dir = dirname( Logger::instance()->get_log_file() );

		$this->assertDirectoryExists( $log_dir );
	}

	/**
	 * Test the log directory contains a `.htaccess` file denying access.
	 */
	public function test_log_directory_has_htaccess_denying_access(): void {
		Logger::instance();

		$log_dir       = dirname( Logger::instance()->get_log_file() );
		$htaccess_file = $log_dir . '/.htaccess';

		$this->assertFileExists( $htaccess_file );

		$content = (string) file_get_contents( $htaccess_file );

		$this->assertStringContainsString( 'Require all denied', $content );
		$this->assertStringContainsString( 'Deny from all', $content );
	}

	/**
	 * Test the log directory contains an empty `index.php` guard.
	 */
	public function test_log_directory_has_index_php_guard(): void {
		Logger::instance();

		$log_dir    = dirname( Logger::instance()->get_log_file() );
		$index_file = $log_dir . '/index.php';

		$this->assertFileExists( $index_file );
	}

	/**
	 * Test the log filename is not the predictable default `soma.log`.
	 */
	public function test_log_filename_is_not_predictable(): void {
		$log_file = Logger::instance()->get_log_file();

		$this->assertNotSame( 'soma.log', basename( $log_file ) );
		$this->assertMatchesRegularExpression( '/^soma-[0-9a-f]{16}\.log$/', basename( $log_file ) );
	}

	/**
	 * Test the log filename stays stable across singleton re-creation.
	 */
	public function test_log_filename_is_stable_across_instances(): void {
		$first_log_file = Logger::instance()->get_log_file();

		$this->reset_singleton();

		$second_log_file = Logger::instance()->get_log_file();

		$this->assertSame( $first_log_file, $second_log_file );
	}
}
