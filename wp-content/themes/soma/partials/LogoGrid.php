<?php
/**
 * Block Partial: LogoGrid
 *
 * Grid of logos (partners, clients, etc.)
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'logo_grid_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('LogoGrid')
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

<?php if ( get_query_var( 'soma_block_content' ) ) : ?>
<section class="logogrid-partial-7384ec">
	<div class="container">
		<div class="content">
			<?php foreach ( get_query_var( 'soma_block_content' ) as $key => $item ) : ?>
				<?php
				$idlogo = $item['title'];
					// $idlogo = str_replace("[:en]"," ",$idlogo);
					// $idlogo = str_replace("[:es]"," ",$idlogo);
					// $idlogo = str_replace("[:]"," ",$idlogo);
					$idlogo = do_shortcode( "[wpm_translate]{$idlogo}[/wpm_translate]" );
					$idlogo = preg_replace( '/[^A-Za-z0-9\-]/', '', $idlogo );
				?>
				<div class="item" onclick="scrolltoelement('<?php echo $idlogo; ?>',this)">
					<img src="<?php echo $item['url']; ?>" alt="<?php echo $item['alt']; ?>">
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
<script>
$( document ).ready(function() {
	var pathname = window.location.pathname; 
	var parts = pathname .split("/");
	var last_part = parts[parts.length-2];

	if (last_part == "soma-brands") {
		$('img').css("cursor", "pointer");
	}
});

function scrolltoelement(atributo,element) {
	if( $("#" + atributo.replace(/\s/g, '')).length )         
		{
			$('html, body').animate({
			scrollTop: $("#" + atributo.replace(/\s/g, '')).offset().top
			}, 2000);
		}
	}
</script>