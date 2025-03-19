<?php

function search_chapters()
{
    if (!isset($_GET['query'])) {
        wp_send_json([]);
        return;
    }

    $query = sanitize_text_field($_GET['query']);
    $args = [
        'post_type' => 'chapter',
        'posts_per_page' => 5,
        'orderby' => 'date',
        's' => $query
    ];

    $search_query = new WP_Query($args);

    $results = [];

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            $results[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
            ];
        }
    }

    wp_reset_postdata();
    wp_send_json($results);
}

add_action('wp_ajax_search_chapters', 'search_chapters');
add_action('wp_ajax_nopriv_search_chapters', 'search_chapters');
