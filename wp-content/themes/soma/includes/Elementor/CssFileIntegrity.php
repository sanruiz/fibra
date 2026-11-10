<?php
/**
 * Elementor CSS File Integrity
 *
 * Regenerates Elementor post CSS files whose meta says they exist on disk
 * when the file is actually missing.
 *
 * @package Soma
 * @subpackage Elementor
 * @since 3.1.33
 */

namespace Soma\Elementor;

use Elementor\Core\Files\CSS\Base as Css_Base;
use Elementor\Core\Files\CSS\Post as Post_Css;
use Elementor\Core\Files\CSS\Post_Local_Cache;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keeps Elementor post CSS files in sync with their `_elementor_css` meta.
 *
 * Elementor only regenerates a post CSS file when its meta status is empty.
 * When the status is `file` it enqueues the URL without checking the disk, so a
 * deleted file stays a 404 until something resets the meta.
 *
 * Observed in production with WP-Multilang Pro: pages in one language linked a
 * `post-{ID}.css` that returned 404 while their meta still reported `file`
 * with an old timestamp. Likely cause: WP-Multilang Pro keeps a per-language
 * copy of the meta in `_elementor_css_translate`, which Elementor's
 * "Clear Files & Data" does not delete, so one language can keep reading a
 * stale `file` record after the files are removed.
 *
 * Hooking `elementor/css-file/before_enqueue` lets us regenerate the file in
 * place before Elementor prints the link. `get_url()` re-reads the meta time,
 * so the link gets a fresh `ver` query string.
 *
 * @since 3.1.33
 */
final class CssFileIntegrity {

	/**
	 * Transient prefix used to throttle retries when the file cannot be written.
	 *
	 * @var string
	 */
	private const RETRY_TRANSIENT_PREFIX = 'soma_el_css_retry_';

	/**
	 * Seconds to wait before retrying a regeneration that did not produce a file.
	 *
	 * @var int
	 */
	private const RETRY_DELAY = 300;

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'elementor/css-file/before_enqueue', array( $this, 'regenerate_missing_file' ) );
	}

	/**
	 * Regenerate a post CSS file whose meta points to a file missing on disk.
	 *
	 * @param mixed $css_file Elementor CSS file instance passed by the hook.
	 * @return void
	 */
	public function regenerate_missing_file( $css_file ): void {
		// Preview and local-cache files keep their meta in memory and always rebuild.
		if ( ! $css_file instanceof Post_Css || $css_file instanceof Post_Local_Cache ) {
			return;
		}

		// With the internal print method there is no file to check.
		if ( 'internal' === get_option( 'elementor_css_print_method' ) ) {
			return;
		}

		if ( Css_Base::CSS_STATUS_FILE !== $css_file->get_meta( 'status' ) ) {
			return;
		}

		$path = $css_file->get_path();

		if ( file_exists( $path ) ) {
			return;
		}

		$retry_key = self::RETRY_TRANSIENT_PREFIX . md5( $path );

		if ( false !== get_transient( $retry_key ) ) {
			return;
		}

		$css_file->update();

		// Empty CSS: Elementor marks the status as `empty` and stops enqueueing it.
		if ( Css_Base::CSS_STATUS_FILE !== $css_file->get_meta( 'status' ) ) {
			return;
		}

		$context = array(
			'post_id' => $css_file->get_post_id(),
			'file'    => $css_file->get_file_name(),
		);

		clearstatcache( true, $path );

		if ( file_exists( $path ) ) {
			\soma_log_info( 'Regenerated missing Elementor CSS file', $context );
			return;
		}

		set_transient( $retry_key, 1, self::RETRY_DELAY );

		\soma_log_warning( 'Elementor CSS file could not be regenerated', $context );
	}
}
