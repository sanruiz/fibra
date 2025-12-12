   
<?php
/**
 * 
 * Partial Name: TeamMembersFibrasoma
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<section class="teammembersfibrasoma-partial-936df8">
    <div class="container">
        <div class="content">
            <?php if($pageBlock['block_content']['title']): ?>
                <div class="title">
                    <h3><?= $pageBlock['block_content']['title'] ?></h3>
                </div>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['text']): ?>
                <div class="text">
                    <p><?= $pageBlock['block_content']['text'] ?></p>
                </div>
            <?php endif; ?>
            <?php if($pageBlock['block_content']['team']): ?>
                <div class="team-members">
                    <?php foreach($pageBlock['block_content']['team'] as $key => $item): ?>
                        <?php 
                            $info = get_field('team_member_info', $item);
                            $terms = get_the_terms( $item, 'team-members-taxonomy' );
                            $categories = '';
                            if($terms):
                                foreach($terms as $key_term => $term):
                                    if($key_term == 0):
                                        $categories .= $term->name;
                                    else:
                                        $categories .= ', ' . $term->name;
                                    endif;
                                endforeach;
                            endif;
                        ?>

                        <div class="member <?= $info['hide_single_page'] ? 'single-page-hidden' : '' ?>">
                            <a <?= $info['hide_single_page'] ? '' : 'href="'. get_the_permalink($item). '"' ?>>
                                <h3><?= get_the_title($item) ?></h3>
                                <span class="categories"><?= $categories ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
                    