   
<?php
/**
 * 
 * Partial Name: AnnualReports
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['category']): ?>
<section class="annualreports-partial-5d3457 <?= $pageBlock['block_content']['style'] ?>" 
    data-last-year="<?= $pageBlock['block_content']['latest_year_preselect'] ? '1' : '0' ?>" 
    data-category="<?= $pageBlock['block_content']['category'] ?>" 
    data-lang="<?= wpm_get_language() ?>">
    <div class="container">
        <div class="content">
            <div class="year-list">
                <div class="mobile-title"><?= (wpm_get_language() == 'en') ? 'Filter by Year' : 'Filtrar por año' ?> <span></span></div>
                <div class="years">
                    <!-- Ajax -->
                </div>
                <div class="all">
                    <a><?= (wpm_get_language() == 'en') ? 'See All' : 'Ver Todos' ?></a>
                </div>
            </div>
            <div class="documents">
                <div class="document-list">
                    <!-- Ajax -->
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
                    