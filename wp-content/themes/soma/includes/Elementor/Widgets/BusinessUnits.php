<?php
/**
 * Business Units Elementor Widget
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
 * Business Units widget class
 *
 * @todo Implement full controls and rendering
 */
class BusinessUnits extends WidgetBase {

	public function get_name(): string {
		return 'soma-business-units';
	}

	public function get_title(): string {
		return __( 'Business Units', 'soma' );
	}

	public function get_icon(): string {
		return 'eicon-gallery-grid';
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'soma' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'grid' => __( 'Grid', 'soma' ),
					'list' => __( 'List', 'soma' ),
				],
				'default' => 'grid',
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		?>
		<section class="soma-business-units businessunits-partial">
			<div class="container">
				<p><?php _e( 'Business Units Widget - To be implemented', 'soma' ); ?></p>
			</div>
		</section>
		<?php
	}
}

