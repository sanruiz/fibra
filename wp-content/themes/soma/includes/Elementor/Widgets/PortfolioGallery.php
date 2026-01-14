<?php
/**
 * PortfolioGallery Elementor Widget.
 *
 * Hero slider for portfolio single pages displaying project images
 * from ACF gallery field with optional video support.
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
 * PortfolioGallery widget for displaying project hero slider.
 *
 * Displays images from the project_gallery ACF field in a full-width
 * Slick carousel with navigation arrows and optional video overlay.
 *
 * @since 3.1.17
 */
class PortfolioGallery extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'soma-portfolio-gallery';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'SOMA Portfolio Gallery', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-slider-push';
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
		return array( 'soma-portfolio-gallery' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends(): array {
		return array( 'slick' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		// Content Section.
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'data_source',
			array(
				'label'       => esc_html__( 'Data Source', 'soma' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => esc_html__( 'Auto-detect from URL', 'soma' ),
					'manual' => esc_html__( 'Select Portfolio Item', 'soma' ),
				),
				'description' => esc_html__( 'Auto-detect uses the current portfolio post from URL context.', 'soma' ),
			)
		);

		$this->add_control(
			'portfolio_id',
			array(
				'label'       => esc_html__( 'Portfolio Item', 'soma' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_portfolio_options(),
				'default'     => '',
				'condition'   => array(
					'data_source' => 'manual',
				),
				'description' => esc_html__( 'Select a portfolio item to display its gallery.', 'soma' ),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => esc_html__( 'Video URL', 'soma' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://vimeo.com/...',
				'default'     => array(
					'url' => '',
				),
				'description' => esc_html__( 'Optional Vimeo URL for play button overlay.', 'soma' ),
			)
		);

		$this->add_control(
			'show_navigation',
			array(
				'label'        => esc_html__( 'Show Navigation', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'        => esc_html__( 'Show Dots', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'soma' ),
				'label_off'    => esc_html__( 'Hide', 'soma' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soma' ),
				'label_off'    => esc_html__( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed (ms)', 'soma' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'min'       => 1000,
				'max'       => 10000,
				'step'      => 500,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Layout Section.
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => esc_html__( 'Layout', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'slider_height',
			array(
				'label'      => esc_html__( 'Slider Height', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'vh',
					'size' => 60,
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-portfolio-gallery__slide' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_fit',
			array(
				'label'     => esc_html__( 'Image Fit', 'soma' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => esc_html__( 'Cover', 'soma' ),
					'contain' => esc_html__( 'Contain', 'soma' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-gallery__image' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Navigation.
		$this->start_controls_section(
			'style_navigation',
			array(
				'label'     => esc_html__( 'Navigation', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_navigation' => 'yes',
				),
			)
		);

		$this->add_control(
			'nav_color',
			array(
				'label'     => esc_html__( 'Arrow Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-gallery__nav' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'nav_bg_color',
			array(
				'label'     => esc_html__( 'Arrow Background', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.3)',
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-gallery__nav' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'nav_size',
			array(
				'label'      => esc_html__( 'Arrow Size', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-portfolio-gallery__nav' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Play Button.
		$this->start_controls_section(
			'style_play_button',
			array(
				'label' => esc_html__( 'Play Button', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'play_button_size',
			array(
				'label'      => esc_html__( 'Button Size', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 150,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 80,
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-portfolio-gallery__play' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'play_button_color',
			array(
				'label'     => esc_html__( 'Button Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-gallery__play' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_button_bg',
			array(
				'label'     => esc_html__( 'Button Background', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.5)',
				'selectors' => array(
					'{{WRAPPER}} .soma-portfolio-gallery__play' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get portfolio options for SELECT2 control.
	 *
	 * @return array Portfolio ID => Title pairs.
	 */
	private function get_portfolio_options(): array {
		$options = array();

		$portfolios = get_posts(
			array(
				'post_type'      => 'portfolio',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		foreach ( $portfolios as $portfolio ) {
			// Use get_the_title() for WP-Multilang compatibility.
			$options[ $portfolio->ID ] = get_the_title( $portfolio->ID );
		}

		return $options;
	}

	/**
	 * Get the portfolio post ID to use.
	 *
	 * @param array $settings Widget settings.
	 * @return int|null Portfolio post ID or null.
	 */
	private function get_portfolio_id( array $settings ): ?int {
		if ( 'manual' === $settings['data_source'] && ! empty( $settings['portfolio_id'] ) ) {
			return (int) $settings['portfolio_id'];
		}

		// Auto-detect from URL context.
		$queried_object = get_queried_object();
		if ( $queried_object instanceof \WP_Post && 'portfolio' === $queried_object->post_type ) {
			return $queried_object->ID;
		}

		return null;
	}

	/**
	 * Get gallery images from ACF field.
	 *
	 * @param int $post_id Portfolio post ID.
	 * @return array Gallery images array.
	 */
	private function get_gallery_images( int $post_id ): array {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		$gallery = get_field( 'project_gallery', $post_id );

		return is_array( $gallery ) ? $gallery : array();
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings     = $this->get_settings_for_display();
		$portfolio_id = $this->get_portfolio_id( $settings );

		if ( ! $portfolio_id ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soma-portfolio-gallery__notice">';
				echo esc_html__( 'No portfolio item detected. Select one manually or use on a portfolio single page.', 'soma' );
				echo '</div>';
			}
			return;
		}

		$gallery = $this->get_gallery_images( $portfolio_id );

		if ( empty( $gallery ) ) {
			// Fallback to featured image if no gallery.
			$featured_id = get_post_thumbnail_id( $portfolio_id );
			if ( $featured_id ) {
				$gallery = array(
					array(
						'ID'    => $featured_id,
						'url'   => wp_get_attachment_image_url( $featured_id, 'full' ),
						'alt'   => get_post_meta( $featured_id, '_wp_attachment_image_alt', true ),
						'title' => get_the_title( $featured_id ),
					),
				);
			}
		}

		if ( empty( $gallery ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soma-portfolio-gallery__notice">';
				echo esc_html__( 'No gallery images found. Add images to the Project Gallery field.', 'soma' );
				echo '</div>';
			}
			return;
		}

		$show_nav    = 'yes' === $settings['show_navigation'];
		$show_dots   = 'yes' === $settings['show_dots'];
		$autoplay    = 'yes' === $settings['autoplay'];
		$video_url   = ! empty( $settings['video_url']['url'] ) ? $settings['video_url']['url'] : '';
		$has_video   = ! empty( $video_url );
		$image_count = count( $gallery );

		$slider_options = wp_json_encode(
			array(
				'arrows'         => $show_nav && $image_count > 1,
				'dots'           => $show_dots && $image_count > 1,
				'autoplay'       => $autoplay,
				'autoplaySpeed'  => (int) $settings['autoplay_speed'],
				'infinite'       => $image_count > 1,
				'slidesToShow'   => 1,
				'slidesToScroll' => 1,
				'fade'           => true,
				'cssEase'        => 'ease-in-out',
				'prevArrow'      => '<button type="button" class="soma-portfolio-gallery__nav soma-portfolio-gallery__nav--prev" aria-label="Previous"><i class="eicon-chevron-left"></i></button>',
				'nextArrow'      => '<button type="button" class="soma-portfolio-gallery__nav soma-portfolio-gallery__nav--next" aria-label="Next"><i class="eicon-chevron-right"></i></button>',
			)
		);

		?>
		<div class="soma-portfolio-gallery" data-slick-options="<?php echo esc_attr( $slider_options ); ?>">
			<div class="soma-portfolio-gallery__slider">
				<?php foreach ( $gallery as $image ) : ?>
					<?php
					$image_url = isset( $image['url'] ) ? $image['url'] : '';
					$image_alt = isset( $image['alt'] ) ? $image['alt'] : get_the_title( $portfolio_id );
					?>
					<div class="soma-portfolio-gallery__slide">
						<img 
							class="soma-portfolio-gallery__image" 
							src="<?php echo esc_url( $image_url ); ?>" 
							alt="<?php echo esc_attr( $image_alt ); ?>"
							loading="lazy"
						>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $has_video ) : ?>
				<a 
					href="<?php echo esc_url( $video_url ); ?>" 
					class="soma-portfolio-gallery__play"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php echo esc_attr__( 'Play Video', 'soma' ); ?>"
				>
					<i class="eicon-play"></i>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
