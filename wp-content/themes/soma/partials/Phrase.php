   
<?php
/**
 * 
 * Partial Name: Phrase
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="phrase-partial-3feb8a style-<?= $pageBlock['block_content']['style'] ?>">
    <div class="container">
        <div class="content">
            <?php if($pageBlock['block_content']['style'] == "withimage"): ?>
                <div class="image">
                    <?php if($pageBlock['block_content']['image']): ?>
                        <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="phrase">
                <h4><?= $pageBlock['block_content']['phrase'] ?></h4>
                <?php if($pageBlock['block_content']['name']): ?>
                    <div class="name"><?= $pageBlock['block_content']['name'] ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    