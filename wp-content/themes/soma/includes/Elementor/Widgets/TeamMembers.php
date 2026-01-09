<?php
/**
 * Team Members Elementor Widget
 *
 * Displays team members from a selected category in a configurable grid.
 * Use multiple widget instances to show different categories with different layouts.
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.0.0
 * @since 3.1.9 Refactored to single-category modular design
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
 * Modular widget that displays team members from a single category.
 * Features:
 * - Single category selection per widget instance
 * - Configurable column count (2, 3, or 4)
 * - Optional category title display
 * - B/W photos with color on hover
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
		$options = array( '' => __( 'All Categories', 'soma' ) );

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
			'category',
			array(
				'label'       => __( 'Category', 'soma' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_team_categories(),
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Select a category to display. Use multiple widget instances for different categories.', 'soma' ),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'       => __( 'Number of Members', 'soma' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => -1,
				'min'         => -1,
				'max'         => 50,
				'description' => __( '-1 to show all members.', 'soma' ),
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
			'show_section_title',
			array(
				'label'        => __( 'Show Section Title', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Display the category name as section title.', 'soma' ),
			)
		);

		$this->add_control(
			'section_title_tag',
			array(
				'label'     => __( 'Title Tag', 'soma' ),
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
					'show_section_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_title',
			array(
				'label'       => __( 'Custom Title', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Override category name with custom title.', 'soma' ),
				'condition'   => array(
					'show_section_title' => 'yes',
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
				'description'  => __( 'Link member name to profile page (if not hidden).', 'soma' ),
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
			'show_photo',
			array(
				'label'        => __( 'Show Photo', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Display member featured image.', 'soma' ),
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

		// Section Title styles.
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => __( 'Section Title', 'soma' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_section_title' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_title_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .soma-team-members .section-title',
			)
		);

		$this->add_control(
			'section_title_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .soma-team-members .section-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'section_title_margin',
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
					'{{WRAPPER}} .soma-team-members .section-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'name'           => 'name_typography',
				'label'          => __( 'Typography', 'soma' ),
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'       => '{{WRAPPER}} .team-member .member-name',
				'fields_options' => array(
					'font_size' => array(
						'default' => array(
							'size' => 34,
							'unit' => 'px',
						),
					),
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#171717',
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .team-member .member-name'   => 'color: {{VALUE}};',
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
				'name'           => 'position_typography',
				'label'          => __( 'Typography', 'soma' ),
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector'       => '{{WRAPPER}} .team-member .member-position',
				'fields_options' => array(
					'font_size' => array(
						'default' => array(
							'size' => 20,
							'unit' => 'px',
						),
					),
				),
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#171717',
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
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
	 * Get team members based on settings
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @return array<int, \WP_Post>
	 */
	private function get_members( array $settings ): array {
		$category       = $settings['category'] ?? '';
		$posts_per_page = (int) ( $settings['posts_per_page'] ?? -1 );
		$orderby        = $settings['orderby'] ?? 'menu_order';
		$order          = $settings['order'] ?? 'ASC';

		$args = array(
			'post_type'      => 'team-members',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'orderby'        => $orderby,
			'order'          => $order,
		);

		// Filter by category if selected.
		if ( ! empty( $category ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'team-members-taxonomy',
					'field'    => 'slug',
					'terms'    => $category,
				),
			);
		}

		return get_posts( $args );
	}

	/**
	 * Get section title
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_section_title( array $settings ): string {
		// Use custom title if provided.
		$custom_title = $settings['custom_title'] ?? '';
		if ( ! empty( $custom_title ) ) {
			return $custom_title;
		}

		// Otherwise use category name.
		$category = $settings['category'] ?? '';
		if ( ! empty( $category ) ) {
			$term = get_term_by( 'slug', $category, 'team-members-taxonomy' );
			if ( $term ) {
				return $term->name;
			}
		}

		return __( 'Team Members', 'soma' );
	}

	/**
	 * Render widget output
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$show_section_title = 'yes' === $settings['show_section_title'];
		$section_title_tag  = $settings['section_title_tag'] ?? 'h2';
		$show_position      = 'yes' === $settings['show_position'];
		$link_to_profile    = 'yes' === $settings['link_to_profile'];
		$grayscale_images   = 'yes' === $settings['grayscale_images'];
		$show_photo         = 'yes' === $settings['show_photo'];
		$name_underline     = 'yes' === $settings['name_underline'];
		$columns            = $settings['columns'] ?? '3';
		$no_members_text    = $settings['no_members_text'] ?? __( 'No team members found.', 'soma' );

		$members = $this->get_members( $settings );

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
				<?php if ( $show_section_title ) : ?>
					<<?php echo esc_attr( $section_title_tag ); ?> class="section-title">
						<?php echo esc_html( $this->get_section_title( $settings ) ); ?>
					</<?php echo esc_attr( $section_title_tag ); ?>>
				<?php endif; ?>

				<?php if ( ! empty( $members ) ) : ?>
					<div class="team-grid columns-<?php echo esc_attr( $columns ); ?>">
						<?php foreach ( $members as $member ) : ?>
							<?php
							// Get ACF team member info group.
							$info = get_field( 'team_member_info', $member->ID );
							if ( ! is_array( $info ) ) {
								$info = array();
							}
							// Image: Use WordPress Featured Image.
							$image_url = get_the_post_thumbnail_url( $member->ID, 'large' );
							// Position: Use ACF field team_member_info.title (field_5f94478d81128).
							$member_position = $info['title'] ?? '';
							$hide_single     = ! empty( $info['hide_single_page'] );
							$can_link        = $link_to_profile && ! $hide_single;
							$profile_url     = $can_link ? get_permalink( $member->ID ) : '';
							$use_card_link   = $can_link && $profile_url;
							?>
							<article class="team-member<?php echo $use_card_link ? ' has-link' : ''; ?>">
								<?php if ( $use_card_link ) : ?>
										<?php
										// translators: %s is the team member's name.
										$aria_label = sprintf( __( 'View %s profile', 'soma' ), get_the_title( $member->ID ) );
										?>
										<a href="<?php echo esc_url( $profile_url ); ?>" class="team-member-link" aria-label="<?php echo esc_attr( $aria_label ); ?>">
								<?php endif; ?>
								<?php if ( $show_photo ) : ?>
									<div class="member-image <?php echo esc_attr( ! $image_url ? 'no-image' : '' ); ?>">
									<?php if ( $image_url ) : ?>
										<img 
											src="<?php echo esc_url( $image_url ); ?>" 
											alt="<?php echo esc_attr( get_the_title( $member->ID ) ); ?>"
											loading="lazy"
										>
									<?php endif; ?>
									</div>
								<?php endif; ?>

								<div class="member-info">
									<div class="member-name">
										<?php echo esc_html( get_the_title( $member->ID ) ); ?>
									</div>

									<?php if ( $show_position && $member_position ) : ?>
										<div class="member-position">
											<?php echo esc_html( $member_position ); ?>
										</div>
									<?php endif; ?>
								</div>

								<?php if ( $use_card_link ) : ?>
								</a>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p class="no-members"><?php echo esc_html( $no_members_text ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

