<?php
/**
 * Team Member Elementor Widget (Single)
 *
 * Displays a single team member's detailed profile.
 * Replicates the structure of singles/team-members.php template.
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.1.12
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
 * Team Member widget class
 *
 * Single member profile widget with detailed information display.
 * Features:
 * - Team member selection dropdown
 * - Featured image display
 * - Name and position
 * - Featured text highlight
 * - Full biography
 * - SOMA logo integration
 * - Global Site Kit styles
 */
class TeamMember extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-team-member';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Team Member Profile', 'soma' );
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
		return array( 'soma-team-member' );
	}

	/**
	 * Get team members for dropdown
	 *
	 * @return array<int, string>
	 */
	private function get_team_members_options(): array {
		$members = get_posts(
			array(
				'post_type'      => 'team-members',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$options = array( '' => __( 'Select a team member...', 'soma' ) );

		foreach ( $members as $member ) {
			$options[ $member->ID ] = get_the_title( $member->ID );
		}

		return $options;
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
	 * Register content controls
	 *
	 * @return void
	 */
	private function register_content_controls(): void {
		// Content section.
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'soma' ),
			)
		);

		$this->add_control(
			'use_current_member',
			array(
				'label'        => __( 'Use Current Member', 'soma' ),
				'description'  => __( 'Automatically detect team member from URL. Ideal for global templates.', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'team_member_id',
			array(
				'label'       => __( 'Select Team Member', 'soma' ),
				'description' => __( 'Manually select a specific team member.', 'soma' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_team_members_options(),
				'default'     => '',
				'label_block' => true,
				'condition'   => array(
					'use_current_member!' => 'yes',
				),
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
			)
		);

		$this->add_control(
			'show_featured_text',
			array(
				'label'        => __( 'Show Featured Text', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_logo',
			array(
				'label'        => __( 'Show SOMA Logo', 'soma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soma' ),
				'label_off'    => __( 'No', 'soma' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
					'{{WRAPPER}} .soma-team-member' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => __( 'Background Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soma-team-member' => 'background-color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .member-name',
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
					'{{WRAPPER}} .member-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'name_margin',
			array(
				'label'      => __( 'Margin Bottom', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Position styles.
		$this->start_controls_section(
			'section_style_position',
			array(
				'label' => __( 'Position', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'position_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .member-title',
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .member-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'position_margin',
			array(
				'label'      => __( 'Margin Bottom', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .member-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Biography styles.
		$this->start_controls_section(
			'section_style_bio',
			array(
				'label' => __( 'Biography', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bio_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .body-content',
			)
		);

		$this->add_control(
			'bio_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .body-content' => 'color: {{VALUE}};',
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
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .featured-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
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

		$use_current_member = 'yes' === ( $settings['use_current_member'] ?? 'yes' );
		$team_member_id     = 0;

		// Try to get team member from current URL if option is enabled.
		if ( $use_current_member ) {
			$queried_object = get_queried_object();
			if ( $queried_object instanceof \WP_Post && 'team-members' === $queried_object->post_type ) {
				$team_member_id = $queried_object->ID;
			}
		} else {
			// Use manually selected team member.
			$team_member_id = absint( $settings['team_member_id'] ?? 0 );
		}

		// Validate team member ID.
		if ( empty( $team_member_id ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="elementor-alert elementor-alert-warning">';
				if ( $use_current_member ) {
					echo esc_html__( 'This widget will display the current team member when viewed on a team member page. Preview not available in editor.', 'soma' );
				} else {
					echo esc_html__( 'Please select a team member from the widget settings.', 'soma' );
				}
				echo '</div>';
			}
			return;
		}

		// Get team member data.
		$member = get_post( $team_member_id );

		if ( ! $member || 'team-members' !== $member->post_type ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="elementor-alert elementor-alert-danger">';
				echo esc_html__( 'Invalid team member selected.', 'soma' );
				echo '</div>';
			}
			return;
		}

		$info               = get_field( 'team_member_info', $team_member_id );
		$image              = get_the_post_thumbnail_url( $team_member_id, 'full' );
		$member_url         = get_permalink( $team_member_id );
		$show_photo         = 'yes' === ( $settings['show_photo'] ?? 'yes' );
		$show_logo          = 'yes' === ( $settings['show_logo'] ?? 'yes' );
		$show_featured_text = 'yes' === ( $settings['show_featured_text'] ?? 'yes' );

		// Determine if we should wrap the entire card in a link.
		$use_card_link = ! empty( $member_url ) && ( $use_current_member || ! empty( $settings['team_member_id'] ) );
		?>

		<section class="soma-team-member">
			<div class="container">
				<?php if ( $use_card_link ) : ?>
					<a href="<?php echo esc_url( $member_url ); ?>" class="soma-team-member__card-link">
				<?php endif; ?>
				
				<div class="content">
					<div class="title">
						<h3 class="member-name"><?php echo esc_html( get_the_title( $team_member_id ) ); ?></h3>
						<h3 class="member-title"><?php echo esc_html( $info['title'] ?? '' ); ?></h3>
					</div>

					<?php if ( $show_photo && $image ) : ?>
						<div class="featured-image">
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title( $team_member_id ) ); ?>">
						</div>
					<?php endif; ?>

					<?php if ( $show_featured_text && ! empty( $info['featured_text'] ) ) : ?>
						<div class="featured-text movil">
							<h3><?php echo esc_html( $info['featured_text'] ); ?></h3>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $info['body'] ) ) : ?>
						<div class="body">
							<div class="body-content">
								<?php echo wp_kses_post( $info['body'] ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				
				<?php if ( $use_card_link ) : ?>
					</a>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
