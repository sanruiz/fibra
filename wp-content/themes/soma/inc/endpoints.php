<?php

/**
 * Register endpoints
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('rest_api_init', function () {
    // News
    register_rest_route('soma', '/news', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'news_handler',
            'permission_callback'   => '__return_true',
        )
    ));
    // Careers
    register_rest_route('soma', '/careers', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'careers_handler',
            'permission_callback'   => '__return_true',
        )
    ));
    // Portfolio
    register_rest_route('soma', '/portfolio', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'portfolio_handler',
            'permission_callback'   => '__return_true',
        )
    ));
    // Documents
    register_rest_route('soma', '/documents', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'documents_handler',
            'permission_callback'   => '__return_true',
        )
    ));
    // Events
    register_rest_route('soma', '/events', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'events_handler',
            'permission_callback'   => '__return_true',
        )
    ));
    // Stock Data
    register_rest_route('soma', '/stock-data', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'stock_data_handler',
            'permission_callback'   => '__return_true',
        )
    ));

});

function news_handler($request)
{
    $params = $request->get_params();

    $args = [
        'numberposts'        => -1,
        'post_type'            => array('news', 'events'),
        'post_status'       => array('publish'),
        'meta_query'        => array(
            'relation'        => 'OR',
            'new_date'      => array(
                'key'         => 'news_content_date',
            ),
            'events_date'   => array(
                'key'          => 'event_info_init_date',
            ),
        ),
        'orderby'            => array(
            'new_date'      => 'DESC',
            'events_date'   => 'DESC',
        ),
        'order'                => $params['order'] ? $params['order'] : 'DESC'

    ];

    if ($params['id']) $args['p'] = $params['id'];
    if ($params['posts_per_page']) $args['posts_per_page'] = $params['posts_per_page'];
    if ($params['offset']) $args['offset'] = $params['offset'];

    $news = get_posts($args);
    $formatedNews = [];
    if ($news):
        foreach ($news as $key => $item):
            $info;
            $date;
            $timestamp;
            $file = [];
            $label;
            if ($item->post_type == 'news'):
                $info = get_field('news_content', $item->ID);
                $date = DateTime::createFromFormat('U', $info['date']);
                $timestamp = $info['date'];
            elseif ($item->post_type == 'events'):
                $info = get_field('event_info', $item->ID);
                $date = DateTime::createFromFormat('U', $info['init_date']);
                $timestamp = $info['init_date'];
                $label = $info['label'];
                $file = array(
                    'filelabel' => $info['file_label'],
                    'filedata' => $info['file'],
                );
            endif;
            $formatedNews[] = [
                "ID"                => $item->ID,
                "title"             => get_the_title($item->ID),
                "permalink"         => get_the_permalink($item->ID),
                "featured_image"    => get_the_post_thumbnail_url($item->ID),
                "date"              => $date->format('F j, Y'),
                "timestamp"         => $timestamp,
                "label"             => $label,
                "file"              => $file,
                "post_type"         => $item->post_type
            ];
        endforeach;
    endif;
    $date = array_column($formatedNews, 'timestamp');
    array_multisort($date, SORT_DESC, $formatedNews);
    $output = [
        "status"        => "success",
        "total"         => (int)wp_count_posts('news')->publish,
        "count"         => count($formatedNews),
        "data"          => $formatedNews
    ];

    return $output;
}

function portfolio_handler($request)
{
    $params = $request->get_params();

    $args = [
        'numberposts'   => -1,
        'post_type'     => 'portfolio',
        'post_status'   => ['publish'],
        'orderby'       => 'menu_order',
        'order' => isset($params['order']) ? $params['order'] : 'DESC'
    ];

    if (isset($params['categories']) && $params['categories']) {
        $args['tax_query'] = [
            [
                'taxonomy'  => 'portfolio-taxonomy',
                'field'     => 'id',
                'terms'     => array_map('intval', explode(',', $params['categories'])),
                'operator'  => 'AND',
            ]
        ];
    }

    $total = count(get_posts($args));

    if (isset($params['id']) && $params['id']) {
        $args['p'] = $params['id'];
    }
    if (isset($params['posts_per_page']) && $params['posts_per_page']) {
        $args['posts_per_page'] = $params['posts_per_page'];
    }
    if (isset($params['offset']) && $params['offset']) {
        $args['offset'] = $params['offset'];
    }

    $portfolio = get_posts($args);

    $formatedPosts = [];
    if ($portfolio) {
        foreach ($portfolio as $key => $item) {
            $info = get_field('project_info', $item->ID);
            $formatedPosts[] = [
                "ID"                => $item->ID,
                "title"             => get_the_title($item->ID),
                "permalink"         => get_the_permalink($item->ID),
                "featured_image"    => get_the_post_thumbnail_url($item->ID),
                "city"              => $info['city'] ?? '',
                "year"              => $info['year'] ?? ''
            ];
        }
    }

    // Sort by year
    $year = array_column($formatedPosts, 'year');
    array_multisort($year, SORT_DESC, $formatedPosts);

    $output = [
        "status"        => "success",
        "total"         => $total,
        "count"         => count($formatedPosts),
        "data"          => $formatedPosts
    ];

    return $output;
}

function documents_handler($request)
{
    $params = $request->get_params();

    $args = [
        'numberposts'    => -1,
        'post_type'        => 'documents-reports',
        'post_status'   => array('publish'),
        'order'            => $params['order'] ? $params['order'] : 'ASC'
    ];

    if ($params['order_by'] && $params['order_by'] == 'custom_date') {
        $args['meta_key'] = 'document_content_date';
        $args['orderby'] = 'meta_value';
    } else {
        $args['orderby'] = 'menu_order';
    }

    if ($params['categories']) {
        $args['tax_query'] = [
            [
                'taxonomy'  => 'documents-taxonomy',
                'field'     => 'id',
                'terms'     => array_map('intval', explode(',', $params['categories'])),
                'operator'  => 'AND',
            ]
        ];
    }

    $total = count(get_posts($args));

    if (isset($params['id'])) {
        $args['p'] = $params['id'];
    }

    if (isset($params['posts_per_page'])) {
        $args['posts_per_page'] = $params['posts_per_page'];
    }

    if (isset($params['offset'])) {
        $args['offset'] = $params['offset'];
    }

    $documents = get_posts($args);

    $formatedDocuments = [];
    if ($documents):
        foreach ($documents as $key => $item):
            $content = get_field('document_content', $item->ID);
            $terms = get_the_terms($item->ID, 'documents-taxonomy');
            $formatedTerms = null;
            $formatedFiles = null;
            $formated_date = $content['date'] ? translateDate(date("F j, Y", $content['date'])) : null;
            $year = $content['date'] ? date("Y", $content['date']) : null;

            if ($terms):
                foreach ($terms as $key => $term):
                    $formatedTerms[$term->term_id] = $term->name;
                endforeach;
            endif;

            if ($content['has_additional_files'] && $content['additional_files']):
                foreach ($content['additional_files'] as $key => $file):
                    $fileContent = (wpm_get_language() == 'en') ? $file['file'] : $file['file_es'];
                    $formatedFiles[$key] = [
                        "label" => $file['label'],
                        "file"  => $fileContent ? [
                            "title"     => $fileContent['title'],
                            "filename"  => $fileContent['filename'],
                            "filesize"  => $fileContent['filesize'],
                            "url"       => $fileContent['url'],
                            "type"      => $fileContent['subtype']
                        ] : null
                    ];
                endforeach;
            endif;

            $mainFile = (wpm_get_language() == 'en') ? $content['file'] : $content['file_es'];

            $document = [
                "ID"                    => $item->ID,
                "title"                 => get_the_title($item->ID),
                "featured_image"        => get_the_post_thumbnail_url($item->ID),
                "label"                 => $content['label'],
                "date"                  => $content['date'],
                "formated_date"         => $formated_date,
                "year"                  => $year,
                "description"           => $content['description'],
                "has_additional_files"  => $content['has_additional_files'],
                "file"                  => $mainFile ? [
                    "title"     => $mainFile['title'],
                    "filename"  => $mainFile['filename'],
                    "filesize"  => $mainFile['filesize'],
                    "url"       => $mainFile['url'],
                    "type"      => $mainFile['subtype']
                ] : null,
                "additional_files"      => $formatedFiles,
                "categories"            => $formatedTerms
            ];

            if (isset($params['year'])) {
                if ($params['year'] == $year) {
                    $formatedDocuments[] = $document;
                }
            } else {
                $formatedDocuments[] = $document;
            }
            

        endforeach;
    endif;

    $output = [
        "status"        => "success",
        "total"         => $total,
        "count"         => count($formatedDocuments),
        "data"          => $formatedDocuments
    ];

    return $output;
}

function events_handler($request)
{
    $params = $request->get_params();

    $args = [
        'numberposts'    => -1,
        'post_type'        => 'events',
        'post_status'   => array('publish'),
        'order'            => $params['order'] ? $params['order'] : 'ASC',
    ];

    if ($params['order_by'] && $params['order_by'] == 'custom_date') {
        $args['meta_key'] = 'event_info_init_date';
        $args['orderby'] = 'meta_value';
    }

    $total = count(get_posts($args));

if (isset($params['id']) && $params['id']) {
    $args['p'] = $params['id'];
}
if (isset($params['posts_per_page']) && $params['posts_per_page']) {
    $args['posts_per_page'] = $params['posts_per_page'];
}
if (isset($params['offset']) && $params['offset']) {
    $args['offset'] = $params['offset'];
}

    $events = get_posts($args);

    $formatedEvents = [];
    if ($events):
        foreach ($events as $key => $item):
            $content = get_field('event_info', $item->ID);
            $formated_init_date = $content['end_date'] ? translateDate(date("M j", $content['init_date']), 'short') : translateDate(date("M j, Y", $content['init_date']));
            $formated_end_date = $content['end_date'] ? translateDate(date("M j", $content['end_date']), 'short') : null;
            $year = date("Y", $content['init_date']);
            $filter = translateDate(date("M Y", $content['init_date']), 'short');

            $mainFile = (wpm_get_language() == 'en') ? $content['file'] : $content['file_es'];

            $event = [
                "ID"                    => $item->ID,
                "title"                 => get_the_title($item->ID),
                "featured_image"        => get_the_post_thumbnail_url($item->ID),
                "label"                 => $content['label'],
                "init_date"             => $content['init_date'],
                "end_date"              => $content['end_date'] ? $content['end_date'] : null,
                "formated_date"         => $formated_end_date ? $formated_init_date . ' - ' . $formated_end_date : $formated_init_date,
                "year"                  => $year,
                "description"           => $content['description'],
                "file_label"            => $content['file_label'],
                "file"                  => $mainFile ? [
                    "title"     => $mainFile['title'],
                    "filename"  => $mainFile['filename'],
                    "filesize"  => $mainFile['filesize'],
                    "url"       => $mainFile['url'],
                    "type"      => $mainFile['subtype']
                ] : null,
                "filter"                => $filter
            ];

            if (isset($params['year']) && $params['year']) {
                if ($params['year'] == $filter) $formatedEvents[] = $event;
            } else {
                // if(((int)$content['init_date'] + 86400 ) > (int)date('U') || ((int)$content['end_date'] + 86400 ) > (int)date('U')) $formatedEvents[] = $event;
                $formatedEvents[] = $event;
            }

        endforeach;
    endif;

    $output = [
        "status"        => "success",
        "total"         => $total,
        "count"         => count($formatedEvents),
        "data"          => $formatedEvents
    ];

    return $output;
}

/**
 * Fetch stored stock data.
 */
function stock_data_handler()
{
    // Obtener los datos del stock almacenados en la opción
    $stock_data = get_option('stock_data');

    if (!$stock_data) {
        return new WP_Error('no_data', 'No stock data available', ['status' => 404]);
    }

    return rest_ensure_response($stock_data);
}
