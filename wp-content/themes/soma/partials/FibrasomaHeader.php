<?php
/**
 * Block Partial: FibrasomaHeader
 *
 * Fibrasoma-specific header layout
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'fibrasoma_header_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('FibrasomaHeader')
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

<section class="fibrasomaheader-partial-d71c92">
    <div class="container">
        <?php if($pageBlock['block_content']['big_text']): ?>
            <h3><?= $pageBlock['block_content']['big_text'] ?></h3>
        <?php endif; ?>
        <?php if($pageBlock['block_content']['link']): ?>
            <a href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>">
                <?= $pageBlock['block_content']['link']['title'] ?>
            </a>
        <?php endif; ?>
        <?php if($pageBlock['block_content']['image']): ?>
            <div class="image">
                <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
            </div>
        <?php endif; ?>
    </div>
</section>
