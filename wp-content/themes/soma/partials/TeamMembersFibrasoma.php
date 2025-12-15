<?php
/**
 * Block Partial: TeamMembersFibrasoma
 *
 * Fibrasoma-specific team member display
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'team_members_fibrasoma_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('TeamMembersFibrasoma')
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

<section class="teammembersfibrasoma-partial-936df8">
	<div class="container">
		<div class="content">
			<?php if ( get_query_var( 'soma_block_content' )['title'] ) : ?>
				<div class="title">
					<h3><?php echo esc_html( get_query_var( 'soma_block_content' )['title'] ); ?></h3>
				</div>
			<?php endif; ?>
			<?php if ( get_query_var( 'soma_block_content' )['text'] ) : ?>
				<div class="text">
					<p><?php echo wp_kses_post( get_query_var( 'soma_block_content' )['text'] ); ?></p>
				</div>
			<?php endif; ?>
			<?php if ( get_query_var( 'soma_block_content' )['team'] ) : ?>
				<div class="team-members">
					<?php foreach ( get_query_var( 'soma_block_content' )['team'] as $key => $item ) : ?>
						<?php
							$info       = get_field( 'team_member_info', $item );
							$terms      = get_the_terms( $item, 'team-members-taxonomy' );
							$categories = '';
						if ( $terms ) :
							foreach ( $terms as $key_term => $term ) :
								if ( $key_term === 0 ) :
									$categories .= $term->name;
									else :
										$categories .= ', ' . $term->name;
									endif;
								endforeach;
							endif;
						?>

						<div class="member <?php echo $info['hide_single_page'] ? 'single-page-hidden' : ''; ?>">
						<a <?php echo $info['hide_single_page'] ? '' : 'href="' . esc_url( get_the_permalink( $item ) ) . '"'; ?>>
							<h3><?php echo esc_html( get_the_title( $item ) ); ?></h3>
							<span class="categories"><?php echo esc_html( $categories ); ?></span>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
					