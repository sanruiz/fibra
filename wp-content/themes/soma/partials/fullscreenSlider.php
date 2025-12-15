<?php
/**
 * Block Partial: fullscreenSlider
 *
 * Full-screen image slider
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'fullscreen_slider_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('fullscreenSlider')
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


?>

<?php if ( get_query_var( 'soma_block_content' )['slides'] ) : ?>
<section class="fullscreenslider-partial-09e45b">
	<div class="slider">
		<?php foreach ( get_query_var( 'soma_block_content' )['slides'] as $key => $item ) : ?>
			<?php if ( $item['type'] === 'image' && $item['image'] ) : ?>
				<div class="item image-item">
					<img src="<?php echo $item['image']['url']; ?>" alt="<?php echo $item['image']['alt']; ?>">
					<div class="container">
						<div class="title"><?php echo $item['title']; ?></div>
						<div class="text"><?php echo $item['text']; ?></div>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( $item['type'] === 'video' && $item['vimeo_id'] ) : ?>
				<div class="item video-item">
					<div class="vimeo-player" data-video-id="<?php echo $item['vimeo_id']; ?>"></div>
					<div class="container">
						<div class="title"><?php echo $item['title']; ?></div>
						<div class="text"><?php echo $item['text']; ?></div>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>    
</section>
<?php endif; ?>
