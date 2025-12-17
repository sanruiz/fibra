<?php
/**
 * Assets Management
 *
 * @package Soma\Core
 */

namespace Soma\Core;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Assets class - manages CSS and JS enqueueing
 */
class Assets implements LoadableInterface {

	/**
	 * Singleton instance
	 *
	 * @var Assets|null
	 */
	private static ?Assets $instance = null;

	/**
	 * Theme version
	 *
	 * @var string
	 */
	private string $version = '3.1.0';

	/**
	 * Legacy stylesheet version
	 *
	 * @var string
	 */
	private string $legacy_version = '2.0.7';

	/**
	 * Get singleton instance
	 *
	 * @return Assets
	 */
	public static function instance(): Assets {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization
	 *
	 * @throws \Exception When trying to unserialize.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize the component
	 */
	public function init(): void {
		add_filter( 'style_loader_src', $this->add_custom_version_to_stylesheet( ... ), 10, 1 );
		add_action( 'wp_enqueue_scripts', $this->enqueue_scripts( ... ) );
		add_action( 'login_enqueue_scripts', $this->login_styles( ... ) );

		// Deregister default jQuery and use latest version.
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', $this->use_latest_jquery( ... ), 1 );
		}
	}

	/**
	 * Get component loading priority
	 *
	 * @return int Priority (lower = earlier)
	 */
	public function get_priority(): int {
		return 5; // Load very early, before most components.
	}

	/**
	 * Check if component should load
	 *
	 * @return bool Always true - assets always needed
	 */
	public function should_load(): bool {
		return true;
	}

	/**
	 * Add custom version to stylesheet for cache busting
	 *
	 * @param string $src The original source URL of the stylesheet.
	 * @return string Modified source URL with the custom version added.
	 */
	public function add_custom_version_to_stylesheet( string $src ): string {
		if ( strpos( $src, 'style.css' ) !== false ) {
			$src = add_query_arg( 'ver', $this->legacy_version, $src );
		}
		return $src;
	}

	/**
	 * Enqueue theme scripts and styles
	 */
	public function enqueue_scripts(): void {
		$theme_uri = get_template_directory_uri();

		// CSS Variables - Load FIRST (highest priority).
		wp_enqueue_style(
			'soma-variables',
			$theme_uri . '/assets/css/variables.css',
			array(),
			$this->version
		);

		// Core styles.
		wp_enqueue_style(
			'core',
			$theme_uri . '/style.css',
			array( 'soma-variables' )
		);

		wp_enqueue_style(
			'main-styles',
			$theme_uri . '/css/main.bundle.css',
			array( 'soma-variables' ),
			$this->legacy_version
		);

		// Scripts.
		wp_enqueue_script(
			'main-scripts',
			$theme_uri . '/js/main.bundle.js',
			array( 'jquery' ),
			$this->legacy_version,
			true
		);
	}

	/**
	 * Use latest jQuery version
	 */
	public function use_latest_jquery(): void {
		wp_deregister_script( 'jquery' );
		wp_register_script(
			'jquery',
			'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js',
			array(),
			'3.5.1',
			false
		);
		wp_enqueue_script( 'jquery' );
	}

	/**
	 * Login page styles
	 */
	public function login_styles(): void {
		$theme_uri = get_template_directory_uri();
		?>
		<style type="text/css">
			body {
				background-color: #222 !important;
			}
			#login h1 a, .login h1 a {
				display: none;
			}
			#login h1 img {
				width: 100%;
				max-width: 240px;
				max-height: 180px;
			}
		</style>
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function(event) { 
				let loginImg = document.createElement("img");
				loginImg.src = "<?php echo esc_url( $theme_uri . '/images/soma_white.svg' ); ?>";
				loginImg.alt = "WordPress login image";
				document.querySelector('#login h1').appendChild(loginImg);
			});
		</script>
		<?php
	}
}
