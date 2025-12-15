<?php
/**
 *
 * Default 404.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

$content = get_field( 'error_404_content', 'options' );
?>

<main id="ditto_404_error">
	<section>
		<div class="container">
			<div class="content">
				<?php echo $content; ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>