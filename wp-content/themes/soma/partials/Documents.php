   
<?php
/**
 * 
 * Partial Name: Documents
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>
<section class="documents-partial-15af9d style-<?= $pageBlock['block_content']['style'] ?>" 
    data-order-by-date="<?= $pageBlock['block_content']['order_by_custom_date'] ? 1 : 0 ?>" 
    data-category="<?= $pageBlock['block_content']['category'] ?>" 
    data-posts-per-page="<?= $pageBlock['block_content']['posts_per_page'] ?>" 
    data-lang="<?= wpm_get_language() ?>"
    >
    <div class="container">
        <div class="content">
            <!-- Ajax -->
        </div>
        <div class="loader-container">
            <!-- <div class="loader"><div></div><div></div><div></div></div> -->
            <span class="loading">Loading more</span>
        </div>
    </div>
</section>
