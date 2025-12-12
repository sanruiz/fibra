<?php
/**
 * Portfolio Elementor Widget
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.0.0
 */

namespace Soma\Elementor\Widgets;

use Soma\Elementor\Base\WidgetBase;
use Elementor\Controls_Manager;
use Soma\Core\Enums\PostType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portfolio widget class
 *
 * @todo Implement full controls and rendering
 */
class Portfolio extends WidgetBase {

	public function get_name(): string {
		return 'soma-portfolio';
	}

	public function get_title(): string {
		return __( 'Portfolio', 'soma' );
	}

	public function get_icon(): string {
		return 'eicon-gallery-masonry';
	}

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
				'default' => 9,
				'min'     => 1,
				'max'     => 100,
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings  = $this->get_settings_for_display();
		$portfolio = soma_get_portfolio_items(
			[
				'posts_per_page' => $settings['posts_per_page'],
			]
		);
		?>
		<section class="soma-portfolio portfolio-partial">
			<div class="container">
				<?php if ( $portfolio->have_posts() ) : ?>
					<div class="portfolio-grid">
						<?php
						while ( $portfolio->have_posts() ) :
							$portfolio->the_post();
							?>
							<div class="portfolio-item">
								<?php the_post_thumbnail( 'large' ); ?>
								<h3><?php the_title(); ?></h3>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<p><?php _e( 'No portfolio items found.', 'soma' ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

