import $ from 'jquery';
import slick from 'slick-carousel';
import imagesLoaded from 'imagesloaded';

const arrow = `
<svg width="46px" height="42px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-1062.000000, -4239.000000)">
            <g transform="translate(1063.000000, 4239.000000)" stroke="#171717">
                <g transform="translate(22.011719, 21.437902) rotate(-90.000000) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                    <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="2" stroke-linecap="square"></line>
                    <polygon stroke-width="0.5" fill="#171717" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
`;

const timelineHandler = (containers) => {
    containers.each((key, el) => {
        imagesLoaded(el, () => {
            $(el).find('.timeline-slider').slick({
                centerMode: true,
                centerPadding: '19.55vw',
                speed: 800,
                infinite: false,
                prevArrow: `<button class="slick-arrow prev-arrow">${arrow}</button>`,
                nextArrow: `<button class="slick-arrow next-arrow">${arrow}</button>`,
                asNavFor: '.dot-container',
                focusOnSelect: true,
                autoplay: $(el).data('autoplay') ? true : false,
                autoplay: ($(el).data('autoplay') && $(el).data('speed')) ? $(el).data('speed') : 0
            });
            $(el).find('.timeline-captions').slick({
                fade: true,
                speed: 800,
                arrows: false,
                infinite: false,
                draggable: false,
                pauseOnFocus: false,
                pauseOnHover: false
            });
            $(el).find('.dot-container').slick({
                speed: 800,
                arrows: false,
                infinite: false,
                draggable: false,
                pauseOnFocus: false,
                pauseOnHover: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                centerMode: false,
                centerPadding: '45px',
                asNavFor: '.timeline-slider',
                focusOnSelect: true
            });
            $(el).find('.timeline-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
                $(el).find('.timeline-captions').slick('slickGoTo', nextSlide);
            });
            
            if(location.hash.substr(1) == 'timeline') {
                scroll({
                    top: el.offsetTop,
                    behavior: "smooth"
                });
            }
        });
    });
}

export default timelineHandler;

// Left = 13.1vw + Right = 26vw
// 39.1 / 2 = 19.55