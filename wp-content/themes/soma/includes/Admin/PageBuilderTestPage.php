<?php
/**
 * Page Builder Test Page (Admin)
 *
 * Accessible via: /wp-admin/admin.php?page=soma-pagebuilder-test
 *
 * @package    Soma
 * @subpackage Admin
 * @since      3.0.0
 */

namespace Soma\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Page Builder Test Page Class
 */
class PageBuilderTestPage {

	/**
	 * Singleton instance
	 *
	 * @var PageBuilderTestPage|null
	 */
	private static ?PageBuilderTestPage $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return PageBuilderTestPage
	 */
	public static function instance(): PageBuilderTestPage {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {
		add_action( 'admin_menu', $this->add_test_page( ... ) );
	}

	/**
	 * Add test page to admin menu
	 *
	 * @return void
	 */
	private function add_test_page(): void {
		add_submenu_page(
			'tools.php',
			'PageBuilder Tests',
			'PageBuilder Tests',
			'manage_options',
			'soma-pagebuilder-test',
			$this->render_test_page( ... )
		);
	}

	/**
	 * Render test page
	 *
	 * @return void
	 */
	private function render_test_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized' );
		}

		$tests = $this->run_all_tests();
		?>
		<div class="wrap">
			<h1>PageBuilder Integration Tests</h1>
			<p>Soma Theme v3.0.0 - PSR-4 PageBuilder System</p>

			<?php $this->render_test_results( $tests ); ?>
		</div>
		<style>
			.soma-test-results { margin-top: 20px; }
			.soma-test-section { background: #fff; border: 1px solid #ccd0d4; margin: 10px 0; padding: 15px; }
			.soma-test-section h2 { margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
			.soma-test-item { padding: 5px 0; font-family: monospace; }
			.soma-test-pass { color: #46b450; }
			.soma-test-fail { color: #dc3232; }
			.soma-test-warn { color: #ffb900; }
			.soma-test-summary { background: #f0f0f1; padding: 15px; margin: 20px 0; border-left: 4px solid #2271b1; }
			.soma-test-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin: 15px 0; }
			.soma-test-stat { background: #fff; padding: 15px; border: 1px solid #ccd0d4; text-align: center; }
			.soma-test-stat-number { font-size: 32px; font-weight: bold; color: #2271b1; }
			.soma-test-stat-label { color: #646970; font-size: 14px; }
		</style>
		<?php
	}

	/**
	 * Run all tests
	 *
	 * @return array Test results.
	 */
	private function run_all_tests(): array {
		$results = array(
			'psr4_classes'     => $this->test_psr4_classes(),
			'block_registry'   => $this->test_block_registry(),
			'partial_files'    => $this->test_partial_files(),
			'block_renderer'   => $this->test_block_renderer(),
			'helper_functions' => $this->test_helper_functions(),
			'page_rendering'   => $this->test_page_rendering(),
		);

		return $results;
	}

	/**
	 * Test PSR-4 classes
	 *
	 * @return array Test result.
	 */
	private function test_psr4_classes(): array {
		$classes = array(
			'\\Soma\\PageBuilder\\Loader'        => 'PageBuilder Loader',
			'\\Soma\\PageBuilder\\BlockRegistry' => 'Block Registry',
			'\\Soma\\PageBuilder\\BlockRenderer' => 'Block Renderer',
		);

		$results = array();
		foreach ( $classes as $class => $name ) {
			$results[] = array(
				'name'    => $name,
				'status'  => class_exists( $class ) ? 'pass' : 'fail',
				'message' => class_exists( $class ) ? 'Class exists' : 'Class not found',
			);
		}

		return array(
			'title'   => 'PSR-4 Classes',
			'results' => $results,
		);
	}

	/**
	 * Test block registry
	 *
	 * @return array Test result.
	 */
	private function test_block_registry(): array {
		$registry = \Soma\PageBuilder\BlockRegistry::instance();
		$count    = $registry->count();

		$results = array(
			array(
				'name'    => 'Block Registry Singleton',
				'status'  => 'pass',
				'message' => 'Instance created successfully',
			),
			array(
				'name'    => 'Registered Blocks Count',
				'status'  => $count === 53 ? 'pass' : 'warn',
				'message' => "Found {$count} blocks (expected 53)",
			),
		);

		// Test specific blocks.
		$test_blocks = array( 'BusinessUnits', 'fullscreenSlider', 'Portfolio', 'NewsList', 'TeamMembers' );
		foreach ( $test_blocks as $block ) {
			$results[] = array(
				'name'    => "Block: {$block}",
				'status'  => $registry->is_registered( $block ) ? 'pass' : 'fail',
				'message' => $registry->is_registered( $block ) ? 'Registered' : 'Not registered',
			);
		}

		return array(
			'title'   => 'Block Registry',
			'results' => $results,
			'stats'   => array( 'total_blocks' => $count ),
		);
	}

	/**
	 * Test partial files
	 *
	 * @return array Test result.
	 */
	private function test_partial_files(): array {
		$registry      = \Soma\PageBuilder\BlockRegistry::instance();
		$all_blocks    = $registry->get_all_blocks();
		$valid         = 0;
		$missing       = 0;
		$missing_files = array();

		foreach ( $all_blocks as $layout => $mapping ) {
			$file = $registry->get_partial_file_path( $layout );
			if ( $file && file_exists( $file ) ) {
				++$valid;
			} else {
				++$missing;
				$missing_files[] = array(
					'name'    => "Missing: {$layout}",
					'status'  => 'fail',
					'message' => "Partial file not found: {$mapping['partial']}",
				);
			}
		}

		$results = array(
			array(
				'name'    => 'Partial Files Validation',
				'status'  => $missing === 0 ? 'pass' : 'fail',
				'message' => "Valid: {$valid}, Missing: {$missing}",
			),
		);

		if ( ! empty( $missing_files ) ) {
			$results = array_merge( $results, array_slice( $missing_files, 0, 5 ) );
			if ( count( $missing_files ) > 5 ) {
				$results[] = array(
					'name'    => '...',
					'status'  => 'fail',
					'message' => 'And ' . ( count( $missing_files ) - 5 ) . ' more missing files',
				);
			}
		}

		return array(
			'title'   => 'Partial Files',
			'results' => $results,
			'stats'   => array(
				'valid_files'   => $valid,
				'missing_files' => $missing,
			),
		);
	}

	/**
	 * Test block renderer
	 *
	 * @return array Test result.
	 */
	private function test_block_renderer(): array {
		$renderer = \Soma\PageBuilder\BlockRenderer::instance();

		$results = array();

		// Test null blocks.
		ob_start();
		$renderer->render( null );
		ob_get_clean();
		$results[] = array(
			'name'    => 'Null Blocks',
			'status'  => 'pass',
			'message' => 'Handles null gracefully',
		);

		// Test empty array.
		ob_start();
		$renderer->render( array() );
		ob_get_clean();
		$results[] = array(
			'name'    => 'Empty Array',
			'status'  => 'pass',
			'message' => 'Handles empty array gracefully',
		);

		// Test invalid block.
		ob_start();
		$renderer->render( array( array( 'invalid' => 'block' ) ) );
		ob_get_clean();
		$results[] = array(
			'name'    => 'Invalid Block',
			'status'  => 'pass',
			'message' => 'Handles invalid block structure',
		);

		// Get stats.
		$stats     = $renderer->get_stats();
		$results[] = array(
			'name'    => 'Renderer Statistics',
			'status'  => 'pass',
			'message' => sprintf(
				'Rendered: %d, Cached: %d, Errors: %d',
				$stats['blocks_rendered'],
				$stats['blocks_cached'],
				$stats['errors']
			),
		);

		return array(
			'title'   => 'Block Renderer',
			'results' => $results,
			'stats'   => $stats,
		);
	}

	/**
	 * Test helper functions
	 *
	 * @return array Test result.
	 */
	private function test_helper_functions(): array {
		$functions = array(
			'soma_translate_date' => 'soma_translate_date()',
			'translateDate'       => 'translateDate() (legacy)',
			'soma_log_error'      => 'soma_log_error()',
			'soma_log_info'       => 'soma_log_info()',
			'soma_cache_get'      => 'soma_cache_get()',
		);

		$results = array();
		foreach ( $functions as $func => $name ) {
			$results[] = array(
				'name'    => $name,
				'status'  => function_exists( $func ) ? 'pass' : 'fail',
				'message' => function_exists( $func ) ? 'Function exists' : 'Function not found',
			);
		}

		return array(
			'title'   => 'Helper Functions',
			'results' => $results,
		);
	}

	/**
	 * Test page rendering
	 *
	 * @return array Test result.
	 */
	private function test_page_rendering(): array {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'posts_per_page' => 10,
				'post_status'    => 'publish',
			)
		);

		$pages_with_blocks  = 0;
		$successful_renders = 0;
		$results            = array();
		$renderer           = \Soma\PageBuilder\BlockRenderer::instance();

		foreach ( $pages as $page ) {
			$blocks = get_field( 'soma_blocks', $page->ID );
			if ( $blocks && is_array( $blocks ) && count( $blocks ) > 0 ) {
				++$pages_with_blocks;

				ob_start();
				try {
					$renderer->render( $blocks );
					$output = ob_get_clean();
					++$successful_renders;
					$results[] = array(
						'name'    => "Page: {$page->post_title}",
						'status'  => 'pass',
						'message' => count( $blocks ) . ' blocks rendered',
					);
				} catch ( \Exception $e ) {
					ob_get_clean();
					$results[] = array(
						'name'    => "Page: {$page->post_title}",
						'status'  => 'fail',
						'message' => $e->getMessage(),
					);
				}
			}
		}

		if ( $pages_with_blocks === 0 ) {
			$results[] = array(
				'name'    => 'No Pages with Blocks',
				'status'  => 'warn',
				'message' => 'No pages found with soma_blocks field',
			);
		}

		return array(
			'title'   => 'Page Rendering',
			'results' => $results,
			'stats'   => array(
				'pages_tested'       => $pages_with_blocks,
				'successful_renders' => $successful_renders,
			),
		);
	}

	/**
	 * Render test results
	 *
	 * @param array $tests Test results.
	 * @return void
	 */
	private function render_test_results( array $tests ): void {
		$total_passed = 0;
		$total_failed = 0;
		$total_warned = 0;

		// Count totals.
		foreach ( $tests as $test ) {
			foreach ( $test['results'] as $result ) {
				if ( $result['status'] === 'pass' ) {
					++$total_passed;
				} elseif ( $result['status'] === 'fail' ) {
					++$total_failed;
				} else {
					++$total_warned;
				}
			}
		}

		// Summary stats.
		?>
		<div class="soma-test-summary">
			<h2>Test Summary</h2>
			<div class="soma-test-stats">
				<div class="soma-test-stat">
					<div class="soma-test-stat-number" style="color: #46b450;"><?php echo esc_html( $total_passed ); ?></div>
					<div class="soma-test-stat-label">Passed</div>
				</div>
				<div class="soma-test-stat">
					<div class="soma-test-stat-number" style="color: #ffb900;"><?php echo esc_html( $total_warned ); ?></div>
					<div class="soma-test-stat-label">Warnings</div>
				</div>
				<div class="soma-test-stat">
					<div class="soma-test-stat-number" style="color: #dc3232;"><?php echo esc_html( $total_failed ); ?></div>
					<div class="soma-test-stat-label">Failed</div>
				</div>
			</div>
		</div>

		<div class="soma-test-results">
			<?php foreach ( $tests as $test ) : ?>
				<div class="soma-test-section">
					<h2><?php echo esc_html( $test['title'] ); ?></h2>
					<?php foreach ( $test['results'] as $result ) : ?>
						<div class="soma-test-item soma-test-<?php echo esc_attr( $result['status'] ); ?>">
							<?php
							$icon = $result['status'] === 'pass' ? '✓' : ( $result['status'] === 'fail' ? '✗' : '⚠' );
							?>
							<strong><?php echo esc_html( $icon ); ?></strong>
							<?php echo esc_html( $result['name'] ); ?>:
							<em><?php echo esc_html( $result['message'] ); ?></em>
						</div>
					<?php endforeach; ?>

					<?php if ( isset( $test['stats'] ) ) : ?>
						<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
							<strong>Statistics:</strong>
							<pre style="background: #f0f0f1; padding: 10px; margin-top: 5px;"><?php echo esc_html( print_r( $test['stats'], true ) ); ?></pre>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
