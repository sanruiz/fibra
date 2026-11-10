<?php
/**
 * Elementor CSS File Integrity Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 */

namespace Soma\Tests\Integration\Elementor;

use Elementor\Core\Files\CSS\Post as Post_Css;
use Soma\Elementor\CssFileIntegrity;
use WP_UnitTestCase;

/**
 * Test regeneration of Elementor post CSS files missing from disk.
 *
 * @group integration
 * @group elementor
 */
class CssFileIntegrityTest extends WP_UnitTestCase {

	/**
	 * Integrity handler under test.
	 *
	 * @var CssFileIntegrity
	 */
	private CssFileIntegrity $integrity;

	/**
	 * Post used to generate CSS files.
	 *
	 * @var int
	 */
	private int $post_id;

	/**
	 * Set up test
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( Post_Css::class ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}

		delete_option( 'elementor_css_print_method' );

		$this->integrity = new CssFileIntegrity();
		$this->post_id   = self::factory()->post->create();
	}

	/**
	 * Remove generated CSS files.
	 */
	public function tearDown(): void {
		if ( isset( $this->post_id ) && class_exists( Post_Css::class ) ) {
			$path = $this->make_css_file()->get_path();

			if ( file_exists( $path ) ) {
				unlink( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			}
		}

		parent::tearDown();
	}

	/**
	 * Build a post CSS file whose content does not depend on Elementor documents.
	 *
	 * @param string $css CSS returned by the parser.
	 * @return Post_Css
	 */
	private function make_css_file( string $css = '.soma-test{color:red}' ): Post_Css {
		return new class( $this->post_id, $css ) extends Post_Css {

			/**
			 * Number of times the CSS was parsed.
			 *
			 * @var int
			 */
			public int $parses = 0;

			/**
			 * CSS returned by the parser.
			 *
			 * @var string
			 */
			private string $test_css;

			/**
			 * Constructor.
			 *
			 * @param int    $post_id Post ID.
			 * @param string $css     CSS returned by the parser.
			 */
			public function __construct( $post_id, string $css ) {
				$this->test_css = $css;
				parent::__construct( $post_id );
			}

			/**
			 * Return the test CSS.
			 *
			 * @return string
			 */
			protected function parse_content() {
				++$this->parses;
				return $this->test_css;
			}
		};
	}

	/**
	 * Test the handler hooks into Elementor's CSS enqueue.
	 */
	public function test_register_adds_before_enqueue_hook(): void {
		$this->integrity->register();

		$this->assertSame(
			10,
			has_action( 'elementor/css-file/before_enqueue', array( $this->integrity, 'regenerate_missing_file' ) )
		);
	}

	/**
	 * Test a missing file with `file` status is regenerated with a new version.
	 */
	public function test_regenerates_missing_file_and_refreshes_version(): void {
		$this->make_css_file()->update();

		$meta         = get_post_meta( $this->post_id, '_elementor_css', true );
		$meta['time'] = 1000;
		update_post_meta( $this->post_id, '_elementor_css', $meta );

		$css_file = $this->make_css_file();
		unlink( $css_file->get_path() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
		$stale_url = $css_file->get_url();

		$this->integrity->regenerate_missing_file( $css_file );

		$this->assertFileExists( $css_file->get_path() );
		$this->assertSame( 1, $css_file->parses );
		$this->assertNotSame( $stale_url, $css_file->get_url() );
	}

	/**
	 * Test an existing file is not regenerated.
	 */
	public function test_skips_when_file_exists(): void {
		$this->make_css_file()->update();

		$css_file = $this->make_css_file();
		$this->integrity->regenerate_missing_file( $css_file );

		$this->assertSame( 0, $css_file->parses );
	}

	/**
	 * Test files never generated are left to Elementor's own first-time update.
	 */
	public function test_skips_when_status_is_not_file(): void {
		$css_file = $this->make_css_file();
		$this->integrity->regenerate_missing_file( $css_file );

		$this->assertSame( 0, $css_file->parses );
		$this->assertFileDoesNotExist( $css_file->get_path() );
	}

	/**
	 * Test the internal print method is left untouched.
	 */
	public function test_skips_internal_print_method(): void {
		$this->make_css_file()->update();

		$css_file = $this->make_css_file();
		unlink( $css_file->get_path() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink

		update_option( 'elementor_css_print_method', 'internal' );
		$this->integrity->regenerate_missing_file( $css_file );

		$this->assertSame( 0, $css_file->parses );
	}

	/**
	 * Test objects other than post CSS files are ignored.
	 */
	public function test_ignores_non_post_css_objects(): void {
		$this->integrity->regenerate_missing_file( new \stdClass() );
		$this->integrity->regenerate_missing_file( null );

		$this->assertTrue( true );
	}
}
