<?php
/**
 * Related Projects Elementor Widget.
 *
 * Displays portfolio projects that share the same taxonomy term as the current project.
 * Designed to be used at the end of portfolio single pages.
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.23
 */

namespace Soma\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RelatedProjects Widget Class.
 *
 * @since 3.1.23
 */
class RelatedProjects extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'soma-related-projects';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return __( 'SOMA Related Projects', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-posts-grid';
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
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords(): array {
		return array( 'related', 'projects', 'portfolio', 'grid', 'soma' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends(): array {
		return array( 'soma-related-projects' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @return void
	 */
	private function register_content_controls(): void {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'section_title',
			array(
				'label'       => __( 'Section Title', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Related Projects', 'soma' ),
				'placeholder' => __( 'Related Projects', 'soma' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Number of Projects', 'soma' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 12,
				'default' => 4,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
			)
		);

		$this->add_control(
			'show_city',
			array(
				'label'        => __( 'Show City', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'        => __( 'Show Category', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'rand',
				'options' => array(
					'rand'       => __( 'Random', 'soma' ),
					'date'       => __( 'Date', 'soma' ),
					'title'      => __( 'Title', 'soma' ),
					'menu_order' => __( 'Menu Order', 'soma' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @return void
	 */
	private function register_style_controls(): void {
		// Section Style.
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Section', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style_variant',
			array(
				'label'   => __( 'Style Variant', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => array(
					'dark'  => __( 'Dark Background', 'soma' ),
					'light' => __( 'Light Background', 'soma' ),
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-related-projects' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'section_padding',
			array(
				'label'      => __( 'Padding', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 80,
					'right'  => 0,
					'bottom' => 80,
					'left'   => 0,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-related-projects' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Title Style.
		$this->start_controls_section(
			'title_style',
			array(
				'label' => __( 'Title', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-related-projects__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Title Typography', 'soma' ),
				'selector' => '{{WRAPPER}} .soma-related-projects__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin Bottom', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-related-projects__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Card Style.
		$this->start_controls_section(
			'card_style',
			array(
				'label' => __( 'Card', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => __( 'Grid Gap', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 24,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-related-projects__grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_name_color',
			array(
				'label'     => __( 'Project Name Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-related-projects__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_name_typography',
				'label'    => __( 'Project Name Typography', 'soma' ),
				'selector' => '{{WRAPPER}} .soma-related-projects__name',
			)
		);

		$this->add_control(
			'card_meta_color',
			array(
				'label'     => __( 'Meta Color (City/Category)', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-related-projects__city, {{WRAPPER}} .soma-related-projects__type' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_meta_typography',
				'label'    => __( 'Meta Typography', 'soma' ),
				'selector' => '{{WRAPPER}} .soma-related-projects__city, {{WRAPPER}} .soma-related-projects__type',
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => __( 'Image Height', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 150,
						'max' => 500,
					),
					'vh' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 250,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .soma-related-projects__image' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$current_post_id = get_the_ID();

		// Parent/umbrella taxonomy terms to exclude from filtering and display.
		$excluded_slugs = array( 'soma_real_estate', 'soma_construction', 'fibrasoma' );

		// Get current project's taxonomy terms (excluding parent/umbrella terms).
		$current_terms = get_the_terms( $current_post_id, 'portfolio-taxonomy' );
		$term_slugs    = array();

		if ( $current_terms && ! is_wp_error( $current_terms ) ) {
			foreach ( $current_terms as $current_term ) {
				if ( ! in_array( $current_term->slug, $excluded_slugs, true ) ) {
					$term_slugs[] = $current_term->slug;
				}
			}
		}

		// Only query related projects if we have valid terms.
		if ( empty( $term_slugs ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p style="padding: 20px; text-align: center;">' . esc_html__( 'No taxonomy terms found for current post. Related projects will appear here.', 'soma' ) . '</p>';
			}
			return;
		}

		$related_args = array(
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => intval( $settings['posts_per_page'] ),
			'post__not_in'   => array( $current_post_id ),
			'orderby'        => $settings['orderby'],
			'tax_query'      => array(
				array(
					'taxonomy' => 'portfolio-taxonomy',
					'field'    => 'slug',
					'terms'    => $term_slugs,
				),
			),
		);

		$related_projects = new \WP_Query( $related_args );

		if ( ! $related_projects->have_posts() ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p style="padding: 20px; text-align: center;">' . esc_html__( 'No related projects found. They will appear here when available.', 'soma' ) . '</p>';
			}
			return;
		}

		$style_class = 'dark' === $settings['style_variant'] ? 'soma-related-projects--dark' : 'soma-related-projects--light';
		$columns     = intval( $settings['columns'] );
		?>
		<section class="soma-related-projects <?php echo esc_attr( $style_class ); ?>">
			<div class="soma-related-projects__container">
				<?php if ( ! empty( $settings['section_title'] ) ) : ?>
					<h2 class="soma-related-projects__title"><?php echo esc_html( $settings['section_title'] ); ?></h2>
				<?php endif; ?>

				<div class="soma-related-projects__grid soma-related-projects__grid--cols-<?php echo esc_attr( $columns ); ?>">
					<?php
					while ( $related_projects->have_posts() ) :
						$related_projects->the_post();
						$related_info  = get_field( 'project_info' );
						$related_city  = $related_info['city'] ?? '';
						$related_terms = get_the_terms( get_the_ID(), 'portfolio-taxonomy' );
						?>
						<a href="<?php the_permalink(); ?>" class="soma-related-projects__card">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="soma-related-projects__image">
									<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
								</div>
							<?php endif; ?>
							<div class="soma-related-projects__info">
								<h3 class="soma-related-projects__name"><?php the_title(); ?></h3>
								<?php if ( 'yes' === $settings['show_city'] && $related_city ) : ?>
									<span class="soma-related-projects__city"><?php echo esc_html( $related_city ); ?></span>
								<?php endif; ?>
								<?php if ( 'yes' === $settings['show_category'] && $related_terms && ! is_wp_error( $related_terms ) ) : ?>
									<?php foreach ( $related_terms as $related_term ) : ?>
										<?php if ( ! in_array( $related_term->slug, $excluded_slugs, true ) ) : ?>
											<span class="soma-related-projects__type"><?php echo esc_html( $related_term->name ); ?></span>
											<?php break; // Only show first valid term. ?>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	}
}
