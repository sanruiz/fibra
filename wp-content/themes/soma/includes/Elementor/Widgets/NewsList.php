<?php
/**
 * News List Elementor Widget
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
 * News List widget class
 *
 * @todo Implement full controls and rendering
 */
class NewsList extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-news-list';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'News List', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-post-list';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return [ 'soma-news-list' ];
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
				'default' => 5,
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
		$news     = \soma_get_news_items(
			[
				'posts_per_page' => $settings['posts_per_page'],
			]
		);
		?>
		<section class="soma-news-list newslist-partial">
			<div class="container">
				<?php if ( $news->have_posts() ) : ?>
					<div class="news-list">
						<?php
						while ( $news->have_posts() ) :
							$news->the_post();
							?>
							<article class="news-item">
								<h3><a href="<?php the_permalink(); ?>">< the_title(); ?></a></h3>
								<div class="meta"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></div>
								<div class="excerpt"><?php the_excerpt(); ?></div>
							</article>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<p><?php esc_html_e( 'No news found.', 'soma' ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

