<?php
/**
 * TeamMember Widget Integration Tests
 *
 * Integration tests for TeamMember Elementor widget (singular profile view).
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.12
 */

namespace Soma\Tests\Integration\Elementor;

use Soma\Elementor\Widgets\TeamMember;
use WP_UnitTestCase;
use ReflectionClass;

/**
 * TeamMember widget integration tests
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class TeamMemberWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var TeamMember|null
	 */
	private ?TeamMember $widget = null;

	/**
	 * Set up test environment
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
			return; // Prevent further execution when skipped.
		}

		$this->widget = new TeamMember();
	}

	/**
	 * Tear down test environment
	 */
	public function tearDown(): void {
		$this->widget = null;
		parent::tearDown();
	}

	/**
	 * Test widget name
	 */
	public function test_widget_name(): void {
		$this->assertSame( 'soma-team-member', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_widget_title(): void {
		$title = $this->widget->get_title();
		$this->assertIsString( $title );
		$this->assertNotEmpty( $title );
		$this->assertStringContainsString( 'Team Member', $title );
	}

	/**
	 * Test widget icon
	 */
	public function test_widget_icon(): void {
		$icon = $this->widget->get_icon();
		$this->assertIsString( $icon );
		$this->assertNotEmpty( $icon );
	}

	/**
	 * Test widget categories
	 */
	public function test_widget_categories(): void {
		$categories = $this->widget->get_categories();
		$this->assertIsArray( $categories );
		$this->assertContains( 'soma', $categories );
	}

	/**
	 * Test widget style dependencies
	 */
	public function test_style_depends(): void {
		$styles = $this->widget->get_style_depends();
		$this->assertIsArray( $styles );
		$this->assertContains( 'soma-team-member', $styles );
	}

	/**
	 * Test that widget uses Global_Colors
	 */
	public function test_uses_global_colors(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			'Global_Colors',
			$source,
			'TeamMember widget should use Global_Colors from Site Kit'
		);
	}

	/**
	 * Test that widget uses Global_Typography
	 */
	public function test_uses_global_typography(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			'Global_Typography',
			$source,
			'TeamMember widget should use Global_Typography from Site Kit'
		);
	}

	/**
	 * Test that CSS uses SOMA variables
	 */
	public function test_css_uses_soma_variables(): void {
		$css_file = get_template_directory() . '/assets/css/widgets/team-member.css';
		$this->assertFileExists( $css_file, 'team-member.css should exist' );

		$css_content = file_get_contents( $css_file );

		// Check for SOMA CSS variables.
		$this->assertStringContainsString( '--soma-spacing', $css_content, 'CSS should use SOMA spacing variables' );
		$this->assertStringContainsString( '--soma-font-family', $css_content, 'CSS should use SOMA font variables' );
		$this->assertStringContainsString( '--soma-font-size', $css_content, 'CSS should use SOMA font-size variables' );
		$this->assertStringContainsString( '--soma-color', $css_content, 'CSS should use SOMA color variables' );
	}

	/**
	 * Test responsive breakpoints in CSS
	 */
	public function test_css_has_responsive_breakpoints(): void {
		$css_file = get_template_directory() . '/assets/css/widgets/team-member.css';
		$this->assertFileExists( $css_file, 'team-member.css should exist' );

		$css_content = file_get_contents( $css_file );

		// Check for responsive breakpoints (only mobile, no tablet in current implementation).
		$this->assertStringContainsString( '@media (max-width: 767px)', $css_content, 'CSS should have mobile breakpoint' );
	}

	/**
	 * Test that widget has team member selection control
	 */
	public function test_has_member_selection_control(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			"'team_member_id'",
			$source,
			'TeamMember widget should have team_member_id control'
		);
		$this->assertStringContainsString(
			'Controls_Manager::SELECT',
			$source,
			'TeamMember widget should use SELECT for member selection'
		);
	}

	/**
	 * Test that widget has featured text toggle
	 */
	public function test_has_featured_text_toggle(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			"'show_featured_text'",
			$source,
			'TeamMember widget should have show_featured_text control'
		);
		$this->assertStringContainsString(
			'Controls_Manager::SWITCHER',
			$source,
			'TeamMember widget should use SWITCHER for featured text toggle'
		);
	}

	/**
	 * Test that widget has logo toggle
	 */
	public function test_has_logo_toggle(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			"'show_logo'",
			$source,
			'TeamMember widget should have show_logo control'
		);
	}

	/**
	 * Test that render method checks for ACF
	 */
	public function test_render_checks_acf(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			'get_field',
			$source,
			'TeamMember widget should use get_field() from ACF'
		);
	}

	/**
	 * Test that render method handles empty member selection
	 */
	public function test_render_handles_empty_member(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		// Should check if member_id is empty.
		$this->assertStringContainsString(
			'empty(',
			$source,
			'TeamMember widget should check for empty member_id'
		);
	}

	/**
	 * Test that widget replicates single template structure
	 */
	public function test_replicates_template_structure(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		// Should have same sections as singles/team-members.php.
		$this->assertStringContainsString( 'soma-team-member', $source, 'Widget should have main wrapper class' );
		$this->assertStringContainsString( 'featured-image', $source, 'Widget should render featured image' );
		$this->assertStringContainsString( 'member-name', $source, 'Widget should render member name' );
		$this->assertStringContainsString( 'member-title', $source, 'Widget should render member title/position' );
		$this->assertStringContainsString( 'featured-text', $source, 'Widget should render featured text' );
		$this->assertStringContainsString( 'body-content', $source, 'Widget should render body content' );
	}

	/**
	 * Test that widget has photo toggle.
	 */
	public function test_has_photo_toggle(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			"'show_photo'",
			$source,
			'TeamMember widget should have show_photo control'
		);
		$this->assertStringContainsString(
			'Controls_Manager::SWITCHER',
			$source,
			'TeamMember widget should use SWITCHER for photo toggle'
		);
	}

	/**
	 * Test that show_photo control defaults to visible.
	 */
	public function test_show_photo_defaults_to_visible(): void {
		$reflection = new ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();
		$this->assertArrayHasKey( 'show_photo', $controls, 'Widget should have show_photo control' );
		$this->assertSame( 'yes', $controls['show_photo']['default'], 'show_photo should default to visible (yes)' );
	}

	/**
	 * Test that widget has full-card link.
	 */
	public function test_has_card_link(): void {
		$reflection = new ReflectionClass( $this->widget );
		$source     = file_get_contents( $reflection->getFileName() );

		$this->assertStringContainsString(
			'soma-team-member__card-link',
			$source,
			'TeamMember widget should have card link wrapper'
		);
		$this->assertStringContainsString(
			'use_card_link',
			$source,
			'TeamMember widget should check if card link should be used'
		);
	}
}
