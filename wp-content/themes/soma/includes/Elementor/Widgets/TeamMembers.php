<?php
/**
 * Team Members Elementor Widget
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
 * Team Members widget class
 *
 * @todo Implement full controls and rendering
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
	 * Register widget controls
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'soma' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => __( 'Posts Per Page', 'soma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 100,
			]
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
		$members  = \soma_get_team_members(
			[
				'posts_per_page' => $settings['posts_per_page'],
			]
		);
		?>
		<section class="soma-team-members">
			<div class="container">
				<?php if ( $members->have_posts() ) : ?>
					<div class="team-grid">
						<?php
						while ( $members->have_posts() ) :
							$members->the_post();
							?>
							<div class="team-member">
								<?php the_post_thumbnail( 'medium' ); ?>
								<h3><?php the_title(); ?></h3>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<p><?php esc_html_e( 'No team members found.', 'soma' ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

