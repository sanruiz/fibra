   
<?php
/**
 * 
 * Partial Name: ContactHeader
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="contactheader-partial-a07d41">
    <div class="container">
        <div class="content">
            <div class="text">
                <?php if($pageBlock['block_content']['text']): ?>
                    <p><?= $pageBlock['block_content']['text'] ?></p>
                <?php endif; ?>
            </div>
            <div class="info">
                <?php if($pageBlock['block_content']['info']): ?>
                    <?php foreach($pageBlock['block_content']['info'] as $key => $item): ?>
                        <div class="item">
                            <?php if($item['label']): ?>
                                <p><?= $item['label'] ?></p>
                            <?php endif; ?>
                            <?php if($item['link']): ?>
                                <a href="<?= $item['link']['url'] ?>" target="<?= $item['link']['target'] ?>">
                                    <?= $item['link']['title'] ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    