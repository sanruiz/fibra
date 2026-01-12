<?php
/**
 * AnnualReports Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.13
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\AnnualReports;

/**
 * Test AnnualReports widget integration
 *
 * Tests the AnnualReports widget for displaying documents with year filtering.
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class AnnualReportsWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var AnnualReports|null
	 */
	private ?AnnualReports $widget = null;

	/**
	 * Test document post IDs
	 *
	 * @var array<int>
	 */
	private array $test_posts = array();

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
			return;
		}

		$this->widget = new AnnualReports();
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
		$this->widget     = null;

		parent::tearDown();
	}

	/**
	 * Create test documents for testing
	 *
	 * @param int    $count Number of documents to create.
	 * @param string $title_prefix Title prefix for documents.
	 * @return void
	 */
	private function create_test_documents( int $count = 3, string $title_prefix = 'Test Document' ): void {
		for ( $i = 1; $i <= $count; $i++ ) {
			$post_id = $this->factory->post->create(
				array(
					'post_type'   => 'documents-reports',
					'post_title'  => "{$title_prefix} {$i}",
					'post_status' => 'publish',
				)
			);

			if ( $post_id ) {
				$this->test_posts[] = $post_id;
			}
		}
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
		$this->assertSame( 'soma-annual-reports', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Reports', $this->widget->get_title() );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$this->assertSame( 'eicon-document-file', $this->widget->get_icon() );
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
	 * Test style dependencies
	 */
	public function test_get_style_depends(): void {
		$styles = $this->widget->get_style_depends();

		$this->assertIsArray( $styles );
		$this->assertContains( 'soma-annual-reports', $styles );
	}

	/**
	 * Test script dependencies
	 */
	public function test_get_script_depends(): void {
		$scripts = $this->widget->get_script_depends();

		$this->assertIsArray( $scripts );
		$this->assertContains( 'soma-annual-reports', $scripts );
	}

	/**
	 * Test widget has controls registered
	 */
	public function test_has_controls(): void {
		// Use reflection to call protected register_controls method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertNotEmpty( $controls, 'Widget should have controls registered' );

		// Verify specific controls exist.
		$this->assertArrayHasKey( 'category', $controls, 'Widget should have category control' );
		$this->assertArrayHasKey( 'latest_year_preselect', $controls, 'Widget should have latest_year_preselect control' );
		$this->assertArrayHasKey( 'style_variant', $controls, 'Widget should have style_variant control' );
		$this->assertArrayHasKey( 'filter_title', $controls, 'Widget should have filter_title control' );
		$this->assertArrayHasKey( 'see_all_text', $controls, 'Widget should have see_all_text control' );
		$this->assertArrayHasKey( 'download_text', $controls, 'Widget should have download_text control' );
		$this->assertArrayHasKey( 'image_height_full_width', $controls, 'Widget should have image_height_full_width control' );
		$this->assertArrayHasKey( 'image_height_three_columns', $controls, 'Widget should have image_height_three_columns control' );
	}

	/**
	 * Test widget renders without errors
	 */
	public function test_renders_without_errors(): void {
		// Use reflection to call protected render method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertNotEmpty( $output, 'Widget should render output' );
		$this->assertStringContainsString( 'soma-annual-reports-widget', $output );
	}

	/**
	 * Test widget renders year filter container
	 */
	public function test_renders_year_filter_container(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'year-list', $output );
	}

	/**
	 * Test widget renders documents container
	 */
	public function test_renders_documents_container(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'documents', $output );
		$this->assertStringContainsString( 'document-list', $output );
	}

	/**
	 * Test widget renders with full-width style variant
	 */
	public function test_renders_full_width_variant(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Default variant is full-width.
		$this->assertStringContainsString( 'full-width', $output );
	}

	/**
	 * Test widget has legacy class for JS compatibility
	 */
	public function test_renders_legacy_class_for_js(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'annualreports-partial-5d3457', $output );
	}

	/**
	 * Test widget renders REST API endpoint data attribute
	 */
	public function test_renders_api_endpoint_data_attribute(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'data-endpoint', $output );
		$this->assertStringContainsString( '/wp-json/soma/documents', $output );
	}

	/**
	 * Test widget renders loading state container
	 */
	public function test_renders_loading_state_container(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'loading', $output );
	}

	/**
	 * Test style variant control has correct options
	 */
	public function test_style_variant_control_options(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'style_variant', $controls );

		$style_variant = $controls['style_variant'];
		$this->assertArrayHasKey( 'options', $style_variant );
		$this->assertArrayHasKey( 'full-width', $style_variant['options'] );
		$this->assertArrayHasKey( 'three-columns', $style_variant['options'] );
	}

	/**
	 * Test category control is SELECT2 type
	 */
	public function test_category_control_is_select2(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'category', $controls );

		$category = $controls['category'];
		$this->assertSame( 'select2', $category['type'] );
	}
}
