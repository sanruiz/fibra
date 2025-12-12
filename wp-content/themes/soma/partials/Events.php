   
<?php
/**
 * 
 * Partial Name: Events
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<section class="events-partial-e5e1bb" data-lang="<?= wpm_get_language() ?>">
    <div class="container">
        <div class="content">
            <div class="filters">
                <div class="mobile-title" onclick="$(this).toggleClass('open')"><?= (wpm_get_language() == 'en') ? 'Filter by Month' : 'Filtrar por mes' ?> <span></span></div>
                <div class="list">
                    <!-- Ajax -->
                    <div class="item active" data-filter="all"><?= (wpm_get_language() == 'en') ? 'See All' : 'Ver Todos' ?></div>
                </div>
            </div>
            <div class="events">
                <div class="event-list">
                    <!-- Ajax -->
                </div>
            </div>
        </div>
    </div>
</section>
                    