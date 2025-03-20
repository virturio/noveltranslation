<?php

/**
 * Series Template Functions
 * 
 * This file contains reusable template parts for series pages
 */

if (!function_exists('nv_get_series_tags')) {
    /**
     * Get series tags data
     * 
     * @return array Array of tag data
     */
    function nv_get_series_tags()
    {
        $tags = [];
        $terms = get_the_terms(get_the_ID(), 'genre');
        foreach ($terms as $term) {
            $tags[] = $term->name;
        }
        return is_array($tags) ? $tags : [];
    }
}

if (!function_exists('nv_get_series_rating')) {
    /**
     * Get series rating data
     * 
     * @return array Array of rating data
     */
    function nv_get_series_rating()
    {
        $post_id = get_the_ID();
        return [
            'score' => get_post_meta($post_id, '_nv_series_rating', true) ?: 4.0,
            'total' => get_post_meta($post_id, '_nv_series_rating_total', true) ?: 5.0
        ];
    }
}

if (!function_exists('nv_get_series_details')) {
    /**
     * Get series details data
     * 
     * @return array Array of series details
     */
    function nv_get_series_details()
    {
        $post_id = get_the_ID();
        return [
            'status' => get_post_meta($post_id, '_nv_series_status', true) ?: 'Ongoing',
            'publisher' => [
                'name' => get_post_meta($post_id, '_nv_series_publisher', true),
                'url' => get_post_meta($post_id, '_nv_series_publisher_url', true)
            ],
            'rawsource_url' => get_post_meta($post_id, '_nv_series_rawsource_url', true),
            'author' => [
                'name' => get_post_meta($post_id, '_nv_series_author', true),
                'url' => get_post_meta($post_id, '_nv_series_author_url', true)
            ],
            'post_date' => get_the_time('d-m-Y'),
            'views' => get_post_meta($post_id, '_nv_series_views', true) ?: 0
        ];
    }
}

if (!function_exists('nv_get_series_alternative_names')) {
    /**
     * Get series alternative names
     * 
     * @return array Array of alternative names
     */
    function nv_get_series_alternative_names()
    {
        $names = get_post_meta(get_the_ID(), '_nv_series_alternative_names', true);
        $names = explode(',', $names);
        $names = array_map('trim', $names);
        return is_array($names) ? $names : [];
    }
}

if (!function_exists('nv_get_social_buttons')) {
    /**
     * Get social sharing buttons configuration
     * 
     * @return array Array of social button configurations
     */
    function nv_get_social_buttons()
    {
        return [
            'facebook' => [
                'icon' => 'facebook',
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . nv_get_current_url() . '&t=' . get_the_title(),
                'label' => 'Facebook',
                'color' => 'bg-nv-facebook'
            ],
            'whatsapp' => [
                'icon' => 'whatsapp',
                'url' => 'whatsapp://send?text=' . get_the_title() . ' ' . nv_get_current_url(),
                'label' => 'Whatsapp',
                'color' => 'bg-nv-whatsapp'
            ],
            'telegram' => [
                'icon' => 'telegram',
                'url' => 'https://t.me/share/url?url=' . nv_get_current_url() . '&text=' . get_the_title(),
                'label' => 'Telegram',
                'color' => 'bg-nv-telegram'
            ],
            'x' => [
                'icon' => 'x',
                'url' => 'https://x.com/intent/tweet?url=' . nv_get_current_url() . '&text=' . get_the_title(),
                'label' => 'Share on X',
                'color' => 'bg-nv-x'
            ],
            'pinterest' => [
                'icon' => 'pinterest',
                'url' => 'https://pinterest.com/pin/create/button/?url=' . nv_get_current_url() . '&media=https://www.novelupdates.com/img/noimagefound.jp&description=' . get_the_title(),
                'label' => 'Pinterest',
                'color' => 'bg-nv-pinterest'
            ]
        ];
    }
}


if (!function_exists('nv_get_star_icon')) {
    /**
     * Get star icon HTML
     * 
     * @param bool $filled Whether the star should be filled
     * @return string Star icon HTML
     */
    function nv_get_star_icon($filled = true)
    {
        $color = $filled ? 'text-yellow-300' : 'text-gray-300 dark:text-gray-500';
        return sprintf(
            '<svg class="w-4 h-4 %s ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
            </svg>',
            esc_attr($color)
        );
    }
}

if (!function_exists('nv_construct_tags')) {
    /**
     * Construct series tags section
     * 
     * @return void
     */
    function nv_construct_tags()
    {
        $tags = nv_get_series_tags();
        ?>
        <div class="flex-1 flex flex-col gap-6 info-section justify-between max-md:w-full">
            <div class="flex flex-col gap-4">
                <div class="flex flex-wrap gap-2 tag-container">
                    <?php foreach ($tags as $tag): ?>
                        <span class="px-3 py-1 bg-nv-tag border border-nv-border rounded text-xs font-light tracking-wider">
                            <?php echo esc_html($tag); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <?php
    }
}

if (!function_exists('nv_construct_rating')) {
    /**
     * Construct series rating section
     * 
     * @return void
     */
    function nv_construct_rating()
    {
        $rating = nv_get_series_rating();
        $score = number_format($rating['score'], 1);
        ?>
                <div class="rounded p-4 flex flex-col items-center gap-1 w-full">
                    <span class="text-m font-medium">Rating <?php echo esc_html($score); ?> / 5.0</span>
                    <div class="flex gap-1">
                        <div class="flex items-center">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php echo nv_get_star_icon($i <= $score); ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <?php
    }
}

if (!function_exists('nv_construct_series_details')) {
    /**
     * Construct series details section
     * 
     * @return void
     */
    function nv_construct_series_details()
    {
        $details = nv_get_series_details();
        ?>
                <div class="flex gap-6 max-md:flex-col">
                    <div class="space-y-2">
                        <p class="text-base font-semibold tracking-wide">
                            Status: <span class="font-light"><?php echo esc_html($details['status']); ?></span>
                        </p>
                        <p class="text-base font-semibold tracking-wide">
                            Publisher:
                            <span class="font-light"><?php echo esc_html($details['publisher']['name']); ?></span>
                        </p>
                        <p class="text-base font-semibold tracking-wide">
                            Author:
                            <span class="font-light"><?php echo esc_html($details['author']['name']); ?></span>
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-base font-semibold tracking-wide">
                            Raw Source:
                            <a href="<?php echo esc_url($details['rawsource_url']); ?>" target="_blank"
                                class="hover:underline text-blue-500 font-light">
                                <span class="font-light"><?php echo truncate_text($details['rawsource_url'], 20); ?></span>
                            </a>
                        </p>
                        <p class="text-base font-semibold tracking-wide">
                            Posted at: <span class="font-light"><?php echo esc_html($details['post_date']); ?></span>
                        </p>
                        <p class="text-base font-semibold tracking-wide">
                            Views: <span class="font-light"><?php echo esc_html($details['views']); ?></span>
                        </p>
                    </div>
                </div>
                <?php
    }
}

if (!function_exists('nv_construct_series_alternative_names')) {
    /**
     * Construct series alternative names section
     * 
     * @return void
     */
    function nv_construct_series_alternative_names()
    {
        $names = nv_get_series_alternative_names();
        ?>
                <div>
                    <h2 class="text-base font-semibold tracking-wide mb-2">
                        Alternative Names
                    </h2>
                    <?php foreach ($names as $name): ?>
                        <p class="text-sm tracking-wide leading-relaxed">
                            <?php echo esc_html($name); ?>
                        </p>
                    <?php endforeach; ?>
                </div>
                <?php
    }
}

if (!function_exists('nv_get_social_button_icon')) {
    /**
     * Get social button icon HTML
     * 
     * @param string $platform Social platform name
     * @return string Icon HTML
     */
    function nv_get_social_button_icon($platform)
    {
        $icons = [
            'facebook' => '<path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z" />',
            'whatsapp' => '<path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2ZM12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16C10.56 20.16 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67ZM8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7 8.5 7 9.71C7 10.93 7.89 12.1 8 12.27C8.14 12.44 9.76 14.94 12.25 16C12.84 16.27 13.3 16.42 13.66 16.53C14.25 16.72 14.79 16.69 15.22 16.63C15.7 16.56 16.68 16.03 16.89 15.45C17.1 14.87 17.1 14.38 17.04 14.27C16.97 14.17 16.81 14.11 16.56 14C16.31 13.86 15.09 13.26 14.87 13.18C14.64 13.1 14.5 13.06 14.31 13.3C14.15 13.55 13.67 14.11 13.53 14.27C13.38 14.44 13.24 14.46 13 14.34C12.74 14.21 11.94 13.95 11 13.11C10.26 12.45 9.77 11.64 9.62 11.39C9.5 11.15 9.61 11 9.73 10.89C9.84 10.78 10 10.6 10.1 10.45C10.23 10.31 10.27 10.2 10.35 10.04C10.43 9.87 10.39 9.73 10.33 9.61C10.27 9.5 9.77 8.26 9.56 7.77C9.36 7.29 9.16 7.35 9 7.34C8.86 7.34 8.7 7.33 8.53 7.33Z" />',
            'telegram' => '<path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.64 8.8C16.49 10.38 15.84 14.22 15.51 16.03C15.37 16.82 15.09 17.07 14.83 17.09C14.25 17.14 13.81 16.71 13.25 16.34C12.37 15.77 11.89 15.42 11.04 14.87C10.07 14.25 10.69 13.91 11.24 13.34C11.39 13.18 13.95 10.83 14 10.6C14.01 10.56 14.01 10.45 13.95 10.4C13.89 10.35 13.8 10.37 13.73 10.38C13.64 10.4 12.15 11.34 9.24 13.19C8.74 13.53 8.29 13.69 7.89 13.69C7.45 13.68 6.61 13.45 5.97 13.24C5.19 12.99 4.57 12.85 4.63 12.43C4.66 12.21 4.97 11.99 5.57 11.76C8.7 10.37 10.77 9.43 11.77 8.93C14.67 7.47 15.35 7.24 15.8 7.24C15.9 7.24 16.12 7.27 16.26 7.38C16.37 7.47 16.41 7.59 16.42 7.67C16.41 7.74 16.43 7.96 16.64 8.8Z" />',
            'pinterest' => '<path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13.79 15.71C13.11 15.71 12.47 15.39 12.19 14.98L11.43 17.58C11.35 17.85 11.15 18.06 10.89 18.15C10.63 18.25 10.34 18.21 10.11 18.05C9.88 17.89 9.74 17.61 9.74 17.31V17.29L10.48 14.69C10.48 14.69 10.11 13.57 10.11 11.85C10.11 9.79 11.03 8.53 12.25 8.53C13.23 8.53 13.83 9.31 13.83 10.43C13.83 11.69 13.23 13.43 13.23 14.07C13.23 14.77 13.67 15.35 14.45 15.35C16.28 15.35 17.77 13.15 17.77 10.49C17.77 8.03 15.91 6.29 12.91 6.29C9.83 6.29 7.41 8.53 7.41 11.73C7.41 12.73 7.71 13.71 8.25 14.47C8.35 14.61 8.37 14.77 8.31 14.93L8.13 15.61C8.07 15.87 7.81 15.99 7.57 15.89C6.43 15.43 5.69 13.71 5.69 11.75C5.69 7.89 8.77 4.87 13.15 4.87C16.71 4.87 19.53 7.45 19.53 10.57C19.53 14.09 17.11 16.77 14.61 16.77C13.79 16.77 13.01 16.35 12.71 15.71H13.79Z" />',
            'x' => '<path d="M16.44 2H21l-7.47 8.6L22 22h-6.06l-4.9-5.95L5.7 22H1.12l7.9-9.07L2 2h6.16l4.43 5.53L16.44 2Zm-1.08 17.92h1.92L7.32 4.06H5.26l10.1 15.86Z"/>
'
        ];

        return sprintf(
            '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                %s
            </svg>',
            $icons[$platform] ?? ''
        );
    }
}

if (!function_exists('nv_construct_social_buttons')) {
    /**
     * Construct social sharing buttons section
     * 
     * @return void
     */
    function nv_construct_social_buttons()
    {
        $buttons = nv_get_social_buttons();
        ?>
                <div class="flex gap-4 justify-end max-md:justify-center social-buttons">
                    <?php foreach ($buttons as $platform => $config): ?>
                        <a href="<?php echo esc_url($config['url']); ?>" target="_blank"
                            class="social-button px-3 py-1 rounded flex items-center gap-2 border border-nv-border max-lg:rounded-full max-lg:w-10 max-lg:h-10 hover:text-decoration-none <?php echo esc_attr($config['color']); ?>">
                            <?php echo nv_get_social_button_icon($platform); ?>
                            <span class="text-sm font-medium tracking-wide max-lg:hidden">
                                <?php echo esc_html($config['label']); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php
    }
}

if (!function_exists('nv_construct_series_synopsis')) {
    /**
     * Construct series synopsis section
     * 
     * @return void
     */
    function nv_construct_series_synopsis()
    {
        $synopsis = get_post_meta(get_the_ID(), '_nv_series_synopsis', true) ?: 'No synopsis available';
        ?>
                <div class="bg-nv-header rounded-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold mb-3">Synopsis</h2>
                    <div class="relative flex flex-col justify-center items-center gap-2">
                        <p class="series-synopsis">
                            <?php echo wp_kses_post($synopsis); ?>
                        </p>
                        <input type="checkbox" name="show-more" id="show-more"
                            class="show-more-btn appearance-none before:content-['Show\00a0more'] text-nv-text-gray font-semibold text-sm hover:text-white">
                    </div>
                </div>
                <?php
    }
}