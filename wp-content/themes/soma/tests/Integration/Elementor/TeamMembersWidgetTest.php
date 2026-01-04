<?php
/**
 * Team Members Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.9
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\TeamMembers;

/**
 * Test TeamMembers widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class TeamMembersWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var TeamMembers
	 */
	private TeamMembers $widget;

	/**
	 * Test team member post IDs
	 *
	 * @var array<int>
	 */
	private array $test_posts = array();

	/**
	 * Test taxonomy term IDs
	 *
	 * @var array<int>
	 */
	private array $test_terms = array();

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}

		$this->widget = new TeamMembers();
	}

	/**
	 * Tear down test
	 */
	public function tearDown(): void {
		// Clean up test posts.
		foreach ( $this->test_posts as $post_id ) {
			wp_delete_post( $post_id, true );
		}
		$this->test_posts = array();

		// Clean up test terms.
		foreach ( $this->test_terms as $term_id ) {
			wp_delete_term( $term_id, 'team-members-taxonomy' );
		}
		$this->test_terms = array();

		parent::tearDown();
	}

	/**
	 * Test widget extends correct base class
	 */
	public function test_extends_widget_base(): void {
		$this->assertInstanceOf(
			\Soma\Elementor\Base\WidgetBase::class,
			$this->widget
		);
	}

	/**
	 * Test widget name
	 */
	public function test_get_name(): void {
		$this->assertSame( 'soma-team-members', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Team Members', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-person', $this->widget->get_icon() );
	}

	/**
	 * Test widget categories
	 */
	public function test_get_categories(): void {
		$categories = $this->widget->get_categories();

		$this->assertIsArray( $categories );
		$this->assertContains( 'soma', $categories );
	}

	/**
	 * Test widget style dependencies
	 */
	public function test_get_style_depends(): void {
		$styles = $this->widget->get_style_depends();

		$this->assertIsArray( $styles );
		$this->assertContains( 'soma-team-members', $styles );
	}

	/**
	 * Test widget has required methods
	 */
	public function test_has_required_methods(): void {
		$this->assertTrue( method_exists( $this->widget, 'get_name' ) );
		$this->assertTrue( method_exists( $this->widget, 'get_title' ) );
		$this->assertTrue( method_exists( $this->widget, 'get_icon' ) );
		$this->assertTrue( method_exists( $this->widget, 'get_categories' ) );
		$this->assertTrue( method_exists( $this->widget, 'get_style_depends' ) );
	}

	/**
	 * Test widget has protected register_controls method
	 */
	public function test_has_register_controls_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );

		$this->assertTrue( $method->isProtected() );
	}

	/**
	 * Test widget has protected render method
	 */
	public function test_has_render_method(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		$this->assertTrue( $method->isProtected() );
	}

	/**
	 * Test widget CSS file exists
	 */
	public function test_css_file_exists(): void {
		$css_path = get_template_directory() . '/assets/css/widgets/team-members.css';

		$this->assertFileExists( $css_path );
	}

	/**
	 * Test CSS file contains required SOMA variables
	 */
	public function test_css_uses_soma_variables(): void {
		$css_path    = get_template_directory() . '/assets/css/widgets/team-members.css';
		$css_content = file_get_contents( $css_path );

		// Check for SOMA CSS variable usage.
		$this->assertStringContainsString( '--soma-font-family-primary', $css_content );
		$this->assertStringContainsString( '--soma-font-size-h3', $css_content );
		$this->assertStringContainsString( '--soma-font-size-body', $css_content );
		$this->assertStringContainsString( '--soma-color-text-primary', $css_content );
		$this->assertStringContainsString( '--soma-spacing-lg', $css_content );
		$this->assertStringContainsString( '--soma-transition-base', $css_content );
	}

	/**
	 * Test CSS file contains responsive breakpoints
	 */
	public function test_css_has_responsive_breakpoints(): void {
		$css_path    = get_template_directory() . '/assets/css/widgets/team-members.css';
		$css_content = file_get_contents( $css_path );

		// Check for tablet breakpoint.
		$this->assertStringContainsString( 'max-width: 991px', $css_content );

		// Check for mobile breakpoint.
		$this->assertStringContainsString( 'max-width: 767px', $css_content );
	}

	/**
	 * Test CSS file contains mobile-specific variables
	 */
	public function test_css_uses_mobile_variables(): void {
		$css_path    = get_template_directory() . '/assets/css/widgets/team-members.css';
		$css_content = file_get_contents( $css_path );

		// Check for mobile-specific SOMA variables.
		$this->assertStringContainsString( '--soma-font-size-h3-mobile', $css_content );
		$this->assertStringContainsString( '--soma-font-size-small', $css_content );
		$this->assertStringContainsString( '--soma-line-height-h3-mobile', $css_content );
	}

	/**
	 * Test CSS file contains column classes
	 */
	public function test_css_has_column_classes(): void {
		$css_path    = get_template_directory() . '/assets/css/widgets/team-members.css';
		$css_content = file_get_contents( $css_path );

		$this->assertStringContainsString( '.columns-2', $css_content );
		$this->assertStringContainsString( '.columns-3', $css_content );
		$this->assertStringContainsString( '.columns-4', $css_content );
	}

	/**
	 * Test CSS file contains grayscale effect class
	 */
	public function test_css_has_grayscale_class(): void {
		$css_path    = get_template_directory() . '/assets/css/widgets/team-members.css';
		$css_content = file_get_contents( $css_path );

		$this->assertStringContainsString( '.soma-team-members.grayscale', $css_content );
		$this->assertStringContainsString( 'filter: grayscale(100%)', $css_content );
		$this->assertStringContainsString( 'filter: grayscale(0%)', $css_content );
	}
}
