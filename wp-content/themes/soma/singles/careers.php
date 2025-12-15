<?php
/**
 * Single Careers Partial
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$info           = get_field( 'careers_info' );
$featured_image = get_the_post_thumbnail_url();
?>

<section class="single-careers-d3cb75">
	<div class="container">
		<div class="container-box">
			<h2 class="career-name"><?php echo esc_html( get_the_title() ); ?></h2>
			<div class="career-text"><?php echo wp_kses_post( $info['text'] ); ?></div>
		</div>
		<div class="container-apply">
			<a href="<?php echo esc_url( $info['apply_now']['url'] ); ?>" target="<?php echo esc_attr( $info['apply_now']['target'] ); ?>"><h3><?php echo esc_html( $info['apply_now']['title'] ); ?></h3></a>
		</div>
	</div>
</section>

					
