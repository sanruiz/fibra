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
				'default' => '3',
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
		$posts_per_page  = intval( $settings['posts_per_page'] );
		$orderby         = $settings['orderby'];

		// Get current project's data for layered matching.
		$current_info         = soma_get_portfolio_project_info( $current_post_id );
		$current_city         = $current_info['city'];
		$current_year         = $current_info['year'];
		$current_categories   = get_the_terms( $current_post_id, 'portfolio-taxonomy' );
		$current_project_type = get_the_terms( $current_post_id, 'project-type' );

		// Find related projects using layered search strategy.
		$related_projects = $this->find_related_projects(
			$current_post_id,
			$posts_per_page,
			$orderby,
			$current_categories,
			$current_project_type,
			$current_city,
			$current_year
		);

		// No related projects found after all layers.
		if ( ! $related_projects || ! $related_projects->have_posts() ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p style="padding: 20px; text-align: center;">' . esc_html__( 'No related projects found. They will appear here when available.', 'soma' ) . '</p>';
			}
			return;
		}

		$this->render_projects_grid( $related_projects, $settings );
	}

	/**
	 * Find related projects using layered search strategy.
	 *
	 * Search order:
	 * 1. Portfolio Category (portfolio-taxonomy)
	 * 2. Project Type (project-type taxonomy)
	 * 3. City (ACF field)
	 * 4. Year (ACF field)
	 *
	 * @param int         $current_post_id    Current post ID to exclude.
	 * @param int         $posts_per_page     Number of posts to retrieve.
	 * @param string      $orderby            Order by parameter.
	 * @param array|false $current_categories Current post's portfolio-taxonomy terms.
	 * @param array|false $current_project_type Current post's project-type terms.
	 * @param string      $current_city       Current post's city.
	 * @param string      $current_year       Current post's year.
	 * @return \WP_Query|null Query object or null if no results.
	 */
	private function find_related_projects(
		int $current_post_id,
		int $posts_per_page,
		string $orderby,
		$current_categories,
		$current_project_type,
		string $current_city,
		string $current_year
	): ?\WP_Query {
		// Base query args.
		$base_args = array(
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'post__not_in'   => array( $current_post_id ),
			'orderby'        => $orderby,
		);

		// Layer 1: Search by Portfolio Category.
		if ( $current_categories && ! is_wp_error( $current_categories ) ) {
			$category_slugs = wp_list_pluck( $current_categories, 'slug' );
			$query          = $this->query_by_taxonomy( $base_args, 'portfolio-taxonomy', $category_slugs );
			if ( $query->have_posts() ) {
				return $query;
			}
		}

		// Layer 2: Search by Project Type.
		if ( $current_project_type && ! is_wp_error( $current_project_type ) ) {
			$type_slugs = wp_list_pluck( $current_project_type, 'slug' );
			$query      = $this->query_by_taxonomy( $base_args, 'project-type', $type_slugs );
			if ( $query->have_posts() ) {
				return $query;
			}
		}

		// Layer 3: Search by City.
		if ( ! empty( $current_city ) ) {
			$query = $this->query_by_meta( $base_args, 'project_info_city', $current_city );
			if ( $query->have_posts() ) {
				return $query;
			}
		}

		// Layer 4: Search by Year.
		if ( ! empty( $current_year ) ) {
			$query = $this->query_by_meta( $base_args, 'project_info_year', $current_year );
			if ( $query->have_posts() ) {
				return $query;
			}
		}

		// No matches found in any layer.
		return null;
	}

	/**
	 * Query portfolio items by taxonomy.
	 *
	 * @param array  $base_args Base query arguments.
	 * @param string $taxonomy  Taxonomy name.
	 * @param array  $terms     Term slugs to search.
	 * @return \WP_Query Query results.
	 */
	private function query_by_taxonomy( array $base_args, string $taxonomy, array $terms ): \WP_Query {
		if ( empty( $terms ) ) {
			return new \WP_Query(
				array_merge(
					$base_args,
					array(
						'post__in' => array( 0 ),
					)
				)
			);
		}

		$args              = $base_args;
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $terms,
			),
		);
		return new \WP_Query( $args );
	}

	/**
	 * Query portfolio items by ACF meta field.
	 *
	 * @param array  $base_args Base query arguments.
	 * @param string $meta_key  Meta key to search.
	 * @param string $value     Value to match.
	 * @return \WP_Query Query results.
	 */
	private function query_by_meta( array $base_args, string $meta_key, string $value ): \WP_Query {
		$args               = $base_args;
		$args['meta_query'] = array(
			array(
				'key'     => $meta_key,
				'value'   => $value,
				'compare' => '=',
			),
		);
		return new \WP_Query( $args );
	}

	/**
	 * Render the projects grid.
	 *
	 * @param \WP_Query $related_projects Query object with related projects.
	 * @param array     $settings         Widget settings.
	 * @return void
	 */
	private function render_projects_grid( \WP_Query $related_projects, array $settings ): void {
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
						$related_info = soma_get_portfolio_project_info();
						$related_city = $related_info['city'];
						$related_project_type = get_the_terms( get_the_ID(), 'project-type' );
						?>
						<a href="<?php the_permalink(); ?>" class="soma-related-projects__card">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="soma-related-projects__image">
									<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
								</div>
							<?php endif; ?>
							<div class="soma-related-projects__info">
								<h3 class="soma-related-projects__name"><?php echo esc_html( get_the_title() ); ?></h3>
								<?php if ( 'yes' === $settings['show_city'] && $related_city ) : ?>
									<span class="soma-related-projects__city"><?php echo esc_html( $related_city ); ?></span>
								<?php endif; ?>
								<?php if ( 'yes' === $settings['show_category'] && $related_project_type && ! is_wp_error( $related_project_type ) ) : ?>
									<?php
									$first_type = null;
									if ( isset( $related_project_type[0] ) ) {
										$first_type = $related_project_type[0];
									}
									?>
									<?php if ( $first_type ) : ?>
										<span class="soma-related-projects__type"><?php echo esc_html( $first_type->name ); ?></span>
									<?php endif; ?>
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
