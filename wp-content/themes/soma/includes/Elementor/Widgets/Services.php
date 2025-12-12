<?php
/**
 * Services Elementor Widget
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
 * Services widget class
 *
 * @todo Implement full controls and rendering
 */
class Services extends WidgetBase {

	public function get_name(): string {
		return 'soma-services';
	}

	public function get_title(): string {
		return __( 'Services', 'soma' );
	}

	public function get_icon(): string {
		return 'eicon-gallery-justified';
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'soma' ),
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => __( 'Columns', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'2' => __( '2 Columns', 'soma' ),
					'3' => __( '3 Columns', 'soma' ),
					'4' => __( '4 Columns', 'soma' ),
				],
				'default' => '3',
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		?>
		<section class="soma-services services-grid">
			<div class="container">
				<p><?php _e( 'Services Widget - To be implemented', 'soma' ); ?></p>
			</div>
		</section>
		<?php
	}
}

