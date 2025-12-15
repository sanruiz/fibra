<?php
/**
 * Block Partial: Timeline
 *
 * Timeline visualization
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'timeline_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Timeline')
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
<section class="timeline-partial-04e48b" data-autoplay="<?php echo get_query_var( 'soma_block_content' )['autoplay']; ?>" data-speed="<?php echo get_query_var( 'soma_block_content' )['autoplay_speed']; ?>">
	<div class="timeline-slider">
		<?php foreach ( get_query_var( 'soma_block_content' )['slides'] as $key => $item ) : ?>
			<div class="item">
				<?php if ( $item['image'] ) : ?>
					<div class="image">
						<img src="<?php echo $item['image']['url']; ?>" alt="<?php echo $item['image']['alt']; ?>">
					</div>
				<?php endif; ?>
				<?php if ( $item['year'] ) : ?>
					<div class="year"><?php echo $item['year']; ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="timeline-dots movil">
		<div class="dot-container">
			<?php foreach ( get_query_var( 'soma_block_content' )['slides'] as $key => $item ) : ?>
				<div class="dot"></div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="timeline-captions">
		<?php foreach ( get_query_var( 'soma_block_content' )['slides'] as $key => $item ) : ?>
			<div>
				<div id= "text-item" class="box-text-item item">
					<?php echo $item['text']; ?>
				</div>
				<div class="read" onclick="deploy(this)" >
					Read more
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="timeline-dots desk">
		<div class="dot-container">
			<?php foreach ( get_query_var( 'soma_block_content' )['slides'] as $key => $item ) : ?>
				<div class="dot"></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<script>
	var allboxtext = $('.box-text-item');
	allboxtext.each(function (index,objeto) {
		var boxtextH = $(objeto).height();
		   
		if (boxtextH > 131 && !$(objeto).hasClass("boxtext-H")) {
			$(objeto).addClass("boxtext-H");
		}
		else if (!$(objeto).hasClass("boxtext-H")) {
			$(objeto).parent().find('.read').hide();
		}
	});

	
	function deploy(element) {
		if ($(element).text().trim() == "Read more") {
			$(element).text("Read less");
			$(element).parent().parent().find('.box-text-item').removeClass("boxtext-H");
		}
		else{
			$(element).text("Read more");
			$(element).parent().parent().find('.box-text-item').addClass("boxtext-H");
		}
	}
</script>

					