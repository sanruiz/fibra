   
<?php
/**
 * 
 * Partial Name: AnalystCoverage
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="analystcoverage-partial-805458">
    <div class="container">
        <?php if($pageBlock['block_content']['items']): ?>
            <div class="content">
                <?php foreach($pageBlock['block_content']['items'] as $key => $item): ?>
                    <div class="item">
                        <?php if($item['title']): ?>
                            <h3><?= $item['title'] ?></h3>
                        <?php endif; ?>
                        <?php if($item['name']): ?>
                            <div class="name"><?= $item['name'] ?></div>
                        <?php endif; ?>
                        <?php if($item['phone']): ?>
                            <div class="phone">
                                <a href="<?= $item['phone']['url'] ?>" target="<?= $item['phone']['target'] ?>"><?= $item['phone']['title'] ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if($item['email']): ?>
                            <div class="email">
                                <a href="<?= $item['email']['url'] ?>" target="<?= $item['email']['target'] ?>"><?= $item['email']['title'] ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
                    