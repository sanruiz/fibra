<?php
/**
 * TextWithReadMore Elementor Widget.
 *
 * Display text content with a "Read More" expandable feature.
 * Useful for long content sections that need to be truncated
 * with a user-controllable expand/collapse option.
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.17
 */

declare(strict_types=1);

namespace Soma\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TextWithReadMore widget for displaying expandable text content.
 *
 * Displays a title and content with configurable truncation.
 * Users can expand/collapse content via a "Read More"/"Read Less" button.
 *
 * @since 3.1.17
 */
class TextWithReadMore extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'soma-text-with-read-more';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'SOMA Text with Read More', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-post-content';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories(): array {
		return array( 'soma' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends(): array {
		return array( 'soma-text-with-read-more' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends(): array {
		return array( 'soma-text-with-read-more' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_display_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @return void
	 */
	protected function register_content_controls(): void {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Title', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soma' ),
				'label_off'    => esc_html__( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Section Title', 'soma' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => esc_html__( 'Title HTML Tag', 'soma' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'content',
			array(
				'label'       => esc_html__( 'Content', 'soma' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'Enter your content here. This text can be expanded or collapsed using the Read More button below.', 'soma' ),
				'placeholder' => esc_html__( 'Type your content here', 'soma' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register display controls.
	 *
	 * @return void
	 */
	protected function register_display_controls(): void {
		$this->start_controls_section(
			'section_display',
			array(
				'label' => esc_html__( 'Display Settings', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'max_lines',
			array(
				'label'       => esc_html__( 'Lines to Show', 'soma' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5,
				'min'         => 1,
				'max'         => 50,
				'step'        => 1,
				'description' => esc_html__( 'Number of text lines to show before truncating.', 'soma' ),
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label'       => esc_html__( 'Read More Text', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read More', 'soma' ),
				'label_block' => false,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'read_less_text',
			array(
				'label'       => esc_html__( 'Read Less Text', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read Less', 'soma' ),
				'label_block' => false,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => esc_html__( 'Show Toggle Icon', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soma' ),
				'label_off'    => esc_html__( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'animation_duration',
			array(
				'label'       => esc_html__( 'Animation Duration (ms)', 'soma' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 300,
				'min'         => 0,
				'max'         => 1000,
				'step'        => 50,
				'description' => esc_html__( 'Expand/collapse animation duration in milliseconds.', 'soma' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @return void
	 */
	protected function register_style_controls(): void {
		// Title Style.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-text-read-more__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .soma-text-read-more__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .soma-text-read-more__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '20',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_section();

		// Content Style.
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-text-read-more__content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .soma-text-read-more__content',
			)
		);

		$this->add_responsive_control(
			'content_line_height',
			array(
				'label'      => esc_html__( 'Line Height', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'em', 'px' ),
				'range'      => array(
					'em' => array(
						'min'  => 1,
						'max'  => 3,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 10,
						'max'  => 60,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 1.6,
					'unit' => 'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-text-read-more__content' => 'line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Button Style.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Read More Button', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-text-read-more__toggle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-text-read-more__toggle:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .soma-text-read-more__toggle',
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .soma-text-read-more__toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '15',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$show_title         = 'yes' === $settings['show_title'];
		$title              = $settings['title'] ?? '';
		$title_tag          = $settings['title_tag'] ?? 'h2';
		$content            = $settings['content'] ?? '';
		$max_lines          = absint( $settings['max_lines'] ?? 5 );
		$read_more_text     = $settings['read_more_text'] ?? esc_html__( 'Read More', 'soma' );
		$read_less_text     = $settings['read_less_text'] ?? esc_html__( 'Read Less', 'soma' );
		$show_icon          = 'yes' === $settings['show_icon'];
		$animation_duration = absint( $settings['animation_duration'] ?? 300 );

		// Get line height for calculating max height.
		$line_height = $settings['content_line_height']['size'] ?? 1.6;
		$line_unit   = $settings['content_line_height']['unit'] ?? 'em';

		if ( empty( $content ) ) {
			return;
		}

		$widget_id    = 'soma-text-read-more-' . $this->get_id();
		$is_collapsed = true;
		?>
		<div 
			id="<?php echo esc_attr( $widget_id ); ?>" 
			class="soma-text-read-more"
			data-max-lines="<?php echo esc_attr( (string) $max_lines ); ?>"
			data-line-height="<?php echo esc_attr( (string) $line_height ); ?>"
			data-line-unit="<?php echo esc_attr( $line_unit ); ?>"
			data-animation-duration="<?php echo esc_attr( (string) $animation_duration ); ?>"
			data-read-more="<?php echo esc_attr( $read_more_text ); ?>"
			data-read-less="<?php echo esc_attr( $read_less_text ); ?>"
		>
			<?php if ( $show_title && ! empty( $title ) ) : ?>
				<<?php echo esc_html( $title_tag ); ?> class="soma-text-read-more__title">
					<?php echo esc_html( $title ); ?>
				</<?php echo esc_html( $title_tag ); ?>>
			<?php endif; ?>

			<div class="soma-text-read-more__wrapper">
				<div class="soma-text-read-more__content" aria-expanded="false">
					<?php echo wp_kses_post( $content ); ?>
				</div>
				<div class="soma-text-read-more__gradient"></div>
			</div>

			<button 
				type="button" 
				class="soma-text-read-more__toggle" 
				aria-expanded="false"
				aria-controls="<?php echo esc_attr( $widget_id ); ?>-content"
			>
				<span class="soma-text-read-more__toggle-text"><?php echo esc_html( $read_more_text ); ?></span>
				<?php if ( $show_icon ) : ?>
					<span class="soma-text-read-more__toggle-icon" aria-hidden="true">
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				<?php endif; ?>
			</button>
		</div>
		<?php
	}
}
