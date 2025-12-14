   
<?php
/**
 * 
 * Single careers Partial
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$info = get_field('careers_info');
$featured_image = get_the_post_thumbnail_url();
?>

<section class="single-careers-d3cb75">
    <div class="container">
        <div class="container-box">
            <h2 class="career-name"><?= get_the_title() ?></h2>
            <div class="career-text"><?= $info["text"]?></div>
        </div>
        <div class="container-apply">
            <a href="<?= $info["apply_now"]["url"]?>" target="<?= $info["apply_now"]['target'] ?>"><h3><?= $info["apply_now"]["title"]?></h3></a>
        </div>
    </div>
</section>

                    