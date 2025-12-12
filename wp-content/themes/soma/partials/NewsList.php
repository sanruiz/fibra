   
<?php
/**
 * 
 * Partial Name: NewsList
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
$data = $pageBlock['block_content'];

$post_per_page = $data['posts_per_page'] ? "data-posts-per-page='{$data['posts_per_page']}'" : '';
$infinite_scroll = $data['infinite_scroll'] ? "data-infinite-scroll='{$data['infinite_scroll']}'" : '';

$formatedPostList = $data['post_list'] ? json_encode( $data['post_list'] ) : '';
$post_list = $data['post_list'] ? "data-post-list='{$formatedPostList}'" : '';

$props = "{$post_per_page} {$infinite_scroll} {$post_list}";
?>

<section class="newslist-partial-afa6f9 <?= ($data['style'] == 'white') ? 'news-white' : 'news-black' ?>" <?= $props ?> data-lang="<?= wpm_get_language() ?>">
    <div class="container">
        <div class="title-container">
            <div class="title">
                <?php if($data['title_size'] == 'big'): ?>
                    <h2><?= $data['title'] ?></h2>
                <?php else: ?>
                    <h3><?= $data['title'] ?></h3> 
                <?php endif; ?>
            </div>
            <div class="link">
                <?php if($data['link']): ?>
                    <a href="<?= $data['link']['url'] ?>" target="<?= $data['link']['target'] ?>"><?= $data['link']['title'] ?></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="content"></div>
        <div class="loader-container">
            <!-- <div class="loader"><div></div><div></div><div></div></div> -->
            <span class="loading">Loading more</span>
        </div>
    </div>
</section>
                    