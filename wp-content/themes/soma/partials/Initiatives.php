<?php
/**
 * Block Partial: Initiatives
 *
 * Initiatives showcase
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'initiatives_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Initiatives')
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

<?php if(get_query_var('soma_block_content')['initiatives']): ?>
<section class="initiatives-partial-215232">
    <div class="container">
        <?php if(get_query_var('soma_block_content')['title']): ?>
            <div class="main-title">
                <h3><?= get_query_var('soma_block_content')['title'] ?></h3>
            </div>
        <?php endif; ?>
        <div class="content">
            <?php foreach(get_query_var('soma_block_content')['initiatives'] as $key => $item): ?>
                <?php if($item['title'] && $item['image'] && $item['pdf']): ?>
                    <div class="item">
                        <a href="<?= $item['pdf']['url'] ?>" target="_blank">
                            <div class="image">
                                <img src="<?= $item['image']['url'] ?>" alt="<?= $item['image']['alt'] ?>">
                            </div>
                            <div class="title">
                                <h3><?= $item['title'] ?></h3>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
                    