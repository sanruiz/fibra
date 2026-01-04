<?php
/**
 * Team Members Elementor Widget
 *
 * Displays team members grouped by category with different layouts.
 * - "Principals" category: 2-column large cards with portrait photos
 * - Other categories: 3-column standard grid
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.0.0
 * @since 3.1.9 Refactored with category grouping and multiple layouts
 */

namespace Soma\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Team Members widget class
 *
 * Queries team-members post type and displays them grouped by category with:
 * - Category section headers
 * - Special "Principals" layout (2 columns, larger cards)
 * - Standard layout for other categories (3 columns)
 * - B/W photos with placeholder support
 * - Responsive design
 */
class TeamMembers extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-team-members';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Team Members', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-person';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array<int, string>
	 */
	public function get_style_depends(): array {
		return array( 'soma-team-members' );
	}

	/**
	 * Register widget controls
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Get team members taxonomy terms as options
	 *
	 * @return array<int|string, string>
	 */
	private function get_team_categories(): array {
		$terms   = get_terms(
			array(
				'taxonomy'   => 'team-members-taxonomy',
				'hide_empty' => false,
			)
		);
		$options = array();

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name;
			}
		}

		return $options;
	}

	/**
	 * Register content controls
	 *
	 * @return void
	 */
	private function register_content_controls(): void {
		// Query section.
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'soma' ),
			)
		);

		$this->add_control(
			'categories',
			array(
				'label'       => __( 'Categories to Display', 'soma' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_team_categories(),
				'multiple'    => true,
				'label_block' => true,
				'description' => __( 'Select categories to display. Leave empty to show all.', 'soma' ),
			)
		);

		$this->add_control(
			'principals_category',
			array(
				'label'       => __( 'Principals Category', 'soma' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array( '' => __( 'None', 'soma' ) ) + $this->get_team_categories(),
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Select the category to display with the "Principals" layout (2 columns, larger cards).', 'soma' ),
			)
		);

		$this->add_control(
			'posts_per_category',
			array(
				'label'       => __( 'Members per Category', 'soma' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => -1,
				'min'         => -1,
				'max'         => 50,
				'description' => __( 'Number of members to show per category. -1 for all.', 'soma' ),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'menu_order',
				'options' => array(
					'menu_order' => __( 'Menu Order', 'soma' ),
					'date'       => __( 'Date', 'soma' ),
					'title'      => __( 'Title', 'soma' ),
					'modified'   => __( 'Modified Date', 'soma' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => __( 'Ascending', 'soma' ),
					'DESC' => __( 'Descending', 'soma' ),
				),
			)
		);

		$this->end_controls_section();

		// Display section.
		$this->start_controls_section(
			'section_display',
			array(
				'label' => __( 'Display', 'soma' ),
			)
		);

		$this->add_control(
			'show_category_titles',
			array(
				'label'        => __( 'Show Category Titles', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'category_title_tag',
			array(
				'label'     => __( 'Category Title Tag', 'soma' ),
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
				),
				'condition' => array(
					'show_category_titles' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_position',
			array(
				'label'        => __( 'Show Position', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'link_to_profile',
			array(
				'label'        => __( 'Link to Profile', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Link member name to their profile page (if not hidden).', 'soma' ),
			)
		);

		$this->add_control(
			'grayscale_images',
			array(
				'label'        => __( 'Grayscale Images', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Display photos in black and white.', 'soma' ),
			)
		);

		$this->add_control(
			'standard_columns',
			array(
				'label'   => __( 'Standard Grid Columns', 'soma' ),
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
			'principals_columns',
			array(
				'label'     => __( 'Principals Grid Columns', 'soma' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '2',
				'options'   => array(
					'2' => '2',
					'3' => '3',
				),
				'condition' => array(
					'principals_category!' => '',
				),
			)
		);

		$this->end_controls_section();

		// Labels section.
		$this->start_controls_section(
			'section_labels',
			array(
				'label' => __( 'Labels', 'soma' ),
			)
		);

		$this->add_control(
			'no_members_text',
			array(
				'label'   => __( 'No Members Text', 'soma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'No team members found.', 'soma' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 *
	 * @return void
	 */
	private function register_style_controls(): void {
		// Container styles.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => __( 'Container', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .soma-team-members' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => __( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-team-members' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Category Title styles.
		$this->start_controls_section(
			'section_style_category_title',
			array(
				'label'     => __( 'Category Title', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_category_titles' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_title_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .category-section .category-title',
			)
		);

		$this->add_control(
			'category_title_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .category-section .category-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_title_margin',
			array(
				'label'      => __( 'Margin Bottom', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 100,
					),
					'rem' => array(
						'min' => 0,
						'max' => 6,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .category-section .category-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Grid styles.
		$this->start_controls_section(
			'section_style_grid',
			array(
				'label' => __( 'Grid', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => __( 'Gap', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 100,
					),
					'rem' => array(
						'min' => 0,
						'max' => 6,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .team-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'section_spacing',
			array(
				'label'      => __( 'Section Spacing', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 150,
					),
					'rem' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => 80,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .category-section + .category-section' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Image styles.
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Image', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_aspect_ratio',
			array(
				'label'       => __( 'Aspect Ratio', 'soma' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array(
					'%' => array(
						'min' => 80,
						'max' => 150,
					),
				),
				'default'     => array(
					'size' => 120,
					'unit' => '%',
				),
				'selectors'   => array(
					'{{WRAPPER}} .team-member .member-image' => 'padding-top: {{SIZE}}{{UNIT}};',
				),
				'description' => __( '100% = Square, 120% = Portrait, 80% = Landscape', 'soma' ),
			)
		);

		$this->add_responsive_control(
			'principals_image_aspect_ratio',
			array(
				'label'      => __( 'Principals Aspect Ratio', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 80,
						'max' => 150,
					),
				),
				'default'    => array(
					'size' => 130,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .category-section.principals .team-member .member-image' => 'padding-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'placeholder_background',
			array(
				'label'     => __( 'Placeholder Background', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e0e0e0',
				'selectors' => array(
					'{{WRAPPER}} .team-member .member-image.no-image' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Name styles.
		$this->start_controls_section(
			'section_style_name',
			array(
				'label' => __( 'Name', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .team-member .member-name',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .team-member .member-name' => 'color: {{VALUE}};',
					'{{WRAPPER}} .team-member .member-name a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_hover_color',
			array(
				'label'     => __( 'Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .team-member .member-name a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'name_margin',
			array(
				'label'      => __( 'Margin Top', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 50,
					),
					'rem' => array(
						'min' => 0,
						'max' => 3,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .team-member .member-name' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'name_underline',
			array(
				'label'        => __( 'Underline on Hover', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Position styles.
		$this->start_controls_section(
			'section_style_position',
			array(
				'label'     => __( 'Position', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_position' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'position_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .team-member .member-position',
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .team-member .member-position' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'position_margin',
			array(
				'label'      => __( 'Margin Top', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 30,
					),
					'rem' => array(
						'min' => 0,
						'max' => 2,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .team-member .member-position' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get members grouped by category
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @return array<string, array<string, mixed>>
	 */
	private function get_grouped_members( array $settings ): array {
		$categories          = $settings['categories'] ?? array();
		$principals_category = $settings['principals_category'] ?? '';
		$posts_per_category  = (int) ( $settings['posts_per_category'] ?? -1 );
		$orderby             = $settings['orderby'] ?? 'menu_order';
		$order               = $settings['order'] ?? 'ASC';

		$grouped = array();

		// Get all terms if no specific categories selected.
		if ( empty( $categories ) ) {
			$terms = get_terms(
				array(
					'taxonomy'   => 'team-members-taxonomy',
					'hide_empty' => true,
				)
			);
			if ( ! is_wp_error( $terms ) ) {
				$categories = wp_list_pluck( $terms, 'slug' );
			}
		}

		// Ensure principals category is first if set and included.
		if ( ! empty( $principals_category ) && in_array( $principals_category, $categories, true ) ) {
			$categories = array_diff( $categories, array( $principals_category ) );
			array_unshift( $categories, $principals_category );
		}

		foreach ( $categories as $category_slug ) {
			$term = get_term_by( 'slug', $category_slug, 'team-members-taxonomy' );
			if ( ! $term ) {
				continue;
			}

			$args = array(
				'post_type'      => 'team-members',
				'post_status'    => 'publish',
				'posts_per_page' => $posts_per_category,
				'orderby'        => $orderby,
				'order'          => $order,
				'tax_query'      => array(
					array(
						'taxonomy' => 'team-members-taxonomy',
						'field'    => 'slug',
						'terms'    => $category_slug,
					),
				),
			);

			$members = get_posts( $args );

			if ( ! empty( $members ) ) {
				$grouped[ $category_slug ] = array(
					'term'         => $term,
					'members'      => $members,
					'is_principal' => ( $category_slug === $principals_category ),
				);
			}
		}

		return $grouped;
	}

	/**
	 * Render widget output
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$show_category_titles = 'yes' === $settings['show_category_titles'];
		$category_title_tag   = $settings['category_title_tag'] ?? 'h2';
		$show_position        = 'yes' === $settings['show_position'];
		$link_to_profile      = 'yes' === $settings['link_to_profile'];
		$grayscale_images     = 'yes' === $settings['grayscale_images'];
		$name_underline       = 'yes' === $settings['name_underline'];
		$standard_columns     = $settings['standard_columns'] ?? '3';
		$principals_columns   = $settings['principals_columns'] ?? '2';
		$no_members_text      = $settings['no_members_text'] ?? __( 'No team members found.', 'soma' );

		$grouped_members = $this->get_grouped_members( $settings );

		// Build widget classes.
		$widget_classes = array( 'soma-team-members' );
		if ( $grayscale_images ) {
			$widget_classes[] = 'grayscale';
		}
		if ( $name_underline ) {
			$widget_classes[] = 'name-underline';
		}
		?>
		<section class="<?php echo esc_attr( implode( ' ', $widget_classes ) ); ?>">
			<div class="container">
				<?php if ( ! empty( $grouped_members ) ) : ?>
					<?php foreach ( $grouped_members as $category_slug => $group ) : ?>
						<?php
						$section_classes = array( 'category-section' );
						if ( $group['is_principal'] ) {
							$section_classes[] = 'principals';
						}
						$columns = $group['is_principal'] ? $principals_columns : $standard_columns;
						?>
						<div class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
							<?php if ( $show_category_titles ) : ?>
								<<?php echo esc_html( $category_title_tag ); ?> class="category-title">
									<?php echo esc_html( $group['term']->name ); ?>
								</<?php echo esc_html( $category_title_tag ); ?>>
							<?php endif; ?>

							<div class="team-grid columns-<?php echo esc_attr( $columns ); ?>">
								<?php foreach ( $group['members'] as $member ) : ?>
									<?php
									$info         = get_field( 'team_member_info', $member->ID );
									$image_url    = get_the_post_thumbnail_url( $member->ID, 'large' );
									$member_title = $info['title'] ?? '';
									$hide_single  = ! empty( $info['hide_single_page'] );
									$can_link     = $link_to_profile && ! $hide_single;
									$profile_url  = $can_link ? get_permalink( $member->ID ) : '';
									?>
									<article class="team-member">
										<div class="member-image <?php echo ! $image_url ? 'no-image' : ''; ?>">
											<?php if ( $image_url ) : ?>
												<img 
													src="<?php echo esc_url( $image_url ); ?>" 
													alt="<?php echo esc_attr( get_the_title( $member->ID ) ); ?>"
													loading="lazy"
												>
											<?php endif; ?>
										</div>

										<div class="member-info">
											<div class="member-name">
												<?php if ( $can_link ) : ?>
													<a href="<?php echo esc_url( $profile_url ); ?>">
														<?php echo esc_html( get_the_title( $member->ID ) ); ?>
													</a>
												<?php else : ?>
													<?php echo esc_html( get_the_title( $member->ID ) ); ?>
												<?php endif; ?>
											</div>

											<?php if ( $show_position && $member_title ) : ?>
												<div class="member-position">
													<?php echo esc_html( $member_title ); ?>
												</div>
											<?php endif; ?>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p class="no-members"><?php echo esc_html( $no_members_text ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

