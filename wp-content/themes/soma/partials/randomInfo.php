   
<?php
/**
 * 
 * Partial Name: randomInfo
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['data']): ?>
<section class="randominfo-partial-716012">
    <?php 
        shuffle($pageBlock['block_content']['data']);
        usort($pageBlock['block_content']['data'], function($a, $b) {
            return $b['static'] <=> $a['static'];
        });
    ?>
    <div class="container">
        <?php if($pageBlock['block_content']['title']): ?>
            <div class="title">
                <h2><?= $pageBlock['block_content']['title'] ?></h2>
            </div>
        <?php endif; ?>
        <div class="data">
            <?php $wall = (count($pageBlock['block_content']['data']) < 6) ? count($pageBlock['block_content']['data']) : 6; ?>
            <?php for ($i = 1; $i <= $wall; $i++): ?>
                <div class="item">
                    <div class="value"><?= $pageBlock['block_content']['data'][($i - 1)]['value'] ?></div>
                    <div class="label"><?= $pageBlock['block_content']['data'][($i - 1)]['label'] ?></div>
                </div>
            <?php endfor ?>
        </div>
    </div>
</section>
<?php endif; ?>
                    