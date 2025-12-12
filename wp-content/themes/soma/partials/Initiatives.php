   
<?php
/**
 * 
 * Partial Name: Initiatives
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['initiatives']): ?>
<section class="initiatives-partial-215232">
    <div class="container">
        <?php if($pageBlock['block_content']['title']): ?>
            <div class="main-title">
                <h3><?= $pageBlock['block_content']['title'] ?></h3>
            </div>
        <?php endif; ?>
        <div class="content">
            <?php foreach($pageBlock['block_content']['initiatives'] as $key => $item): ?>
                <?php if($item['title'] && $item['image'] && $item['pdf']): ?>
                    <div class="item">
                        <a href="<?= $item['pdf']['url'] ?>" target="_blank">
                            <div class="image">
                                <img src="<?= $item['image']['url'] ?>" alt="<?= $item['image']['alt'] ?>">
                            </div>
                            <div class="title">
                                <h3><?= $item['title'] ?></h3>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
                    