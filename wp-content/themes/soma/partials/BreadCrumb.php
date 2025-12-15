<?php
/**
 * Block Partial: BreadCrumb
 *
 * Breadcrumb navigation
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'breadcrumb_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('BreadCrumb')
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



global $post;
$post_ancestors = array_reverse( get_post_ancestors( get_the_id() ) );

/**
 * Limit text to a specific number of words.
 *
 * @param string $text  Text to limit.
 * @param int    $limit Maximum number of words.
 * @return string Limited text with [...] if truncated.
 */
function limit_text( $text, $limit ) {
	if ( str_word_count( $text, 0 ) > $limit ) {
		$words = str_word_count( $text, 2 );
		$pos   = array_keys( $words );
		$text  = substr( $text, 0, $pos[ $limit ] ) . '[...]';
	}
	return $text;
}

$header_options = get_field( 'header_content', 'options' );
?>

<section class="breadcrumb-partial-ad683a">
	<div class="container">
		<div class="content">

			<?php if ( $post_ancestors ) : ?>
				<?php foreach ( $post_ancestors as $key => $item ) : ?>
				<a href="<?php echo esc_url( get_the_permalink( $item ) ); ?>"><?php echo esc_html( get_the_title( $item ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( is_page() ) : ?>
			<span><?php echo esc_html( get_the_title() ); ?></span>
			<?php endif; ?>
		<?php if ( get_post_type() === 'news' ) : ?>
				<?php if ( get_page_by_path( 'news/' ) ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'news/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'news/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endif; ?>
				<span><?php echo esc_html( limit_text( get_the_title(), 3 ) ); ?></span>
			<?php endif; ?>

		<?php if ( get_post_type() === 'careers' ) : ?>
			<?php if ( get_page_by_path( 'company/' ) && $header_options['style'] === 'soma' ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'company/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'company/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endif; ?>
				<?php if ( get_page_by_path( 'company/careers/' ) ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'company/careers/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'company/careers/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endif; ?>
				<span><?php echo esc_html( limit_text( get_the_title(), 3 ) ); ?></span>
			<?php endif; ?>

		<?php if ( get_post_type() === 'team-members' ) : ?>
			<?php if ( get_page_by_path( 'company/' ) && $header_options['style'] === 'soma' ) : ?>
				<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'company/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'company/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
			<?php endif; ?>
			<?php if ( get_page_by_path( 'corporate-governance/' ) && $header_options['style'] === 'fibrasoma' ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'corporate-governance/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'corporate-governance/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endif; ?>
				<?php if ( get_page_by_path( 'company/leadership/' ) ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'company/leadership/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'company/leadership/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endif; ?>
				<span><?php echo esc_html( get_the_title() ); ?></span>
			<?php endif; ?>

			<?php if ( get_post_type() === 'portfolio' ) : ?>
				<?php if ( get_page_by_path( 'business-units/' ) ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'business-units/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'business-units/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endif; ?>
				<?php $terms = get_the_terms( get_the_id(), 'portfolio-taxonomy' ); ?>
				<?php if ( $terms ) : ?>
					<?php foreach ( $terms as $key => $taxonomy_term ) : ?>
						<?php if ( $taxonomy_term->slug === 'soma_real_estate' ) : ?>
							<?php if ( get_page_by_path( 'business-units/soma-real-estate/' ) ) : ?>
								<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'business-units/soma-real-estate/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'business-units/soma-real-estate/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( $taxonomy_term->slug === 'soma_construction' ) : ?>
							<?php if ( get_page_by_path( 'business-units/soma-construction/' ) ) : ?>
								<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'business-units/soma-construction/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'business-units/soma-construction/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if ( get_page_by_path( 'portfolio/' ) ) : ?>
					<a href="<?php echo esc_url( get_the_permalink( get_page_by_path( 'portfolio/' )->ID ) ); ?>"><?php echo esc_html( get_the_title( get_page_by_path( 'portfolio/' )->ID ) ); ?></a><i>&nbsp;—&nbsp;</i>
				<?php endif; ?>
				<span><?php echo esc_html( get_the_title() ); ?></span>
			<?php endif; ?>

		</div>
	</div>
</section>
				
