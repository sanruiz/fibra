import $ from 'jquery';

const textSliderHandler = (containers) => {
    containers.each((key, el) => {
        // Init Carousel
        $(el).find('.slider').slick({
            fade: true,
            arrows: false,
            dots: false,
            swipe: false,
            autoplay: ($(el).data('autoplay') == 1 && $(el).data('autoplay-speed') ) ? true : false,
            autoplaySpeed: ($(el).data('autoplay') == 1 && $(el).data('autoplay-speed') ) ? $(el).data('autoplay-speed') : null,
        });

        // Events
        $(el).find('.slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            $(el).find(`.list .item[data-slide="${nextSlide}"]`).addClass('active').siblings().removeClass('active');
        });

        // Triggers
        $(el).find('.list .item').on('click', function() {
            $(el).find('.slider').slick('slickGoTo', $(this).data('slide'));
        });
    })
}

export default textSliderHandler;