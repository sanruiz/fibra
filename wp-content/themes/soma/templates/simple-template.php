<?php
/**
 * Template Name: Simple
 *
 * A simple template that renders the content with full HTML support.
 * Uses the_content() to allow iframes, styles, and embedded content.
 *
 * @package Soma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
?>
<main id="simple-template-ee30ff">
	<section>
		<div class="container">
			<?php
			// Use the_content() to properly render content with iframes, styles, and embeds.
			// This applies WordPress content filters and respects user capabilities.
			the_content();
			?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
