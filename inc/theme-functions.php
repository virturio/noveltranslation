<?php

if (!function_exists('nv_get_icon')) {
    function nv_get_icon($icon, $attr = [])
    {
        return Novo_Icons::get_icon($icon, $attr);
    }
}

function nv_wp_body_open()
{
    echo sprintf(
        '<div aria-hidden="true" class="hidden">%s</div>',
        do_shortcode('[post-views]')
    );
}
add_action('wp_body_open', 'nv_wp_body_open');

require_once THEME_DIR . '/inc/structure/comment-form.php';
require_once THEME_DIR . '/inc/structure/comment-field.php';
require_once THEME_DIR . '/inc/structure/card-series.php';
require_once THEME_DIR . '/inc/template-chapter.php';
require_once THEME_DIR . '/inc/template-parts.php';
require_once THEME_DIR . '/inc/template-series.php';
