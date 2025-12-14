<?php
/**
 * Block Partial: Text
 *
 * Simple text content block
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'text_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Text')
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


?>

<?php if(get_query_var('soma_block_content')['text']): ?>
<section class="text-partial-4ad1f2 <?= get_query_var('soma_block_content')['dark_style'] ? 'dark-style' : '' ?>">
    <div class="container">
        <div class="content">
            <div id="content" onload="countLines();" class="box-content columns-<?= get_query_var('soma_block_content')['columns'] ?> justify-<?= get_query_var('soma_block_content')['justify'] ?> font-size-<?= get_query_var('soma_block_content')['font_size'] ?>">
                <?= get_query_var('soma_block_content')['text'] ?>
            </div>
            <div class="read" onclick="deploy(this)" >
                Read more
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    var allboxtext = $('.box-content');
    allboxtext.each(function (index,objeto) {
        var boxtextH = $(objeto).height();
        if (boxtextH > 400 && !$(objeto).hasClass("boxtextcontent-H")) {
            $(objeto).addClass("boxtextcontent-H");
        }
        else if (!$(objeto).hasClass("boxtextcontent-H")) {
            $(objeto).parent().find('.read').hide();
        }
    });

    
    function deploy(element) {
        if ($(element).text().trim() == "Read more") {
            $(element).text("Read less");
            $(element).parent().parent().find('.box-content').removeClass("boxtextcontent-H");
        }
        else{
            $(element).text("Read more");
            $(element).parent().parent().find('.box-content').addClass("boxtextcontent-H");
        }
    }
</script>
                    