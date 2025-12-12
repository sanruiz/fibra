import $ from 'jquery';
import slick from 'slick-carousel';
import imagesLoaded from 'imagesloaded';

const arrow = `
<svg width="37px" height="34px" viewBox="0 0 37 34" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-668.000000, -2589.000000)">
            <g transform="translate(669.000002, 2588.867188)" stroke="#171717">
                <g transform="translate(17.509375, 16.950320) rotate(-90.000000) translate(-17.509375, -16.950320) translate(0.509375, -0.549680)">
                    <line x1="16.892746" y1="0.0774246277" x2="16.892746" y2="33.3422766" stroke-width="1.5999999" stroke-linecap="square"></line>
                    <polygon stroke-width="0.5" fill="#171717" fill-rule="nonzero" transform="translate(16.892746, 26.370033) rotate(-270.000000) translate(-16.892746, -26.370033) " points="9.09551414 42.7974356 8.26257542 41.9644969 23.8570392 26.3700331 8.26257542 10.7755694 9.09551414 9.94263065 25.5229166 26.3700331"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
`;

const brandHandler = (brands) => {
    brands.each((key, el) => {
        imagesLoaded(el, () => {
            $(el).find('.brand-slick').slick({
                dots: true,
                prevArrow: `<button class="slick-arrow prev-arrow">${arrow}</button>`,
                nextArrow: `<button class="slick-arrow next-arrow">${arrow}</button>`
            })
        })
    })
}

export default brandHandler;