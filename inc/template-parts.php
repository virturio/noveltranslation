<?php

/**
 * Template Parts Functions
 * 
 * This file contains reusable template parts and UI components
 */

if (!function_exists('nv_get_breadcrumb_items')) {
    /**
     * Get breadcrumb navigation items
     * 
     * @param int $post_id The post ID
     * @return array Array of breadcrumb items
     */
    function nv_get_breadcrumb_items($post_id)
    {
        $series_name = get_post_meta($post_id, '_nv_related_series_name', true);
        $series_slug = sanitize_title($series_name);
        $chapter_title = get_post_meta($post_id, '_nv_chapter_title', true);

        return [
            [
                'text' => 'Home',
                'url' => '/',
                'icon' => 'home'
            ],
            [
                'text' => 'Series',
                'url' => '/series'
            ],
            [
                'text' => $series_name,
                'url' => "/series/{$series_slug}",
                'truncate' => true
            ],
            [
                'text' => $chapter_title,
                'truncate' => true,
                'current' => true
            ]
        ];
    }
}

if (!function_exists('nv_get_breadcrumb_icon')) {
    /**
     * Get breadcrumb icon HTML
     * 
     * @param string $type Icon type (separator or home)
     * @return string Icon HTML
     */
    function nv_get_breadcrumb_icon($type)
    {
        if ($type === 'separator') {
            return '<svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
            </svg>';
        }

        if ($type === 'home') {
            return '<svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
            </svg>';
        }

        return '';
    }
}

if (!function_exists('nv_construct_breadcrumb')) {
    /**
     * Generate breadcrumb navigation for chapter pages
     * 
     * @param int $post_id The post ID
     * @return void
     */
    function nv_construct_breadcrumb($post_id)
    {
        $items = nv_get_breadcrumb_items($post_id);
        ?>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse flex-wrap">
                <?php foreach ($items as $index => $item): ?>
                    <li <?php echo isset($item['current']) ? 'aria-current="page"' : ''; ?>>
                        <div class="flex items-center">
                            <?php if ($index > 0): ?>
                                <?php echo nv_get_breadcrumb_icon('separator'); ?>
                            <?php endif; ?>

                            <?php if (isset($item['icon'])): ?>
                                <?php echo nv_get_breadcrumb_icon('home'); ?>
                            <?php endif; ?>

                            <?php if (isset($item['url'])): ?>
                                <a href="<?php echo esc_url($item['url']); ?>"
                                    class="ms-1 text-sm font-light text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white <?php echo isset($item['truncate']) ? 'line-clamp-1' : ''; ?>">
                                    <?php echo esc_html($item['text']); ?>
                                </a>
                            <?php else: ?>
                                <span
                                    class="ms-1 text-sm font-light text-gray-500 md:ms-2 dark:text-gray-400 <?php echo isset($item['truncate']) ? 'line-clamp-1' : ''; ?>">
                                    <?php echo esc_html($item['text']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
        <?php
    }
}

if (!function_exists('nv_get_dropdown_icons')) {
    /**
     * Get dropdown icons based on context
     * 
     * @param string $context Dropdown context
     * @return array Array of leading and trailing icons
     */
    function nv_get_dropdown_icons($context)
    {
        $leading_icon = null;
        $trailing_icon = 'chevron-down';

        switch ($context) {
            case 'sort-by':
                $leading_icon = 'sort';
                break;
            case 'date':
                $leading_icon = 'calendar';
                break;
        }

        return [
            'leading' => $leading_icon,
            'trailing' => $trailing_icon
        ];
    }
}

if (!function_exists('nv_construct_dropdown')) {
    /**
     * Construct a dropdown component
     * 
     * @param string $context Dropdown context
     * @return void
     */
    function nv_construct_dropdown($context)
    {
        ?>
        <div class="dropdown filter-<?php echo esc_attr($context); ?> relative h-full">
            <?php nv_dropdown_handler($context); ?>
            <?php nv_dropdown_content($context); ?>
        </div>
        <?php
    }
}

if (!function_exists('nv_dropdown_handler')) {
    /**
     * Generate dropdown button handler
     * 
     * @param string $context Dropdown context
     * @return void
     */
    function nv_dropdown_handler($context)
    {
        $icons = nv_get_dropdown_icons($context);
        ?>
        <button <?php nv_do_attr('filter-button'); ?>>
            <?php nv_button_content(
                sanitize_text_field($_GET[$context] ?? $context),
                $icons['leading'],
                $icons['trailing']
            ); ?>
        </button>
        <?php
    }
}

if (!function_exists('nv_dropdown_content')) {
    /**
     * Generate dropdown content
     * 
     * @param string $context Dropdown context
     * @return void
     */
    function nv_dropdown_content($context)
    {
        $contents = nv_dropdown_allowed_contents($context);
        ?>
        <div <?php nv_do_attr('dropdown-content'); ?>>
            <?php foreach ($contents as $content): ?>
                <div <?php nv_do_attr('dropdown-item'); ?>>
                    <?php echo esc_html($content); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}

if (!function_exists('nv_dropdown_allowed_contents')) {
    /**
     * Get allowed dropdown contents based on context
     * 
     * @param string $context Dropdown context
     * @return array Array of allowed contents
     */
    function nv_dropdown_allowed_contents($context)
    {
        $contents = [
            'date' => ['This Month', 'This Year'],
            'sort-by' => ['Newest to oldest', 'Oldest to newest']
        ];

        return $contents[$context] ?? [];
    }
}

if (!function_exists('nv_button_content')) {
    /**
     * Generate button content with optional icons
     * 
     * @param string $context The text content
     * @param string|null $leading_icon Optional leading icon
     * @param string|null $trailing_icon Optional trailing icon
     * @return void
     */
    function nv_button_content($context, $leading_icon = null, $trailing_icon = null)
    {
        $formatted_text = ucwords(str_replace('-', ' ', $context));
        $content = '';

        if ($leading_icon) {
            $content .= Novo_Icons::get_icon($leading_icon);
        }

        $content .= sprintf(
            '<span %s>%s</span>',
            nv_get_attr('filter-text'),
            esc_html($formatted_text)
        );

        if ($trailing_icon) {
            $content .= Novo_Icons::get_icon($trailing_icon);
        }

        echo $content;
    }
}

if (!function_exists('nv_construct_kofi_button')) {
    /**
     * Construct Ko-fi donation button
     * 
     * @return void
     */
    function nv_construct_kofi_button()
    {
        ?>
        <a href="https://ko-fi.com/F2F21C4DA1"
            class="kofi-button bg-nv-kofi px-6 h-[44px] rounded-lg flex items-center gap-3 max-sm:h-[44px] max-sm:w-[44px] max-sm:p-0">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/kofi-cup.png'); ?>"
                alt='Buy Me a Coffee at ko-fi.com' target='_blank' class="kofi-cup h-5 max-sm:height-[18px]" />
            <span class="kofi-text max-sm:hidden text-base font-semibold">Buy Me a Coffee</span>
        </a>
        <?php
    }
}

if (!function_exists('nv_construct_searchbar')) {
    /**
     * Construct search bar component
     * 
     * @return void
     */
    function nv_construct_searchbar()
    {
        ?>
        <div class="flex-1 mx-2 h-[44px]">
            <div class="search-container h-full relative transition-all duration-300 ease">
                <div
                    class="search-bar group flex items-center justify-center bg-nv-search rounded-lg px-5 h-full gap-4 transition-all duration-300 ease border border-transparent hover:bg-nv-search-hover hover:shadow-lg focus-within:border-yellow-500 focus-within:border-2 focus-within:shadow-lg">
                    <?php echo nv_get_search_icon(); ?>
                    <input type="text" id="chapter-search" placeholder="Search posts"
                        class="search-input bg-transparent text-[14px] font-semibold text-white focus:outline-none w-full"
                        autocomplete="off" />
                </div>
                <?php echo nv_get_search_loading_indicator(); ?>
                <div id="search-results"></div>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('nv_get_search_icon')) {
    /**
     * Get search icon HTML
     * 
     * @return string Search icon HTML
     */
    function nv_get_search_icon()
    {
        return '<svg class="h-6 text-nv-text-gray search-icon transition-all duration-300 ease group-hover:text-white group-hover:scale-110 group-focus-within:text-white group-focus-within:scale-110"
            fill="currentColor" viewBox="0 0 24 24">
            <path d="M21.71 20.29l-5.01-5.01C17.54 13.68 18 11.91 18 10c0-4.41-3.59-8-8-8S2 5.59 2 10s3.59 8 8 8c1.91 0 3.68-.46 5.28-1.3l5.01 5.01c.39.39 1.02.39 1.41 0l1.41-1.41c.39-.39.39-1.02 0-1.41zM10 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" />
        </svg>';
    }
}

if (!function_exists('nv_get_search_loading_indicator')) {
    /**
     * Get search loading indicator HTML
     * 
     * @return string Loading indicator HTML
     */
    function nv_get_search_loading_indicator()
    {
        return '<div id="search-loading" class="hidden absolute top-full left-0 right-0 mt-2 bg-nv-search rounded-lg p-4 border border-nv-border">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-500"></div>
            </div>
        </div>';
    }
}

