<?php
/**
 * Block Partial: TextSlider
 *
 * Text content carousel/slider
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'text_slider_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('TextSlider')
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


function addZero($num) {
    if($num < 10) {
        $num = '0'.$num;
    }
    return $num;
}
?>

<?php if(get_query_var('soma_block_content')['slides']): ?>
<section class="textslider-partial-8bf200" <?= get_query_var('soma_block_content')['autoplay'] ? 'data-autoplay="1"' : 'data-autoplay="0"' ?> data-autoplay-speed="<?= get_query_var('soma_block_content')['autoplay_speed'] ?>">
    <div class="container">
        <div class="content">
            <div class="selector">
                <?php if(get_query_var('soma_block_content')['title']): ?>
                    <div class="title" onClick="$(this).toggleClass('closed')">
                        <?= get_query_var('soma_block_content')['title'] ?>
                        <span class="close-button"></span>
                    </div>
                <?php endif; ?>
                <div class="list">
                    <?php foreach(get_query_var('soma_block_content')['slides'] as $key => $item): ?>
                        <?php if($item['label']): ?>
                            <div class="item" data-slide="<?= $key ?>">
                                <?= $item['label'] ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="slider">
                <?php foreach(get_query_var('soma_block_content')['slides'] as $key => $item): ?>
                    <?php if($item): ?>
                        <div class="item">
                            <h2><?= addZero($key + 1) ?></h2>
                            <?php if($item['title']): ?>
                                <h3><?= $item['title'] ?></h3>
                            <?php endif; ?>
                            <?php if($item['text']): ?>
                                <p><?= $item['text'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    $( document ).ready(function() {
        $('.item[data-slide="0"]').addClass('active');
    });
</script>
                    