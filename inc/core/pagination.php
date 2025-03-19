<?php

function wpdocs_get_paginated_links($query)
{
    $currentPage = max(1, get_query_var('page', 1));
    $max_page = $query->max_num_pages;
    $has_next = $currentPage < $max_page;
    $post_end = $currentPage * intval($query->post_count);
    if (!$has_next) {
        $post_end = $query->found_posts;
    }
    $post_start = $post_end - $query->post_count + 1;
    $next_url = $has_next ? get_pagenum_link($currentPage + 1) : null;
    $prev_url = $currentPage > 1 ? get_pagenum_link($currentPage - 1) : null;

    return (object) array(
        "page" => $currentPage,
        "next_url" => $next_url,
        "prev_url" => $prev_url,
        "post_start" => $post_start,
        "post_end" => $post_end,
        "total_posts" => $query->found_posts
    );
}

function novo_construct_paginated_links($query)
{
    $paginated_links = wpdocs_get_paginated_links($query);
    $from = sprintf(__('Showing <span class="font-semibold text-gray-900 dark:text-white">%s</span>', 'novotl'), $paginated_links->post_start);
    $to = sprintf(__('to <span class="font-semibold text-gray-900 dark:text-white">%s</span>', 'novotl'), $paginated_links->post_end);
    $total = sprintf(__('of <span class="font-semibold text-gray-900 dark:text-white">%s</span> Entries', 'novotl'), $paginated_links->total_posts);

    $classes = sprintf('flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 rounded-s hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white %s', $paginated_links->prev_url ? '' : 'disabled');
    ?>



    <div class="flex flex-col items-center">
        <!-- Help text -->
        <span class="text-sm text-gray-700 dark:text-gray-400">
            <?php echo $from; ?>
            <?php echo $to; ?>
            <?php echo $total; ?>
        </span>
        <div class="inline-flex mt-2 xs:mt-0">
            <!-- Buttons -->
            <a href="<?php echo $paginated_links->prev_url ?? '#'; ?>"
                class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 rounded-s hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white <?php echo $paginated_links->prev_url ? '' : 'disabled'; ?>">
                <svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5H1m0 0 4 4M1 5l4-4" />
                </svg>
                Prev
            </a>
            <a href="<?php echo $paginated_links->next_url ?? '#'; ?>"
                class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-e hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white <?php echo $paginated_links->next_url ? '' : 'disabled'; ?>">
                Next
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </a>
        </div>
    </div>

    <?php
    return $paginated_links;
}

?>