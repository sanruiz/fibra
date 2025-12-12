   
<?php
/**
 * 
 * Partial Name: FibrasomaHome4
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;

if($pageBlock['block_content']['press_releases'] == 'latest') {
    $args = [
        'numberposts'	=> 3,
        'post_type'		=> 'documents-reports',
        'post_status'   => array('publish'),
        'order'			=> 'DESC',
        'tax_query'     => [
            [
                'taxonomy'  => 'documents-taxonomy',
                'field'     => 'slug',
                'terms'     => 'press_releases'
            ]
        ]
    ];
    $press = get_posts( $args );
} else {
    $press[0] = $pageBlock['block_content']['items'][0]['press'] ? $pageBlock['block_content']['items'][0]['press'] : null;
    $press[1] = $pageBlock['block_content']['items'][1]['press'] ? $pageBlock['block_content']['items'][1]['press'] : null;
    $press[2] = $pageBlock['block_content']['items'][2]['press'] ? $pageBlock['block_content']['items'][2]['press'] : null;
}

$arrow = '
<svg width="16px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" fill="none" fill-rule="evenodd">
        <g transform="translate(1.000000, 0.000000)" stroke="#171717">
            <g transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="3" stroke-linecap="square"></line>
                <polygon stroke-width="2" fill="#171717" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
            </g>
        </g>
    </g>
</svg>
';
?>

<section class="fibrasomahome4-partial-d09078">
    <div class="container">
        <div class="content">
            <div class="text">
                <?php if($pageBlock['block_content']['number']): ?>
                    <div class="number">
                        <h2><?= $pageBlock['block_content']['number'] ?></h2>
                    </div>
                <?php endif; ?>
                <?php if($pageBlock['block_content']['title']): ?>
                    <div class="title">
                        <h3><?= $pageBlock['block_content']['title'] ?></h3>
                    </div>
                <?php endif; ?>
            </div>
            <div class="link">
                <?php if($pageBlock['block_content']['link']): ?>
                    <a class="underline-text" href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>">
                        <?= $pageBlock['block_content']['link']['title'] ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="press-col-1">
                <?php if($press[0]): ?>
                    <?php $content = get_field('document_content', $press[0]->ID); ?>
                    <?php $featured_image = get_the_post_thumbnail_url($press[0]->ID);  ?>

                    <?php $mainFile = (wpm_get_language() == 'en') ? $content['file'] : $content['file_es']; ?>
                    <?php if($mainFile): ?>
                        <div class="item">
                            <a href="<?= $mainFile['url'] ?>" target="_blank">
                                <?php if($featured_image): ?>
                                    <div class="image">
                                        <img src="<?= $featured_image ?>" alt="Featured Image">
                                    </div>
                                <?php endif; ?>
                                <?php if($content['date']): ?>
                                    <?php $formated_date = date("F j, Y", $content['date']) ?>
                                    <div class="date">
                                        <?= translateDate($formated_date) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="press-title">
                                    <?= get_the_title($press[0]->ID) ?>
                                </div>
                                <?php if($content['label']): ?>
                                    <div class="label">
                                        <span><?= $content['label'] . $arrow ?></span>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="press-col-2">
                <?php if($press[1]): ?>
                    <?php $content = get_field('document_content', $press[1]->ID); ?>
                    <?php $featured_image = get_the_post_thumbnail_url($press[1]->ID);  ?>

                    <?php $mainFile = (wpm_get_language() == 'en') ? $content['file'] : $content['file_es']; ?>
                    <?php if($mainFile): ?>
                        <div class="item">
                            <a href="<?= $mainFile['url'] ?>" target="_blank">
                                <?php if($featured_image): ?>
                                    <div class="image">
                                        <img src="<?= $featured_image ?>" alt="Featured Image">
                                    </div>
                                <?php endif; ?>
                                <?php if($content['date']): ?>
                                    <?php $formated_date = date("F j, Y", $content['date']) ?>
                                    <div class="date">
                                        <?= translateDate($formated_date) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="press-title">
                                    <?= get_the_title($press[1]->ID) ?>
                                </div>
                                <?php if($content['label']): ?>
                                    <div class="label">
                                        <span><?= $content['label'] . $arrow ?></span>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($press[2]): ?>
                    <?php $content = get_field('document_content', $press[2]->ID); ?>
                    <?php $featured_image = get_the_post_thumbnail_url($press[2]->ID);  ?>

                    <?php $mainFile = (wpm_get_language() == 'en') ? $content['file'] : $content['file_es']; ?>
                    <?php if($mainFile): ?>
                        <div class="item">
                            <a href="<?= $mainFile['url'] ?>" target="_blank">
                                <?php if($featured_image): ?>
                                    <div class="image">
                                        <img src="<?= $featured_image ?>" alt="Featured Image">
                                    </div>
                                <?php endif; ?>
                                <?php if($content['date']): ?>
                                    <?php $formated_date = date("F j, Y", $content['date']) ?>
                                    <div class="date">
                                        <?= translateDate($formated_date) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="press-title">
                                    <?= get_the_title($press[2]->ID) ?>
                                </div>
                                <?php if($content['label']): ?>
                                    <div class="label">
                                        <span><?= $content['label'] . $arrow ?></span>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    