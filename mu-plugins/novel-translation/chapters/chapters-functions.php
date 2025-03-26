<?php

/**
 * Key constants for series relationship
 */
define('NAME_KEY', 'related_series_name');
define('ID_KEY', 'related_series_id');
define('SLUG_KEY', 'related_series_slug');

include_once __DIR__ . '/chapters-metabox.php';

/**
 * Register WordPress actions and filters for chapter functionality
 */
add_action('init', 'nv_register_chapter_post_types');
add_action('init', 'nv_chapter_post_rewrite_rules');
add_action('template_redirect', 'nv_chapter_post_template_redirect');

add_filter('query_vars', 'nv_chapter_post_query_vars');
add_filter('post_type_link', 'nv_chapter_post_permalink', 10, 2);
add_filter('name_save_pre', 'nv_chapter_post_name_filter');

/**
 * Register the Chapter custom post type
 * 
 * Registers a new post type 'chapter' with custom labels and settings.
 * The post type supports title, editor, and page attributes.
 * 
 * @since 1.0.0
 * @return void
 */
if (!function_exists('nv_register_chapter_post_types')) {
    function nv_register_chapter_post_types()
    {
        register_post_type('chapter', array(
            'labels' => array(
                'name' => __('All Chapter', DOMAIN),
                'singular_name' => __('Chapter', DOMAIN),
                'add_new_item' => __('Add New Chapter', DOMAIN),
                'edit_item' => __('Edit Chapter', DOMAIN),
                'new_item' => __('New Chapter', DOMAIN),
                'view_item' => __('View Chapter', DOMAIN),
                'search_items' => __('Search Chapters', DOMAIN),
                'not_found' => __('No chapters found', DOMAIN),
                'not_found_in_trash' => __('No chapters found in Trash', DOMAIN)
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'read/%_nv_related_series_name%/chapter', 'with_front' => true),
            'supports' => array('title', 'editor', 'page-attributes', 'comments'),
            'show_in_rest' => true,
        ));
    }
}

/**
 * Add custom rewrite rules for chapter URLs
 * 
 * Adds a rewrite rule to handle chapter URLs in the format:
 * /read/{series-slug}/chapter/{chapter-slug}/
 * 
 * @since 1.0.0
 * @return void
 */
if (!function_exists('nv_chapter_post_rewrite_rules')) {
    function nv_chapter_post_rewrite_rules()
    {
        add_rewrite_rule(
            '^read/([^/]+)/chapter/([^/]+)/?$',
            'index.php?post_type=chapter&sslug=$matches[1]&name=$matches[2]',
            'top'
        );
    }
}

/**
 * Filter chapter permalink structure
 * 
 * Modifies the permalink structure for chapter posts to include the series name.
 * If no series name is found, falls back to using the post ID.
 * 
 * @since 1.0.0
 * @param string $post_link The post's permalink
 * @param WP_Post $post The post object
 * @return string Modified permalink
 */
if (!function_exists('nv_chapter_post_permalink')) {
    function nv_chapter_post_permalink($post_link, $post)
    {
        if ($post->post_type === 'chapter') {
            $key = PREFIX . NAME_KEY;
            $series_name = get_post_meta($post->ID, $key, true);
            $str = '%' . $key . '%';

            if (!empty($series_name)) {
                return str_replace($str, sanitize_title($series_name), $post_link);
            } else {
                return str_replace($str, $post->ID, $post_link); // Fallback to post ID
            }
        }
        return $post_link;
    }
}

/**
 * Add custom query variables for chapter URLs
 * 
 * Adds 'sslug' as a valid query variable for WordPress to recognize
 * in chapter URLs.
 * 
 * @since 1.0.0
 * @param array $query_vars Array of query variables
 * @return array Modified array of query variables
 */
if (!function_exists('nv_chapter_post_query_vars')) {
    function nv_chapter_post_query_vars($query_vars)
    {
        $query_vars[] = 'sslug';
        return $query_vars;
    }
}

/**
 * Handle template redirection for chapter posts
 * 
 * Checks if the requested chapter belongs to the specified series.
 * If not, returns a 404 error page.
 * 
 * @since 1.0.0
 * @return void
 */
if (!function_exists('nv_chapter_post_template_redirect')) {
    function nv_chapter_post_template_redirect()
    {
        global $wp_query;
        $query_vars = $wp_query->query_vars;
        if (!isset($query_vars['sslug'])) {
            return;
        }

        $series_slug = $query_vars['sslug'];
        $post = get_posts(array(
            'post_type' => 'chapter',
            'name' => $query_vars['name'],
            'numberposts' => 1,
            'meta_key' => PREFIX . SLUG_KEY,
            'meta_value' => $series_slug
        ));

        $series_exist = !empty($post);

        if (isset($query_vars['sslug']) && isset($query_vars['name']) && $query_vars['post_type'] === 'chapter') {
            if (!$series_exist) {
                $wp_query->set_404();
                status_header(404);
                include get_stylesheet_directory() . '/404.php';
                exit;
            }
        }
    }
}

/**
 * Filter chapter post name before saving
 * 
 * Modifies the chapter post name based on the related series ID.
 * This ensures unique and consistent post names for chapters.
 * 
 * @since 1.0.0
 * @param string $name The post name to be saved
 * @return string Modified post name
 */
if (!function_exists('nv_chapter_post_name_filter')) {
    function nv_chapter_post_name_filter($name)
    {
        if (isset($_POST['_nv_related_series'])) {
            $series_id = explode('-', $_POST['_nv_related_series'])[0];
            $new_name = $series_id;
            return $new_name;
        }

        return $name;
    }
}
