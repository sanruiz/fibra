import $ from 'jquery';

const arrow = `
<svg width="18px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-733.000000, -553.000000)">
            <g transform="translate(734.000000, 553.052734)">
                <g stroke="#171717" transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
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

    return true;
}

const documentItem = (data) => {

    const file = data.file ? `<a href="${data.file.url}" target="_blank">${data.label + arrow}</a>` : `<a style="color: #171717;">${data.label}</a>`;

    return `
        <div class="document" data-year="${data.year}">
            <div class="image">
                <h3>${data.title}</h3>
                ${ data.featured_image ? `<img src="${data.featured_image}" alt="News featured image" />` : ''}
            </div>
            <div class="text">
                <h3>${data.title}</h3>
                <div class="description">${data.description}</div>
                <div class="link">
                    ${file}
                    ${ (data.has_additional_files && data.additional_files) ? data.additional_files.map(file => {
                        return `<a href="${file.file.url}" target="_blank">${file.label + arrow}</a>`;
                    }) : ''}
                </div>
                <div class="date">
                    ${data.formated_date}
                </div>
            </div>
        </div>
    `;
}

const annualReportsHandler = (containers) => {
    containers.each((key, el) => {
        const content = $(el).find('.documents .document-list');
        const yearList = $(el).find('.year-list .years');
        let years = [];

        // Initial args
        let args = { 'order_by': 'custom_date', 'order' : 'DESC' };
            if($(el).data('lang')) args = { ...args, 'lang': $(el).data('lang') };
            if($(el).data('category')) args = { ...args, 'categories': $(el).data('category') };

        // First Render
        getDocuments(args, res => {
            if(res.data) {
                res.data.map(item => { 
                    if (!years.includes(item.year)) years.push(item.year);
                    content.append( documentItem(item) ) 
                });
            }
            if(years) {
                years.map(year => {
                    yearList.append(`<h3 data-year="${year}">${year}</h3>`);
                });

                // Triggers
                yearList.find('h3').on('click', function() {
                    $(this).addClass('active').siblings().removeClass('active');
                    content.find(`.document`).addClass('hidden');
                    content.find(`.document[data-year="${$(this).data('year')}"]`).removeClass('hidden');
                    content.addClass('filtered');
                });
                $(el).find('.year-list .all a').on('click', function() {
                    yearList.find('h3').removeClass('active');
                    content.find(`.document`).removeClass('hidden');
                    content.removeClass('filtered');
                });
                $(el).find('.mobile-title').on('click', function() { $(this).toggleClass('open') });

                // Events
                if($(el).data('last-year') == 1) $(el).find(`.year-list h3:first-of-type`).click();
            }
        });

    })
}

export default annualReportsHandler;