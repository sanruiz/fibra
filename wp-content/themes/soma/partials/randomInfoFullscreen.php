   
<?php
/**
 * 
 * Partial Name: randomInfoFullscreen
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['data'] && $pageBlock['block_content']['image']): ?>
<section class="randominfofullscreen-partial-c09bb0">
    <?php 
        usort($pageBlock['block_content']['data'], function($a, $b) {
            return $b['static'] <=> $a['static'];
        });
    ?>

    <div class="image">
        <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
    </div>

    <?php $wall = (count($pageBlock['block_content']['data']) < 4) ? count($pageBlock['block_content']['data']) : 4; ?>
    <div class="data-container">
        <?php for ($i = 1; $i <= $wall; $i++): ?>
            <div class="item">
                <div class="value"><?= $pageBlock['block_content']['data'][($i - 1)]['value'] ?></div>
                <div class="label"><?= $pageBlock['block_content']['data'][($i - 1)]['label'] ?></div>
            </div>
        <?php endfor ?>
    </div>
</section>
<?php endif; ?>
                    