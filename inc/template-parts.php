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
    function nv_get_breadcrumb_items()
    {
        global $post;

        $items = [
            [
                'text' => 'Home',
                'url' => '/',
                'icon' => 'home',
                'icon_attr' => ['class' => 'w-3 h-3', 'aria-hidden' => 'true']
            ],
            [
                'text' => 'Series',
                'url' => '/series'
            ],

        ];

        if ($post->post_type === 'series') {
            $post_title = get_the_title($post->ID);
            $items[] = [
                'text' => $post_title,
                'truncate' => true,
                'current' => true
            ];
        }

        if ($post->post_type === 'chapter') {
            $series_name = get_post_meta($post->ID, '_nv_related_series_name', true);
            $series_slug = sanitize_title($series_name);
            $chapter_title = get_post_meta($post->ID, '_nv_chapter_title', true);
            array_push(
                $items,
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
            );
        }

        return $items;
    }
}

if (!function_exists('nv_construct_breadcrumb')) {
    /**
     * Generate breadcrumb navigation for chapter pages
     * 
     * @param int $post_id The post ID
     * @return void
     */
    function nv_construct_breadcrumb()
    {
        $items = nv_get_breadcrumb_items();
        $separator_icon = nv_get_icon('separator', ['class' => 'rtl:rotate-180 w-3 h-3 text-gray-400 mx-1', 'aria-hidden' => 'true', 'viewBox' => '0 0 6 10', 'fill' => "none"]);
        ?>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse flex-wrap">
                <?php foreach ($items as $index => $item): ?>
                    <li <?php echo isset($item['current']) ? 'aria-current="page"' : ''; ?>>
                        <div class="flex items-center">
                            <?php if ($index > 0): ?>
                                <?php echo $separator_icon; ?>
                            <?php endif; ?>

                            <?php if (isset($item['icon'])): ?>
                                <?php echo nv_get_icon($item['icon'], $item['icon_attr']); ?>
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
            'leading' => [
                'icon' => $leading_icon,
                'attr' => ['aria-hidden' => 'true']
            ],
            'trailing' => [
                'icon' => $trailing_icon,
                'attr' => ['class' => 'w-5 h-5 ml-2 max-md:hidden']
            ]
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
        <button
            class="filter-button flex items-center justify-between px-4 h-full rounded-lg bg-nv-card border border-nv-border focus:outline-none max-md:min-w-[40px] max-md:px-0 max-md:py-[10px] max-lg:min-w-[44px] max-lg:justify-center"
            onclick="filterSelect(event)">
            <?php nv_filter_button_content(
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
        <div
            class="dropdown-content absolute top-[calc(100%+4px)] left-0 w-full text-center bg-nv-card-full border border-nv-border rounded-lg overflow-hidden z-10">
            <?php foreach ($contents as $content): ?>
                <div class="dropdown-item px-4 py-2 cursor-pointer text-[14px] font-semibold">
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

if (!function_exists('nv_filter_button_content')) {
    /**
     * Generate button content with optional icons
     * 
     * @param string $context The text content
     * @param string|null $leading_icon Optional leading icon
     * @param string|null $trailing_icon Optional trailing icon
     * @return void
     */
    function nv_filter_button_content($context, $leading_icon = null, $trailing_icon = null)
    {
        $formatted_text = ucwords(str_replace('-', ' ', $context));
        $content = '';

        if ($leading_icon) {
            $content .= nv_get_icon($leading_icon['icon'], $leading_icon['attr']);
        }

        $content .= sprintf(
            '<span class="%s">%s</span>',
            'filter-text max-md:hidden text-[14px] font-semibold selected-value ml-2',
            esc_html($formatted_text)
        );

        if ($trailing_icon) {
            $content .= nv_get_icon($trailing_icon['icon'], $trailing_icon['attr']);
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
        $search_icon_attr = ['class' => 'transition-all w-6 h-6 text-nv-text-gray duration-300 ease group-hover:text-white group-hover:scale-110 group-focus-within:text-white group-focus-within:scale-110'];
        $search_icon = nv_get_icon('search', $search_icon_attr);
        ?>
        <div class="flex-1 mx-2 h-[44px]">
            <div class="search-container h-full relative transition-all duration-300 ease">
                <div
                    class="search-bar group flex items-center justify-center bg-nv-search rounded-lg px-5 h-full gap-4 transition-all duration-300 ease border border-transparent hover:bg-nv-search-hover hover:shadow-lg focus-within:border-yellow-500 focus-within:border-2 focus-within:shadow-lg">
                    <?php echo $search_icon; ?>
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


