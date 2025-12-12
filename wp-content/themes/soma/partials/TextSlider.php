   
<?php
/**
 * 
 * Partial Name: TextSlider
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;

function addZero($num) {
    if($num < 10) {
        $num = '0'.$num;
    }
    return $num;
}
?>

<?php if($pageBlock['block_content']['slides']): ?>
<section class="textslider-partial-8bf200" <?= $pageBlock['block_content']['autoplay'] ? 'data-autoplay="1"' : 'data-autoplay="0"' ?> data-autoplay-speed="<?= $pageBlock['block_content']['autoplay_speed'] ?>">
    <div class="container">
        <div class="content">
            <div class="selector">
                <?php if($pageBlock['block_content']['title']): ?>
                    <div class="title" onClick="$(this).toggleClass('closed')">
                        <?= $pageBlock['block_content']['title'] ?>
                        <span class="close-button"></span>
                    </div>
                <?php endif; ?>
                <div class="list">
                    <?php foreach($pageBlock['block_content']['slides'] as $key => $item): ?>
                        <?php if($item['label']): ?>
                            <div class="item" data-slide="<?= $key ?>">
                                <?= $item['label'] ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="slider">
                <?php foreach($pageBlock['block_content']['slides'] as $key => $item): ?>
                    <?php if($item): ?>
                        <div class="item">
                            <h2><?= addZero($key + 1) ?></h2>
                            <?php if($item['title']): ?>
                                <h3><?= $item['title'] ?></h3>
                            <?php endif; ?>
                            <?php if($item['text']): ?>
                                <p><?= $item['text'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    $( document ).ready(function() {
        $('.item[data-slide="0"]').addClass('active');
    });
</script>
                    