   
<?php
/**
 * 
 * Partial Name: HeaderText
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="headertext-partial-40964d">
    <div class="container">
        <div class="content">
            <?php if($pageBlock['block_content']['title']): ?>
                <h2><?= $pageBlock['block_content']['title'] ?></h2>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['subtitle']): ?>
                <h4><?= $pageBlock['block_content']['subtitle'] ?></h4>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['text']): ?>
                <p><?= $pageBlock['block_content']['text'] ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
