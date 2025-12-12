<?php
/**
 * Block Partial: TwoColumnsText
 *
 * Two-column text layout
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'two_columns_text_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('TwoColumnsText')
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
<section class="twocolumnstext-partial-8dc1b0">
    <div class="container">
        <div class="content">
            <?php if($pageBlock['block_content']['logo']): ?>
                <div class="logo">
                    <img src="<?= $pageBlock['block_content']['logo']['url'] ?>" alt="<?= $pageBlock['block_content']['logo']['alt'] ?>">
                </div>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['title']): ?>
                <div class="title">
                    <?= $pageBlock['block_content']['title'] ?>
                </div>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['text']): ?>
                <div class="text">
                    <p><?= $pageBlock['block_content']['text'] ?></p>
                </div>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['link']): ?>
                <div class="link">
                    <a href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>">
                        <?= $pageBlock['block_content']['link']['title'] ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
                    