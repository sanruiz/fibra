import $ from 'jquery';
import { escapeHtml } from '../utils/helpers';

const navigationSideBarTempleateHandler = (container) => {
    container.each((key, el)  => {
        if ($(el).find('.current-menu-item').length == 1) {
            const menuItemText = escapeHtml($(el).find('.current-menu-item a').text());
            $(el).find('#menu-navigation-sidebar-template').before(`
                <div class="mobile-title">
                    ${menuItemText}
                    <div class="close-button"></div>
                </div>
            `);
            $(el).find('.menu-navigation-sidebar-template-container .mobile-title').on('click', function() {
                $(this).toggleClass('active');
            });
        }
    })
}

export default navigationSideBarTempleateHandler;