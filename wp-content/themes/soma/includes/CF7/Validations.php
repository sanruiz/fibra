<?php
/**
 * Contact Form 7 Custom Validations
 *
 * Provides custom validation rules for Contact Form 7 forms.
 *
 * @package Soma\CF7
 * @since 3.0.0
 */

namespace Soma\CF7;

use WPCF7_Submission;
use WPCF7_Validation;
use WPCF7_FormTag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Validations
 *
 * Singleton class for CF7 validation rules.
 *
 * @package Soma\CF7
 */
class Validations {

	/**
	 * Singleton instance
	 *
	 * @var Validations|null
	 */
	private static ?Validations $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Validations The singleton instance.
	 */
	public static function instance(): Validations {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Prevent cloning of the instance.
	 *
	 * @return void
	 */
	private function __clone() {
		// Singleton pattern - no cloning.
	}

	/**
	 * Prevent unserialization of the instance.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Singleton pattern - no unserialization.
	}

	/**
	 * Initialize validation filters.
	 *
	 * @return void
	 */
	private function init(): void {
		add_filter( 'wpcf7_validate_email*', $this->validate_email( ... ), 20, 2 );
		add_filter( 'wpcf7_validate_text*', $this->validate_text( ... ), 30, 2 );
		add_action( 'wpcf7_display_message', $this->custom_validation_message( ... ) );
	}

	/**
	 * Validate name field (letters and spaces only).
	 *
	 * @param string $value The string to validate.
	 * @return bool True if valid, false otherwise.
	 */
	private function validate_name( string $value ): bool {
		return ctype_alpha( str_replace( ' ', '', $value ) );
	}

	/**
	 * Validate email format.
	 *
	 * @param string $value The email to validate.
	 * @return bool True if valid, false otherwise.
	 */
	private function validate_email_format( string $value ): bool {
		if ( empty( $value ) ) {
			return false;
		}
		return (bool) preg_match( '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $value );
	}

	/**
	 * Validate text fields (name validation).
	 *
	 * @param WPCF7_Validation $result The validation result object.
	 * @param WPCF7_FormTag    $tag    The form tag object.
	 * @return WPCF7_Validation
	 */
	public function validate_text( $result, $tag ) {
		if ( 'fname' === $tag->name ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- CF7 handles nonce verification.
			$txtname = isset( $_POST['fname'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['fname'] ) ) ) : '';
			if ( ! $this->validate_name( $txtname ) ) {
				$result->invalidate( $tag, __( 'Must contain letters only', 'soma' ) );
			}
		}
		return $result;
	}

	/**
	 * Validate email fields.
	 *
	 * @param WPCF7_Validation $result The validation result object.
	 * @param WPCF7_FormTag    $tag    The form tag object.
	 * @return WPCF7_Validation
	 */
	public function validate_email( $result, $tag ) {
		if ( \in_array( $tag->name, array( 'email', 'newsletter-email' ), true ) ) {
			$email = '';
			// phpcs:disable WordPress.Security.NonceVerification.Missing -- CF7 handles nonce verification.
			if ( isset( $_POST['email'] ) ) {
				$email = trim( sanitize_email( wp_unslash( $_POST['email'] ) ) );
			} elseif ( isset( $_POST['newsletter-email'] ) ) {
				$email = trim( sanitize_email( wp_unslash( $_POST['newsletter-email'] ) ) );
			}
			// phpcs:enable WordPress.Security.NonceVerification.Missing

			if ( ! $this->validate_email_format( $email ) ) {
				$result->invalidate( $tag, __( 'Invalid email address', 'soma' ) );
			}
		}
		return $result;
	}

	/**
	 * Custom validation message display.
	 *
	 * @param string $message The original message.
	 * @return string Modified message.
	 */
	public function custom_validation_message( $message ): string {
		$submission = WPCF7_Submission::get_instance();

		if ( ! $submission ) {
			return $message;
		}

		$posted_data = $submission->get_posted_data();

		if ( ! isset( $posted_data['fname'], $posted_data['email'], $posted_data['message'] ) ) {
			return $message;
		}

		// Check for empty required fields.
		if ( empty( $posted_data['fname'] ) || empty( $posted_data['email'] ) || empty( $posted_data['message'] ) ) {
			return __( '* Required fields missing', 'soma' );
		}

		// Validate name.
		if ( ! $this->validate_name( $posted_data['fname'] ) ) {
			return __( 'Name must contain letters only', 'soma' );
		}

		// Validate email.
		if ( ! $this->validate_email_format( $posted_data['email'] ) ) {
			return __( 'Invalid email address', 'soma' );
		}

		return $message;
	}
}
