   
<?php
/**
 * 
 * Partial Name: VimeoPlayer
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;

$cover = $pageBlock['block_content']['cover'] ? $pageBlock['block_content']['cover']['url'] : '';
?>

<section class="vimeoplayer-partial-8e5131 <?= $pageBlock['block_content']['dark_style'] ? 'dark-style' : '' ?>" data-video-id="<?= $pageBlock['block_content']['vimeo_id'] ?>" data-cover="<?= $cover ?>">
    <div class="container">
        <div class="content"></div>
        <?php if($pageBlock['block_content']['label']): ?>
            <div class="label">
                <?= $pageBlock['block_content']['label'] ?>
            </div>
        <?php endif; ?>
    </div>
</section>
                    