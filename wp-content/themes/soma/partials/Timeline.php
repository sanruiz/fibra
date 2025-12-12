   
<?php
/**
 * 
 * Partial Name: Timeline
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['slides']): ?>
<section class="timeline-partial-04e48b" data-autoplay="<?= $pageBlock['block_content']['autoplay'] ?>" data-speed="<?= $pageBlock['block_content']['autoplay_speed'] ?>">
    <div class="timeline-slider">
        <?php foreach($pageBlock['block_content']['slides'] as $key => $item): ?>
            <div class="item">
                <?php if($item['image']): ?>
                    <div class="image">
                        <img src="<?= $item['image']['url'] ?>" alt="<?= $item['image']['alt'] ?>">
                    </div>
                <?php endif; ?>
                <?php if($item['year']): ?>
                    <div class="year"><?= $item['year'] ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="timeline-dots movil">
        <div class="dot-container">
            <?php foreach($pageBlock['block_content']['slides'] as $key => $item): ?>
                <div class="dot"></div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="timeline-captions">
        <?php foreach($pageBlock['block_content']['slides'] as $key => $item): ?>
            <div>
                <div id= "text-item" class="box-text-item item">
                    <?= $item['text'] ?>
                </div>
                <div class="read" onclick="deploy(this)" >
                    Read more
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="timeline-dots desk">
        <div class="dot-container">
            <?php foreach($pageBlock['block_content']['slides'] as $key => $item): ?>
                <div class="dot"></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    var allboxtext = $('.box-text-item');
    allboxtext.each(function (index,objeto) {
        var boxtextH = $(objeto).height();
           
        if (boxtextH > 131 && !$(objeto).hasClass("boxtext-H")) {
            $(objeto).addClass("boxtext-H");
        }
        else if (!$(objeto).hasClass("boxtext-H")) {
            $(objeto).parent().find('.read').hide();
        }
    });

    
    function deploy(element) {
        if ($(element).text().trim() == "Read more") {
            $(element).text("Read less");
            $(element).parent().parent().find('.box-text-item').removeClass("boxtext-H");
        }
        else{
            $(element).text("Read more");
            $(element).parent().parent().find('.box-text-item').addClass("boxtext-H");
        }
    }
</script>

                    