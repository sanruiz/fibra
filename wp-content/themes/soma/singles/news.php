<?php
/**
 *
 *
 * @package Soma
 * Single News Partial
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$info           = get_field( 'news_content' );
$featured_image = get_the_post_thumbnail_url();
?>

<section class="single-news-d3cb75">
	<div class="container">
		<div class="content">
			<div class="title">
				<h3><?php echo esc_html( get_the_title() ); ?></h3>
				<div class="date">
					<?php if ( $info['date'] ) : ?>
						<?php $date = DateTime::createFromFormat( 'U', $info['date'] ); ?>
						<?php echo esc_html( $date->format( 'F j, Y' ) ); ?>
					<?php endif; ?>
					<?php if ( $info['author'] ) : ?>
						&nbsp;—&nbsp;<?php echo esc_html( $info['author'] ); ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="featured-image">
				<?php if ( $featured_image ) : ?>
					<img src="<?php echo esc_url( $featured_image ); ?>" alt="Featured image">
				<?php endif; ?>
			</div>
			<div class="featured-text">
				<?php if ( $info['featured_text'] ) : ?>
					<h3><?php echo esc_html( $info['featured_text'] ); ?></h3>
				<?php endif; ?>
			</div>
			<div class="body">
				<?php if ( $info['body'] ) : ?>
					<div class="text"><?php echo wp_kses_post( $info['body'] ); ?></div>
				<?php endif; ?>
				<?php if ( $info['link'] ) : ?>
					<div class="link">
						<a href="<?php echo esc_url( $info['link']['url'] ); ?>" target="<?php echo esc_attr( $info['link']['target'] ); ?>">
							<?php echo esc_html( $info['link']['title'] ); ?>
							<svg width="17px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<g transform="translate(-733.000000, -553.000000)">
										<g transform="translate(734.000000, 553.052734)">
											<g transform="translate(22.011719, 21.437902) rotate(-90.000000) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
												<line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" id="Line-2" stroke-width="2" stroke-linecap="square"></line>
												<polygon id="Shape" stroke-width="0.5" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
											</g>
										</g>
									</g>
								</g>
							</svg>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

					
