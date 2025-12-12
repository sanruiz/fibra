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
use Elementor\Plugin as ElementorPlugin;

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
	private array $widgets = [
		'Navbar'        => Widgets\Navbar::class,
		'Footer'        => Widgets\Footer::class,
		'BusinessUnits' => Widgets\BusinessUnits::class,
		'Services'      => Widgets\Services::class,
		'TeamMembers'   => Widgets\TeamMembers::class,
		'NewsList'      => Widgets\NewsList::class,
		'Portfolio'     => Widgets\Portfolio::class,
		'ContactForm'   => Widgets\ContactForm::class,
	];

	/**
	 * Get singleton instance
	 *
	 * @return Loader
	 */
	public static function instance(): Loader {
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

		// Register widgets.
		add_action( 'elementor/widgets/register', $this->register_widgets( ... ) );

		\soma_log_info(
			'Elementor integration initialized',
			[
				'widgets' => count( $this->widgets ),
			]
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
			[
				'title' => __( 'Soma', 'soma' ),
				'icon'  => 'eicon-posts-grid',
			]
		);

		\soma_log_debug( 'Registered Elementor category: soma' );
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
					[
						'widget' => $name,
						'class'  => $class,
					]
				);
				continue;
			}

			try {
				$widgets_manager->register( new $class() );
				++$registered_count;

				\soma_log_debug(
					'Registered Elementor widget',
					[
						'widget' => $name,
						'class'  => $class,
					]
				);
			} catch ( \Exception $e ) {
				\soma_log_error(
					'Failed to register Elementor widget',
					[
						'widget' => $name,
						'error'  => $e->getMessage(),
					]
				);
			}
		}

		\soma_log_info(
			'Registered Elementor widgets',
			[
				'total'      => count( $this->widgets ),
				'registered' => $registered_count,
			]
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
