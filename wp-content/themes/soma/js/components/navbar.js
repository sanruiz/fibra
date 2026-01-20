import $ from 'jquery';
import { escapeHtml } from '../utils/helpers';

const arrow = `
    <svg width="13px" height="21px" viewBox="0 0 13 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g transform="translate(-328.000000, -146.000000)" fill="#171717" fill-rule="nonzero" stroke="#171717" stroke-width="0.8">
                <polygon transform="translate(334.603808, 156.826999) rotate(-360.000000) translate(-334.603808, -156.826999) " points="329.787015 166.97514 329.272461 166.460586 338.906048 156.826999 329.272461 147.193412 329.787015 146.678859 339.935155 156.826999"></polygon>
            </g>
        </g>
    </svg>
`;

const navbarHandler = (containers) => {
    containers.each((key, el) => {
        // Extra elements
        $(el).find('.menu-item-has-children').each((key, submenuParent) => {
            const menuText = escapeHtml($(submenuParent).find('> a').text());
            $(submenuParent).find('> .sub-menu').prepend(`<div class="submenu-title">${arrow}${menuText}</div>`);
            $(submenuParent).find('> a').append(arrow);
        });

        // Triggers
        $(el).find('.hamburger').on('click', () => {
            $(el).toggleClass('open-menu');
            if($(el).hasClass('open-menu')) {
                $('html').addClass('no-scroll');
            } else {
                $('html').removeClass('no-scroll');
                $(el).removeClass('submenu-active');
                $(el).find('.sub-menu').removeClass('open-submenu');
            }
        });
        $(el).find('.menu-item-has-children > a').on('click', function() {
            $(this).parent().find('> .sub-menu').addClass('open-submenu');
            $(el).addClass('submenu-active');
        });
        $(el).find('.submenu-title').on('click', function() {
            $(this).parent().removeClass('open-submenu');
            $(el).removeClass('submenu-active');
        });

        // Current item class added to ancestors
        $(el).find('ul.sub-menu li.current-menu-item').parents('li.menu-item-has-children').addClass('current-menu-item');

        // Sticky navbar functions
        const openSticky = () => {
            $(el).removeClass('sticky-close');
            $(el).addClass('sticky-open');
        }

        // Sticky navbar events
        $(window).on('scroll', () => {
            if($(window).scrollTop() > 100) {
                $('#page').css('padding-top', $(el).not('.prepare-sticky').innerHeight() + 'px');
                $(el).addClass('prepare-sticky');
                openSticky();
            } else {
                $(el).removeClass('prepare-sticky sticky-open sticky-close');
                $('#page').css('padding-top', '0px'); 
            }
        });

        // Prevent default anchor
        $(el).find('a[href="#"]').on('click', event => { event.preventDefault(); })
    })
}

export default navbarHandler;