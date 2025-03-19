<?php

if (!function_exists('nv_do_attr')) {
    function nv_do_attr($context, $attributes = array(), $settings = array())
    {
        $attributes = nv_get_attr($context, $attributes, $settings);
        echo $attributes;
    }
}

if (!function_exists('nv_get_attr')) {
    function nv_get_attr($context, $attributes = array(), $settings = array())
    {
        $attributes = apply_filters('nv_parse_attr', $attributes, $context, $settings);
        $output = '';

        // Cycle through attributes, build tag attribute string.
        foreach ($attributes as $key => $value) {
            if (!$value) {
                continue;
            }

            // Remove any whitespace at the start or end of our classes.
            if ('class' === $key) {
                $value = trim($value);
            }

            if (true === $value) {
                $output .= esc_html($key) . ' ';
            } else {
                $output .= sprintf('%s="%s" ', esc_html($key), esc_attr($value));
            }
        }

        return $output;
    }
}
