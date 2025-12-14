import $ from 'jquery';

const getStoredQuote = (handleData) => {
    $.ajax({
        "async": true,
        "crossDomain": true,
        "url": "/wp-json/soma/stock-data",
        "method": "GET",
    }).done(response => {
        if (response) {
            //get lenguage from url /es/ or /en/ if (path.includes('/es/')) {
            const lang = window.location.pathname.includes('/es/') ? 'es' : 'en';
            response.lang = lang;
            console.log(response);

            handleData({
                price: response.price,
                volume: response.volume,
                change: response.change,
                percent: response.percent,
                exchangeTimezoneName: response.exchangeTimezoneName,
                exchangeTimezoneOffset: response.exchangeTimezoneOffset,
                timestamp: response.timestamp,
                lang: response.lang || 'en'
            });
        } else {
            handleData(null);
        }
    });

    return true;
}

const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

const ShareQuotationInit = (containers) => {
    containers.each((key, el) => {
        const fillData = (data) => {
            const exchangeTime = new Date(data.timestamp * 1000); // Convertir el timestamp a una fecha
            const options = { 
                hour: 'numeric', 
                minute: 'numeric', 
                hour12: true, 
                timeZone: data.exchangeTimezoneName, 
                timeZoneName: 'short' 
            };
            const formattedTime = new Intl.DateTimeFormat('en-US', options).format(exchangeTime);
            const formattedDate = exchangeTime.toLocaleDateString('en-US');
            const formattedDateTime = `${formattedTime} ${formattedDate}`;

            const stockInfoDate = data.lang === "es" ? `Desde las ${formattedDateTime}` : `As of ${formattedDateTime}`;

            $(el).find('.data-price').html(`$${data.price.toFixed(2)}`);
            $(el).find('.data-volume').html(numberWithCommas(data.volume));
            $(el).find('.data-change').html(`$ ${data.change > 0 ? '+' + data.change.toFixed(2) : data.change.toFixed(2)}`);
            $(el).find('.data-percent').html(`${data.percent > 0 ? '+' + data.percent.toFixed(2) : data.percent.toFixed(2)} %`);
            $(el).find('.data-exchange-date').html(stockInfoDate);

        }

        if($(el).data('symbol') && $(el).data('origin') == 'api') {
            getStoredQuote(response => {
                if (response) fillData(response);
            });
        }

        if ($(el).data('origin') === 'custom') {
            fillData({
                'price': $(el).data('price') ? $(el).data('price') : 0,
                'change': $(el).data('percent-one') ? $(el).data('percent-one') : 0,
                'percent': $(el).data('percent-two') ? $(el).data('percent-two') : 0,
                'volume': $(el).data('volume') ? $(el).data('volume') : 0,
                'lang': $(el).data('lang') ? $(el).data('lang') : 'en',
                'timestamp': Date.now() / 1000 // Usar la fecha actual si es un valor personalizado
            });
        }
    });
}

export default ShareQuotationInit;
