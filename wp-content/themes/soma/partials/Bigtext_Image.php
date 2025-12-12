<?php
/**
 * Block Partial: Bigtext_Image
 *
 * Large text block with accompanying image
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'bigtext_image_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Bigtext_Image')
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
<section class="bigtext-image-partial-af66cd">
    <div class="container">
        <div class="content">
            <div class="text">
                <h3><?= get_query_var('soma_block_content')['text'] ?></h3>
            </div>
            <div class="image">
                <?php if(get_query_var('soma_block_content')['image']): ?>
                    <img src="<?= get_query_var('soma_block_content')['image']['url'] ?>" alt="<?= get_query_var('soma_block_content')['image']['alt'] ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    