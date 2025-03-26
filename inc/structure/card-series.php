<?php
/**
 * Series Card Component
 * 
 * This file contains functions for rendering series cards
 */

if (!function_exists('nv_get_series_card_data')) {
    /**
     * Get series card data
     * 
     * @param int $post_id The post ID
     * @return array Series card data
     */
    function nv_get_series_card_data($post_id)
    {
        return [
            'title' => get_the_title($post_id),
            'status' => get_post_meta($post_id, '_nv_series_status', true) ?: 'Ongoing',
            'thumbnail' => get_the_post_thumbnail_url($post_id, 'full') ?: get_template_directory_uri() . '/assets/images/No-Image-Placeholder.webp',
            'genres' => wp_get_post_terms($post_id, 'genre', ['fields' => 'names']) ?: [],
            'synopsis' => wp_trim_words(get_post_meta($post_id, '_nv_series_synopsis', true), 30, '...'),
            'rating' => get_post_meta($post_id, '_nv_series_rating', true) ?: 0,
            'url' => get_the_permalink($post_id),
            'author' => get_post_meta($post_id, '_nv_series_author', true) ?: 'Unknown',
        ];
    }
}

if (!function_exists('nv_get_series_card_classes')) {
    /**
     * Get series card CSS classes
     * 
     * @return string CSS classes
     */
    function nv_get_series_card_classes()
    {
        return implode(' ', [
            'flex',
            'relative',
            'items-end',
            'justify-between',
            'flex-col',
            'w-[165px]',
            'h-[260px]',
            'rounded-[10px]',
            'overflow-hidden',
            'group',
            'transition-transform',
            'duration-300',
            'ease-in-out',
            'cursor-pointer',
            'border-2',
            'border-nv-border',
            'series-card',
        ]);
    }
}

if (!function_exists('nv_get_series_tooltip_classes')) {
    /**
     * Get series tooltip CSS classes
     * 
     * @return string CSS classes
     */
    function nv_get_series_tooltip_classes()
    {
        return implode(' ', [
            'fixed',
            'invisible',
            'opacity-0',
            'transition-opacity',
            'duration-200',
            'ease-in-out',
            'z-50',
            'w-[280px]',
            'bg-[#202324]',
            'rounded-lg',
            'p-4',
            'shadow-xl',
            'border',
            'border-nv-border',
            'text-white',
            'pointer-events-none',
            'overflow-hidden',
            'series-tooltip',
        ]);
    }
}

if (!function_exists('nv_get_series_status_classes')) {
    /**
     * Get series status badge CSS classes
     * 
     * @return string CSS classes
     */
    function nv_get_series_status_classes()
    {
        return implode(' ', [
            'bg-[#562C9F]',
            'text-white',
            'text-[11px]',
            'font-light',
            'px-[10px]',
            'py-[5px]',
            'rounded-bl-[10px]',
            'shadow-[-2px_2px_4.4px_rgba(0,0,0,0.6)]',
            'z-10'
        ]);
    }
}

if (!function_exists('nv_get_series_info_classes')) {
    /**
     * Get series info section CSS classes
     * 
     * @return string CSS classes
     */
    function nv_get_series_info_classes()
    {
        return implode(' ', [
            'absolute',
            'bottom-0',
            'left-0',
            'right-0',
            'bg-[#202324]',
            'px-3',
            'py-2',
            'shadow-[0_-7px_8.8px_rgba(0,0,0,1)]',
            'z-10'
        ]);
    }
}

if (!function_exists('nv_enqueue_tooltip_script')) {
    /**
     * Enqueue tooltip JavaScript
     */
    function nv_enqueue_tooltip_script()
    {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const cards = document.querySelectorAll('.series-card');

                cards.forEach(card => {
                    const tooltip = card.querySelector('.series-tooltip');
                    let timeout;

                    function updateTooltipPosition(e) {
                        const tooltipWidth = tooltip.offsetWidth;
                        const tooltipHeight = tooltip.offsetHeight;
                        const viewportWidth = window.innerWidth;
                        const viewportHeight = window.innerHeight;

                        // Calculate position relative to mouse
                        let x = e.clientX + 16; // 16px offset from cursor
                        let y = e.clientY + 16;

                        // Check right edge
                        if (x + tooltipWidth > viewportWidth) {
                            x = e.clientX - tooltipWidth - 16;
                        }

                        // Check bottom edge
                        if (y + tooltipHeight > viewportHeight) {
                            y = e.clientY - tooltipHeight - 16;
                        }

                        // Apply position
                        tooltip.style.left = x + 'px';
                        tooltip.style.top = y + 'px';
                    }

                    card.addEventListener('mouseenter', function (e) {
                        clearTimeout(timeout);
                        tooltip.classList.remove('invisible', 'opacity-0');
                        updateTooltipPosition(e);
                    });

                    card.addEventListener('mousemove', function (e) {
                        updateTooltipPosition(e);
                    });

                    card.addEventListener('mouseleave', function () {
                        timeout = setTimeout(() => {
                            tooltip.classList.add('invisible', 'opacity-0');
                        }, 200);
                    });
                });
            });
        </script>
        <?php
    }
}

if (!function_exists('nv_construct_series_tooltip')) {
    /**
     * Construct series tooltip content
     * 
     * @param array $data Series data
     * @return void
     */
    function nv_construct_series_tooltip($data)
    {
        $genres_text = implode(', ', $data['genres']);
        ?>
        <div class="<?php echo nv_get_series_tooltip_classes(); ?>">
            <div class="flex flex-col gap-2">
                <h4 class="text-[15px] font-semibold text-white">
                    <?php echo esc_html($data['title']); ?>
                </h4>

                <div class="flex gap-2 justify-between w-full">
                    <div class="flex items-center gap-1">
                        <?php echo nv_get_icon('star', ['class' => 'w-3 h-3 text-yellow-500']); ?>
                        <span class="text-xs font-medium"><?php echo esc_html($data['rating']); ?></span>
                    </div>
                    <span class="text-xs font-medium"><?php echo esc_html($data['status']); ?></span>
                </div>

                <p class="text-sm leading-relaxed">
                    <?php echo esc_html($data['synopsis']); ?>
                </p>

                <div class="space-y-1">
                    <p class="text-sm line-clamp-1">
                        <span class="font-medium text-white">Author: </span>
                        <?php echo esc_html($data['author']); ?>
                    </p>

                    <p class="text-sm line-clamp-2">
                        <span class="font-medium text-white">Genre: </span>
                        <?php echo esc_html($genres_text); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('nv_construct_series_card')) {
    /**
     * Construct series card component
     * 
     * @param int $post_id The post ID
     * @return void
     */
    function nv_construct_series_card($post_id)
    {
        $data = nv_get_series_card_data($post_id);
        $genres_text = implode(', ', array_slice($data['genres'], 0, 3));
        if (count($data['genres']) > 3) {
            $genres_text .= '...';
        }
        ?>

        <div class="<?php echo nv_get_series_card_classes(); ?>">
            <span class="<?php echo nv_get_series_status_classes(); ?>">
                <?php echo esc_html($data['status']); ?>
            </span>
            <a href="<?php echo esc_url($data['url']); ?>">
                <img src="<?php echo esc_url($data['thumbnail']); ?>" alt="<?php echo esc_attr($data['title']); ?>"
                    class="w-full h-full object-cover absolute top-0 left-0">
            </a>
            <div class="bg-nv-card h-[60px] px-3 py-3 shadow-[0_-7px_8.8px_rgba(0,0,0,.5)] z-10">
                <a href="<?php echo esc_url($data['url']); ?>">
                    <h3 class="text-white text-sm font-semibold leading-[1.2] line-clamp-1 mb-1">
                        <?php echo esc_html($data['title']); ?>
                    </h3>
                </a>
                <p class="text-xs font-light leading-[1.2] line-clamp-1">
                    <?php echo esc_html($genres_text); ?>
                </p>
            </div>

            <?php nv_construct_series_tooltip($data); ?>
        </div>

        <?php

        // Output the script only once
        static $script_output = false;
        if (!$script_output) {
            nv_enqueue_tooltip_script();
            $script_output = true;
        }
    }
}
