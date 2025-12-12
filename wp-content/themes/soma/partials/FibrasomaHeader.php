   
<?php
/**
 * 
 * Partial Name: FibrasomaHeader
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
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
