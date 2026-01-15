import $ from 'jquery';

const arrow = `
<svg width="46px" height="42px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-733.000000, -553.000000)">
            <g transform="translate(734.000000, 553.052734)">
                <g transform="translate(22.011719, 21.437902) rotate(-90.000000) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                    <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" id="Line-2" stroke-width="2" stroke-linecap="square"></line>
                    <polygon id="Shape" stroke-width="0.5" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
`;

const arrowbottom = `
<svg xmlns="http://www.w3.org/2000/svg" width="16.563" height="18.238" viewBox="0 0 16.563 18.238">
    <g id="Flecha_Copy" data-name="Flecha Copy" transform="translate(16.512 0.958) rotate(90)">
        <g id="Group_2_Copy_15" data-name="Group 2 Copy 15" transform="translate(0.042 16.073) rotate(-90)">
            <g id="Line_2" data-name="Line 2" transform="translate(7.885)">
            <path id="Path" d="M.817.1V16.064" transform="translate(-0.817 -0.097)" fill="none" stroke="#171717" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"/>
            <path id="Path-2" data-name="Path" d="M.817.1V16.064" transform="translate(-0.817 -0.097)" fill="none"/>
            </g>
            <g id="Shape" transform="translate(0 8.478)">
            <path id="Shape-2" data-name="Shape" d="M.4,15.77l-.4-.4L7.485,7.885,0,.4.4,0,8.285,7.885Z" transform="translate(15.77) rotate(90)" fill="#171717" stroke="#171717" stroke-miterlimit="10" stroke-width="0.5"/>
            <path id="Shape-3" data-name="Shape" d="M.4,15.77l-.4-.4L7.485,7.885,0,.4.4,0,8.285,7.885Z" transform="translate(15.77) rotate(90)" fill="none"/>
            </g>
        </g>
    </g>
</svg>
`;

const getNews = (args, handleData) => {
    $.ajax({
        method: 'GET',
        data: args,
        url: _dittoURL_ + '/wp-json/soma/news'
    }).done(response => {
        handleData( response );
    });

    return false;
}

const mewsItem = (data) => {
    // console.log(data);
    var pdf = ``;
    var classevento;
    var label;
    if (data.post_type == "news") {
        classevento = "news"
        label = `<span> </span>`
        return `
        <div class="${classevento}">
            <a href="${data.permalink}">
                <div class="news-item">
                    <div class="image">
                        ${ data.featured_image ? `<img src="${data.featured_image}" alt="News featured image" loading="lazy" />` : ''}
                    </div>
                    <div class="text">
                        <div class="type-content"><span>${data.post_type}<span>${label}</div>
                        <div class="news-title"><h3>${data.title}</h3></div>
                        <div class="arrow">${arrow}</div>
                        <div class="news-date">${data.date}</div> 
                    </div>
                </div>
            </a>
        </div>
    `;
    }
    else{
        classevento = "evento"
        label = `<span> · ${data.label}</span>`
        // pdf = `<div class="pdf"><a href="${data.file.filedata.url}" target="_blank">${data.file.filelabel}</a><span>${arrowbottom}</span></div>`;
        pdf = `<div class="pdf"><a href="${data.file.filedata.url}" target="_blank">${data.file.filelabel}</a><span>${arrowbottom}</span></div>`;
        return `
        <div class="${classevento}">
            <div class="news-item">
                <div class="image">
                    ${ data.featured_image ? `<img src="${data.featured_image}" alt="News featured image" loading="lazy" />` : ''}
                </div>
                <div class="text">
                    <div class="type-content"><span>${data.post_type}<span>${label}</div>
                    <div class="news-title"><h3>${data.title}</h3></div>
                    <div class="arrow">${arrow}</div>
                    <div class="news-date">${data.date}${pdf}</div> 
                </div>
            </div>
        </div>
    `;
    }
    
   
}

const newsListHandler = (containers) => {
    containers.each((key, el) => {
        const content = $(el).find('.content');
        const loader = $(el).find('.loader-container');
        let current_offset = 0;
        let current_total = 0;

        // Initial args
        let args = {};
            if($(el).data('posts-per-page')) args = { ...args, 'posts_per_page': $(el).data('posts-per-page') };
            if($(el).data('infinite-scroll')) args = { ...args, 'infinite_scroll': $(el).data('infinite-scroll') };
            if($(el).data('post-list')) args = { ...args, 'post_list': $(el).data('post-list') };
            if($(el).data('lang')) args = { ...args, 'lang': $(el).data('lang') };

        // First Render
        if(args.post_list) {
            args.post_list.map(item => {
                loader.addClass('loaded');
                getNews({ 'lang': args.lang, 'id': item }, res => { content.append(mewsItem(res.data[0])) });
            });
        } else {
            getNews(args, res => {
                if(res.data) {
                    res.data.map(item => { content.append( mewsItem(item) ) });
                }
                if (args.posts_per_page) {
                    current_offset += args.posts_per_page;
                    current_total += res.total;
                }
                loader.addClass('loaded');
            });
        }

        // Infinite scroll
        if(args.posts_per_page && args.infinite_scroll && !args.post_list) {
            let loading = true;
            $(window).on('scroll', () => {
                let scroll = $(window).scrollTop() + $(window).height();
                let item_height = $(el).innerHeight() + $(el).offset().top;

                if(scroll > (item_height + 100) && current_total > current_offset && loading) {
                    loading = false;
                    loader.removeClass('loaded');
                    args = { ...args, 'offset': current_offset };
                    getNews(args, res => {
                        if(res.data) {
                            res.data.map(item => { content.append( mewsItem(item) ) });
                        }
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

export default newsListHandler;