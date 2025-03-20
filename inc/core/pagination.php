<?php

/**
 * Get pagination data from WP_Query
 * 
 * @param WP_Query $query WordPress query object
 * @return object Pagination data object
 */
function nv_get_pagination_data($query)
{
    $current_page = max(1, get_query_var('page', 1));
    $max_page = $query->max_num_pages;
    $has_next = $current_page < $max_page;

    // Calculate post range
    $post_end = $current_page * intval($query->post_count);
    if (!$has_next) {
        $post_end = $query->found_posts;
    }
    $post_start = $post_end - $query->post_count + 1;

    // Generate navigation URLs
    $next_url = $has_next ? get_pagenum_link($current_page + 1) : null;
    $prev_url = $current_page > 1 ? get_pagenum_link($current_page - 1) : null;

    return (object) [
        'page' => $current_page,
        'next_url' => $next_url,
        'prev_url' => $prev_url,
        'post_start' => $post_start,
        'post_end' => $post_end,
        'total_posts' => $query->found_posts
    ];
}

/**
 * Get pagination text elements
 * 
 * @param object $pagination_data Pagination data object
 * @return array Array of pagination text elements
 */
function nv_get_pagination_text($pagination_data)
{
    return [
        'from' => sprintf(
            __('Showing <span class="font-semibold text-gray-900 dark:text-white">%s</span>', DOMAIN),
            esc_html($pagination_data->post_start)
        ),
        'to' => sprintf(
            __('to <span class="font-semibold text-gray-900 dark:text-white">%s</span>', DOMAIN),
            esc_html($pagination_data->post_end)
        ),
        'total' => sprintf(
            __('of <span class="font-semibold text-gray-900 dark:text-white">%s</span> Entries', DOMAIN),
            esc_html($pagination_data->total_posts)
        )
    ];
}

/**
 * Generate pagination button HTML
 * 
 * @param array $button_data Button configuration array
 * @param string $direction Button direction ('prev' or 'next')
 * @return string HTML for the pagination button
 */
function nv_generate_navigation_button($button_data, $direction)
{
    $classes = [
        'flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 hover:bg-gray-900',
        'dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
        $button_data['disabled'] ? 'disabled' : '',
        'border-0 border-s border-gray-700 rounded-md'
    ];

    $output = sprintf(
        '<a href="%s" class="%s">',
        esc_url($button_data['url']),
        esc_attr(implode(' ', array_filter($classes)))
    );

    // Add icon before text if specified
    if ($button_data['icon']['position'] === 'before') {
        $output .= $button_data['icon']['svg'];
    }

    // Add button text
    $output .= sprintf('<span>%s</span>', esc_html(ucfirst($direction)));

    // Add icon after text if specified
    if ($button_data['icon']['position'] === 'after') {
        $output .= $button_data['icon']['svg'];
    }

    $output .= '</a>';

    return $output;
}

/**
 * Get pagination button configuration
 * 
 * @param object $pagination_data Pagination data object
 * @return array Array of button configurations
 */
function nv_get_pagination_button_data($direction, $pagination_data)
{
    if ($direction === 'prev') {
        return [
            'url' => $pagination_data->prev_url ?? '#',
            'disabled' => !$pagination_data->prev_url,
            'icon' => [
                'position' => 'before',
                'svg' => '<svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4" />
                </svg>'
            ]
        ];
    }

    return [
        'url' => $pagination_data->next_url ?? '#',
        'disabled' => !$pagination_data->next_url,
        'icon' => [
            'position' => 'after',
            'svg' => '<svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>'
        ]
    ];
}

/**
 * Display pagination UI
 * 
 * @param WP_Query $query WordPress query object
 * @return object Pagination data object
 */
function nv_display_pagination($query)
{
    $pagination_data = nv_get_pagination_data($query);
    $pagination_text = nv_get_pagination_text($pagination_data);
    $prev_button = nv_get_pagination_button_data('prev', $pagination_data);
    $next_button = nv_get_pagination_button_data('next', $pagination_data);

    ?>
    <div class="flex flex-col items-center w-full">


        <!-- Pagination buttons -->
        <div class="inline-flex justify-between items-center w-full mt-2 xs:mt-0 gap-4">
            <?php echo nv_generate_navigation_button($prev_button, 'prev'); ?>
            <!-- Help text -->
            <span class="text-sm text-gray-700 dark:text-gray-400 flex-wrap text-center">
                <?php echo $pagination_text['from']; ?>
                <?php echo $pagination_text['to']; ?>
                <?php echo $pagination_text['total']; ?>
            </span>
            <?php echo nv_generate_navigation_button($next_button, 'next'); ?>
        </div>
    </div>
    <?php

    return $pagination_data;
}

/**
 * Get chapter navigation data
 * 
 * @param string $direction 'next' or 'prev'
 * @return array|null Navigation data or null if not found
 */
function nv_get_chapter_navigation($direction)
{
    global $post;

    if (!$post) {
        return null;
    }

    $meta_key = '_nv_related_series_id';
    $meta_value = get_post_meta($post->ID, $meta_key, true);

    if (!$meta_value) {
        return null;
    }

    $args = [
        'post_type' => 'chapter',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => $direction === 'next' ? 'ASC' : 'DESC',
        'meta_key' => $meta_key,
        'meta_value' => $meta_value,
        'date_query' => [
            [
                $direction === 'next' ? 'after' : 'before' => $post->post_date,
                'inclusive' => false,
            ],
        ],
    ];

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return null;
    }

    $query->the_post();
    $navigation_data = [
        'url' => get_permalink(),
        'title' => get_the_title(),
        'post_id' => get_the_ID()
    ];
    wp_reset_postdata();

    return $navigation_data;
}

/**
 * Get chapter navigation button configuration
 * 
 * @param string $direction 'next' or 'prev'
 * @return array Button configuration array
 */
function nv_get_chapter_navigation_button($direction)
{
    $navigation = nv_get_chapter_navigation($direction);

    if (!$navigation) {
        return [
            'url' => '#',
            'disabled' => true,
            'icon' => [
                'position' => $direction === 'prev' ? 'before' : 'after',
                'svg' => $direction === 'prev'
                    ? '<svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4" />
                    </svg>'
                    : '<svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>'
            ]
        ];
    }

    return [
        'url' => $navigation['url'],
        'disabled' => false,
        'icon' => [
            'position' => $direction === 'prev' ? 'before' : 'after',
            'svg' => $direction === 'prev'
                ? '<svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4" />
                </svg>'
                : '<svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>'
        ]
    ];
}

/**
 * Display chapter navigation
 * 
 * @param array $pagination_text Optional pagination text to display
 * @return void
 */
function nv_display_chapter_navigation($pagination_text = [])
{
    $prev_button = nv_get_chapter_navigation_button('prev');
    $next_button = nv_get_chapter_navigation_button('next');

    ?>
    <div class="h-[64px] bg-nv-header border-y w-full border-nv-border flex justify-between items-center">
        <div class="flex w-full justify-between items-center px-4">
            <?php echo nv_generate_navigation_button($prev_button, 'prev'); ?>

            <?php if (!empty($pagination_text)): ?>
                <!-- Pagination text -->
                <span class="text-sm text-gray-700 dark:text-gray-400 flex-wrap">
                    <?php echo $pagination_text['from']; ?>
                    <?php echo $pagination_text['to']; ?>
                    <?php echo $pagination_text['total']; ?>
                </span>
            <?php endif; ?>

            <?php echo nv_generate_navigation_button($next_button, 'next'); ?>
        </div>
    </div>
    <?php
}

?>