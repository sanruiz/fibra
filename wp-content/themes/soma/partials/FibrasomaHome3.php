<?php
/**
 * Block Partial: FibrasomaHome3
 *
 * Fibrasoma homepage section 3
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'fibrasoma_home_3_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('FibrasomaHome3')
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
<section class="fibrasomahome3-partial-1f0e42">
	<div class="container">
		<div class="content">

			<div class="text">
				<?php if ( get_query_var( 'soma_block_content' )['number'] ) : ?>
					<div class="number">
						<h2><?php echo get_query_var( 'soma_block_content' )['number']; ?></h2>
					</div>
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
					<div class="title">
						<h3><?php echo get_query_var( 'soma_block_content' )['title']; ?></h3>
					</div>
				<?php endif; ?>
			</div>

			<div class="list">
				<?php if ( get_query_var( 'soma_block_content' )['list'] ) : ?>
					<?php foreach ( get_query_var( 'soma_block_content' )['list'] as $key => $item ) : ?>
						<?php if ( $item['link'] ) : ?>
							<div class="item">
								<a href="<?php echo $item['link']['url']; ?>" target="<?php echo $item['link']['target']; ?>" data-item="<?php echo $key; ?>">
									<?php echo $item['link']['title']; ?>
								</a>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="link">
				<?php if ( get_query_var( 'soma_block_content' )['link'] ) : ?>
					<a class="underline-text" href="<?php echo get_query_var( 'soma_block_content' )['link']['url']; ?>" target="<?php echo get_query_var( 'soma_block_content' )['link']['target']; ?>">
						<?php echo get_query_var( 'soma_block_content' )['link']['title']; ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="image">
				<?php if ( get_query_var( 'soma_block_content' )['image'] ) : ?>
					<img src="<?php echo get_query_var( 'soma_block_content' )['image']['url']; ?>" alt="<?php echo get_query_var( 'soma_block_content' )['image']['alt']; ?>">
				<?php endif; ?>
				<?php if ( get_query_var( 'soma_block_content' )['list'] ) : ?>
					<?php foreach ( get_query_var( 'soma_block_content' )['list'] as $key => $item ) : ?>
						<?php if ( $item['image'] ) : ?>
							<div class="link-image"  data-item="<?php echo $key; ?>">
								<img src="<?php echo $item['image']['url']; ?>" alt="<?php echo $item['image']['alt']; ?>">
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			
		</div>
	</div>
	<script>
		$('.fibrasomahome3-partial-1f0e42').find('.list a').on('mouseover', function() {
			$('.fibrasomahome3-partial-1f0e42').find(`.image .link-image[data-item="${$(this).data('item')}"]`).addClass('active').siblings().removeClass('active');
		});
		$('.fibrasomahome3-partial-1f0e42').find('.list a').on('mouseout', function() {
			$('.fibrasomahome3-partial-1f0e42').find(`.image .link-image`).removeClass('active');
		});
	</script>
</section>