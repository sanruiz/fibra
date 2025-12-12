   
<?php
/**
 * 
 * Partial Name: Contact
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $pageBlock;
?>

<?php if($pageBlock['block_content']['form_shortcode']): ?>
<section class="contact-partial-555b5f">
    <div class="container">
        <div class="content">
            <div class="image">
                <?php if($pageBlock['block_content']['image']): ?>
                    <img src="<?= $pageBlock['block_content']['image']['url'] ?>" alt="<?= $pageBlock['block_content']['image']['alt'] ?>">
                <?php endif; ?>
            </div>
            <div class="form">
                <?= do_shortcode($pageBlock['block_content']['form_shortcode']) ?>
                <div class="thankyou-message" style="display: none">
                    <?= $pageBlock['block_content']['thank_you_message'] ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
                    