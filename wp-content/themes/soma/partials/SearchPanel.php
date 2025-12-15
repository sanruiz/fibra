<?php
/**
 * Block Partial: SearchPanel
 *
 * Search functionality panel
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'search_panel_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('SearchPanel')
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



$header_options = get_field( 'header_content', 'options' );
?>

<section class="searchpanel-partial-1749fc" data-lang="<?php echo wpm_get_language(); ?>">
	<div class="container">
		<div class="title"><?php echo $header_options['search_title']; ?></div>
		<div class="search-form">
			<form>
				<input id="theFieldID" type="text" autofocus="autofocus" placeholder="<?php echo ( wpm_get_language() == 'en' ) ? 'Search' : 'Buscar'; ?>">
			</form>
			<div class="close-button">
				<svg width="32px" height="31px" viewBox="0 0 32 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<g  transform="translate(-1329.000000, -62.000000)">
							<g transform="translate(1330.000000, 63.000000)" stroke="#7E7E87" stroke-linecap="square" stroke-width="2">
								<line x1="0.5" y1="0.357864376" x2="28.7842712" y2="28.6421356"></line>
								<line x1="0.5" y1="28.6421356" x2="28.7842712" y2="0.357864376"></line>
							</g>
						</g>
					</g>
				</svg>
			</div>
		</div>
		<div class="search-nav">
			<div class="message"></div>
		</div>
		<div class="search-results grid-view"></div>
	</div>
</section>

<!-- <script type = "text / javascript"> 
	document.theFormID.theFieldID.focus (); 
</script> -->
					