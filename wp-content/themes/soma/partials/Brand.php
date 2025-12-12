   
<?php
/**
 * 
 * Partial Name: Brand
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
global $pageBlock;
$iditem = str_replace(' ', '',$pageBlock['block_content']['title']);
$iditem  = preg_replace('/[^A-Za-z0-9\-]/', '', $iditem ); 
?>
<section class="brand-partial-e66256" id="<?= $iditem  ?>">
    <div class="container">
        <div class="content">
            <div class="text tablet">
                <?php if($pageBlock['block_content']['title']): ?>
                    <h3><u><?= $pageBlock['block_content']['title'] ?></u></h3>
                <?php endif; ?>
                <?php if($pageBlock['block_content']['subtitle']): ?>
                    <div class="subtitle"><?= $pageBlock['block_content']['subtitle'] ?></div>
                <?php endif; ?>
            </div>
            <div class="gallery">
                <?php if($pageBlock['block_content']['gallery']): ?>
                    <div class="brand-slick">
                        <?php foreach($pageBlock['block_content']['gallery'] as $key => $item): ?>
                            <div class="item">
                                <img src="<?= $item['url'] ?>" alt="<?= $item['alt'] ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text">
                <div class="boxtext">
                    <?php if($pageBlock['block_content']['title']): ?>
                        <h3 class="desk"><u><?= $pageBlock['block_content']['title'] ?></u></h3>
                    <?php endif; ?>
                    <?php if($pageBlock['block_content']['subtitle']): ?>
                        <div class="subtitle desk"><?= $pageBlock['block_content']['subtitle'] ?></div>
                    <?php endif; ?>
                    <?php if($pageBlock['block_content']['description']): ?>
                        <div class="description">
                            <p><?= $pageBlock['block_content']['description'] ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($pageBlock['block_content']['categories']): ?>
                        <ul>
                            <?php foreach($pageBlock['block_content']['categories'] as $key => $item): ?>
                                <li><?= $item['category'] ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="boxlink">
                    <div class="read" onclick="deploy(this)" >
                        Read more
                    </div>
                    <?php if($pageBlock['block_content']['link']): ?>
                        <div class="link">
                            <a href="<?= $pageBlock['block_content']['link']['url'] ?>" target="<?= $pageBlock['block_content']['link']['target'] ?>">
                                <?= $pageBlock['block_content']['link']['title'] ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var allboxtext = $('.boxtext');
    allboxtext.each(function (index,objeto) {
        var boxtextH = $(objeto).height();

        if (boxtextH > 200 && !$(objeto).hasClass("boxtext-H")) {
            $(objeto).addClass("boxtext-H");
        }
        else if (!$(objeto).hasClass("boxtext-H")) {
            $(objeto).parent().find('.read').hide();
        }
    });

    
    function deploy(element) {
        if ($(element).text().trim() == "Read more") {
            $(element).text("Read less");
            $(element).parent().parent().find('.boxtext').removeClass("boxtext-H");
        }
        else{
            $(element).text("Read more");
            $(element).parent().parent().find('.boxtext').addClass("boxtext-H");
        }
    }
</script>

                    