<?php
/**
 * Elementor Loader Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Loader;

/**
 * Test Elementor Loader integration
 *
 * @group integration
 * @group elementor
 */
class LoaderTest extends WP_UnitTestCase {

	/**
	 * Loader instance
	 *
	 * @var Loader
	 */
	private Loader $loader;

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		// Skip if Elementor not active.
		if ( ! $this->is_elementor_active() ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}

		$this->loader = Loader::instance();
	}

	/**
	 * Check if Elementor is active
	 *
	 * @return bool
	 */
	private function is_elementor_active(): bool {
		return did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' );
	}

	/**
	 * Test LoadableInterface implementation
	 */
	public function test_implements_loadable_interface(): void {
		$this->assertInstanceOf(
			\Soma\Core\Interfaces\LoadableInterface::class,
			$this->loader
		);
	}

	/**
	 * Test singleton pattern
	 */
	public function test_singleton_pattern(): void {
		$instance1 = Loader::instance();
		$instance2 = Loader::instance();

		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test loading priority
	 */
	public function test_get_priority(): void {
		$this->assertSame( 30, $this->loader->get_priority() );
	}

	/**
	 * Test should load
	 */
	public function test_should_load(): void {
		$this->assertTrue( $this->loader->should_load() );
	}

	/**
	 * Test widgets registry
	 */
	public function test_get_widgets(): void {
		$widgets = $this->loader->get_widgets();

		$this->assertIsArray( $widgets );
		$this->assertCount( 9, $widgets );

		$expected_widgets = [
			'Navbar',
			'Footer',
			'BusinessUnits',
			'Services',
			'TeamMembers',
			'NewsList',
			'Portfolio',
			'ContactForm',
			'StockPrice',
		];

		foreach ( $expected_widgets as $widget_name ) {
			$this->assertArrayHasKey( $widget_name, $widgets );
			$this->assertTrue( class_exists( $widgets[ $widget_name ] ) );
		}
	}

	/**
	 * Test specific widget check
	 */
	public function test_has_widget(): void {
		$this->assertTrue( $this->loader->has_widget( 'Navbar' ) );
		$this->assertTrue( $this->loader->has_widget( 'Footer' ) );
		$this->assertFalse( $this->loader->has_widget( 'NonExistent' ) );
	}

	/**
	 * Test Elementor category registration
	 */
	public function test_category_registered(): void {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor Plugin class not available' );
		}

		// Initialize loader to trigger hooks.
		$this->loader->init();

		// Trigger Elementor category registration.
		do_action( 'elementor/elements/categories_registered', \Elementor\Plugin::$instance->elements_manager );

		$categories = \Elementor\Plugin::$instance->elements_manager->get_categories();

		$this->assertArrayHasKey( 'soma', $categories );
		$this->assertSame( 'Soma', $categories['soma']['title'] );
	}

	/**
	 * Test widget styles registration
	 */
	public function test_widget_styles_registered(): void {
		global $wp_styles;

		// Initialize loader.
		$this->loader->init();

		// Trigger style registration.
		do_action( 'elementor/frontend/after_register_styles' );

		$expected_styles = [
			'soma-navbar',
			'soma-footer',
			'soma-business-units',
			'soma-services',
			'soma-team-members',
			'soma-news-list',
			'soma-portfolio',
			'soma-contact-form',
			'soma-stock-price',
		];

		foreach ( $expected_styles as $handle ) {
			$this->assertTrue(
				wp_style_is( $handle, 'registered' ),
				"Style '$handle' should be registered"
			);
		}
	}

	/**
	 * Test widget style files exist
	 */
	public function test_widget_style_files_exist(): void {
		$style_files = [
			'navbar.css',
			'footer.css',
			'business-units.css',
			'services.css',
			'team-members.css',
			'news-list.css',
			'portfolio.css',
			'contact-form.css',
			'stock-price.css',
		];

		$assets_dir = get_template_directory() . '/assets/css/widgets/';

		foreach ( $style_files as $file ) {
			$file_path = $assets_dir . $file;
			$this->assertFileExists( $file_path, "CSS file '$file' should exist" );
		}
	}

	/**
	 * Test widgets registration
	 */
	public function test_widgets_registered(): void {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor Plugin class not available' );
		}

		// Initialize loader.
		$this->loader->init();

		// Get initial widget count.
		$widgets_manager = \Elementor\Plugin::$instance->widgets_manager;
		$initial_count   = count( $widgets_manager->get_widget_types() );

		// Trigger widget registration.
		do_action( 'elementor/widgets/register', $widgets_manager );

		// Get new widget count.
		$new_count = count( $widgets_manager->get_widget_types() );

		// Should have registered 9 new widgets.
		$this->assertSame(
			9,
			$new_count - $initial_count,
			'Should register 9 Soma widgets'
		);
	}

	/**
	 * Test Elementor missing notice (when plugin not active)
	 *
	 * This test would need Elementor to be deactivated, so we skip it in normal runs
	 */
	public function test_elementor_missing_notice(): void {
		$this->markTestSkipped( 'Requires Elementor to be deactivated' );
	}
}
