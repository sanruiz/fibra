<?php
/**
 * Page Builder Integration Tests
 *
 * Tests the PSR-4 PageBuilder system integration with WordPress.
 *
 * @package    Soma
 * @subpackage Tests\Integration
 * @since      3.0.0
 */

namespace Soma\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Soma\PageBuilder\Loader;
use Soma\PageBuilder\BlockRegistry;
use Soma\PageBuilder\BlockRenderer;

/**
 * Page Builder Integration Test Class
 */
class PageBuilderTest extends TestCase {

	/**
	 * Block Registry instance
	 *
	 * @var BlockRegistry
	 */
	private BlockRegistry $registry;

	/**
	 * Block Renderer instance
	 *
	 * @var BlockRenderer
	 */
	private BlockRenderer $renderer;

	/**
	 * Set up test environment
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->registry = BlockRegistry::instance();
		$this->renderer = BlockRenderer::instance();
	}

	/**
	 * Test that BlockRegistry singleton works
	 */
	public function test_block_registry_singleton(): void {
		$instance1 = BlockRegistry::instance();
		$instance2 = BlockRegistry::instance();

		$this->assertSame( $instance1, $instance2, 'BlockRegistry should be a singleton' );
	}

	/**
	 * Test that BlockRenderer singleton works
	 */
	public function test_block_renderer_singleton(): void {
		$instance1 = BlockRenderer::instance();
		$instance2 = BlockRenderer::instance();

		$this->assertSame( $instance1, $instance2, 'BlockRenderer should be a singleton' );
	}

	/**
	 * Test that all 53 blocks are registered
	 */
	public function test_all_blocks_registered(): void {
		$count = $this->registry->count();

		$this->assertEquals( 53, $count, 'Should have 53 blocks registered' );
	}

	/**
	 * Test that specific blocks are registered
	 */
	public function test_specific_blocks_exist(): void {
		$blocks_to_test = [
			'BusinessUnits',
			'fullscreenSlider',
			'Portfolio',
			'NewsList',
			'TeamMembers',
			'FibrasomaHome1',
			'Documents',
			'ShareQuotation',
			'Events',
		];

		foreach ( $blocks_to_test as $block ) {
			$this->assertTrue(
				$this->registry->is_registered( $block ),
				"Block '{$block}' should be registered"
			);
		}
	}

	/**
	 * Test that block mappings have correct structure
	 */
	public function test_block_mappings_structure(): void {
		$all_blocks = $this->registry->get_all_blocks();

		$this->assertIsArray( $all_blocks, 'get_all_blocks should return an array' );

		foreach ( $all_blocks as $layout => $mapping ) {
			$this->assertIsString( $layout, 'Layout should be a string' );
			$this->assertIsArray( $mapping, 'Mapping should be an array' );
			$this->assertArrayHasKey( 'field_group', $mapping, "Mapping for '{$layout}' should have 'field_group'" );
			$this->assertArrayHasKey( 'partial', $mapping, "Mapping for '{$layout}' should have 'partial'" );
		}
	}

	/**
	 * Test that partial files exist for all registered blocks
	 */
	public function test_partial_files_exist(): void {
		$all_blocks   = $this->registry->get_all_blocks();
		$missing_files = [];

		foreach ( $all_blocks as $layout => $mapping ) {
			$partial_file = $this->registry->get_partial_file_path( $layout );

			if ( ! $partial_file || ! file_exists( $partial_file ) ) {
				$missing_files[] = [
					'layout'  => $layout,
					'partial' => $mapping['partial'],
					'path'    => $partial_file ?: 'N/A',
				];
			}
		}

		$this->assertEmpty(
			$missing_files,
			'All partial files should exist. Missing: ' . print_r( $missing_files, true )
		);
	}

	/**
	 * Test that renderer handles null blocks gracefully
	 */
	public function test_renderer_handles_null_blocks(): void {
		ob_start();
		$this->renderer->render( null );
		$output = ob_get_clean();

		$this->assertEmpty( $output, 'Renderer should handle null blocks without output' );
	}

	/**
	 * Test that renderer handles empty array
	 */
	public function test_renderer_handles_empty_array(): void {
		ob_start();
		$this->renderer->render( [] );
		$output = ob_get_clean();

		$this->assertEmpty( $output, 'Renderer should handle empty array without output' );
	}

	/**
	 * Test that renderer handles invalid block structure
	 */
	public function test_renderer_handles_invalid_block(): void {
		$invalid_blocks = [
			[
				// Missing acf_fc_layout
				'some_field' => 'some_value',
			],
		];

		ob_start();
		$this->renderer->render( $invalid_blocks );
		$output = ob_get_clean();

		// Should handle gracefully without fatal errors
		$this->assertTrue( true, 'Renderer handled invalid block without fatal error' );
	}

	/**
	 * Test that renderer handles unregistered block
	 */
	public function test_renderer_handles_unregistered_block(): void {
		$unregistered_blocks = [
			[
				'acf_fc_layout' => 'UnregisteredBlock',
			],
		];

		ob_start();
		$this->renderer->render( $unregistered_blocks );
		$output = ob_get_clean();

		// Should handle gracefully
		$this->assertTrue( true, 'Renderer handled unregistered block without fatal error' );
	}

	/**
	 * Test renderer statistics
	 */
	public function test_renderer_statistics(): void {
		$stats = $this->renderer->get_stats();

		$this->assertIsArray( $stats, 'get_stats should return an array' );
		$this->assertArrayHasKey( 'blocks_rendered', $stats );
		$this->assertArrayHasKey( 'blocks_cached', $stats );
		$this->assertArrayHasKey( 'cache_hits', $stats );
		$this->assertArrayHasKey( 'errors', $stats );
	}

	/**
	 * Test cache invalidation
	 */
	public function test_cache_invalidation(): void {
		// Test invalidating all caches
		$this->renderer->invalidate_cache();

		// Test invalidating specific block type
		$this->renderer->invalidate_cache( 'BusinessUnits' );

		$this->assertTrue( true, 'Cache invalidation executed without errors' );
	}

	/**
	 * Test that BlockRegistry cannot be cloned
	 */
	public function test_registry_cannot_be_cloned(): void {
		$this->expectException( \Error::class );

		$registry = BlockRegistry::instance();
		$clone    = clone $registry; // Should throw error
	}

	/**
	 * Test that BlockRenderer cannot be cloned
	 */
	public function test_renderer_cannot_be_cloned(): void {
		$this->expectException( \Error::class );

		$renderer = BlockRenderer::instance();
		$clone    = clone $renderer; // Should throw error
	}

	/**
	 * Test getting field group for registered block
	 */
	public function test_get_field_group(): void {
		$field_group = $this->registry->get_field_group( 'BusinessUnits' );

		$this->assertEquals( 'business_units_content', $field_group );
	}

	/**
	 * Test getting field group for unregistered block
	 */
	public function test_get_field_group_unregistered(): void {
		$field_group = $this->registry->get_field_group( 'UnregisteredBlock' );

		$this->assertNull( $field_group );
	}

	/**
	 * Test getting partial path for registered block
	 */
	public function test_get_partial_path(): void {
		$partial = $this->registry->get_partial_path( 'BusinessUnits' );

		$this->assertEquals( 'BusinessUnits', $partial );
	}

	/**
	 * Test getting partial path for unregistered block
	 */
	public function test_get_partial_path_unregistered(): void {
		$partial = $this->registry->get_partial_path( 'UnregisteredBlock' );

		$this->assertNull( $partial );
	}

	/**
	 * Test registering a new block
	 */
	public function test_register_new_block(): void {
		$this->registry->register_block( 'TestBlock', 'test_block_content', 'TestBlock' );

		$this->assertTrue( $this->registry->is_registered( 'TestBlock' ) );
		$this->assertEquals( 'test_block_content', $this->registry->get_field_group( 'TestBlock' ) );
		$this->assertEquals( 'TestBlock', $this->registry->get_partial_path( 'TestBlock' ) );
	}
}
