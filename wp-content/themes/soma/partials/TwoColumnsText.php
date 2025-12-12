   
<?php
/**
 * 
 * Partial Name: TwoColumnsText
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
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
                    