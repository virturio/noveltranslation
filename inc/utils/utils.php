<?php

/**
 * Truncate text to a maximum length, keeping the middle part
 * 
 * @param string $text The text to truncate
 * @param int $max_length The maximum length of the truncated text
 * @param string $separator The separator to use for the truncated text
 * @return string The truncated text
 */
function truncate_text($text, $max_length = 35, $separator = '...')
{
    $text_length = mb_strlen($text);

    if ($text_length <= $max_length) {
        return $text;
    }

    $part_length = floor(($max_length - mb_strlen($separator)) / 2);

    return mb_substr($text, 0, $part_length) . $separator . mb_substr($text, -$part_length);
}


if (!function_exists('nv_filter_args')) {
    function nv_filter_args($args)
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

if (!function_exists('nv_get_current_url')) {
    function nv_get_current_url()
    {
        global $wp;
        return home_url($wp->request);
    }
}