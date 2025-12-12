<?php
/**
 * Elementor Widget Base
 *
 * Abstract base class for all Soma Elementor widgets.
 *
 * @package Soma
 * @subpackage Elementor\Base
 * @since 3.0.0
 */

namespace Soma\Elementor\Base;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract base class for Soma widgets
 *
 * Provides common functionality for all Soma Elementor widgets:
 * - ACF integration helpers
 * - CSS variable support
 * - Common control patterns
 * - Consistent icon definitions
 */
abstract class WidgetBase extends Widget_Base {

	/**
	 * Widget category
	 *
	 * @return string
	 */
	public function get_categories(): array {
		return [ 'soma' ];
	}

	/**
	 * Get widget icon
	 *
	 * Default icon for Soma widgets. Override in child classes for custom icons.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-posts-grid';
	}

	/**
	 * Get ACF field value
	 *
	 * Helper to retrieve ACF field values with fallback.
	 *
	 * @param string   $field_name ACF field name.
	 * @param int|null $post_id    Post ID (null for current post).
	 * @param mixed    $default    Default value if field doesn't exist.
	 * @return mixed
	 */
	protected function get_acf_field( string $field_name, ?int $post_id = null, $default = null ) {
		if ( ! function_exists( 'get_field' ) ) {
			soma_log_warning(
				'ACF not available in Elementor widget',
				[
					'widget' => $this->get_name(),
					'field'  => $field_name,
				]
			);
			return $default;
		}

		$value = get_field( $field_name, $post_id );
		return $value !== false ? $value : $default;
	}

	/**
	 * Get options page ACF field
	 *
	 * Helper to retrieve ACF options page values.
	 *
	 * @param string $field_name ACF field name.
	 * @param mixed  $default    Default value if field doesn't exist.
	 * @return mixed
	 */
	protected function get_acf_option( string $field_name, $default = null ) {
		return $this->get_acf_field( $field_name, 'options', $default );
	}

	/**
	 * Register common typography control
	 *
	 * Adds typography control with CSS variable support.
	 *
	 * @param string $control_name Control name/ID.
	 * @param string $label        Control label.
	 * @param string $selector     CSS selector.
	 * @param array  $default      Default typography settings.
	 */
	protected function add_typography_control(
		string $control_name,
		string $label,
		string $selector,
		array $default = []
	): void {
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => $control_name,
				'label'          => $label,
				'selector'       => $selector,
				'fields_options' => array_merge(
					[
						'font_family' => [
							'default' => 'var(--soma-font-primary)',
						],
						'font_size'   => [
							'default' => [
								'unit' => 'rem',
								'size' => 1,
							],
						],
					],
					$default
				),
			]
		);
	}

	/**
	 * Register common spacing control
	 *
	 * Adds responsive spacing control (margin/padding).
	 *
	 * @param string $type      'margin' or 'padding'.
	 * @param string $selector  CSS selector.
	 * @param array  $default   Default spacing values.
	 */
	protected function add_spacing_control(
		string $type,
		string $selector,
		array $default = []
	): void {
		$this->add_responsive_control(
			$type,
			[
				'label'      => ucfirst( $type ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'selectors'  => [
					$selector => sprintf(
						'%s: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						$type
					),
				],
				'default'    => $default,
			]
		);
	}

	/**
	 * Register color control with CSS variable default
	 *
	 * @param string $control_name Control name/ID.
	 * @param string $label        Control label.
	 * @param string $selector     CSS selector.
	 * @param string $property     CSS property (e.g., 'color', 'background-color').
	 * @param string $default      Default CSS variable (e.g., 'var(--soma-primary)').
	 */
	protected function add_color_control(
		string $control_name,
		string $label,
		string $selector,
		string $property = 'color',
		string $default = ''
	): void {
		$this->add_control(
			$control_name,
			[
				'label'     => $label,
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector => sprintf( '%s: {{VALUE}};', $property ),
				],
				'default'   => $default,
			]
		);
	}

	/**
	 * Register border control group
	 *
	 * @param string $selector CSS selector.
	 */
	protected function add_border_control( string $selector ): void {
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'border',
				'selector' => $selector,
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Register box shadow control group
	 *
	 * @param string $selector CSS selector.
	 */
	protected function add_shadow_control( string $selector ): void {
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => $selector,
			]
		);
	}

	/**
	 * Register background control group
	 *
	 * @param string $selector CSS selector.
	 */
	protected function add_background_control( string $selector ): void {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => $selector,
			]
		);
	}

	/**
	 * Get navigation menu choices
	 *
	 * Returns array of registered nav menus for select control.
	 *
	 * @return array
	 */
	protected function get_menu_choices(): array {
		$menus   = wp_get_nav_menus();
		$choices = [ '' => __( 'Select Menu', 'soma' ) ];

		foreach ( $menus as $menu ) {
			$choices[ $menu->term_id ] = $menu->name;
		}

		return $choices;
	}

	/**
	 * Get post type choices
	 *
	 * Returns array of public post types for select control.
	 *
	 * @param array $exclude Post types to exclude.
	 * @return array
	 */
	protected function get_post_type_choices( array $exclude = [ 'attachment' ] ): array {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$choices    = [];

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type->name, $exclude, true ) ) {
				continue;
			}
			$choices[ $post_type->name ] = $post_type->label;
		}

		return $choices;
	}

	/**
	 * Get taxonomy choices for a post type
	 *
	 * @param string $post_type Post type name.
	 * @return array
	 */
	protected function get_taxonomy_choices( string $post_type ): array {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$choices    = [ '' => __( 'All', 'soma' ) ];

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! $taxonomy->public ) {
				continue;
			}
			$choices[ $taxonomy->name ] = $taxonomy->label;
		}

		return $choices;
	}

	/**
	 * Render attribute
	 *
	 * Safely outputs an HTML attribute with escaped value.
	 *
	 * @param string $attribute Attribute name.
	 * @param mixed  $value     Attribute value.
	 */
	protected function render_attribute( string $attribute, $value ): void {
		if ( empty( $value ) ) {
			return;
		}

		printf(
			' %s="%s"',
			esc_attr( $attribute ),
			esc_attr( $value )
		);
	}

	/**
	 * Render CSS classes
	 *
	 * @param array $classes Array of CSS class names.
	 */
	protected function render_classes( array $classes ): void {
		$sanitized = array_map( 'sanitize_html_class', array_filter( $classes ) );
		if ( ! empty( $sanitized ) ) {
			printf( ' class="%s"', esc_attr( implode( ' ', $sanitized ) ) );
		}
	}
}
