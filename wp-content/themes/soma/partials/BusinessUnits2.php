<?php
/**
 * Block Partial: BusinessUnits2
 *
 * Alternative business units display layout.
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'business_units_2_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('BusinessUnits2')
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

$businessunits = get_posts([
    'post_type' => 'page',
    'fields' => 'ids',
    'nopaging' => true,
    'meta_key' => '_wp_page_template',
    'meta_value' => 'templates/business-unit-template.php'
]);

$logo = '
<svg width="106px" height="108px" viewBox="0 0 106 108" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-80.000000, -749.000000)">
            <g transform="translate(80.000000, 709.531250)">
                <g transform="translate(0.000000, 40.000000)">
                    <polygon class="color" points="105.510877 89.9671368 89.6756306 43.505737 74.8471479 0 30.3834957 0 0 89.1462347 0 107.254858 19.8591859 107.254858 47.73719 24.5409261 57.4924157 24.5409261 85.369382 107.254858 105.510877 107.254858"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
';
?>

<?php if($businessunits): ?>
<section class="businessunits2-partial-adb816">
    <div class="container">
        <div class="content">
            <?php foreach($businessunits as $key => $item): ?>
                <?php $businessunit_info = get_field('business_unit_data', $item) ?>
                <div class="item business_unit_<?= $item ?>">
                    <style>
                        .businessunits2-partial-adb816 .business_unit_<?= $item ?> .logo .color {
                            fill: <?= $businessunit_info['color'] ?>;
                        }
                        @media (min-width: 1025px) {
                            .businessunits2-partial-adb816 .business_unit_<?= $item ?>:hover {
                                border-top: 5px solid <?= $businessunit_info['color'] ?>;
                                margin-top: -4px;
                            }
                        }
                    </style>
                    <a href="<?= get_the_permalink($item) ?>">
                        <div class="item-content">
                            <div class="logo">
                                <?= $logo ?>
                            </div>
                            <div class="text">
                                <h3 class="title"><u><?= get_the_title($item) ?></u></h3>
                                <?php if($businessunit_info['short_description']): ?>
                                    <h3><?= $businessunit_info['short_description'] ?></h3>
                                <?php endif; ?>
                                <svg width="46px" height="43px" viewBox="0 0 46 43" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-267.000000, -920.000000)" stroke="#171717">
                                            <g transform="translate(80.000000, 709.531250)">
                                                <g transform="translate(210.000000, 232.000000) rotate(-90.000000) translate(-210.000000, -232.000000) translate(189.000000, 210.000000)">
                                                    <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="1.41119997" stroke-linecap="square"></line>
                                                    <path d="M11.4778489,53.3883422 C11.1886341,53.658276 10.7258903,53.658276 10.4366755,53.3883422 C10.1474606,53.0991273 10.1474606,52.6363836 10.4366755,52.3471687 L29.9297563,32.8540878 L10.4366755,13.3610069 C10.1474606,13.0717921 10.1474606,12.6090483 10.4366755,12.3198335 C10.7258903,12.0498996 11.1886341,12.0498996 11.4778489,12.3198335 L32.0121033,32.8540878 L11.4778489,53.3883422 Z" stroke-width="0.5" fill="#171717" fill-rule="nonzero" transform="translate(21.115934, 32.854088) rotate(-270.000000) translate(-21.115934, -32.854088) "></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
                    