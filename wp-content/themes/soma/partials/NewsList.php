<?php
/**
 * Block Partial: NewsList
 *
 * Grid or list of news posts
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'news_list_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('NewsList')
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


$data = get_query_var( 'soma_block_content' );

$post_per_page   = $data['posts_per_page'] ? "data-posts-per-page='{$data['posts_per_page']}'" : '';
$infinite_scroll = $data['infinite_scroll'] ? "data-infinite-scroll='{$data['infinite_scroll']}'" : '';

$formated_post_list = $data['post_list'] ? wp_json_encode( $data['post_list'] ) : '';
$post_list          = $data['post_list'] ? "data-post-list='{$formated_post_list}'" : '';

$props = "{$post_per_page} {$infinite_scroll} {$post_list}";
?>

<section class="newslist-partial-afa6f9 <?php echo esc_attr( ( $data['style'] === 'white' ) ? 'news-white' : 'news-black' ); ?>" <?php echo esc_attr( $props ); ?> data-lang="<?php echo esc_attr( wpm_get_language() ); ?>">
	<div class="container">
		<div class="title-container">
			<div class="title">
				<?php if ( $data['title_size'] === 'big' ) : ?>
					<h2><?php echo esc_html( $data['title'] ); ?></h2>
				<?php else : ?>
					<h3><?php echo esc_html( $data['title'] ); ?></h3> 
				<?php endif; ?>
			</div>
			<div class="link">
				<?php if ( $data['link'] ) : ?>
					<a href="<?php echo esc_url( $data['link']['url'] ); ?>" target="<?php echo esc_attr( $data['link']['target'] ); ?>"><?php echo esc_html( $data['link']['title'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<div class="content"></div>
		<div class="loader-container">
			<!-- <div class="loader"><div></div><div></div><div></div></div> -->
			<span class="loading">Loading more</span>
		</div>
	</div>
</section>
					
