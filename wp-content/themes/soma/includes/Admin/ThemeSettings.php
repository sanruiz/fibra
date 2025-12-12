<?php
/**
 * Theme Settings (ACF Options Pages)
 *
 * @package Soma\Admin
 */

namespace Soma\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Settings class
 */
class ThemeSettings {

	/**
	 * Singleton instance
	 *
	 * @var ThemeSettings|null
	 */
	private static ?ThemeSettings $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return ThemeSettings
	 */
	public static function instance(): ThemeSettings {
		if ( self::$instance === null ) {
			self::$instance = new self();
			self::$instance->init();
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
	 * Initialize theme settings
	 */
	private function init(): void {
		add_action( 'init', $this->register_options_pages( ... ) );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Register ACF Options Pages
	 */
	public function register_options_pages(): void {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		// Main options page
		acf_add_options_page(
			array(
				'page_title' => __( 'Theme Settings', 'soma' ),
				'menu_title' => __( 'Theme Settings', 'soma' ),
				'menu_slug'  => 'theme-settings',
				'capability' => 'edit_posts',
				'redirect'   => true,
			)
		);

		// Header subpage
		acf_add_options_sub_page(
			array(
				'page_title'  => __( 'Header', 'soma' ),
				'menu_title'  => __( 'Header', 'soma' ),
				'parent_slug' => 'theme-settings',
			)
		);

		// Footer subpage
		acf_add_options_sub_page(
			array(
				'page_title'  => __( 'Footer', 'soma' ),
				'menu_title'  => __( 'Footer', 'soma' ),
				'parent_slug' => 'theme-settings',
			)
		);

		// 404 Error subpage
		acf_add_options_sub_page(
			array(
				'page_title'  => __( '404 Error', 'soma' ),
				'menu_title'  => __( '404 Error', 'soma' ),
				'parent_slug' => 'theme-settings',
			)
		);
	}
}
