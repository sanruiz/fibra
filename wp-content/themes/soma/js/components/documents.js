import $ from 'jquery'

const arrow = `
<svg viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-733.000000, -553.000000)">
            <g transform="translate(734.000000, 553.052734)">
                <g class="color" transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                    <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="2" stroke-linecap="square"></line>
                    <polygon stroke-width="1" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
`;

const getDocuments = (args, handleData) => {
    $.ajax({
        method: 'GET',
        data: args,
        url: _dittoURL_ + '/wp-json/soma/documents'
    }).done(response => {
        handleData( response );
    });

    return false;
}

const documentItem = (data) => {
    return `
        <a href="${data.file.url}" target="_blank">
            <div class="press-item">
                <div class="image">
                    ${ data.featured_image ? `<img src="${data.featured_image}" alt="News featured image" />` : ''}
                </div>
                <div class="text">
                    <div class="press-title"><h3>${data.title}</h3></div>
                    <div class="press-label">${data.label + arrow}</div>
                    <div class="press-date">${data.formated_date}</div>
                </div>
            </div>
        </a>
    `;
}

const documentsHandler = (containers) => {
    containers.each((key, el) => {
        const content = $(el).find('.content');
        const loader = $(el).find('.loader-container');
        let current_offset = 0;
        let current_total = 0;

        // Initial args
        let args = {};
            if($(el).data('posts-per-page')) args = { ...args, 'posts_per_page': $(el).data('posts-per-page') };
            if($(el).data('lang')) args = { ...args, 'lang': $(el).data('lang') };
            if($(el).data('category')) args = { ...args, 'categories': $(el).data('category') };
            if($(el).data('order-by-date') == 1) args = { ...args, 'order_by': 'custom_date', 'order' : 'DESC' };

        // First Render
        getDocuments(args, res => {
            if(res.data) {
                res.data.map(item => { 
                    if (item.file) {
                        content.append( documentItem(item) ) 
                    }
                });
            }
            if (args.posts_per_page) {
                current_offset += args.posts_per_page;
                current_total += res.total;
            }
            loader.addClass('loaded');
        });

        // Infinite scroll
        if(args.posts_per_page) {
            let loading = true;
            $(window).on('scroll', () => {
                let scroll = $(window).scrollTop() + $(window).height();
                let item_height = $(el).innerHeight() + $(el).offset().top;

                if(scroll > (item_height + 100) && current_total > current_offset && loading) {
                    loading = false;
                    loader.removeClass('loaded');
                    args = { ...args, 'offset': current_offset };
                    getDocuments(args, res => {
                        res.data.map(item => { 
                            if (item.file) {
                                content.append( documentItem(item) ) 
                            }
                        });
                        current_total = res.total;
                        current_offset += args.posts_per_page;
                        loading = true;
                        loader.addClass('loaded');
                    });
                } else {
                    loader.addClass('loaded');
                }
            });
        }
    })
}

export default documentsHandler;