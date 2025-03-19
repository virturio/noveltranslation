<?php

if (!function_exists('novo_query_chapters')) {
    function novo_query_chapters()
    {
        // Sanitize and validate the date parameter
        $date_query = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';
        $sort_by_query = isset($_GET['sort-by']) ? sanitize_text_field($_GET['sort-by']) : '';
        $page_query = isset($_GET['pg']) ? intval(sanitize_text_field($_GET['pg'])) : 1;

        // Define allowed values for date query
        $allowed_date_values = array('this-month', 'this-year');
        $allowed_sort_by_values = array('newest-to-oldest', 'oldest-to-newest');

        $args = array(
            'post_type' => 'chapter',
            'posts_per_page' => 2,
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => get_query_var('page') ?? 1,
        );

        // Only proceed if the value is in our allowed list
        if (in_array($date_query, $allowed_date_values)) {
            if ($date_query === 'this-month') {
                $args['date_query'] = array(
                    'month' => date('n'),
                );
            }
            if ($date_query === 'this-year') {
                $args['date_query'] = array(
                    'year' => date('Y'),
                );
            }
        }

        if (in_array($sort_by_query, $allowed_sort_by_values)) {
            if ($sort_by_query === 'oldest-to-newest') {
                $args['order'] = 'ASC';
            }
        }

        $chapters = new WP_Query($args);


        return $chapters;
    }
}

if (!function_exists('novo_filter_args')) {
    function novo_filter_args($args)
    {
        // Sanitize and validate the date parameter
        $date_query = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';
        $sort_by_query = isset($_GET['sort-by']) ? sanitize_text_field($_GET['sort-by']) : '';

        // Define allowed values for date query
        $allowed_date_values = array('this-month', 'this-year');
        $allowed_sort_by_values = array('newest-to-oldest', 'oldest-to-newest');

        // Only proceed if the value is in our allowed list
        if (in_array($date_query, $allowed_date_values)) {
            if ($date_query === 'this-month') {
                $args['date_query'] = array(
                    'month' => date('n'),
                );
            }
            if ($date_query === 'this-year') {
                $args['date_query'] = array(
                    'year' => date('Y'),
                );
            }
        }

        if (in_array($sort_by_query, $allowed_sort_by_values)) {
            if ($sort_by_query === 'oldest-to-newest') {
                $args['order'] = 'ASC';
            }
        }

        return $args;
    }
}

if (!function_exists('novo_get_chapters')) {
    function novo_get_chapters()
    {
        $chapters = novo_query_chapters();
        return $chapters->posts;
    }
}
