<?php
/**
 * All Widgets Integration Tests
 *
 * Tests common functionality across all 8 Elementor widgets
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;

/**
 * Test all widgets common functionality
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class AllWidgetsTest extends WP_UnitTestCase {

	/**
	 * Widget classes to test
	 *
	 * @var array<string, string>
	 */
	private array $widget_classes = [
		'Navbar'        => \Soma\Elementor\Widgets\Navbar::class,
		'Footer'        => \Soma\Elementor\Widgets\Footer::class,
		'BusinessUnits' => \Soma\Elementor\Widgets\BusinessUnits::class,
		'Services'      => \Soma\Elementor\Widgets\Services::class,
		'TeamMembers'   => \Soma\Elementor\Widgets\TeamMembers::class,
		'NewsList'      => \Soma\Elementor\Widgets\NewsList::class,
		'Portfolio'     => \Soma\Elementor\Widgets\Portfolio::class,
		'ContactForm'   => \Soma\Elementor\Widgets\ContactForm::class,
	];

	/**
	 * Expected style handles for each widget
	 *
	 * @var array<string, string>
	 */
	private array $widget_styles = [
		'Navbar'        => 'soma-navbar',
		'Footer'        => 'soma-footer',
		'BusinessUnits' => 'soma-business-units',
		'Services'      => 'soma-services',
		'TeamMembers'   => 'soma-team-members',
		'NewsList'      => 'soma-news-list',
		'Portfolio'     => 'soma-portfolio',
		'ContactForm'   => 'soma-contact-form',
	];

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}
	}

	/**
	 * Test all widgets extend WidgetBase
	 */
	public function test_all_widgets_extend_widget_base(): void {
		foreach ( $this->widget_classes as $name => $class ) {
			$widget = new $class();

			$this->assertInstanceOf(
				\Soma\Elementor\Base\WidgetBase::class,
				$widget,
				"Widget '$name' should extend WidgetBase"
			);
		}
	}

	/**
	 * Test all widgets are in soma category
	 */
	public function test_all_widgets_in_soma_category(): void {
		foreach ( $this->widget_classes as $name => $class ) {
			$widget     = new $class();
			$categories = $widget->get_categories();

			$this->assertIsArray( $categories, "Widget '$name' should return array of categories" );
			$this->assertContains( 'soma', $categories, "Widget '$name' should be in 'soma' category" );
		}
	}

	/**
	 * Test all widgets have unique names
	 */
	public function test_all_widgets_have_unique_names(): void {
		$names = [];

		foreach ( $this->widget_classes as $widget_name => $class ) {
			$widget = new $class();
			$name   = $widget->get_name();

			$this->assertNotContains(
				$name,
				$names,
				"Widget '$widget_name' name '$name' should be unique"
			);

			$names[] = $name;
		}
	}

	/**
	 * Test all widgets have titles
	 */
	public function test_all_widgets_have_titles(): void {
		foreach ( $this->widget_classes as $name => $class ) {
			$widget = new $class();
			$title  = $widget->get_title();

			$this->assertNotEmpty( $title, "Widget '$name' should have a title" );
			$this->assertIsString( $title, "Widget '$name' title should be a string" );
		}
	}

	/**
	 * Test all widgets have icons
	 */
	public function test_all_widgets_have_icons(): void {
		foreach ( $this->widget_classes as $name => $class ) {
			$widget = new $class();
			$icon   = $widget->get_icon();

			$this->assertNotEmpty( $icon, "Widget '$name' should have an icon" );
			$this->assertStringStartsWith( 'eicon-', $icon, "Widget '$name' icon should be an Elementor icon" );
		}
	}

	/**
	 * Test all widgets have style dependencies
	 */
	public function test_all_widgets_have_style_dependencies(): void {
		foreach ( $this->widget_classes as $name => $class ) {
			$widget         = new $class();
			$style_depends  = $widget->get_style_depends();
			$expected_style = $this->widget_styles[ $name ];

			$this->assertIsArray( $style_depends, "Widget '$name' should return array of styles" );
			$this->assertContains(
				$expected_style,
				$style_depends,
				"Widget '$name' should depend on '$expected_style' style"
			);
		}
	}

	/**
	 * Test all widgets have controls
	 */
	public function test_all_widgets_have_controls(): void {
		foreach ( $this->widget_classes as $name => $class ) {
			$widget = new $class();

			// Use reflection to call protected register_controls method
			$reflection = new \ReflectionClass( $widget );
			$method = $reflection->getMethod( 'register_controls' );
			$method->invoke( $widget );

			$controls = $widget->get_controls();

			$this->assertNotEmpty(
				$controls,
				"Widget '$name' should have controls registered"
			);
		}
	}

	/**
	 * Test all widgets render without PHP errors
	 */
	public function test_all_widgets_render_without_errors(): void {
		foreach ( $this->widget_classes as $name => $class ) {
			$widget = new $class();

			// Use reflection to call protected render method
			$reflection = new \ReflectionClass( $widget );
			$method = $reflection->getMethod( 'render' );

			ob_start();
			$method->invoke( $widget );
			$output = ob_get_clean();

			$this->assertIsString( $output, "Widget '$name' should render string output" );

			// Widgets should render something (even if empty state).
			$this->assertNotFalse( $output, "Widget '$name' should not fail to render" );
		}
	}

	/**
	 * Test all widget CSS files exist
	 */
	public function test_all_widget_css_files_exist(): void {
		$css_files = [
			'Navbar'        => 'navbar.css',
			'Footer'        => 'footer.css',
			'BusinessUnits' => 'business-units.css',
			'Services'      => 'services.css',
			'TeamMembers'   => 'team-members.css',
			'NewsList'      => 'news-list.css',
			'Portfolio'     => 'portfolio.css',
			'ContactForm'   => 'contact-form.css',
		];

		$assets_dir = get_template_directory() . '/assets/css/widgets/';

		foreach ( $css_files as $widget_name => $file ) {
			$file_path = $assets_dir . $file;

			$this->assertFileExists(
				$file_path,
				"CSS file for widget '$widget_name' should exist at '$file_path'"
			);

			// Check file is not empty.
			$this->assertGreaterThan(
				0,
				filesize( $file_path ),
				"CSS file for widget '$widget_name' should not be empty"
			);
		}
	}

	/**
	 * Test widgets render expected HTML structure
	 */
	public function test_widgets_render_expected_structure(): void {
		$expected_classes = [
			'Navbar'        => 'soma-navbar',
			'Footer'        => 'soma-footer',
			'BusinessUnits' => 'soma-business-units',
			'Services'      => 'soma-services',
			'TeamMembers'   => 'soma-team-members',
			'NewsList'      => 'soma-news-list',
			'Portfolio'     => 'soma-portfolio',
			'ContactForm'   => 'soma-contact-form',
		];

		foreach ( $this->widget_classes as $name => $class ) {
			$widget         = new $class();
			$expected_class = $expected_classes[ $name ];

			// Use reflection to call protected render method
			$reflection = new \ReflectionClass( $widget );
			$method = $reflection->getMethod( 'render' );

			ob_start();
			$method->invoke( $widget );
			$output = ob_get_clean();

			$this->assertStringContainsString(
				$expected_class,
				$output,
				"Widget '$name' should render with class '$expected_class'"
			);
		}
	}
}
