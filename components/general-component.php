<?php

if (!function_exists('novo_button_content')) {
    /**
     * Generate button content with optional leading and trailing icons
     *
     * @param string $context The text content to display
     * @param string|null $leading_icon Optional icon name to display before text
     * @param string|null $trailing_icon Optional icon name to display after text
     * @return void
     */
    function novo_button_content($context, $leading_icon = null, $trailing_icon = null)
    {
        // Format the context text
        $formatted_text = ucwords(str_replace('-', ' ', $context));

        // Build the button content
        $content = '';

        // Add leading icon if provided
        if ($leading_icon) {
            $content .= Novo_Icons::get_icon($leading_icon);
        }

        // Add the text content
        $content .= sprintf(
            '<span %s>%s</span>',
            novo_get_attr('filter-text'),
            esc_html($formatted_text)
        );

        // Add trailing icon if provided
        if ($trailing_icon) {
            $content .= Novo_Icons::get_icon($trailing_icon);
        }

        // Output the content
        echo $content;
    }
}