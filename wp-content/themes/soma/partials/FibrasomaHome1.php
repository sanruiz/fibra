<?php
/**
 * Block Partial: FibrasomaHome1
 *
 * Fibrasoma homepage section 1
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'fibrasoma_home_1_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('FibrasomaHome1')
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

<section class="fibrasomahome1-partial-870ca3">
    <div class="container">
        <div class="content">
            <div class="text">
                <?php if($pageBlock['block_content']['number']): ?>
                    <div class="number">
                        <h2><?= $pageBlock['block_content']['number'] ?></h2>
                    </div>
                <?php endif; ?>
                <?php if($pageBlock['block_content']['title']): ?>
                    <div class="title">
                        <h3><?= $pageBlock['block_content']['title'] ?></h3>
                    </div>
                <?php endif; ?>
                <div class="bottom-block">
                    <?php if($pageBlock['block_content']['text']): ?>
                        <div class="text">
                            <p><?= $pageBlock['block_content']['text'] ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($pageBlock['block_content']['link']): ?>
                        <div class="link">
                            <a class="underline-text" href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>"><?= $pageBlock['block_content']['link']['title'] ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="image">
                <?php if($pageBlock['block_content']['image']): ?>
                    <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    