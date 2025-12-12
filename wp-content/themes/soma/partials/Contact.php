<?php
/**
 * Block Partial: Contact
 *
 * Contact form or details
 *
 * @package    Soma
 * @subpackage Partials
 * @since      3.0.0
 *
 * Query Variables (set by BlockRenderer):
 * @uses get_query_var('soma_block_counter') int    Block index in the page
 * @uses get_query_var('soma_block_content') array  ACF field data from 'contact_content'
 * @uses get_query_var('soma_block_layout')  string Layout name ('Contact')
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

<?php if(get_query_var('soma_block_content')['form_shortcode']): ?>
<section class="contact-partial-555b5f">
    <div class="container">
        <div class="content">
            <div class="image">
                <?php if(get_query_var('soma_block_content')['image']): ?>
                    <img src="<?= get_query_var('soma_block_content')['image']['url'] ?>" alt="<?= get_query_var('soma_block_content')['image']['alt'] ?>">
                <?php endif; ?>
            </div>
            <div class="form">
                <?= do_shortcode(get_query_var('soma_block_content')['form_shortcode']) ?>
                <div class="thankyou-message" style="display: none">
                    <?= get_query_var('soma_block_content')['thank_you_message'] ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
                    