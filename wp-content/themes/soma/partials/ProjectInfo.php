   
<?php
/**
 * 
 * Partial Name: ProjectInfo
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="projectinfo-partial-dcffdb">
    <div class="container">
        <div class="content">
            <div class="description">
                <?php if($pageBlock['block_content']['column_1']): ?>
                    <p><?= $pageBlock['block_content']['column_1'] ?></p>
                <?php endif; ?>
            </div>
            <div class="info_1">
                <?php if($pageBlock['block_content']['column_2']): ?>
                    <p><?= $pageBlock['block_content']['column_2'] ?></p>
                <?php endif; ?>
                <?php if ($pageBlock['block_content']['designed']): ?>

                        <?php if ($pageBlock['block_content']['designed']['type'] == "Link") : ?>   
                                <div class="designed-by">
                                    <p><br><?= (wpm_get_language() == 'en') ? 'Designed by' : 'Diseñado por' ?> <br></p>
                                    <u><a href="<?=  $pageBlock['block_content']['designed']['desidesigned_by_link']['url']?>" target="<?= $pageBlock['block_content']['designed']['desidesigned_by_link']['target'] ?>">
                                        <?= $pageBlock['block_content']['designed']['desidesigned_by_link']['title'] ?>
                                        </a>
                                    </u>
                                </div>
                        <?php elseif ($pageBlock['block_content']['designed']['type'] == "Text") : ?>
                                <div class="designed-by">
                                    <p><br><?= (wpm_get_language() == 'en') ? 'Designed by' : 'Diseñado por' ?> <br></p>
                                    <u><?= $pageBlock['block_content']['designed']['designed_by'] ?></u> 
                                </div>
                        <?php else :?>
                            
                        <?php endif ?>       
                <?php endif; ?>
            </div>
            <div class="info_2">
                <?php if($pageBlock['block_content']['column_3']): ?>
                    <p><?= $pageBlock['block_content']['column_3'] ?></p>
                <?php endif; ?>
                <?php if ($pageBlock['block_content']['designed']['type'] == "Link") : ?>   
                        <div class="designed-by">
                            <p><br><?= (wpm_get_language() == 'en') ? 'Designed by' : 'Diseñado por' ?> <br></p>
                            <u><a href="<?=  $pageBlock['block_content']['designed']['desidesigned_by_link']['url']?>" target="<?= $pageBlock['block_content']['designed']['desidesigned_by_link']['target'] ?>">
                                <?= $pageBlock['block_content']['designed']['desidesigned_by_link']['title'] ?>
                                </a>
                            </u>
                        </div>
                <?php elseif ($pageBlock['block_content']['designed']['type'] == "Text") : ?>
                        <div class="designed-by">
                            <p><br><?= (wpm_get_language() == 'en') ? 'Designed by' : 'Diseñado por' ?> <br></p>
                            <u><?= $pageBlock['block_content']['designed']['designed_by'] ?></u> 
                        </div>
                <?php else :?>
                <?php endif ?>  
            </div>
            <div class="link">
                <?php if($pageBlock['block_content']['link']): ?>
                    <a href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>">
                        <?= $pageBlock['block_content']['link']['title'] ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
