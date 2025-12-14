import $ from 'jquery';

const getProjects = (args, handlerData) => {
    $.ajax({
        data: args,
        method: 'GET',
        url: _dittoURL_ + '/wp-json/soma/portfolio'
    }).done(response => { 
        console.log("Response", response, "args", args);
        handlerData( response );
    });
    
    return true;
}

const printProject = (data) => {
    return `
        <div class="project">
            <a href="${data.permalink}" target="_blank">
                <div class="image">
                    <img src="${data.featured_image}" alt="Featured image" />
                </div>
                <div class="info-container">
                    ${data.year ? `<h2 class="year">${data.year}</h2>` : ''}
                    <div class="title"><h3>${data.title}</h3><div>
                    <div class="city">${data.year ? `<span>${data.year} — </span>` : ''}${data.city}</div>
                </div>
            </a>
        </div>
    `;
}

const portfolioHandler = (containers) => {
    containers.each((key, el) => {
        const content = $(el).find('.projects');
        const $lang = $(el).data('lang');
        const $posts_per_page = $(el).data('posts-per-page');
        const loader = $(el).find('.loader-container');
        let $categories = $(el).find('.filter.active:not(.all)') ? $(el).find('.filter.active').data('filters') : $(el).data('main-category');
        let $offset = 0;
        let total = 0;
        let loading = true;

        // View handler
        $(el).find('.view-mode > div').on('click', function() {
            if($(this).hasClass('grid')) {
                content.removeClass('list-view').addClass('grid-view');
            } else {
                content.removeClass('grid-view').addClass('list-view');
            }
            $(this).addClass('current-view').siblings().removeClass('current-view');
        });

        //View all movil
        $(el).find('.ViewAll').on('click', function() {
            $('.IteamView').toggleClass('current-view-all');
            $('.ViewAllsvg').toggleClass('ViewAllx');
        });

        // First render - fetch all and sort by year
        getProjects({
            lang: $lang,
            posts_per_page: -1, // Fetch all posts
            categories: $categories
        }, response => {
            if(response.data) {
                total = response.total;

                // Sort data by year (descending - newest first)
                const sortedData = response.data.sort((a, b) => {
                    const yearA = parseInt(a.year) || 0;
                    const yearB = parseInt(b.year) || 0;
                    return yearB - yearA; // Descending order
                });

                // Display first batch based on posts_per_page
                const firstBatch = sortedData.slice(0, $posts_per_page);
                $offset = $posts_per_page;

                firstBatch.forEach(item => {
                    content.append( printProject(item) );
                });

                // Store sorted data for pagination/filtering
                $(el).data('sorted-data', sortedData);
            }
        });

        // Filters
        if($(el).find('.filter').length > 1) {
            $(el).find('.filter').on('click', function() {
                if(loading) {
                    loading = false;
                    content.html('');
                    $offset = 0;
                    $categories = $(this).data('filters');
                    $(this).addClass('active').siblings().removeClass('active');
                    loader.removeClass('loaded');

                    // Fetch new data for the selected filter
                    getProjects({
                        lang: $lang,
                        posts_per_page: -1, // Fetch all posts for this filter
                        categories: $categories
                    }, response => {
                        loader.addClass('loaded');
                        loading = true;
                        if(response.data) {
                            total = response.total;

                            // Sort data by year (descending - newest first)
                            const sortedData = response.data.sort((a, b) => {
                                const yearA = parseInt(a.year) || 0;
                                const yearB = parseInt(b.year) || 0;
                                return yearB - yearA; // Descending order
                            });

                            // Display first batch
                            const firstBatch = sortedData.slice(0, $posts_per_page);
                            $offset = $posts_per_page;

                            firstBatch.forEach(item => {
                                content.append( printProject(item) );
                            });

                            // Store sorted data for pagination
                            $(el).data('sorted-data', sortedData);
                        }
                    });
                }
            });
        }

        // Infinite scroll - use stored sorted data
        $(window).on('scroll', () => {
            let scroll = $(window).scrollTop() + $(window).height();
            let item_height = $(el).innerHeight() + $(el).offset().top;
            if(scroll > (item_height + 100) && total > $offset && loading) {
                loading = false;
                loader.removeClass('loaded');

                const sortedData = $(el).data('sorted-data');
                if (sortedData && sortedData.length > $offset) {
                    const nextBatch = sortedData.slice($offset, $offset + $posts_per_page);
                    nextBatch.forEach(item => {
                        content.append(printProject(item));
                    });
                    $offset += $posts_per_page;
                }

                loading = true;
                loader.addClass('loaded');
            } else {
                loader.addClass('loaded');
            }
        });
    });
}

export default portfolioHandler;