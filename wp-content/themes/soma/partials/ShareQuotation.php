<?php
/**
 * Block Partial: ShareQuotation
 *
 * Stock share quotation display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'share_quotation_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('ShareQuotation')
 *
 * Example Usage:
 * <code>
 * $counter = get_query_var('soma_block_counter');
 * $content = get_query_var('soma_block_content');
 * $layout  = get_query_var('soma_block_layout');
 * </code>
 *
 * @see \Soma\PageBuilder\BlockRenderer
 * @see \Soma\PageBuilder\BlockRegistry
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


$arrow = '
<svg width="16px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" fill="none" fill-rule="evenodd">
        <g transform="translate(1.000000, 0.000000)" stroke="#ffffff">
            <g transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="3" stroke-linecap="square"></line>
                <polygon stroke-width="2" fill="#ffffff" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
            </g>
        </g>
    </g>
</svg>
';
?>

<?php if($pageBlock['block_content']['symbol']): ?>
<section 
    class="sharequotation-partial-7baa8d <?= $pageBlock['block_content']['dark_background'] ? 'black-bg' : 'white-bg' ?>" 
    data-symbol="<?= $pageBlock['block_content']['symbol'] ?>" 
    data-origin="<?= $pageBlock['block_content']['data_origin'] ?>"
    data-price="<?= $pageBlock['block_content']['custom_data']['actual_price'] ?>"
    data-percent-one="<?= $pageBlock['block_content']['custom_data']['percent_1'] ?>"
    data-percent-two="<?= $pageBlock['block_content']['custom_data']['percent_2'] ?>"
    data-volume="<?= $pageBlock['block_content']['custom_data']['volume'] ?>"
    data-lang="<?= wpm_get_language() ?>"
    >
    <div class="container">
        <div class="col">
            <div class="title">
                <?= $pageBlock['block_content']['title'] ? "<h3>{$pageBlock['block_content']['title']}</h3>" : '' ?>
            </div>
            <?php if($pageBlock['block_content']['file'] && $pageBlock['block_content']['file_label'] && wpm_get_language() == 'en'): ?>
                <a class="desk" href="<?= $pageBlock['block_content']['file']['url'] ?>" target="_blank">
                    <?= $pageBlock['block_content']['file_label'] . $arrow ?>
                </a>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['file_es'] && $pageBlock['block_content']['file_label'] && wpm_get_language() == 'es'): ?>
                <a class="desk" href="<?= $pageBlock['block_content']['file_es']['url'] ?>" target="_blank">
                    <?= $pageBlock['block_content']['file_label'] . $arrow ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="col">
            <div class="price">
                <?= $pageBlock['block_content']['label_1'] ? "<h3>{$pageBlock['block_content']['label_1']}</h3>" : '' ?>
                <h2 class="data-price">$0</h2>
                <p>
                    <span class="data-change">$0</span>&nbsp;
                    <span class="data-percent">0%</span>
                </p>
                <p style="margin-top: -20px;">
                    <span class="data-exchange-date">-</span>
                </p>
            </div>
        </div>
        <div class="col">
            <div class="price">
                <?= $pageBlock['block_content']['label_2'] ? "<h3>{$pageBlock['block_content']['label_2']}</h3>" : '' ?>
                <h2 class="data-volume">0</h2>
            </div>
            <?php if($pageBlock['block_content']['file'] && $pageBlock['block_content']['file_label']): ?>
                <a class="mobile" href="<?= $pageBlock['block_content']['file']['url'] ?>" target="_blank">
                    <?= $pageBlock['block_content']['file_label'] . $arrow ?>
                </a>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['file_es'] && $pageBlock['block_content']['file_label'] && wpm_get_language() == 'es'): ?>
                <a class="mobile" href="<?= $pageBlock['block_content']['file_es']['url'] ?>" target="_blank">
                    <?= $pageBlock['block_content']['file_label'] . $arrow ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>