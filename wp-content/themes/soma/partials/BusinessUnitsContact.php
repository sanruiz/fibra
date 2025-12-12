   
<?php
/**
 * 
 * Partial Name: BusinessUnitsContact
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="businessunitscontact-partial-6f1892">
    <div class="container">
        <div class="content">
            <div class="title">
                <?php if($pageBlock['block_content']['title']): ?>
                    <h3><?= $pageBlock['block_content']['title'] ?></h3>
                <?php endif; ?>
            </div>
            <div class="info">
                <?php if($pageBlock['block_content']['units']): ?>
                    <div class="units">
                        <?php foreach($pageBlock['block_content']['units'] as $key => $unit): ?>
                            <div class="unit">
                                <div class="logo">
                                    <?php if($unit['color']): ?>
                                        <svg width="40px" height="42px" viewBox="0 0 40 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-625.000000, -654.000000)">
                                                    <g transform="translate(625.000000, 654.847656)">
                                                        <polygon fill="<?= $unit['color'] ?>" points="39.8831073 34.0075742 33.8973849 16.4451669 28.292219 0 11.4849602 0 0 33.6972732 0 40.5423322 7.50677148 40.5423322 18.044656 9.27646911 21.7321309 9.27646911 32.2696231 40.5423322 39.8831073 40.5423322"></polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <?php if($unit['title']): ?>
                                    <div class="unit-title"><?= $unit['title'] ?></div>
                                <?php endif; ?>
                                <?php if($unit['link']): ?>
                                    <div class="link">
                                        <a href="<?= $unit['link']['url'] ?>" target="<?= $unit['link']['target'] ?>"><?= $unit['link']['title'] ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    