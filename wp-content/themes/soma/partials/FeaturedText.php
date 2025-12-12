   
<?php
/**
 * 
 * Partial Name: FeaturedText
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['text']): ?>
<section class="featuredtext-partial-c8599e">
    <div class="container">
        <h3><?= $pageBlock['block_content']['text'] ?></h3>
    </div>
</section>
<?php endif; ?>
                    