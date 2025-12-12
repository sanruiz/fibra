<?php
/**
 * Log Level Enum
 *
 * PSR-3 compliant log level definitions.
 *
 * @package Soma
 * @subpackage Utils\Enums
 * @since 3.0.0
 */

namespace Soma\Utils\Enums;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Log Level Enum
 *
 * PSR-3 compliant logging levels.
 * Ordered from highest severity (EMERGENCY) to lowest (DEBUG).
 *
 * @since 3.0.0
 * @link https://www.php-fig.org/psr/psr-3/
 */
enum LogLevel: string {
	case EMERGENCY = 'emergency'; // System is unusable.
	case ALERT     = 'alert';     // Action must be taken immediately.
	case CRITICAL  = 'critical';  // Critical conditions.
	case ERROR     = 'error';     // Runtime errors that do not require immediate action.
	case WARNING   = 'warning';   // Exceptional occurrences that are not errors.
	case NOTICE    = 'notice';    // Normal but significant events.
	case INFO      = 'info';      // Interesting events.
	case DEBUG     = 'debug';     // Detailed debug information.

	/**
	 * Get the log level value
	 *
	 * @return string
	 */
	public function value(): string {
		return $this->value;
	}

	/**
	 * Get human-readable label for the log level
	 *
	 * @return string
	 */
	public function label(): string {
		return match ( $this ) {
			self::EMERGENCY => __( 'Emergency', 'soma' ),
			self::ALERT     => __( 'Alert', 'soma' ),
			self::CRITICAL  => __( 'Critical', 'soma' ),
			self::ERROR     => __( 'Error', 'soma' ),
			self::WARNING   => __( 'Warning', 'soma' ),
			self::NOTICE    => __( 'Notice', 'soma' ),
			self::INFO      => __( 'Info', 'soma' ),
			self::DEBUG     => __( 'Debug', 'soma' ),
		};
	}

	/**
	 * Get severity weight (higher = more severe)
	 *
	 * @return int
	 */
	public function severity(): int {
		return match ( $this ) {
			self::EMERGENCY => 800,
			self::ALERT     => 700,
			self::CRITICAL  => 600,
			self::ERROR     => 500,
			self::WARNING   => 400,
			self::NOTICE    => 300,
			self::INFO      => 200,
			self::DEBUG     => 100,
		};
	}

	/**
	 * Check if this level is more severe than another
	 *
	 * @param self $other Other log level.
	 * @return bool
	 */
	public function isMoreSevereThan( self $other ): bool {
		return $this->severity() > $other->severity();
	}

	/**
	 * Get all log level values
	 *
	 * @return array<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}

	/**
	 * Create from string value
	 *
	 * @param string $value Log level value.
	 * @return self|null
	 */
	public static function tryFrom( string $value ): ?self {
		foreach ( self::cases() as $case ) {
			if ( $case->value === $value ) {
				return $case;
			}
		}
		return null;
	}
}
