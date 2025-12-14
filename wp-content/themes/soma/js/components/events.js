import $ from 'jquery';

const arrow = `
<svg width="18px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-733.000000, -553.000000)">
            <g transform="translate(734.000000, 553.052734)">
                <g class="color transform="translate(22.011719, 21.437902) translate(-22.011719, -21.437902) translate(1.011719, -0.562098)">
                    <line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="2" stroke-linecap="square"></line>
                    <polygon stroke-width="1" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543) " points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
`;

const calendar = `
<svg width="34px" height="33px" viewBox="0 0 34 33">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-529.000000, -338.000000)">
            <g transform="translate(530.000000, 338.936000)">
                <polygon stroke="#000000" stroke-width="1.21196244" points="24.037255 30.4801685 0.201993739 30.4801685 0.201993739 3.00901994 32.1170045 3.00901994 32.1170045 22.1984252"></polygon>
                <polygon fill="#000000" points="6.26180592 14.010003 9.71024304 14.010003 9.71024304 12.523329 6.26180592 12.523329"></polygon>
                <polygon fill="#000000" points="14.6372743 14.010003 18.0861154 14.010003 18.0861154 12.523329 14.6372743 12.523329"></polygon>
                <polygon fill="#000000" points="23.0127427 14.010003 26.4611798 14.010003 26.4611798 12.523329 23.0127427 12.523329"></polygon>
                <polygon fill="#000000" points="6.26180592 18.4070027 9.71024304 18.4070027 9.71024304 16.9207327 6.26180592 16.9207327"></polygon>
                <polygon fill="#000000" points="14.6372743 18.4070027 18.0861154 18.4070027 18.0861154 16.9207327 14.6372743 16.9207327"></polygon>
                <polygon fill="#000000" points="23.0127427 18.4070027 26.4611798 18.4070027 26.4611798 16.9207327 23.0127427 16.9207327"></polygon>
                <polygon fill="#000000" points="6.26180592 22.8044064 9.71024304 22.8044064 9.71024304 21.3177325 6.26180592 21.3177325"></polygon>
                <polygon fill="#000000" points="14.6372743 22.8044064 18.0861154 22.8044064 18.0861154 21.3177325 14.6372743 21.3177325"></polygon>
                <polygon fill="#000000" points="6.26180592 1.48639113 9.71024304 1.48639113 9.71024304 0.000121196244 6.26180592 0.000121196244"></polygon>
                <polygon fill="#000000" points="14.6368703 1.48639113 18.0857114 1.48639113 18.0857114 0.000121196244 14.6368703 0.000121196244"></polygon>
                <polygon fill="#000000" points="23.0127427 1.48639113 26.4611798 1.48639113 26.4611798 0.000121196244 23.0127427 0.000121196244"></polygon>
            </g>
        </g>
    </g>
</svg>
`;

const getEvents = (args, handleData) => {
    $.ajax({
        method: 'GET',
        data: args,
        url: _dittoURL_ + '/wp-json/soma/events'
    }).done(response => {
        handleData( response );
    });

    return true;
}

const eventItem = (data) => {

    const link = data.file ? `<a target="_BLANK" href="${data.file.url}">${data.file_label + arrow}</a>` : '';

    return `
        <div class="event" data-filter="${data.filter}">
            <div class="label">${data.label}</div>
            <h3>${data.formated_date + calendar}  </h3>
            <div class="description">${data.description}</div>
            <div class="link">
                ${link}
            </div>
        </div>
    `;
}

const eventsHandler = (containers) => {
    containers.each((key, el) => {
        console.log('init event');
        const content = $(el).find('.events .event-list');
        const filterList = $(el).find('.filters .list');
        let filters = [];

        // Initial args
         let args = { 'order_by': 'custom_date', 'order' : 'ASC' };
         if($(el).data('lang')) args = { ...args, 'lang': $(el).data('lang') };

         getEvents(args, res => {
            if(res.data) {
                res.data.map(item => { 
                    if (!filters.includes(item.filter)) filters.push(item.filter);
                    content.prepend( eventItem(item) );
                });
            }
            if(filters) {
                filters.map(filter => {
                    filterList.prepend(`<div class="item" data-filter="${filter}">${filter}</div>`);
                });

                // Triggers
                filterList.find('.item').on('click', function() {
                    $(this).addClass('active').siblings().removeClass('active');
                    content.find(`.event`).addClass('hidden');
                    if ($(this).data('filter') != "all") {
                        content.find(`.event[data-filter="${$(this).data('filter')}"]`).removeClass('hidden');
                    } else {
                        content.find('.event').removeClass('hidden');
                    }
                });

            }
        });

    })
}

export default eventsHandler;