<?php

define("THEME_DIR_URI", get_template_directory_uri());
define("THEME_DIR", get_template_directory());

function enqueue_scripts()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('dropdown-click', THEME_DIR_URI . '/assets/js/filter-select.js', [], '1.0.0', true);
    wp_enqueue_script('search-chapter', THEME_DIR_URI . '/assets/js/search-chapter.js', [], '1.0.0', true);
    wp_enqueue_script('next-page', THEME_DIR_URI . '/assets/js/next-page.js', [], '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_scripts');

function add_novo_theme_support()
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'add_novo_theme_support');


require_once THEME_DIR . '/inc/core/class-novo-icons.php';
require THEME_DIR . '/inc/core/novo-html-attributes.php';
require THEME_DIR . '/inc/core/search-endpoint.php';
require THEME_DIR . '/inc/core/get-chapters.php';
require THEME_DIR . '/inc/core/pagination.php';

require THEME_DIR . '/inc/theme-functions.php';

require THEME_DIR . '/components/general-component.php';
require THEME_DIR . '/components/dropdown.php';
require THEME_DIR . '/components/searchbar.php';
