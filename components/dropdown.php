<?php

if (!function_exists('novo_construct_dropdown')) {

    function novo_construct_dropdown($context)
    {
        ?>
        <div class="dropdown filter-<?php echo $context; ?> relative h-full">
            <?php novo_dropdown_handler($context); ?>
            <?php novo_dropdown_content($context); ?>
        </div>
        <?php
    }
    ;
}

if (!function_exists('novo_dropdown_handler')) {
    function novo_dropdown_handler($context)
    {
        $leading_icon = null;
        $trailing_icon = 'chevron-down';

        if ($context === 'sort-by') {
            $leading_icon = 'sort';
        }
        if ($context === 'date') {
            $leading_icon = 'calendar';
        }

        ?>
        <button <?php novo_do_attr('filter-button'); ?>>
            <?php novo_button_content(sanitize_text_field($_GET[$context] ?? $context), $leading_icon, $trailing_icon); ?>
        </button>
        <?php
    }
    ;
}

if (!function_exists('novo_dropdown_content')) {
    function novo_dropdown_content($context)
    {
        $contents = novo_dropdown_allowed_contents($context);

        ?>
        <div <?php novo_do_attr('dropdown-content'); ?>>
            <?php foreach ($contents as $content): ?>
                <div <?php novo_do_attr('dropdown-item'); ?>>
                    <?php echo $content ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}


if (!function_exists('novo_dropdown_allowed_contents')) {
    function novo_dropdown_allowed_contents($context)
    {
        if ($context === 'date') {
            return ['This Month', 'This Year'];
        }

        if ($context === 'sort-by') {
            return ['Newest to oldest', 'Oldest to newest'];
        }

        return [];
    }
}

