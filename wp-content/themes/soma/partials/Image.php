<?php
/**
 * Block Partial: Image
 *
 * Simple image block
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'image_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Image')
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

<?php if($pageBlock['block_content']['image']): ?>
<section class="image-partial-7ce04d <?= $pageBlock['block_content']['mobile_fullscreen'] ? 'mobile-fullscreen' : '' ?> <?= $pageBlock['block_content']['dark_style'] ? 'dark-style' : '' ?>">
    <div class="container">
        <div class="content <?= $pageBlock['block_content']['two_images'] ? 'two-images' : '' ?>">
            <div class="image <?= $pageBlock['block_content']['size'] ?>">
                <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
            </div>
            <?php if($pageBlock['block_content']['two_images'] && $pageBlock['block_content']['image_2']): ?>
                <div class="image-2">
                    <img src="<?= $pageBlock['block_content']['image_2']['url'] ?>" alt="<?= $pageBlock['block_content']['image_2']['alt'] ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
                    