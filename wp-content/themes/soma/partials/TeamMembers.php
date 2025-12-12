   
<?php
/**
 * 
 * Partial Name: TeamMembers
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;

$args = [
    'numberposts'	=> -1,
    'post_type'		=> 'team-members',
    'post_status'   => array('publish'),
    'order'			=> $params['order'] ? $params['order'] : 'DESC'
];

if($pageBlock['block_content']['category']) {
    $args['tax_query'] = [
        [
            'taxonomy'  => 'team-members-taxonomy',
            'field'     => 'id',
            'terms'     => $pageBlock['block_content']['category']->term_id
        ]
    ];
}

$members = get_posts( $args );
?>

<section class="teammembers-partial-13dba6">
    <div class="container">
        <?php if($members): ?>
            <?php if($pageBlock['block_content']['title']): ?>
                <div class="title">
                    <h3><?= $pageBlock['block_content']['title'] ?></h3>
                </div>
            <?php endif; ?>
            <div class="members" data-columns="<?= $pageBlock['block_content']['columns'] ?>">
                <?php foreach($members as $key => $item): ?>
                    <?php $info = get_field('team_member_info', $item->ID) ?>
                    <?php $image = get_the_post_thumbnail_url($item->ID) ?>
                    <div class="item <?= $info['hide_single_page'] ? 'single-page-hidden' : '' ?>">
                        <a <?= $info['hide_single_page'] ? '' : 'href="'. get_the_permalink($item->ID). '"' ?>>
                            <?php if($image): ?>
                                <div class="member-image">
                                    <img src="<?= $image ?>" alt="Member image">
                                </div>
                            <?php endif; ?>
                            <div class="member-name">
                                <h3><?= get_the_title($item->ID) ?></h3>
                            </div> 
                            <?php if($info['title']): ?>
                                <div class="member-title">
                                    <?= $info['title'] ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Memebers not found.</p>
        <?php endif; ?>
    </div>
</section>
                    