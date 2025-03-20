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
        $class_attr = sprintf('w-4 h-4 ms-1 %s', $filled ? 'text-yellow-300' : 'text-gray-300 dark:text-gray-500');
        $icon_attr = ['class' => $class_attr];
        return nv_get_icon('star', $icon_attr);
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
                            <?php echo do_shortcode('[post-views]', 120); ?>
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
                <div class="flex gap-2 justify-end max-md:justify-center social-buttons">
                    <?php foreach ($buttons as $platform => $config): ?>
                        <a href="<?php echo esc_url($config['url']); ?>" target="_blank"
                            class="social-button rounded flex items-center justify-center border border-nv-border max-lg:rounded-full max-lg:w-10 px-3 py-1 max-lg:h-10 hover:text-decoration-none <?php echo esc_attr($config['color']); ?>">
                            <?php echo nv_get_icon($platform); ?>
                            <span class="text-sm font-medium tracking-wide ml-1 max-lg:hidden">
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