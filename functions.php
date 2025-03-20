<?php

define("THEME_DIR_URI", get_template_directory_uri());
define("THEME_DIR", get_template_directory());

function enqueue_scripts()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('dropdown-click', THEME_DIR_URI . '/assets/js/filter-select.js', [], VERSION, true);
    wp_enqueue_script('search-chapter', THEME_DIR_URI . '/assets/js/search-chapter.js', [], VERSION, true);
    wp_enqueue_script('next-page', THEME_DIR_URI . '/assets/js/next-page.js', [], VERSION, true);
}

add_action('wp_enqueue_scripts', 'enqueue_scripts');

function add_nv_theme_support()
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'add_nv_theme_support');

function post_time_ago_format()
{
    return sprintf(esc_html__('%s ago', 'textdomain'), human_time_diff(get_the_time('U'), current_time('timestamp')));
}

add_filter('the_time', 'post_time_ago_format');


require_once THEME_DIR . '/inc/core/class-nv-icons.php';
require_once THEME_DIR . '/inc/core/pagination.php';
require_once THEME_DIR . '/inc/theme-functions.php';
require_once THEME_DIR . '/inc/template-chapter.php';
require_once THEME_DIR . '/inc/template-parts.php';
require_once THEME_DIR . '/inc/template-series.php';

require THEME_DIR . '/inc/rest/search-endpoint.php';
require THEME_DIR . '/inc/utils/utils.php';

