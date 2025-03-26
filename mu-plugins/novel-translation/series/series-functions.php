<?php

include_once __DIR__ . '/series-metabox.php';

add_filter('wp_insert_post_data', 'filter_series_name');

add_action('init', 'register_series_post_type');
add_action('init', 'register_genre_taxonomy');
add_action('post_updated', 'series_updated_callback', 10, 3);

if (!function_exists('register_series_post_type')) {
    function register_series_post_type()
    {
        register_post_type('series', array(
            'labels' => array(
                'name' => __('All Series', DOMAIN),
                'singular_name' => __('Series', DOMAIN),
                'add_new_item' => __('Add New Series', DOMAIN),
                'edit_item' => __('Edit Series', DOMAIN),
                'new_item' => __('New Series', DOMAIN),
                'view_item' => __('View Series', DOMAIN),
                'search_items' => __('Search Series', DOMAIN),
                'not_found' => __('No series found', DOMAIN),
                'not_found_in_trash' => __('No series found in Trash', DOMAIN)
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'thumbnail', 'comments'),
            'taxonomies' => array('genre'),
        ));
    }
}

if (!function_exists('series_updated_callback')) {
    function series_updated_callback($post_id, $post_after, $post_before)
    {
        if ($post_after->post_type === 'series') {
            global $wpdb;

            $wpdb->query($wpdb->prepare(
                "UPDATE $wpdb->postmeta 
                SET meta_value = CASE 
                    WHEN meta_value = %s THEN %s 
                    WHEN meta_value = %s THEN %s 
                END 
                WHERE meta_value IN (%s, %s)",
                $post_before->post_title,
                $post_after->post_title,
                $post_before->post_name,
                $post_after->post_name,
                $post_before->post_title,
                $post_before->post_name
            ));
        }
    }
}


if (!function_exists('filter_series_name')) {
    function filter_series_name($data)
    {
        if ($data['post_type'] == 'series') {
            $sanitized_title = sanitize_title($data['post_title']);
            if ($sanitized_title != $data['post_name']) {
                $data['post_name'] = $sanitized_title;
                flush_rewrite_rules();
            }
        }

        return $data;
    }
}

if (!function_exists('register_genre_taxonomy')) {
    function register_genre_taxonomy()
    {
        register_taxonomy('genre', 'series', array(
            'labels' => array(
                'name' => __('Genres', DOMAIN),
                'singular_name' => __('Genre', DOMAIN),
                'search_items' => __('Search Genres', DOMAIN),
                'all_items' => __('All Genres', DOMAIN),
                'edit_item' => __('Edit Genre', DOMAIN),
                'update_item' => __('Update Genre', DOMAIN),
                'add_new_item' => __('Add New Genre', DOMAIN),
                'new_item_name' => __('New Genre Name', DOMAIN),
                'menu_name' => __('Genres', DOMAIN),
            ),
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'genre'],
        ));
    }
}