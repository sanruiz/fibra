<?php
/**
 * Elementor Breadcrumb Widget
 *
 * Clean breadcrumb navigation widget for content pages.
 * Uses soma_get_breadcrumb_items() helper function for breadcrumb generation.
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.7
 */

namespace Soma\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Breadcrumb Widget Class
 *
 * @since 3.1.7
 */
class Breadcrumb extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @since 3.1.7
	 * @return string Widget name
	 */
	public function get_name(): string {
		return 'soma-breadcrumb';
	}

	/**
	 * Get widget title
	 *
	 * @since 3.1.7
	 * @return string Widget title
	 */
	public function get_title(): string {
		return __( 'SOMA Breadcrumb', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @since 3.1.7
	 * @return string Widget icon
	 */
	public function get_icon(): string {
		return 'eicon-navigation-horizontal';
	}

	/**
	 * Get widget categories
	 *
	 * @since 3.1.7
	 * @return array<string> Widget categories
	 */
	public function get_categories(): array {
		return array( 'soma' );
	}

	/**
	 * Get widget keywords
	 *
	 * @since 3.1.7
	 * @return array<string> Widget keywords
	 */
	public function get_keywords(): array {
		return array( 'breadcrumb', 'navigation', 'seo', 'soma' );
	}

	/**
	 * Get style dependencies
	 *
	 * @since 3.1.7
	 * @return array<string> Style dependencies
	 */
	public function get_style_depends(): array {
		return array( 'soma-breadcrumb' );
	}

	/**
	 * Register widget controls
	 *
	 * @since 3.1.7
	 * @return void
	 */
	protected function register_controls(): void {
		// Content Section.
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Breadcrumb Settings', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'       => __( 'Separator', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '/',
				'description' => __( 'Character or text to separate breadcrumb items', 'soma' ),
			)
		);

		$this->add_control(
			'show_home',
			array(
				'label'        => __( 'Show Home', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'soma' ),
				'label_off'    => __( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'show_current',
			array(
				'label'        => __( 'Show Current Page', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'soma' ),
				'label_off'    => __( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Style Section.
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'breadcrumb_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .soma-breadcrumb',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-breadcrumb' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'Link Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-breadcrumb a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-breadcrumb a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'current_color',
			array(
				'label'     => __( 'Current Page Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7E7E87',
				'selectors' => array(
					'{{WRAPPER}} .soma-breadcrumb .current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Separator Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7E7E87',
				'selectors' => array(
					'{{WRAPPER}} .soma-breadcrumb .separator' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'soma' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'soma' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'soma' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'soma' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .soma-breadcrumb' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'spacing',
			array(
				'label'      => __( 'Item Spacing', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-breadcrumb .separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get breadcrumb items
	 *
	 * @since 3.1.7
	 * @return array<int, array<string, mixed>> Array of breadcrumb items
	 */
	private function get_breadcrumb_items(): array {
		// Use the helper function from global helpers.
		if ( function_exists( 'soma_get_breadcrumb_items' ) ) {
			return soma_get_breadcrumb_items();
		}

		// Fallback if helper function not available.
		return array(
			array(
				'name'       => __( 'Home', 'soma' ),
				'url'        => home_url(),
				'is_current' => false,
			),
		);
	}

	/**
	 * Render widget output
	 *
	 * @since 3.1.7
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		// Get breadcrumb items.
		$breadcrumb_items = $this->get_breadcrumb_items();

		// Filter items based on settings.
		if ( 'yes' !== $settings['show_home'] && ! empty( $breadcrumb_items ) ) {
			// Remove home item (first item).
			array_shift( $breadcrumb_items );
		}

		if ( 'yes' !== $settings['show_current'] && ! empty( $breadcrumb_items ) ) {
			// Remove current page (last item).
			array_pop( $breadcrumb_items );
		}

		// Skip if no items or only one item left.
		if ( empty( $breadcrumb_items ) || count( $breadcrumb_items ) < 1 ) {
			return;
		}

		$separator = $settings['separator'] ?? '/';

		?>
		<nav class="soma-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb Navigation', 'soma' ); ?>">
			<?php
			$total_items = \count( $breadcrumb_items );

			foreach ( $breadcrumb_items as $index => $item ) :
				$position   = $index + 1;
				$is_last    = ( $position === $total_items );
				$item_name  = $item['name'] ?? '';
				$item_url   = $item['url'] ?? '';
				$is_current = $item['is_current'] ?? $is_last;

				// Skip empty items.
				if ( empty( $item_name ) ) {
					continue;
				}
				?>

				<span>
					<?php if ( ! $is_current && ! empty( $item_url ) ) : ?>
						<a href="<?php echo esc_url( $item_url ); ?>">
							<span><?php echo esc_html( $item_name ); ?></span>
						</a>
					<?php else : ?>
						<span class="current">
							<?php echo esc_html( $item_name ); ?>
						</span>
					<?php endif; ?>
				</span>

				<?php if ( ! $is_last ) : ?>
					<span class="separator"><?php echo esc_html( $separator ); ?></span>
				<?php endif; ?>

			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * Render widget output in the editor
	 *
	 * @since 3.1.7
	 * @return void
	 */
	protected function content_template(): void {
		?>
		<#
		var breadcrumbItems = [
			{ name: 'Home', url: '/', is_current: false },
			{ name: 'Services', url: '/services/', is_current: false },
			{ name: 'Current Page', url: '', is_current: true }
		];

		if ( 'yes' !== settings.show_home && breadcrumbItems.length > 0 ) {
			breadcrumbItems.shift();
		}

		if ( 'yes' !== settings.show_current && breadcrumbItems.length > 0 ) {
			breadcrumbItems.pop();
		}

		if ( breadcrumbItems.length < 1 ) {
			return;
		}

		var separator = settings.separator || '/';
		#>
		<nav class="soma-breadcrumb" aria-label="Breadcrumb Navigation">
			<# _.each( breadcrumbItems, function( item, index ) {
				var isLast = ( index === breadcrumbItems.length - 1 );
				var isCurrent = item.is_current || isLast;
			#>
				<span>
					<# if ( ! isCurrent && item.url ) { #>
						<a href="{{ item.url }}">
							<span>{{ item.name }}</span>
						</a>
					<# } else { #>
						<span class="current">{{ item.name }}</span>
					<# } #>
				</span>

				<# if ( ! isLast ) { #>
					<span class="separator">{{ separator }}</span>
				<# } #>
			<# }); #>
		</nav>
		<?php
	}
}
