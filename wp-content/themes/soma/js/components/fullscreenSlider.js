import $ from 'jquery';
import Player from '@vimeo/player';
import slick from 'slick-carousel';
import imagesLoaded from 'imagesloaded';

const play_button = `
    <svg width="86px" height="85px" viewBox="0 0 86 85" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" opacity="0.8">
            <g transform="translate(-677.000000, -1021.000000)" fill="#FFFFFF" fill-rule="nonzero">
                <g transform="translate(80.000000, 684.000000)">
                    <g transform="translate(597.500000, 337.070159)">
                        <path d="M63.1108464,41.9689499 L33.0280199,21.243121 C32.7037689,21.0196508 32.283175,20.9958554 31.9330253,21.1769076 C31.5839116,21.3600291 31.3653271,21.721099 31.3653271,22.1152757 L31.3653271,63.5669333 C31.3653271,63.9611099 31.5839117,64.3221799 31.9330253,64.5053013 C32.0884172,64.5859989 32.2572763,64.6263477 32.4261355,64.6263477 C32.6374685,64.6263477 32.8477653,64.5632381 33.0280199,64.4390879 L63.1108463,43.7132591 C63.3988392,43.5156535 63.5697702,43.1897594 63.5697702,42.8411044 C63.5697702,42.4924494 63.3988392,42.1665555 63.1108464,41.9689499 Z M33.4869439,61.5494937 L33.4869439,24.1327152 L60.6411518,42.8411045 L33.4869439,61.5494937 Z"></path>
                        <path d="M42.4323348,0 C19.0355017,0 0,19.0104879 0,42.3765761 C0,65.7426643 19.0355017,84.7531522 42.4323348,84.7531522 C65.8291678,84.7531522 84.8646695,65.7426643 84.8646695,42.3765761 C84.8646695,19.0104879 65.8291678,0 42.4323348,0 Z M42.4323348,82.6343234 C20.2050844,82.6343234 2.12161674,64.5746185 2.12161674,42.3765761 C2.12161674,20.1785337 20.2050844,2.11882881 42.4323348,2.11882881 C64.6595851,2.11882881 82.7430528,20.1785337 82.7430528,42.3765761 C82.7430528,64.5746185 64.6595851,82.6343234 42.4323348,82.6343234 Z"></path>
                    </g>
                </g>
            </g>
        </g>
    </svg>
`;

const getVimeoThumb = (video_id, handleData) => {
    $.ajax({
        type:'GET',
        url: 'https://vimeo.com/api/v2/video/' + video_id + '.json',
        jsonp: 'callback',
        dataType: 'jsonp',
        success: function(data){
            handleData( data[0].thumbnail_large );
        }
    });
}

const fullscreenSliderHandler = (sliders) => {
    sliders.each((key, slider) => {
        imagesLoaded( slider, () => {
            $(slider).find('.slider').slick({
                fade: true,
                dots: true,
                arrows: false
            });
            if($(slider).find('.vimeo-player').length > 0) {
                $(slider).find('.vimeo-player').each((key, el) => {
                    $(el).data('player', new Player(el, {
                        id: $(el).data('video-id'),
                        autoplay: false,
                        controls: true,
                        allowfullscreen: true
                    }));
                    $(el).append(`<button class="play-button">${play_button}</button>`);
                    $(el).find('.play-button').on('click', () => {
                        $(el).data('player').play();
                        $(el).addClass('playing');
                    });
                    getVimeoThumb( $(el).data('video-id'), thumb => {
                        $(el).append(`<div class="thumbnail" style="background-image: url(${thumb})"></div>`);
                    });
                });
            }
        });

    })
}

export default fullscreenSliderHandler;