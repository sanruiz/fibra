import $ from 'jquery';

const getStoredQuote = (handleData, handleError) => {
    $.ajax({
        async: true,
        crossDomain: true,
        url: '/wp-json/soma/stock-data',
        method: 'GET',
        dataType: 'json',
        timeout: 10000
    }).done(response => {
        if (response) {
            const lang = window.location.pathname.includes('/es/') ? 'es' : 'en';
            response.lang = lang;

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
            console.warn('[ShareQuotation] Empty response from API');
            handleData(null);
        }
    }).fail((jqXHR, textStatus, errorThrown) => {
        console.error('[ShareQuotation] AJAX Error:', textStatus, errorThrown);
        if (handleError) {
            handleError(textStatus, errorThrown);
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
            if (!data) {
                console.warn('[ShareQuotation] No data to fill');
                return;
            }

            try {
                const exchangeTime = new Date(data.timestamp * 1000);
                const options = { 
                    hour: 'numeric', 
                    minute: 'numeric', 
                    hour12: true, 
                    timeZone: data.exchangeTimezoneName || 'America/Mexico_City', 
                    timeZoneName: 'short' 
                };
                const formattedTime = new Intl.DateTimeFormat('en-US', options).format(exchangeTime);
                const formattedDate = exchangeTime.toLocaleDateString('en-US');
                const formattedDateTime = `${formattedTime} ${formattedDate}`;

                const stockInfoDate = data.lang === 'es' ? `Desde las ${formattedDateTime}` : `As of ${formattedDateTime}`;

                $(el).find('.data-price').html(`$${data.price.toFixed(2)}`);
                $(el).find('.data-volume').html(numberWithCommas(data.volume));
                $(el).find('.data-change').html(`$ ${data.change > 0 ? '+' + data.change.toFixed(2) : data.change.toFixed(2)}`);
                $(el).find('.data-percent').html(`${data.percent > 0 ? '+' + data.percent.toFixed(2) : data.percent.toFixed(2)} %`);
                $(el).find('.data-exchange-date').html(stockInfoDate);
            } catch (error) {
                console.error('[ShareQuotation] Error filling data:', error);
            }
        };

        const origin = $(el).data('origin');
        const symbol = $(el).data('symbol');

        if (symbol && origin === 'api') {
            getStoredQuote(
                response => {
                    if (response) fillData(response);
                },
                (textStatus, errorThrown) => {
                    console.error('[ShareQuotation] Failed to fetch stock data:', textStatus);
                }
            );
        }

        if (origin === 'custom') {
            fillData({
                price: $(el).data('price') || 0,
                change: $(el).data('percent-one') || 0,
                percent: $(el).data('percent-two') || 0,
                volume: $(el).data('volume') || 0,
                lang: $(el).data('lang') || 'en',
                timestamp: Date.now() / 1000,
                exchangeTimezoneName: 'America/Mexico_City'
            });
        }
    });
}

export default ShareQuotationInit;
