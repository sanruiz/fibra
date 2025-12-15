<?php
/**
 * Block Partial: TeamMembers
 *
 * Grid of team member cards
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'team_members_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('TeamMembers')
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


$args = [
	'numberposts' => -1,
	'post_type'   => 'team-members',
	'post_status' => array( 'publish' ),
	'order'       => $params['order'] ? $params['order'] : 'DESC',
];

if ( get_query_var( 'soma_block_content' )['category'] ) {
	$args['tax_query'] = [
		[
			'taxonomy' => 'team-members-taxonomy',
			'field'    => 'id',
			'terms'    => get_query_var( 'soma_block_content' )['category']->term_id,
		],
	];
}

$members = get_posts( $args );
?>

<section class="teammembers-partial-13dba6">
	<div class="container">
		<?php if ( $members ) : ?>
			<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
				<div class="title">
					<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
				</div>
			<?php endif; ?>
			<div class="members" data-columns="<?php echo esc_attr( get_query_var( 'soma_block_content' )['columns'] ); ?>">
				<?php foreach ( $members as $key => $item ) : ?>
					<?php $info = get_field( 'team_member_info', $item->ID ); ?>
					<?php $image = get_the_post_thumbnail_url( $item->ID ); ?>
					<div class="item <?php echo $info['hide_single_page'] ? 'single-page-hidden' : ''; ?>">
					<a <?php echo $info['hide_single_page'] ? '' : 'href="' . esc_url( get_the_permalink( $item->ID ) ) . '"'; ?>>
							<?php if ( $image ) : ?>
								<div class="member-image">
								<img src="<?php echo esc_url( $image ); ?>" alt="Member image">
								</div>
							<?php endif; ?>
							<div class="member-name">
								<h3><?php echo esc_html( get_the_title( $item->ID ) ); ?></h3>
							</div> 
							<?php if ( $info['title'] ) : ?>
								<div class="member-title">
								<?php echo esc_html( $info['title'] ); ?>
								</div>
							<?php endif; ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p>Memebers not found.</p>
		<?php endif; ?>
	</div>
</section>
					