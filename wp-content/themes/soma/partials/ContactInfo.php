   
<?php
/**
 * 
 * Partial Name: ContactInfo
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="contactinfo-partial-b5328a">
    <div class="container">
        <div class="content">
            <div class="column-1">
                <?php if($pageBlock['block_content']['title']): ?>
                    <h3><?= $pageBlock['block_content']['title'] ?></h3>
                <?php endif; ?>
                <?php if($pageBlock['block_content']['address']): ?>
                    <div class="address">
                        <p><?= $pageBlock['block_content']['address'] ?></p>
                    </div>
                <?php endif; ?>
                <?php if($pageBlock['block_content']['link']): ?>
                    <div class="link">
                        <a href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>"><?= $pageBlock['block_content']['link']['title'] ?></a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="column-2">
                <?php if($pageBlock['block_content']['contact_info']): ?>
                    <div class="contact-info">
                        <?php foreach($pageBlock['block_content']['contact_info'] as $key => $item): ?>
                            <?php if($item['link']): ?>
                                <div class="item">
                                    <div class="title"><?= $item['title'] ?></div>
                                    <div class="link">
                                        <a href="<?= $item['link']['url'] ?>" target="<?= $item['link']['target'] ?>"><?= $item['link']['title'] ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    