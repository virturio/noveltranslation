<?php

const VERSION = '1.0.3';
const PREFIX = "_nv_";

define("THEME_DIR_URI", get_template_directory_uri());
define("THEME_DIR", get_template_directory());
if (!defined("DOMAIN")) {
    define("DOMAIN", 'novel-translation');
}

add_action('wp_print_styles', 'nv_deregister_styles', 100);
function nv_deregister_styles()
{
    wp_dequeue_style('wp-block-library');
}

add_action('wp_enqueue_scripts', 'nv_enqueue_scripts');
function nv_enqueue_scripts()
{
    wp_enqueue_style('nv-style', get_stylesheet_uri(), [], VERSION);
    wp_enqueue_style('nv-tailwind', THEME_DIR_URI . '/assets/css/tailwind.css', [], VERSION);
    if (is_front_page()) {
        wp_enqueue_script('nv-dropdown-click', THEME_DIR_URI . '/assets/js/minified/filter-select.min.js', [], VERSION, true);
        wp_enqueue_script('nv-search-chapter', THEME_DIR_URI . '/assets/js/minified/search-chapter.min.js', [], VERSION, true);
    }

    if (comments_open()) {
        wp_enqueue_script('nv-comment-reply', THEME_DIR_URI . '/assets/js/minified/comment-reply.min.js', [], VERSION, true);
    }
}

add_action('after_setup_theme', 'add_nv_theme_support');
function add_nv_theme_support()
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}

add_filter('the_time', 'post_time_ago_format');
function post_time_ago_format()
{
    return sprintf(esc_html__('%s ago', 'textdomain'), human_time_diff(get_the_time('U'), current_time('timestamp')));
}


add_filter('preprocess_comment', 'nv_add_preprocess_comment_filter');
function nv_add_preprocess_comment_filter($commentdata)
{
    $commentdata['comment_author'] = preg_replace("/[[:punct:]\s]/", "", $commentdata['comment_author']);

    return $commentdata;
}

require_once THEME_DIR . '/inc/core/class-nv-icons.php';
require_once THEME_DIR . '/inc/core/pagination.php';
require_once THEME_DIR . '/inc/theme-functions.php';

require THEME_DIR . '/inc/rest/search-endpoint.php';
require THEME_DIR . '/inc/utils/utils.php';

