   
<?php
/**
 * 
 * Partial Name: Redirect
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>
<section class="redirect-partial-39759a">
    <?php if($pageBlock['block_content']['redirect_to']): ?>
        <meta http-equiv="refresh" content="0; url=<?= get_the_permalink($pageBlock['block_content']['redirect_to']) ?>">
    <?php endif; ?>
</section>
                    