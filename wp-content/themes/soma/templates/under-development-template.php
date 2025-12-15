<?php
/**
 *
 * Template Name: Under Development
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
?>

<style>
	.navbar-partial-df27ae,
	.searchpanel-partial-1749fc,
	.footer-partial-c90350 {
		display: none !important;
	}
	.content {
		position: fixed;
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		height: 100%;
	}
	body {
		background-color: #171717;
	}
</style>

<main id="under-development-template-e38d51">
	<div class="content">
		<img src="<?php echo get_template_directory_uri(); ?>/images/soma_white.svg" alt="Soma Logo" width="300px">
	</div>
</main>


<?php get_footer(); ?>