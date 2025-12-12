<?php
/**
 * Block Partial: Report
 *
 * Report display block
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'report_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Report')
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
<svg width="17px" height="18px" viewBox="0 0 17 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-1128.000000, -514.000000)">
            <g transform="translate(1136.256000, 523.820312) rotate(-270.000000) translate(-1136.256000, -523.820312) translate(1127.616000, 515.564312)" stroke="#171717">
                <g transform="translate(8.504500, 8.296155) rotate(-90.000000) translate(-8.504500, -8.296155) translate(0.504500, -0.203845)">
                    <line x1="8.10851902" y1="0.0371638255" x2="8.10851902" y2="16.0042946" stroke-width="0.768000042" stroke-linecap="square"></line>
                    <polygon stroke-width="0.5" fill="#171717" fill-rule="nonzero" transform="translate(8.108519, 12.657617) rotate(-270.000000) translate(-8.108519, -12.657617) " points="4.36584728 20.5427715 3.96603665 20.1429608 11.4513801 12.6576174 3.96603665 5.17227389 4.36584728 4.77246326 12.2510014 12.6576174"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
';
?>

<?php if($pageBlock['block_content']['report']): ?>
<?php 
    $content = get_field('document_content', $pageBlock['block_content']['report']); 
    $featured_image = get_the_post_thumbnail_url($pageBlock['block_content']['report']);
?>
<section class="report-partial-7c7fc0">
    <div class="container">
        <div class="content">
            <div class="image">
                <h3><?= get_the_title($pageBlock['block_content']['report']) ?></h3>
                <?php if($featured_image): ?>
                    <img src="<?= $featured_image ?>" alt="Featured image">
                <?php endif; ?>
            </div>
            <div class="text">
                <div class="title">
                    <h3><?= get_the_title($pageBlock['block_content']['report']) ?></h3>
                </div>
                <?php if($content['description']): ?>
                    <div class="description">
                        <p><?= $content['description'] ?></p>
                    </div>
                <?php endif; ?>

                <?php $mainFile = (wpm_get_language() == 'en') ? $content['file'] : $content['file_es']; ?>
                <?php if($mainFile): ?>
                    <div class="link">
                        <a href="<?= $mainFile['url'] ?>" target="_blank">
                            <?= $content['label'] . $arrow ?>
                        </a>
                        <?php if($content['has_additional_files'] && $content['additional_files']): ?>
                            <?php foreach($content['additional_files'] as $key => $file): ?>
                                <?php $extraFile = (wpm_get_language() == 'en') ? $file['file'] : $file['file_es']; ?>
                                <a href="<?= $extraFile['url'] ?>" target="_blank"><?= $file['label'] . $arrow ?></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>