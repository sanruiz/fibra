<?php
/**
 * Block Partial: CareersList
 *
 * Grid or list of career opportunities
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'careers_list_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('CareersList')
 *
 * Example Usage:
 * <code>
 * $counter = get_query_var('soma_block_counter');
 * $content = get_query_var('soma_block_content');
 * $layout  = get_query_var('soma_block_layout');
 * </code>
 *
 * @see \Soma\PageBuilder\BlockRenderer
 * @see \Soma\PageBuilder\BlockRegistry
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


$args    = [
	'numberposts' => -1,
	'post_type'   => 'careers',
	'post_status' => array( 'publish' ),
	'order'       => $params['order'] ? $params['order'] : 'DESC',
];
$careers = get_posts( $args );

?>
<section class="careerslist-partial-57001a">
	<div class="container">
		<div class="content">
			<div class="title-container">
				<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
					<h2><?php echo get_query_var( 'soma_block_content' )['title']; ?></h2>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
					<p><?php echo get_query_var( 'soma_block_content' )['text']; ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $careers ) : ?>
				<?php foreach ( $careers as $key => $item ) : ?>
					<?php $info = get_field( 'careers_info', $item->ID ); ?> 
					<a href="<?php echo get_the_permalink( $item->ID ); ?>">
						<div class="careers-item">
							<div class="careers-title">
								<h3><?php echo get_the_title( $item->ID ); ?></h3>
							</div>
							<div class="careers-city">
								<h3><?php echo $info['city']; ?></h3>
								<div class="arrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="45.007" height="41.776" viewBox="0 0 45.007 41.776">
										<g id="Group_2_Copy_15" data-name="Group 2 Copy 15" transform="translate(0.903 42.004) rotate(-90)">
											<g id="Line_2" data-name="Line 2">
											<path id="Line_2-2" data-name="Line 2" d="M.817.1V41.678" transform="translate(20.299 0)" fill="none" stroke="#171717" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"/>
											<path id="Line_2-3" data-name="Line 2" d="M.817.1V41.678" transform="translate(20.299 0)" fill="none"/>
											</g>
											<g id="Shape">
											<path id="Shape-2" data-name="Shape" d="M1.041,41.069,0,40.027,19.493,20.534,0,1.041,1.041,0,21.575,20.534Z" transform="translate(41.65 22.175) rotate(90)" fill="#171717" stroke="#171717" stroke-miterlimit="10" stroke-width="0.5"/>
											<path id="Shape-3" data-name="Shape" d="M1.041,41.069,0,40.027,19.493,20.534,0,1.041,1.041,0,21.575,20.534Z" transform="translate(41.65 22.175) rotate(90)" fill="none"/>
											</g>
										</g>
									</svg>
								</div>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
					