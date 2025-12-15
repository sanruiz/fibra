<?php
/**
 * Block Partial: Portfolio
 *
 * Portfolio items display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'portfolio_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Portfolio')
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


$pre_filter = isset( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : null;
?>

<?php if ( get_query_var( 'soma_block_content' )['main_category'] && get_query_var( 'soma_block_content' )['posts_per_page'] ) : ?>
<section class="portfolio-partial-8f3f8b style-<?php echo esc_attr( get_query_var( 'soma_block_content' )['style'] ); ?>" data-main-category="<?php echo esc_attr( get_query_var( 'soma_block_content' )['main_category'] ); ?>" data-posts-per-page="<?php echo esc_attr( get_query_var( 'soma_block_content' )['posts_per_page'] ); ?>" data-lang="<?php echo esc_attr( wpm_get_language() ); ?>">
	<div class="container">
		<div class="filters desk">
			<div class="filter all <?php echo $pre_filter ? '' : 'active'; ?>" data-filters="<?php echo esc_attr( get_query_var( 'soma_block_content' )['main_category'] ); ?>"><?php echo ( wpm_get_language() === 'en' ) ? 'All' : 'Todos'; ?></div>
			<?php if ( get_query_var( 'soma_block_content' )['filters'] ) : ?>
				<?php foreach ( get_query_var( 'soma_block_content' )['filters'] as $key => $item ) : ?>
					<?php $filter_info = get_term( $item ); ?>
					<?php if ( ( $filter_info->count ) > 0 ) : ?>
					<div class="filter <?php echo ( $filter_info->slug === $pre_filter ) ? 'active' : ''; ?>" data-filters="<?php echo esc_attr( get_query_var( 'soma_block_content' )['main_category'] ); ?>, <?php echo esc_attr( $item ); ?>">
							<?php echo esc_html( $filter_info->name ); ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<div class="view-mode">
				<div class="list current-view">
					<svg width="20px" height="21px" viewBox="0 0 20 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<g transform="translate(-1244.000000, -2068.000000)">
								<g transform="translate(79.000000, 2062.031250)">
									<g transform="translate(1169.000000, 0.000000)" stroke="#171717" stroke-linecap="square" stroke-width="2">
										<line x1="5.33333333" y1="16.5" x2="18.6666667" y2="16.5"></line>
										<line x1="5.33333333" y1="22.5" x2="18.6666667" y2="22.5"></line>
										<line x1="0" y1="17" x2="1" y2="17"></line>
										<line x1="5.33333333" y1="10.5" x2="18.6666667" y2="10.5"></line>
										<line x1="0" y1="11" x2="1" y2="11"></line>
										<line x1="0" y1="23" x2="1" y2="23"></line>
									</g>
								</g>
							</g>
						</g>
					</svg>
					<span><?php echo ( wpm_get_language() === 'en' ) ? 'List View' : 'Vista Lista'; ?></span>
				</div>
				<div class="grid">
					<svg width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<g transform="translate(-1244.000000, -2068.000000)" stroke="#171717" stroke-width="1.27999985">
								<g transform="translate(79.000000, 2062.031250)">
									<g transform="translate(1165.000000, 0.000000)">
										<g transform="translate(0.000001, 6.400001)">
											<rect x="0.639999924" y="0.639999924" width="7.03999916" height="7.03999916"></rect>
											<rect x="0.639999924" y="11.5199986" width="7.03999916" height="7.03999916"></rect>
											<rect x="11.7759986" y="0.639999924" width="7.03999916" height="7.03999916"></rect>
											<rect x="11.7759986" y="11.5199986" width="7.03999916" height="7.03999916"></rect>
										</g>
									</g>
								</g>
							</g>
						</g>
					</svg>
					<span><?php echo ( wpm_get_language() === 'en' ) ? 'Grid View' : 'Vista Cuadrícula'; ?></span>
				</div>
			</div>
		</div>
		<div class="filters movil">
			<div class="ViewAll"> <div style="width: 100%;"><?php echo ( wpm_get_language() === 'en' ) ? 'View All' : 'Ver Todos'; ?> </div> 
				<svg class="ViewAllsvg" style="transition: 0.5s;" xmlns="http://www.w3.org/2000/svg" width="19.172" height="19.172" viewBox="0 0 19.172 19.172">
					<g id="Group" transform="translate(9.548 1.77) rotate(45)">
						<path id="Line" d="M0,0,10.729,10.729" transform="translate(0.19 0.136)" fill="none" stroke="#171717" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"/>
						<path id="Line_2" data-name="Line 2" d="M0,10.729,10.729,0" transform="translate(0.19 0.136)" fill="none" stroke="#171717" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"/>
					</g>
				</svg>
			</div>
			<div class="IteamView" style="display: none;">
				<div class="filter active" data-filters="<?php echo esc_attr( get_query_var( 'soma_block_content' )['main_category'] ); ?>"><?php echo ( wpm_get_language() === 'en' ) ? 'All' : 'Todos'; ?></div>
				<?php if ( get_query_var( 'soma_block_content' )['filters'] ) : ?>
					<?php foreach ( get_query_var( 'soma_block_content' )['filters'] as $key => $item ) : ?>
						<?php if ( ( get_term( $item )->count ) > 0 ) : ?>
							<?php $filter_name = get_term( $item )->name; ?>
						<div class="filter" data-filters="<?php echo esc_attr( get_query_var( 'soma_block_content' )['main_category'] ); ?>, <?php echo esc_attr( $item ); ?>">
							<?php echo esc_html( $filter_name ); ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="projects list-view">
			<!-- Ajax Render -->
		</div>
		<div class="loader-container">
			<!-- <div class="loader"><div></div><div></div><div></div></div> -->
			<span class="loading">Loading more</span>
		</div>
	</div>
</section>
<?php endif; ?>
