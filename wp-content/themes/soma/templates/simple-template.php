<?php
/**
 *
 * Template Name: Simple
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
?>
<main id="simple-template-ee30ff">
	<section>
		<div class="container">
			<?php echo get_the_content(); ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
