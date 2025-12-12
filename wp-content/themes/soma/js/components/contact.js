import $ from 'jquery';

const contactHandler = (containers) => {
    containers.each((key, el) => {
        // Triggers
        $(el).find('.reset-form').on('click', () => {
            $(el).find('form')[0].reset();
            $(el).find('.wpcf7-response-output').html('');
            $(el).find('.wpcf7').css('display', 'block');
            $(el).find('.thankyou-message').css('display', 'none');
        })

        // Events
        $(el).find('.wpcf7')[0].addEventListener( 'wpcf7mailsent', function( event ) {
            $(el).find('.wpcf7').css('display', 'none');
            $(el).find('.thankyou-message').css('display', 'block');
        }, false );
    })
}

export default contactHandler;