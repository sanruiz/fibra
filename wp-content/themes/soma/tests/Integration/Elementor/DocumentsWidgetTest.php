<?php
/**
 * Documents Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since 3.1.5
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\Documents;

/**
 * Test Documents widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class DocumentsWidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var Documents
	 */
	private Documents $widget;

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
		}

		$this->widget = new Documents();
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
		$this->assertSame( 'soma-documents', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$this->assertSame( 'Documents', $this->widget->get_title() );
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
		$this->assertContains( 'soma-documents', $styles );
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
		$this->assertArrayHasKey( 'posts_per_page', $controls, 'Widget should have posts_per_page control' );
		$this->assertArrayHasKey( 'orderby', $controls, 'Widget should have orderby control' );
		$this->assertArrayHasKey( 'order', $controls, 'Widget should have order control' );
		$this->assertArrayHasKey( 'title_tag', $controls, 'Widget should have title_tag control' );
		$this->assertArrayHasKey( 'download_text', $controls, 'Widget should have download_text control' );
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
		$this->assertStringContainsString( 'soma-documents', $output );
	}

	/**
	 * Test widget renders no documents message when empty
	 */
	public function test_renders_no_documents_message(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'no-documents', $output );
	}

	/**
	 * Test widget renders grid when documents exist
	 */
	public function test_renders_grid_with_documents(): void {
		// Create test documents.
		$this->create_test_documents( 3 );

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'documents-grid', $output );
		$this->assertStringContainsString( 'document-item', $output );
	}

	/**
	 * Test widget renders document titles
	 */
	public function test_renders_document_titles(): void {
		// Create test document with specific title.
		$this->create_test_documents( 1, 'Test Document Title' );

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'document-title', $output );
		$this->assertStringContainsString( 'Test Document Title', $output );
	}

	/**
	 * Test widget uses correct title tag
	 */
	public function test_renders_with_correct_title_tag(): void {
		// Create test document.
		$this->create_test_documents( 1 );

		// Use reflection to call protected render method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		// Default title tag is h3.
		$this->assertStringContainsString( '<h3 class="document-title">', $output );
	}

	/**
	 * Test widget renders document content section
	 */
	public function test_renders_document_content_section(): void {
		// Create test document.
		$this->create_test_documents( 1 );

		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'document-content', $output );
	}

	/**
	 * Test default control values
	 */
	public function test_default_control_values(): void {
		// Use reflection to call protected register_controls method.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Check posts_per_page default.
		$this->assertSame( 4, $controls['posts_per_page']['default'] );

		// Check orderby default.
		$this->assertSame( 'date', $controls['orderby']['default'] );

		// Check order default.
		$this->assertSame( 'DESC', $controls['order']['default'] );

		// Check title_tag default.
		$this->assertSame( 'h3', $controls['title_tag']['default'] );
	}

	/**
	 * Test query section exists
	 */
	public function test_query_section_exists(): void {
		// Use reflection to check sections.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Check that query-related controls exist.
		$this->assertArrayHasKey( 'posts_per_page', $controls );
		$this->assertArrayHasKey( 'orderby', $controls );
		$this->assertArrayHasKey( 'order', $controls );
	}

	/**
	 * Test layout section exists
	 */
	public function test_layout_section_exists(): void {
		// Use reflection to check sections.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Check that layout-related controls exist.
		$this->assertArrayHasKey( 'columns', $controls );
		$this->assertArrayHasKey( 'grid_gap', $controls );
	}

	/**
	 * Test style controls exist
	 */
	public function test_style_controls_exist(): void {
		// Use reflection to check sections.
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		// Check that style controls exist.
		$this->assertArrayHasKey( 'image_height', $controls );
		$this->assertArrayHasKey( 'title_color', $controls );
		$this->assertArrayHasKey( 'download_color', $controls );
	}

	/**
	 * Test get_document_categories method exists and returns array
	 */
	public function test_get_document_categories_returns_array(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'get_document_categories' );

		$result = $method->invoke( $this->widget );

		$this->assertIsArray( $result, 'get_document_categories should return an array' );
	}

	/**
	 * Test category control uses SELECT2 type
	 */
	public function test_category_control_is_select2(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();

		$this->assertArrayHasKey( 'category', $controls );
		$this->assertSame( 'select2', $controls['category']['type'], 'Category control should be SELECT2 type' );
	}

	/**
	 * Helper method to create test documents
	 *
	 * @param int    $count Number of documents to create.
	 * @param string $title Optional title prefix.
	 * @return void
	 */
	private function create_test_documents( int $count, string $title = 'Test Document' ): void {
		for ( $i = 0; $i < $count; $i++ ) {
			$post_id = $this->factory->post->create(
				array(
					'post_type'   => 'documents-reports',
					'post_title'  => $title . ( $count > 1 ? ' ' . ( $i + 1 ) : '' ),
					'post_status' => 'publish',
				)
			);

			$this->test_posts[] = $post_id;
		}
	}
}
