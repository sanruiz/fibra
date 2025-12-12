import $ from 'jquery';

const getSearchResults = (query, lang, dataHandler) => {

    $.ajax({
        method: 'GET',
        data: { 's': query, 'lang': lang },
        url: _dittoURL_
    }).done(result => { dataHandler( result ) });

    return true;
}

const printResult = (data, lang) => {
    let post_type = (data.main_category != '') ? data.main_category : data.post_type;
    post_type = (post_type == 'Fibrasoma') ? lang == 'es' ? 'Portafolio' : 'Portfolio' : post_type;
    return `
        <div class="item">
            <a href="${data.permalink}">
                ${data.featured_image ? `
                    <div class="thumb image">
                        <img src="${data.featured_image}" alt="Thumbnail"/>
                    </div>
                ` : `
                    <div class="thumb logo">
                        <img src="${_dittoURI_}/images/soma_white.svg" alt="Thumbnail"/>
                    </div>
                `}
                <div class="post-type">
                    ${(post_type == 'team-members') ? 'Team' : post_type}
                </div>
                <div class="item-title">
                    <h3>${data.title}</h3>
                </div>
            </a>
        </div>
    `;
}

const searchPanelHandler = (containers) => {
    containers.each((key, el) => {

        // Triggers
        if($('.search-trigger').length > 0) {
            $('.search-trigger').each((key, trigger) => {
                $(trigger).on('click', () => {
                    $(el).addClass('open');
                    $('#theFieldID').focus();
                })
            })
        }
        $(el).find('.close-button').on('click', () => {
            $(el).removeClass('open');
        });

        // Form
        $(el).find('form').on('submit', (event) => {
            event.preventDefault();
            getSearchResults( $(el).find('form input').val(), $(el).data('lang'), res => {
                let dataResult = JSON.parse(res);
                $(el).find('.search-nav .message').html( dataResult.message );
                $(el).find('.search-results').html('');
                if(dataResult.status == 'success') {
                    dataResult.data.map(item => {
                        $(el).find('.search-results').append( printResult( item, $(el).data('lang') ) );
                    });
                }
            });
        });

    })
}

export default searchPanelHandler;