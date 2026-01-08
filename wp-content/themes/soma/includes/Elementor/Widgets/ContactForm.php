<?php
/**
 * Contact Form Elementor Widget
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
 * Contact Form widget class
 *
 * Integrates Contact Form 7 with Elementor.
 *
 * @todo Implement full styling controls
 */
class ContactForm extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-contact-form';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Contact Form', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-form-horizontal';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return array( 'soma-contact-form' );
	}

	/**
	 * Register widget controls
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->start_controls_section(
			'section_form',
			array(
				'label' => __( 'Form', 'soma' ),
			)
		);

		$cf7_forms = $this->get_cf7_forms();

		$this->add_control(
			'form_id',
			array(
				'label'       => __( 'Select Form', 'soma' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $cf7_forms,
				'default'     => ! empty( $cf7_forms ) ? array_key_first( $cf7_forms ) : '',
				'label_block' => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['form_id'] ) ) {
			echo '<p>' . esc_html__( 'Please select a contact form.', 'soma' ) . '</p>';
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- do_shortcode is safe
		echo do_shortcode( sprintf( '[contact-form-7 id="%d"]', absint( $settings['form_id'] ) ) );
	}

	/**
	 * Get available CF7 forms
	 *
	 * @return array
	 */
	private function get_cf7_forms(): array {
		$forms     = array();
		$cf7_forms = get_posts(
			array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		foreach ( $cf7_forms as $form ) {
			$forms[ $form->ID ] = get_the_title( $form->ID );
		}

		return $forms;
	}
}
