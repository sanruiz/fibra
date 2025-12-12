<?php
/**
 * Footer Elementor Widget
 *
 * Elementor widget for site footer.
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.0.0
 */

namespace Soma\Elementor\Widgets;

use Soma\Elementor\Base\WidgetBase;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Footer widget class
 *
 * @todo Implement full controls and rendering
 */
class Footer extends WidgetBase {

	public function get_name(): string {
		return 'soma-footer';
	}

	public function get_title(): string {
		return __( 'Footer', 'soma' );
	}

	public function get_icon(): string {
		return 'eicon-footer';
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'soma' ),
			]
		);

		$this->add_control(
			'style_variant',
			[
				'label'   => __( 'Style Variant', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'   => __( 'Default', 'soma' ),
					'fibrasoma' => __( 'Fibrasoma', 'soma' ),
				],
				'default' => 'default',
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		?>
		<section class="soma-footer footer-partial-c90350">
			<div class="container">
				<p><?php _e( 'Footer Widget - To be implemented', 'soma' ); ?></p>
			</div>
		</section>
		<?php
	}
}

