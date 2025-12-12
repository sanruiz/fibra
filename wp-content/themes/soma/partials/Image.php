   
<?php
/**
 * 
 * Partial Name: Image
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
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
                    