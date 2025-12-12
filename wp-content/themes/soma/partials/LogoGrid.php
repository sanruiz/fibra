   
<?php
/**
 * 
 * Partial Name: LogoGrid
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']): ?>
<section class="logogrid-partial-7384ec">
    <div class="container">
        <div class="content">
            <?php foreach($pageBlock['block_content'] as $key => $item): ?>
                <?php $idlogo = $item['title'];
                    // $idlogo = str_replace("[:en]"," ",$idlogo);
                    // $idlogo = str_replace("[:es]"," ",$idlogo);
                    // $idlogo = str_replace("[:]"," ",$idlogo);
                    $idlogo = do_shortcode("[wpm_translate]{$idlogo}[/wpm_translate]");
                    $idlogo  = preg_replace('/[^A-Za-z0-9\-]/', '', $idlogo ); 
                ?>
                <div class="item" onclick="scrolltoelement('<?= $idlogo ?>',this)">
                    <img src="<?= $item['url'] ?>" alt="<?= $item['alt'] ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<script>
$( document ).ready(function() {
    var pathname = window.location.pathname; 
    var parts = pathname .split("/");
    var last_part = parts[parts.length-2];

    if (last_part == "soma-brands") {
        $('img').css("cursor", "pointer");
    }
});

function scrolltoelement(atributo,element) {
    if( $("#" + atributo.replace(/\s/g, '')).length )         
        {
            $('html, body').animate({
            scrollTop: $("#" + atributo.replace(/\s/g, '')).offset().top
            }, 2000);
        }
    }
</script>