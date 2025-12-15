<?php
/**
 * Admin Module Loader
 *
 * @package Soma\Admin
 */

namespace Soma\Admin;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Loader class
 */
class Loader implements LoadableInterface {

	/**
	 * Singleton instance
	 *
	 * @var Loader|null
	 */
	private static ?Loader $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Loader
	 */
	public static function instance(): Loader {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization
	 *
	 * @throws \Exception When trying to unserialize.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize the component
	 */
	public function init(): void {
		// Load admin components.
		ThemeSettings::instance();
		StockData::instance();

		// Load test page (development/testing only).
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			PageBuilderTestPage::instance();
		}

		// Admin hooks.
		add_action( 'admin_menu', $this->remove_default_menus( ... ) );
		add_action( 'admin_enqueue_scripts', $this->admin_custom_scripts( ... ) );
		add_filter( 'use_block_editor_for_post', '__return_false', 10 );
		add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );

		// WP Multilang ACF integration.
		add_filter( 'wpm_acf_link_config', $this->multilang_acf_link_config( ... ) );
	}

	/**
	 * Get component loading priority
	 *
	 * @return int Priority (lower = earlier)
	 */
	public function get_priority(): int {
		return 40; // Load after most components.
	}

	/**
	 * Check if component should load
	 *
	 * @return bool True if in admin or doing ajax
	 */
	public function should_load(): bool {
		return is_admin() || wp_doing_ajax();
	}

	/**
	 * Remove default WordPress menus
	 */
	public function remove_default_menus(): void {
		remove_menu_page( 'edit.php' );          // Posts.
		remove_menu_page( 'edit-comments.php' ); // Comments.
	}

	/**
	 * Admin custom scripts and styles
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function admin_custom_scripts( string $hook ): void {
		?>
		<style>
			body.post-type-portfolio .acf-tooltip.acf-fc-popup.bottom a:not([data-layout="ProjectInfo"]):not([data-layout="Image"]):not([data-layout="Text"]):not([data-layout="VimeoPlayer"]):not([data-layout="ProjectContactInfo"]) {
				display: none !important;
			}
		</style>
		<?php
	}

	/**
	 * Configure WP Multilang for ACF link fields
	 *
	 * @return array Configuration array
	 */
	public function multilang_acf_link_config(): array {
		return array(
			'title' => array(),
			'url'   => array(),
		);
	}
}
