   
<?php
/**
 * 
 * Partial Name: FibrasomaHome3
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>
<section class="fibrasomahome3-partial-1f0e42">
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

            <div class="list">
                <?php if($pageBlock['block_content']['list']): ?>
                    <?php foreach($pageBlock['block_content']['list'] as $key => $item): ?>
                        <?php if($item['link']): ?>
                            <div class="item">
                                <a href="<?= $item['link']['url'] ?>" target="<?= $item['link']['target'] ?>" data-item="<?= $key ?>">
                                    <?= $item['link']['title'] ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="link">
                <?php if($pageBlock['block_content']['link']): ?>
                    <a class="underline-text" href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>">
                        <?= $pageBlock['block_content']['link']['title'] ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="image">
                <?php if($pageBlock['block_content']['image']): ?>
                    <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
                <?php endif; ?>
                <?php if($pageBlock['block_content']['list']): ?>
                    <?php foreach($pageBlock['block_content']['list'] as $key => $item): ?>
                        <?php if($item['image']): ?>
                            <div class="link-image"  data-item="<?= $key ?>">
                                <img src="<?= $item['image']['url'] ?>" alt="<?= $item['image']['alt'] ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
    <script>
        $('.fibrasomahome3-partial-1f0e42').find('.list a').on('mouseover', function() {
            $('.fibrasomahome3-partial-1f0e42').find(`.image .link-image[data-item="${$(this).data('item')}"]`).addClass('active').siblings().removeClass('active');
        });
        $('.fibrasomahome3-partial-1f0e42').find('.list a').on('mouseout', function() {
            $('.fibrasomahome3-partial-1f0e42').find(`.image .link-image`).removeClass('active');
        });
    </script>
</section>