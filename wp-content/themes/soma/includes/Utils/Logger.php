<?php
/**
 * Logger Class
 *
 * PSR-3 compliant logging implementation.
 *
 * @package Soma
 * @subpackage Utils
 * @since 3.0.0
 */

namespace Soma\Utils;

use Soma\Utils\Enums\LogLevel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logger Class
 *
 * Singleton logger implementing PSR-3 compliant logging.
 * Logs to WordPress uploads directory with automatic rotation.
 *
 * @since 3.0.0
 */
class Logger {

	/**
	 * Singleton instance
	 *
	 * @var Logger|null
	 */
	private static ?Logger $instance = null;

	/**
	 * Log file path
	 *
	 * @var string
	 */
	private string $log_file;

	/**
	 * Maximum log file size in bytes (5MB)
	 *
	 * @var int
	 */
	private int $max_file_size = 5242880;

	/**
	 * Number of rotated log files to keep
	 *
	 * @var int
	 */
	private int $max_files = 5;

	/**
	 * Get singleton instance
	 *
	 * @return Logger
	 */
	public static function instance(): Logger {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {
		$upload_dir     = wp_upload_dir();
		$log_dir        = $upload_dir['basedir'] . '/soma-logs';
		$this->log_file = $log_dir . '/soma.log';

		// Create log directory if it doesn't exist.
		if ( ! file_exists( $log_dir ) ) {
			wp_mkdir_p( $log_dir );
		}
	}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization
	 *
	 * @throws \Exception Cannot unserialize singleton.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * System is unusable
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function emergency( string $message, array $context = array() ): void {
		$this->log( LogLevel::EMERGENCY, $message, $context );
	}

	/**
	 * Action must be taken immediately
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function alert( string $message, array $context = array() ): void {
		$this->log( LogLevel::ALERT, $message, $context );
	}

	/**
	 * Critical conditions
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function critical( string $message, array $context = array() ): void {
		$this->log( LogLevel::CRITICAL, $message, $context );
	}

	/**
	 * Runtime errors that do not require immediate action
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function error( string $message, array $context = array() ): void {
		$this->log( LogLevel::ERROR, $message, $context );
	}

	/**
	 * Exceptional occurrences that are not errors
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function warning( string $message, array $context = array() ): void {
		$this->log( LogLevel::WARNING, $message, $context );
	}

	/**
	 * Normal but significant events
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function notice( string $message, array $context = array() ): void {
		$this->log( LogLevel::NOTICE, $message, $context );
	}

	/**
	 * Interesting events
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function info( string $message, array $context = array() ): void {
		$this->log( LogLevel::INFO, $message, $context );
	}

	/**
	 * Detailed debug information
	 *
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function debug( string $message, array $context = array() ): void {
		$this->log( LogLevel::DEBUG, $message, $context );
	}

	/**
	 * Log with arbitrary level
	 *
	 * @param LogLevel             $level Log level.
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return void
	 */
	public function log( LogLevel $level, string $message, array $context = array() ): void {
		// Check if logging is enabled in production.
		if ( ! $this->should_log( $level ) ) {
			return;
		}

		// Rotate log file if needed.
		$this->rotate_if_needed();

		// Format log entry.
		$entry = $this->format_entry( $level, $message, $context );

		// Write to file.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$handle = fopen( $this->log_file, 'a' );
		if ( $handle ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
			fwrite( $handle, $entry . PHP_EOL );
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
			fclose( $handle );
		}

		// Also log to error_log for critical errors.
		if ( $level->severity() >= LogLevel::ERROR->severity() ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( sprintf( '[SOMA %s] %s', strtoupper( $level->value() ), $message ) );
		}
	}

	/**
	 * Check if we should log this level
	 *
	 * @param LogLevel $level Log level to check.
	 * @return bool
	 */
	private function should_log( LogLevel $level ): bool {
		// Always log in development.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return true;
		}

		// In production, only log WARNING and above.
		return $level->severity() >= LogLevel::WARNING->severity();
	}

	/**
	 * Format log entry
	 *
	 * @param LogLevel             $level Log level.
	 * @param string               $message Log message.
	 * @param array<string, mixed> $context Context data.
	 * @return string
	 */
	private function format_entry( LogLevel $level, string $message, array $context ): string {
		$timestamp = gmdate( 'Y-m-d H:i:s' );
		$level_str = strtoupper( $level->value() );

		// Interpolate context into message.
		$message = $this->interpolate( $message, $context );

		// Add context as JSON if present.
		$context_str = '';
		if ( ! empty( $context ) ) {
			$context_str = ' ' . wp_json_encode( $context, JSON_UNESCAPED_SLASHES );
		}

		return sprintf( '[%s] %s: %s%s', $timestamp, $level_str, $message, $context_str );
	}

	/**
	 * Interpolate context values into message placeholders
	 *
	 * @param string               $message Message with {placeholders}.
	 * @param array<string, mixed> $context Context data.
	 * @return string
	 */
	private function interpolate( string $message, array $context ): string {
		$replace = array();
		foreach ( $context as $key => $val ) {
			$replace[ '{' . $key . '}' ] = $this->stringify( $val );
		}
		return strtr( $message, $replace );
	}

	/**
	 * Convert value to string
	 *
	 * @param mixed $value Value to stringify.
	 * @return string
	 */
	private function stringify( $value ): string {
		if ( is_null( $value ) ) {
			return 'null';
		}
		if ( is_bool( $value ) ) {
			return $value ? 'true' : 'false';
		}
		if ( is_scalar( $value ) ) {
			return (string) $value;
		}
		if ( is_array( $value ) || is_object( $value ) ) {
			return wp_json_encode( $value, JSON_UNESCAPED_SLASHES );
		}
		return '[' . gettype( $value ) . ']';
	}

	/**
	 * Rotate log file if it exceeds max size
	 *
	 * @return void
	 */
	private function rotate_if_needed(): void {
		if ( ! file_exists( $this->log_file ) ) {
			return;
		}

		if ( filesize( $this->log_file ) < $this->max_file_size ) {
			return;
		}

		// Rotate existing files.
		for ( $i = $this->max_files - 1; $i > 0; $i-- ) {
			$old_file = $this->log_file . '.' . $i;
			$new_file = $this->log_file . '.' . ( $i + 1 );

			if ( file_exists( $old_file ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
				rename( $old_file, $new_file );
			}
		}

		// Move current log to .1.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
		rename( $this->log_file, $this->log_file . '.1' );
	}

	/**
	 * Get log file path
	 *
	 * @return string
	 */
	public function get_log_file(): string {
		return $this->log_file;
	}

	/**
	 * Clear all log files
	 *
	 * @return bool
	 */
	public function clear_logs(): bool {
		$cleared = false;

		// Remove main log file.
		if ( file_exists( $this->log_file ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			unlink( $this->log_file );
			$cleared = true;
		}

		// Remove rotated log files.
		for ( $i = 1; $i <= $this->max_files; $i++ ) {
			$file = $this->log_file . '.' . $i;
			if ( file_exists( $file ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
				unlink( $file );
				$cleared = true;
			}
		}

		return $cleared;
	}
}
