import $ from 'jquery';

const navigationSideBarTempleateHandler = (container) => {
    container.each((key, el)  => {
        if ($(el).find('.current-menu-item').length == 1) {
            $(el).find('#menu-navigation-sidebar-template').before(`
                <div class="mobile-title">
                    ${$(el).find('.current-menu-item a').text()}
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