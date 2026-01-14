<?php
/**
 * Elementor Loader
 *
 * Initializes Elementor integration and registers custom widgets.
 *
 * @package Soma
 * @subpackage Elementor
 * @since 3.0.0
 */

namespace Soma\Elementor;

use Soma\Core\Interfaces\LoadableInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor integration loader
 *
 * Handles Elementor plugin integration:
 * - Registers custom widget category
 * - Loads and registers custom widgets
 * - Checks Elementor availability
 * - Provides admin notices
 */
class Loader implements LoadableInterface {

	/**
	 * Singleton instance
	 *
	 * @var Loader|null
	 */
	private static ?Loader $instance = null;

	/**
	 * Widget registry
	 *
	 * @var array<string, string> Widget class names
	 */
	private array $widgets = array(
		'AnnualReports'           => Widgets\AnnualReports::class,
		'Breadcrumb'              => Widgets\Breadcrumb::class,
		'BusinessUnits'           => Widgets\BusinessUnits::class,
		'ContactForm'             => Widgets\ContactForm::class,
		'Documents'               => Widgets\Documents::class,
		'Events'                  => Widgets\Events::class,
		'Footer'                  => Widgets\Footer::class,
		'Navbar'                  => Widgets\Navbar::class,
		'NewsList'                => Widgets\NewsList::class,
		'Portfolio'               => Widgets\Portfolio::class,
		'PortfolioGallery'        => Widgets\PortfolioGallery::class,
		'PortfolioTechnicalSpecs' => Widgets\PortfolioTechnicalSpecs::class,
		'Services'                => Widgets\Services::class,
		'TextWithReadMore'        => Widgets\TextWithReadMore::class,
		'ShareQuotation'          => Widgets\ShareQuotation::class,
		'StockPrice'              => Widgets\StockPrice::class,
		'TeamMember'              => Widgets\TeamMember::class,
		'TeamMembers'             => Widgets\TeamMembers::class,
	);

	/**
	 * Widget styles registry
	 *
	 * @var array<string, string> Widget style handles and files
	 */
	private array $widget_styles = array(
		'soma-annual-reports'            => 'annual-reports.css',
		'soma-breadcrumb'                => 'breadcrumb.css',
		'soma-business-units'            => 'business-units.css',
		'soma-contact-form'              => 'contact-form.css',
		'soma-documents'                 => 'documents.css',
		'soma-footer'                    => 'footer.css',
		'soma-navbar'                    => 'navbar.css',
		'soma-news-list'                 => 'news-list.css',
		'soma-portfolio'                 => 'portfolio.css',
		'soma-portfolio-gallery'         => 'portfolio-gallery.css',
		'soma-portfolio-technical-specs' => 'portfolio-technical-specs.css',
		'soma-services'                  => 'services.css',
		'soma-share-quotation'           => 'share-quotation.css',
		'soma-stock-price'               => 'stock-price.css',
		'soma-team-member'               => 'team-member.css',
		'soma-team-members'              => 'team-members.css',
		'soma-text-with-read-more'       => 'text-with-read-more.css',
	);

	/**
	 * Widget scripts registry
	 *
	 * @var array<string, array{file: string, deps: array<int, string>}> Widget script handles, files, and dependencies
	 */
	private array $widget_scripts = array(
		'soma-annual-reports'    => array(
			'file' => 'annualReports.js',
			'deps' => array( 'jquery' ),
		),
		'soma-portfolio'         => array(
			'file' => 'portfolio.js',
			'deps' => array( 'jquery' ),
		),
		'soma-portfolio-gallery'      => array(
			'file' => 'portfolio-gallery.js',
			'deps' => array( 'jquery', 'slick' ),
		),
		'soma-text-with-read-more'    => array(
			'file' => 'text-with-read-more.js',
			'deps' => array( 'jquery' ),
		),
	);

	/**
	 * Get singleton instance
	 *
	 * @return Loader
	 */
	public static function instance(): Loader {
		if ( null === self::$instance ) {
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
	 * @throws \Exception Cannot unserialize singleton.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Initialize Elementor integration
	 */
	public function init(): void {
		if ( ! $this->is_elementor_active() ) {
			add_action( 'admin_notices', $this->elementor_missing_notice( ... ) );
			\soma_log_warning( 'Elementor plugin not active' );
			return;
		}

		// Register custom category.
		add_action( 'elementor/elements/categories_registered', $this->register_category( ... ) );

		// Register widget styles.
		add_action( 'elementor/frontend/after_register_styles', $this->register_styles( ... ) );

		// Register widget scripts.
		add_action( 'elementor/frontend/after_register_scripts', $this->register_scripts( ... ) );

		// Register widgets.
		add_action( 'elementor/widgets/register', $this->register_widgets( ... ) );

		\soma_log_info(
			'Elementor integration initialized',
			array(
				'widgets' => count( $this->widgets ),
			)
		);
	}

	/**
	 * Get component loading priority
	 *
	 * @return int Priority (30 = after PostTypes, before API)
	 */
	public function get_priority(): int {
		return 30;
	}

	/**
	 * Check if component should load
	 *
	 * @return bool Always true (Elementor integration always attempts to load)
	 */
	public function should_load(): bool {
		return true;
	}

	/**
	 * Check if Elementor plugin is active
	 *
	 * @return bool
	 */
	private function is_elementor_active(): bool {
		return did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' );
	}

	/**
	 * Register custom widget category
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function register_category( $elements_manager ): void {
		$elements_manager->add_category(
			'soma',
			array(
				'title' => __( 'Soma', 'soma' ),
				'icon'  => 'eicon-posts-grid',
			)
		);

		\soma_log_debug( 'Registered Elementor category: soma' );
	}

	/**
	 * Register widget styles
	 *
	 * Registers (not enqueues) widget CSS files.
	 * Elementor will enqueue them only when widgets are used.
	 */
	public function register_styles(): void {
		$registered_count = 0;
		$theme_uri        = get_template_directory_uri();
		$theme_version    = wp_get_theme()->get( 'Version' );

		foreach ( $this->widget_styles as $handle => $filename ) {
			$file_path = get_template_directory() . '/assets/css/widgets/' . $filename;

			if ( ! file_exists( $file_path ) ) {
				\soma_log_warning(
					'Widget style file not found',
					array(
						'handle'   => $handle,
						'filename' => $filename,
						'path'     => $file_path,
					)
				);
				continue;
			}

			wp_register_style(
				$handle,
				$theme_uri . '/assets/css/widgets/' . $filename,
				array(),
				$theme_version
			);

			++$registered_count;

			\soma_log_debug(
				'Registered widget style',
				array(
					'handle' => $handle,
					'file'   => $filename,
				)
			);
		}

		\soma_log_info(
			'Registered Elementor widget styles',
			array(
				'total'      => \count( $this->widget_styles ),
				'registered' => $registered_count,
			)
		);
	}

	/**
	 * Register widget scripts
	 *
	 * Registers (not enqueues) widget JavaScript files.
	 * Elementor will enqueue them only when widgets are used.
	 */
	public function register_scripts(): void {
		$registered_count = 0;
		$theme_uri        = get_template_directory_uri();
		$theme_version    = wp_get_theme()->get( 'Version' );

		foreach ( $this->widget_scripts as $handle => $script_data ) {
			$file_path = get_template_directory() . '/assets/js/widgets/' . $script_data['file'];

			if ( ! file_exists( $file_path ) ) {
				\soma_log_warning(
					'Widget script file not found',
					array(
						'handle'   => $handle,
						'filename' => $script_data['file'],
						'path'     => $file_path,
					)
				);
				continue;
			}

			wp_register_script(
				$handle,
				$theme_uri . '/assets/js/widgets/' . $script_data['file'],
				$script_data['deps'],
				$theme_version,
				true
			);

			++$registered_count;

			\soma_log_debug(
				'Registered widget script',
				array(
					'handle' => $handle,
					'file'   => $script_data['file'],
				)
			);
		}

		\soma_log_info(
			'Registered Elementor widget scripts',
			array(
				'total'      => \count( $this->widget_scripts ),
				'registered' => $registered_count,
			)
		);
	}

	/**
	 * Register custom widgets
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ): void {
		$registered_count = 0;

		foreach ( $this->widgets as $name => $class ) {
			if ( ! class_exists( $class ) ) {
				\soma_log_warning(
					'Elementor widget class not found',
					array(
						'widget' => $name,
						'class'  => $class,
					)
				);
				continue;
			}

			try {
				$widgets_manager->register( new $class() );
				++$registered_count;

				\soma_log_debug(
					'Registered Elementor widget',
					array(
						'widget' => $name,
						'class'  => $class,
					)
				);
			} catch ( \Exception $e ) {
				\soma_log_error(
					'Failed to register Elementor widget',
					array(
						'widget' => $name,
						'error'  => $e->getMessage(),
					)
				);
			}
		}

		\soma_log_info(
			'Registered Elementor widgets',
			array(
				'total'      => count( $this->widgets ),
				'registered' => $registered_count,
			)
		);
	}

	/**
	 * Admin notice for missing Elementor plugin
	 */
	public function elementor_missing_notice(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			__( '<strong>%1$s</strong> requires <strong>%2$s</strong> plugin to be installed and activated for full functionality.', 'soma' ),
			'Soma Elementor Widgets',
			'Elementor'
		);

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			wp_kses_post( $message )
		);
	}

	/**
	 * Get registered widgets
	 *
	 * @return array<string, string>
	 */
	public function get_widgets(): array {
		return $this->widgets;
	}

	/**
	 * Check if a specific widget is registered
	 *
	 * @param string $widget_name Widget name.
	 * @return bool
	 */
	public function has_widget( string $widget_name ): bool {
		return isset( $this->widgets[ $widget_name ] );
	}
}
