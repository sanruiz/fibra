<?php
/**
 * Block Partial: Art
 *
 * Artwork or cultural content display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'art_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Art')
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



$art = get_posts([
    'post_type' => 'page',
    'fields' => 'ids',
    'nopaging' => true,
    'meta_key' => '_wp_page_template',
    'meta_value' => 'templates/art-template.php'
]);
?>

<?php if($art): ?>
<section class="art-partial-a957aa">
    <div class="container">
        <div class="content">
            <?php foreach($art as $key => $item): ?>
                <?php $thumb = get_the_post_thumbnail_url($item) ?>
                <div class="item">
                    <a href="<?= get_the_permalink($item) ?>">
                        <?php if($thumb): ?>
                            <div class="image">
                                <img src="<?= $thumb ?>" alt="Art Thumbnail">
                            </div>
                        <?php endif; ?>
                        <div class="title">
                            <h3><?= get_the_title($item) ?></h3>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
                    