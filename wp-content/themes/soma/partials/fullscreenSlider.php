   
<?php
/**
 * 
 * Partial Name: fullscreenSlider
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['slides']): ?>
<section class="fullscreenslider-partial-09e45b">
    <div class="slider">
        <?php foreach($pageBlock['block_content']['slides'] as $key => $item): ?>
            <?php if($item['type'] == 'image' && $item['image']): ?>
                <div class="item image-item">
                    <img src="<?= $item['image']['url'] ?>" alt="<?= $item['image']['alt'] ?>">
                    <div class="container">
                        <div class="title"><?= $item['title'] ?></div>
                        <div class="text"><?= $item['text'] ?></div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if($item['type'] == 'video' && $item['vimeo_id']): ?>
                <div class="item video-item">
                    <div class="vimeo-player" data-video-id="<?= $item['vimeo_id'] ?>"></div>
                    <div class="container">
                        <div class="title"><?= $item['title'] ?></div>
                        <div class="text"><?= $item['text'] ?></div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>    
</section>
<?php endif; ?>
                    