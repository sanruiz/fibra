   
<?php
/**
 * 
 * Partial Name: Bigtext_Image
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>
<section class="bigtext-image-partial-af66cd">
    <div class="container">
        <div class="content">
            <div class="text">
                <h3><?= $pageBlock['block_content']['text'] ?></h3>
            </div>
            <div class="image">
                <?php if($pageBlock['block_content']['image']): ?>
                    <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    